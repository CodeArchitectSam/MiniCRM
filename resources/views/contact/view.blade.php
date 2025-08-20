<x-app-layout>
    <div class="container mx-auto px-4 py-8  md:px-8 lg:px-12 xl:px-16">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Contacts</h1>
                <p class="text-gray-600">Manage your contact list</p>
            </div>
            <div class="flex gap-2">
                <a id="backToListButton"
                    class="flex items-center gap-2 px-4 py-2 border rounded-lg shadow-md transition hover:bg-gray-100 hover:text-gray-700"
                    href="{{ route('contact') }}">
                    Back To List
                </a>
            </div>
        </div>

        <!-- Contact View -->
        <div id="contactView">
            @include('contact.partials.view-container', ['contact' => $contact])
        </div>
    </div>

    <div id="contactsForMergingModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden overflow-auto top-0 left-0 right-0 bottom-0 grid place-items-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-sm md:max-w-5xl my-6">
            <div class="flex justify-between items-center border-b border-gray-200 px-6 py-4">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Select Contact</h3>
                <button onclick="closeModal('contactsForMerging')" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="">
                <div class="grid grid-cols-12 gap-4 px-6 py-3 bg-gray-100 border-b border-gray-200">
                    <div class="col-span-1 font-medium text-gray-700 text-right"></div>
                    <div class="col-span-4 font-medium text-gray-700">Contact</div>
                    <div class="col-span-3 font-medium text-gray-700">Email</div>
                    <div class="col-span-2 font-medium text-gray-700">Phone</div>
                </div>
                <div id="contactsList" class="max-h-96 overflow-y-auto">
                    
                </div>
            </div>
            <div class="flex justify-end items-center border-t border-gray-200 px-6 py-4">
                <button type="button" onclick="closeModal('contactsForMerging')"
                    class="px-4 py-2 mr-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="button" onclick="mergeContact()"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition"
                    id="mergeContactBtn">
                    Merge
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');
            
            function switchTab(tabId) {
                // Hide all tab contents
                tabContents.forEach(content => {
                    content.classList.remove('active');
                });
                
                // Reset all tab buttons
                tabButtons.forEach(button => {
                    button.classList.remove('bg-indigo-100', 'text-indigo-700');
                    button.classList.add('hover:bg-indigo-50', 'hover:text-indigo-600', 'text-gray-600');
                });
                
                // Show the selected tab content
                const selectedTab = document.getElementById(tabId);
                if (selectedTab) {
                    selectedTab.classList.add('active');
                }
                
                // Activate the clicked button
                const selectedButton = document.querySelector(`.tab-button[data-tab="${tabId}"]`);
                if (selectedButton) {
                    selectedButton.classList.add('bg-indigo-100', 'text-indigo-700');
                    selectedButton.classList.remove('hover:bg-indigo-50', 'hover:text-indigo-600', 'text-gray-600');
                }
            }
            
            // Add click event listeners to all tab buttons
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');
                    switchTab(tabId);
                });
            });
            
            // Initialize first tab as active
            if (tabButtons.length > 0) {
                const firstTabId = tabButtons[0].getAttribute('data-tab');
                switchTab(firstTabId);
            }
        });

        function getContactListForMerging() {
            event.preventDefault();

            let url = "{{ route('contact.merge.get-contacts', ':id') }}";
            url = url.replace(':id', {{ $contact->id }});
            
            fetch(url, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showContactsForMergingModal(data.html);
                }
            })
            .catch(error => {
                console.error('Error fetching custom fields:', error);
            });
        }

        function openModal(type) {
            if (type === 'contactsForMerging') {
                document.getElementById('contactsForMergingModal').classList.remove('hidden');
            }
        }

        function closeModal(type) {
            if (type === 'contactsForMerging') {
                document.getElementById('contactsForMergingModal').classList.add('hidden');
            }
        }

        function showContactsForMergingModal(contacts) {
            openModal('contactsForMerging');

            document.getElementById('contactsList').innerHTML = contacts;
        }

        function mergeContact() {
            event.preventDefault();

            if (!confirm('Are you sure you want to merge this contact? This action cannot be undone.')) {
                return;
            }

            showSpinner('mergeContactBtn', 'Merging...');
            
            let parentContact = document.querySelector('input[name="parent_contact"]:checked');

            if (!parentContact) {
                alert('Please select a contact to merge with.');
                return;
            }

            let parentContactId = parentContact.value;
            let childContactId = {{ $contact->id }};
            
            let url = "{{ route('contact.merge') }}";

            let formData = new FormData();
            formData.append('parent_contact', parentContactId);
            formData.append('child_contact', childContactId);

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeModal('contactsForMerging');
                    setContactAsMerged(data.parentContact);
                    showNotification('success', 'Contact details merged successfully.');
                } else {
                    showNotification('error', 'Error merging contact details.' + data.message);
                }
            })
            .catch(error => {
                console.error('Error merging contacts:', error);
                showNotification('error', 'Error merging contact details.' + data.message);
            })
            .finally(() => {
                hideSpinner('mergeContactBtn');
            });
        }

        function setContactAsMerged(parentContact) {

            let mergedContactRoute = "<a href='{{  route('contact.show', ':id') }}'>";

            let mergedContactName = document.getElementById('mergedContactName');
            mergedContactRoute = mergedContactRoute.replace(':id', parentContact.parent_contact_id);
            mergedContactName.innerHTML = mergedContactRoute + parentContact.parent_contact_name + '</a>';

            let mergedContactDiv = document.getElementById('mergedContactDiv');
            mergedContactDiv.classList.remove('hidden');

            hideMergeButtonDiv();
        }

        function hideMergeButtonDiv() {
            let mergeButtonDiv = document.getElementById('mergeButtonDiv');
            mergeButton.classList.add('hidden');
        }

    </script>


    <style>
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: grid;
        }

        .tab-button.active {
            color: #2563eb;
            border-bottom-color: #2563eb;
        }

        .profile-banner {
            height: 200px;
            background: linear-gradient(135deg, #ddd6f3 0%, #faaca8 100%);
        }

        .profile-pic {
            border: 4px solid white;
            /* margin-top: -65px; */
        }
    </style>
</x-app-layout>