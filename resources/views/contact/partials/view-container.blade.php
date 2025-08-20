<div class="w-full mx-auto bg-white shadow-md rounded-lg overflow-hidden">
    <!-- Banner -->
    <div class="profile-banner w-full p-4">
        <!-- Profile Header -->
        <div class="w-full h-full relative">
            <div class="flex justify-between items-end absolute bottom-0 left-0">
                <div class="flex items-end">
                    <img src="{{ $contact->profile_image_url }}" alt="Profile"
                        class="profile-pic w-32 h-32 rounded-full object-cover shadow-md">
                    <div class="">
                        <h1 class="text-2xl font-bold text-gray-800">{{ $contact->name }}</h1>
                    </div>
                </div>
            </div>
            @if ($contact->merged_into == null)
            <div class="flex justify-between items-end absolute bottom-0 right-0" id="mergeButtonDiv">
                <div class="flex items-end">
                    <a id="mergeButton"
                        class="flex items-center gap-2 px-4 py-2 border rounded-lg shadow-md transition hover:bg-gray-100 hover:text-gray-700 cursor-pointer"
                        onclick="getContactListForMerging()">
                        Merge
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Profile Content -->
    <div class="px-6 pb-6">
        <div class="flex space-x-2 mt-6" id="contact-tab-buttons">
            <button
                class="tab-button  px-5 py-2 rounded-full font-medium text-sm focus:outline-none transition-all duration-200 bg-indigo-100 text-indigo-700"
                data-tab="primary_tab" id="primary_tab_btn">
                Primary
            </button>
            @foreach ($contact->mergedContacts as $mergedContact)
            <button
                class="tab-button px-5 py-2 rounded-full font-medium text-sm focus:outline-none transition-all duration-200 hover:bg-indigo-50 hover:text-indigo-600 text-gray-600 bg-gray-50"
                data-tab="tab_{{ $mergedContact->id }}" id="merged_tab_btn">
                Merged : {{ $mergedContact->name }}
            </button>
            @endforeach
        </div>
        <!-- Tab contents -->
        <div class="" id="contact-tab-contents">
            <div id="primary_tab" class="tab-content active pt-6 grid grid-cols-12 gap-4 px-1">
                @include('contact.partials.view-single-contact', ['contact' => $contact])
            </div>
            @foreach ($contact->mergedContacts as $mergedContact)
            <div id="tab_{{ $mergedContact->id }}" class="tab-content pt-6 grid grid-cols-12 gap-4 px-1">
                @include('contact.partials.view-single-contact', ['contact' => $mergedContact])
            </div>
            @endforeach
        </div>
    </div>
</div>