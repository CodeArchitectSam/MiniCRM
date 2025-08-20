<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Services\ContactService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function __construct(protected ContactService $contactService)
    {
    }

    public function index(Request $request): \Illuminate\Contracts\View\View | \Illuminate\Http\JsonResponse
    {
        $contacts = $this->contactService->getContactsAll();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('contact.partials.contact-list', compact('contacts'))->render(),
            ]);
        }
        return view('contact.index', compact('contacts'));
    }

    public function store(StoreContactRequest $request): \Illuminate\Http\RedirectResponse | \Illuminate\Http\JsonResponse
    {
        try {
            $validated = $request->validated();

            $contact = $this->contactService->createContact($validated, $request);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Custom field created successfully',
                    'data' => $contact
                ]);
            }

            return redirect()
            ->route('custom-field')
            ->with('success', 'Custom field created successfully');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating custom field: ' . $e->getMessage(),
                ]);
            }
            return redirect()
            ->route('custom-field')
            ->with('error', 'Error creating custom field: ' . $e->getMessage());
        }
    }

    public function show(Request $request, $id): \Illuminate\Http\JsonResponse | \Illuminate\Contracts\View\View
    {

        $contact = '';
        try {
            $contact = $this->contactService->getContactById($id);
            if (!$contact) {
                throw new \Exception('Contact not found');
            }
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Contact not found' . $e->getMessage()], 404);
            }
            return view('errors.404', ['message' => 'Contact not found']);
        }
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'contact' => $contact
            ]);
        }
        return view('contact.view', compact('contact'));
    }

    public function update(UpdateContactRequest $request, $id): \Illuminate\Http\RedirectResponse | \Illuminate\Http\JsonResponse
    {
        try {
            $validated = $request->validated();
            $contact = $this->contactService->updateContact($id, $validated, $request);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Custom field updated successfully',
                    'data' => $contact
                ]);
            }

            return redirect()
            ->route('custom-field')
            ->with('success', 'Custom field updated successfully');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating custom field: ' . $e->getMessage(),
                ]);
            }
            return redirect()
            ->route('custom-field')
            ->with('error', 'Error updating custom field: ' . $e->getMessage());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->contactService->deleteContact($id);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Contact not found'], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Contact deleted successfully'
        ]);    
    }

    public function filter(Request $request): \Illuminate\Http\JsonResponse
    {
        $contacts = $this->contactService->getContactsAll($request);
        if ($request->ajax()) {
            return response()->json([
                'html' => view('contact.partials.contact-list', compact('contacts'))->render(),
            ]);
        }
        return response()->json([
            'success' => true,
            'contacts' => $contacts
        ]);
    }

    public function getContactsForMerge(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $contacts = $this->contactService->getContactsForMerge($id);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('contact.partials.modal-content-contact-mergable-list', compact('contacts'))->render(),
            ]);
        }
    }

    public function merge(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $parentContact = $this->contactService->mergeContact($request);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Contact not merged'], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Contact merged successfully',
            'parentContact' => $parentContact
        ]);
    }
}
