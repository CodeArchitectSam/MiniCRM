@if ($contacts->isEmpty())
<div class="px-6 py-4 text-center text-gray-500">
    No contacts found.
</div>
@endif
@foreach($contacts as $contact)
<div class="grid grid-cols-12 gap-4 px-6 py-4 border-b border-gray-200 hover:bg-gray-50">
    <div class="col-span-1 flex items-center justify-center gap-2">
        <input type="radio" name="parent_contact" id="parent_contact_{{ $contact->id }}" value="{{ $contact->id }}" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" />
    </div>
    <!-- Combined Profile Image and Name -->
    <div class="col-span-4 flex items-center gap-4">
        <div class="relative">
            <img src="{{ $contact->profile_image_url }}" alt="Profile" class="w-10 h-10 rounded-full object-cover">
            <span class="absolute -bottom-1 -right-1 bg-green-500 rounded-full w-4 h-4 border-2 border-white"></span>
        </div>
        <div>
            <p class="font-medium text-gray-800">{{ $contact->name }}</p>
            <div class="flex items-center mt-1 gap-2">
                <span class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded-full flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                    Primary
                </span>
                @if (count($contact->mergedContacts) > 0)
                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded-full">
                    {{ count($contact->mergedContacts) }} merged
                </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Email -->
    <div class="col-span-3 flex items-center text-gray-600">{{ $contact->email }}</div>

    <!-- Phone -->
    <div class="col-span-2 flex items-center text-gray-600">{{ $contact->phone }}</div>
</div>
@endforeach