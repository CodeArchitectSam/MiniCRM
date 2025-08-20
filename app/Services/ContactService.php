<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\CustomField;
use App\Models\CustomFieldOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ContactService
{

    public function getContactsAll(?Request $request = null)
    {
        if ($request) {
            $contacts = $this->filteredContacts($request);
        } else {
            $contacts = Contact::with(['customFields', 'masterContact', 'mergedContacts'])->orderBy('id', 'desc')->paginate(config('crm.rows_per_page'));
        }

        $contacts->getCollection()->transform(function ($contact) {

            $this->setProfileImageUrl($contact);

            $this->setAdditionalFileUrl($contact);

            return $contact;
        });

        return $contacts;
    }

    public function getContactById(int $id)
    {
        $contact = Contact::with(['customFields', 'customFields.fieldType', 'masterContact', 'mergedContacts'])->where('id', $id)->first();

        $this->setProfileImageUrl($contact);

        $this->setAdditionalFileUrl($contact);

        foreach ($contact->customFields as $customField) {
            $this->setCustomFieldDisplayValue($customField);
        }

        foreach ($contact->mergedContacts as $mergedContact) {
            $this->setProfileImageUrl($mergedContact);
            $this->setAdditionalFileUrl($mergedContact);

            foreach ($mergedContact->customFields as $customField) {
                $this->setCustomFieldDisplayValue($customField);
            }
        }

        return $contact;
    }

    public function createContact(array $data, Request $request): Contact
    {
        $contact = new Contact();

        $data = $this->prepareContactData($data);
        
        $contact = Contact::create($data);

        $this->handleFileUploads($request, $contact);

        $this->attachCustomFieldsToContact($request, $contact);

        return $contact;
    }

    private function getExpirationTime(string $path): int
    {
        if (str_contains($path, 'profile_images')) {
            $expiration = now()->addHours(24)->timestamp;
        } else {
            $expiration = now()->addMinutes(5)->timestamp;
        }        
        
        return $expiration;
    }

    private function getSignedUrl(string $path): string
    {
        return route('file.serve', [
            'path' => $path,
            'expires' => $this->getExpirationTime($path),
            'token' => Str::random(32),
        ]);
    }

    private function prepareContactData(array $data): array
    {
        $contactData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'gender' => $data['gender'],
            'is_active' => $data['is_active'] ?? 1,
            'merged_into' => $data['merged_into'] ?? null,
        ];

        return $contactData;
    }

    private function getCustomFields(): array
    {
        $customFields = CustomField::select('id', 'name', 'field_type_id')
                ->with('fieldType:id,name')
                ->orderBy('field_type_id', 'asc')
                ->orderBy('id', 'asc')
                ->get()
                ->toArray();
        return $customFields;
    }

    private function handleFileUploads(Request $request, Contact $contact): void
    {
        if ($request->hasFile('profile_image_path')) {
            $contact->profile_image_path = $this->uploadFile(
                $request,
                'profile_image_path',
                "profile_images/{$contact->id}"
            );
        }

        if ($request->hasFile('additional_file_path')) {
            $contact->additional_file_path = $this->uploadFile(
                $request,
                'additional_file_path',
                "additional_files/{$contact->id}"
            );
        }

        $contact->save();
    }

    private function uploadFile(Request $request, string $fieldName, string $directory): ?string
    {
        if ($request->hasFile($fieldName)) {
            $originalFileName = $this->getOriginalFileName($fieldName, $request);
            $timestampedFileName = $this->getTimestampedFileName($originalFileName);

            return $request->file($fieldName)->storeAs($directory, $timestampedFileName);
        }

        return null;
    }

    private function removeFile(string $filePath): void
    {
        if ($filePath && Storage::disk('local')->exists($filePath)) {
            Storage::disk('local')->delete($filePath);
        }
    }

    private function removeExistingProfileImage(Contact $contact): void
    {
        if ($contact->profile_image_path) {
            $this->removeFile($contact->profile_image_path);
        }
    }

    private function removeExistingAdditionalFile(Contact $contact): void
    {
        if ($contact->additional_file_path) {
            $this->removeFile($contact->additional_file_path);
        }
    }

    private function getOriginalFileName(string $fileName, Request $request): string
    {
        return $request->file($fileName)->getClientOriginalName();
    }

    private function getTimestampedFileName(string $originalFileName): string
    {
        return time() . '_' . $originalFileName;
    }

    private function attachCustomFieldsToContact(Request $request, Contact $contact): void
    {
        $customFields = $this->getCustomFields();

        $pivotData = [];

        foreach ($customFields as $customField) {
            if (is_array($request->input($customField['name']))) {
                $value = $request->input($customField['name']) ? (implode(',', $request->input($customField['name']))) : null;
            } else {
                $value = $request->input($customField['name']) ?? null;
            }
            $pivotData[$customField['id']] = ['value' => $value];
        }

        $contact->customFields()->sync($pivotData);
    }

    public function updateContact(int $id, array $data, Request $request): Contact
    {
        $contact = Contact::findOrFail($id);

        $data = $this->prepareContactData($data);

        if ($request->hasFile('profile_image_path')) {
            $this->removeExistingProfileImage($contact);
        }

        if ($request->hasFile('additional_file_path')) {
            $this->removeExistingAdditionalFile($contact);
        }
        
        $this->handleFileUploads($request, $contact);

        $this->attachCustomFieldsToContact($request, $contact);

        $contact->update($data);
        
        return $contact;
    }

    public function deleteContact(int $id): void
    {
        $contact = Contact::findOrFail($id);

        $this->removeExistingProfileImage($contact);
        $this->removeExistingAdditionalFile($contact);

        $contact->customFields()->detach();
        $contact->delete();
    }

    private function filteredContacts(Request $request)
    {
        $contacts = Contact::query();

        if ($request->has('appliedFilters')) {
            $appliedFilters = json_decode($request->input('appliedFilters'), true);
            $page = $request->input('page', 1);
            $customFields = CustomField::pluck('id', 'name')->toArray();

            foreach ($appliedFilters as $key => $value) {
                if (isset($customFields[$key])) {
                    $contacts->whereHas('customFields', function($q) use ($customFields, $key, $value) {
                        $q->where('custom_field_id', $customFields[$key]);
                        if (is_array($value)) {
                            /* $q->whereIn('value', (array)$value);
                            $q->whereRaw("CONCAT(',', value, ',') LIKE ?", ['%,9,%']); */
                            $q->where(function($q) use ($value) {
                                $q->whereRaw("CONCAT(',', value, ',') LIKE ?", ['%,' . $value[0] . ',%']);
                                $value = array_slice($value, 1);
                                foreach ($value as $v) {
                                    $q->orWhereRaw("CONCAT(',', value, ',') LIKE ?", ['%,' . $v . ',%']);
                                }
                            });
                            
                        } else {
                            $q->where('value', 'LIKE', '%' . $value . '%');
                        }
                        return $q;
                    });
                } else {
                    if (is_array($value)) {
                        $contacts->where($key, $value[0]);
                    } else {
                        $contacts->where($key, 'LIKE', '%' . $value . '%');
                    }
                }
            }
        }

        //dd($contacts->toSql(), $contacts->getBindings());

        $contacts = $contacts->with(['customFields', 'masterContact', 'mergedContacts'])->orderBy('id', 'desc');

        return $contacts->paginate(config('crm.rows_per_page'), ['*'], 'page', $page);
    }

    public function getContactsForMerge($exceptId)
    {
        $contacts = Contact::select('id', 'name', 'email', 'phone', 'profile_image_path')
        ->where('merged_into', null)
        ->where('id', '!=', $exceptId)
        ->get();

        foreach ($contacts as $contact) {
            $this->setProfileImageUrl($contact);
        }

        return $contacts;
    }

    public function mergeContact(Request $request): array
    {
        $parentContact = Contact::findOrFail($request->input('parent_contact'));
        $childContactId = $request->input('child_contact');

        $childContact = Contact::findOrFail($childContactId);
        $childContact->merged_into = $parentContact->id;
        $childContact->save();

        return [
            'parent_contact_id' => $parentContact->id,
            'parent_contact_name' => $parentContact->name,
        ];
    }

    private function setProfileImageUrl(&$contact): void
    {
        if (
            $contact->profile_image_path 
            && Storage::disk('local')->exists("{$contact->profile_image_path}")
        ) {
            $contact->profile_image_url = $this->getSignedUrl($contact->profile_image_path);
        } else {
            $contact->profile_image_url = asset('images/default-profile.jpg');
        }
    }

    private function setAdditionalFileUrl(&$contact): void
    {
        if (
            $contact->additional_file_path 
            && Storage::disk('local')->exists("{$contact->additional_file_path}")
        ) {
            $contact->additional_file_url = $this->getSignedUrl($contact->additional_file_path);
            $contact->additional_file_name = $this->getFileName($contact->additional_file_path);
        } else {
            $contact->additional_file_url = null;
            $contact->additional_file_name = null;
        }
    }

    private function setCustomFieldDisplayValue(&$customField): void
    {
        if (
            $customField->fieldType->name == 'Dropdown' 
            || $customField->fieldType->name == 'CheckBox' 
            || $customField->fieldType->name == 'Radio Button'
        ) {
            $customFieldValues = explode(',', $customField->pivot->value);
            $customField->display_values = CustomFieldOptions::whereIn('id', $customFieldValues)
                ->pluck('display_text')
                ->implode(',');
        }
    }

    private function getFileName(string $filePath): string
    {
        $fileNameParts = explode('/', $filePath);
        $fileName = end($fileNameParts);
        $fileNameParts = explode('_', $fileName);
        if (count($fileNameParts) > 1) {
            return implode('_', array_slice($fileNameParts, 1));
        }
        return implode(' ', $fileNameParts);
    }
}