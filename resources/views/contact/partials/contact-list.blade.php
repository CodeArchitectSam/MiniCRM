<!-- Contacts List -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <!-- Table Header -->
    <div class="grid grid-cols-12 gap-4 px-6 py-3 bg-gray-100 border-b border-gray-200">
        <div class="col-span-4 font-medium text-gray-700">Contact</div>
        <div class="col-span-4 font-medium text-gray-700">Email</div>
        <div class="col-span-2 font-medium text-gray-700">Phone</div>
        <div class="col-span-2 font-medium text-gray-700 text-right">Actions</div>
    </div>

    <!-- Contact Items -->
    <div id="contactsList">
        @if ($contacts->isEmpty())
        <div class="px-6 py-4 text-center text-gray-500">
            No contacts found.
        </div>
        @endif
        @foreach($contacts as $contact)
        <div class="grid grid-cols-12 gap-4 px-6 py-4 border-b border-gray-200 hover:bg-gray-50 {{ $contact->masterContact ? 'bg-gray-50/50' : '' }}">
            <!-- Combined Profile Image and Name -->
            <div class="col-span-4 flex items-center gap-4">
                <div class="relative">
                    <img src="{{ $contact->profile_image_url }}" alt="Profile" 
                        class="w-10 h-10 rounded-full object-cover">
                    <span class="absolute -bottom-1 -right-1 bg-green-500 rounded-full w-4 h-4 border-2 border-white"></span>
                </div>
                <div>
                    <p class="font-medium text-gray-800">{{ $contact->name }}</p>
                    <div class="flex items-center mt-1 gap-2">
                        @if ($contact->masterContact)
                        <div class="flex items-center mt-1 gap-2">
                            <span
                                class="bg-gray-100 text-gray-800 text-xs px-2 py-0.5 rounded-full flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                                Merged
                            </span>
                            <span class="text-xs text-gray-500">
                                → <span id="mergedContactName">{{ $contact->masterContact->name ?? '' }}</span>
                            </span>
                        </div>
                        @else
                        <span class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded-full flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Primary
                        </span>
                        @endif
                        @if (count($contact->mergedContacts) > 0)
                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded-full">
                            {{ count($contact->mergedContacts) }} merged
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Email -->
            <div class="col-span-4 flex items-center text-gray-600">{{ $contact->email }}</div>
            
            <!-- Phone -->
            <div class="col-span-2 flex items-center text-gray-600">{{ $contact->phone }}</div>
            
            <!-- Actions -->
            <div class="col-span-2 flex items-center justify-end gap-2">
                <a class="text-blue-600 hover:text-blue-800 p-1 cursor-pointer" href="{{ route('contact.show', $contact->id) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </a>
                <a class="p-1 {{ $contact->masterContact ? 'cursor-not-allowed text-gray-300' : 'cursor-pointer text-green-600 hover:text-green-800' }}" onclick="getContactDetails({{ $contact->id }}, 1)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>
                <button class=" {{ $contact->masterContact ? 'cursor-not-allowed text-gray-300' : 'cursor-pointer text-red-600 hover:text-red-800' }}" onclick="deleteContact({{ $contact->id }})">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        </div>
        @endforeach
    </div>

    @if ($contacts->hasPages())
    <div class="px-6 py-3" id="pagination-container">
        {{ $contacts->links() }}
    </div>
    @endif
</div>