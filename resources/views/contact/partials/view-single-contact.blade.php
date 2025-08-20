@php
    $colours = ['green', 'emerald', 'teal', 'cyan', 'blue', 'indigo', 'purple', 'red', 'orange'];
@endphp
<div class="bg-gray-50 p-4 rounded-lg mb-4 col-span-12 {{ $contact->masterContact == null ? 'hidden' : '' }}" id="mergedContactDiv">
    <div class="col-span-4 flex items-center gap-4">
        <div class="relative">
            <img src="{{ $contact->profile_image_url }}" alt="Profile"
                class="w-10 h-10 rounded-full object-cover opacity-70">
            <span
                class="absolute -bottom-1 -right-1 bg-gray-400 rounded-full w-4 h-4 border-2 border-white"></span>
        </div>
        <div>
            <p class="font-medium text-gray-500">{{ $contact->name }}</p>
            <div class="flex items-center mt-1 gap-2">
                <span
                    class="bg-gray-200 text-gray-800 text-xs px-2 py-0.5 rounded-full flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Merged
                </span>
                <span class="text-xs text-gray-500">
                    → <span id="mergedContactName" class="ml-1 text-blue-900"><a href="{{ $contact->masterContact ? route('contact.show', $contact->masterContact->id) : '#' }}">{{ $contact->masterContact->name ?? '' }}</a></span>
                </span>
            </div>
        </div>
    </div>
</div>
<!-- Personal Details Section -->
<div class="mb-4 col-span-12">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Personal Details</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-500 mb-1">Full Name</p>
            <p class="font-medium">{{ $contact->name }}</p>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-500 mb-1">Email</p>
            <p class="font-medium">{{ $contact->email }}</p>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-500 mb-1">Phone</p>
            <p class="font-medium">{{ $contact->phone }}</p>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-500 mb-1">Gender</p>
            <p class="font-medium">{{ $contact->gender }}</p>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-500 mb-1">Additional File</p>
            <p class="font-medium text-blue-700"><a href="{{ $contact->additional_file_url ?? '' }}" target="_blank">{{ $contact->additional_file_name ?? '' }}</a></p>
        </div>
    </div>
</div>

<!-- Professional Information -->
<div class="mb-4 col-span-12">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Custom Fields</h2>
    <div class="grid grid-cols-12 gap-4">
        @foreach ($contact->customFields as $customField)
        <div class="bg-gray-50 p-4 rounded-lg 
            @if ($customField->fieldType->name != 'Long Text')
                col-span-6
            @else
                col-span-12
            @endif
        ">
            <p class="text-sm text-gray-500 mb-1">{{ $customField->display_name }}</p>
            <div class="flex flex-wrap gap-2 mt-2">
                @if ($customField->display_values)
                    @foreach (explode(',', $customField->display_values) as $value)
                        @php
                            $color = $colours[array_rand($colours)];
                        @endphp
                        <span class="px-3 py-1 bg-{{ $color }}-100 text-{{ $color }}-800 text-xs font-medium rounded-full">{{ $value }}</span>
                    @endforeach
                @else
                    @if ($customField->fieldType->name == 'Date')
                        {{ \Carbon\Carbon::parse($customField->pivot->value)->format('d M Y') }}
                    @elseif ($customField->fieldType->name == 'Time')
                        {{ \Carbon\Carbon::parse($customField->pivot->value)->format('h:i A') }}
                    @else
                        {{ $customField->pivot->value }}
                    @endif
                @endif
            </div>
        </div>    
        @endforeach
        
    </div>
</div>