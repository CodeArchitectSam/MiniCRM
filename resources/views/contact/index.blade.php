<x-app-layout>
    <div class="container mx-auto px-4 py-8  md:px-8 lg:px-12 xl:px-16">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Contacts</h1>
                <p class="text-gray-600">Manage your contact list</p>
            </div>
            <div class="flex gap-2">
                <button id="newContactButton" onclick="openModal('contact')"
                    class="flex items-center gap-2 px-4 py-2 border border-green-600 rounded-md text-white bg-green-500 hover:bg-green-600 shadow-md transition">
                    Add New Contact
                </button>
                <button id="filterButton" onclick="openModal('filter')"
                    class="flex items-center gap-2 px-4 py-2 border border-yellow-400 rounded-md text-gray-700 bg-yellow-300 hover:bg-yellow-400 shadow-md transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filters
                    <span id="filterCount"
                        class="bg-red-600 text-white font-bold text-xs px-2 py-0.5 rounded-full hidden">0</span>
                </button>
            </div>
        </div>

        <!-- Applied Filters -->
        <div id="appliedFilters" class="flex flex-wrap gap-2 mb-4 hidden">
            <!-- Filters will appear here as chips -->
        </div>

        <div id="contactList">
            @include('contact.partials.contact-list', ['contacts' => $contacts])
        </div>
    </div>

    <div id="filterModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden overflow-auto top-0 left-0 right-0 bottom-0 grid place-items-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-sm md:max-w-4xl my-6">
            <div class="flex justify-between items-center border-b border-gray-200 px-6 py-4">
                <h3 class="text-lg font-medium text-gray-900">Filter Contacts</h3>
                <button id="closeFilterModal" onclick="closeModal('filter')" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="px-6 py-4 space-y-4">
                <div class="grid grid-cols-12 gap-4 p-4">

                    <!-- Name Field -->
                    <div class="col-span-6">
                        <label for="name_filter" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" id="name_filter" name="name_filter" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Email Field -->
                    <div class="col-span-6">
                        <label for="email_filter" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email_filter" name="email_filter" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Phone Field -->
                    <div class="col-span-6">
                        <label for="phone_filter" class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="tel" id="phone_filter" name="phone_filter"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Gender Field -->
                    <div class="col-span-6">
                        <label for="gender_filter" class="block text-sm font-medium text-gray-700">Gender</label>
                        <div class="flex mt-1">
                            <div class="flex items-center">
                                <input id="gender_filter_male" type="radio" value="male" name="gender_filter" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="gender_filter_male" class="ms-1 text-sm font-medium text-gray-900 dark:text-gray-300 mr-3">Male</label>
                                <input id="gender_filter_female" type="radio" value="female" name="gender_filter" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="gender_filter_female" class="ms-1 text-sm font-medium text-gray-900 dark:text-gray-300 mr-3">Female</label>
                                <input id="gender_filter_other" type="radio" value="other" name="gender_filter" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="gender_filter_other" class="ms-1 text-sm font-medium text-gray-900 dark:text-gray-300 mr-3">Other</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12">
                        <h4 class="text-md font-medium text-gray-900 mt-8 mb-4 pb-2 border-b border-gray-200">Custom
                            Fields</h4>
                        <div class="grid grid-cols-12 gap-4" id="custom_fields_flter_container">
                            <!-- Custom fields will be dynamically inserted here -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4  border-t border-gray-200 flex justify-end gap-3">
                <button id="resetModalFilters" onclick="resetModalFilters()" type="button"
                    class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                    Reset
                </button>
                <button id="applyFiltersBtn" onclick="applyFilters()" type="button"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    Apply Filters
                </button>
                <input type="hidden" name="applied_filters" id="applied_filters" value="">
            </div>
        </div>
    </div>

    <div id="addContactModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden overflow-auto top-0 left-0 right-0 bottom-0 grid place-items-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-sm md:max-w-4xl my-6">
            <div class="flex justify-between items-center border-b border-gray-200 px-6 py-4">
                <h3 class="text-lg font-medium text-gray-900" id="addContactModalTitle">Add New Contact</h3>
                <button onclick="closeModal('contact')" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="addContactForm" class="">
                @csrf
                <div class="px-6 py-5">
                    <div class="grid grid-cols-12 gap-4 px-4 max-w-4xl w-full bg-white overflow-hidden">
                        <!-- Profile Image Section -->
                        <div class="col-span-12 flex flex-col items-center mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Profile Image</label>
                            <div class="relative">
                                <input type="file" id="profile_image" name="profile_image_path" accept="image/*"
                                    class="hidden" onchange="previewProfileImage(this)">
                                <div class="w-24 h-24 rounded-full bg-gray-200 overflow-hidden cursor-pointer border-2 border-gray-300 hover:border-blue-500 transition"
                                    onclick="selectProfileImage()">
                                    <img id="profile_image_preview" src="" alt="Profile Image"
                                        class="w-full h-full object-cover hidden">
                                    <div id="profile_image_placeholder"
                                        class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </div>
                                </div>
                                <button type="button" onclick="removeProfileImage()"
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 hidden"
                                    id="remove_image_btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <p id="profile_image_pathError" class="text-red-500 text-sm mt-1 hidden"></p>
                        </div>

                        <!-- Name Field -->
                        <div class="col-span-6">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name *</label>
                            <input type="text" id="name" name="name" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <input type="hidden" name="contact_id" id="contact_id" value="">
                            <input type="hidden" name="edit" id="edit" value="0">
                            <p id="nameError" class="text-red-500 text-sm mt-1 hidden"></p>
                        </div>

                        <!-- Email Field -->
                        <div class="col-span-6">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                            <input type="email" id="email" name="email" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <input type="hidden" name="edit" id="edit" value="0">
                            <p id="emailError" class="text-red-500 text-sm mt-1 hidden"></p>
                        </div>

                        <!-- Phone Field -->
                        <div class="col-span-6">
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                            <input type="tel" id="phone" name="phone"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <p id="phoneError" class="text-red-500 text-sm mt-1 hidden"></p>
                        </div>

                        <!-- Gender Field -->
                        <div class="col-span-6">
                            <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                            <div class="flex mt-1">
                                <div class="flex items-center">
                                    <input id="gender_male" type="radio" value="male" name="gender" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="gender_male" class="ms-1 text-sm font-medium text-gray-900 dark:text-gray-300 mr-3">Male</label>
                                    <input id="gender_female" type="radio" value="female" name="gender" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="gender_female" class="ms-1 text-sm font-medium text-gray-900 dark:text-gray-300 mr-3">Female</label>
                                    <input id="gender_other" type="radio" value="other" name="gender" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="gender_other" class="ms-1 text-sm font-medium text-gray-900 dark:text-gray-300 mr-3">Other</label>
                                </div>
                            </div>
                        </div>

                        <!-- Additional File Upload -->
                        <div class="col-span-12">
                            <label for="additional_file" class="block text-sm font-medium text-gray-700">Additional
                                File</label>
                            <div class="mt-1">
                                <div id="additional_file_container" class="flex items-center">
                                    <input type="file" id="additional_file" name="additional_file_path" class="hidden"
                                        onchange="updateFileName(this)">
                                    <input type="text" id="additional_file_name" readonly
                                        class="flex-1 border border-gray-300 rounded-l-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="No file chosen">
                                    <button type="button" onclick="document.getElementById('additional_file').click()"
                                        class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-r-md border border-l-0 border-gray-300">
                                        Browse
                                    </button>
                                </div>
                                <div id="existing_file_container" class="flex items-center hidden">
                                    <div class="relative mt-2">
                                        <a class="rounded-md bg-gray-50 px-5 py-2 overflow-hidden cursor-pointer border-2 border-gray-300 hover:border-blue-500 transition"
                                            id="existing_file_placeholder" target="_blank"></a>
                                        <button type="button" onclick="removeExistingAdditionalFile()"
                                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 hidden"
                                            id="remove_existing_file_btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <p id="additional_file_pathError" class="text-red-500 text-sm mt-1 hidden"></p>
                        </div>

                        <div class="col-span-12">
                            <h4 class="text-md font-medium text-gray-900 mt-8 mb-4 pb-2 border-b border-gray-200">
                                Custom Fields</h4>
                            <div class="grid grid-cols-12 gap-4" id="custom_fields_container">
                                <!-- Custom fields will be dynamically inserted here -->
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="col-span-12 flex pt-4 border-t border-gray-200 justify-end gap-3">
                            <button type="button" onclick="closeModal('contact')"
                                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                                Cancel
                            </button>
                            <button type="button" onclick="saveContact()"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition"
                                id="saveContactBtn">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            getCustomFields();
        });

        function loadContacts(url = '') {
            let appliedFilters = document.getElementById('applied_filters').value;
            let formData = new FormData();
            let method = 'GET';
            let fetchObject = {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            };
            if (url === '' && appliedFilters == '') {
                url = "{{ route('contact') }}";
            } else if (appliedFilters != '') {
                //if pagination url, then split the url and get the last part
                let urlParts = url.split('?');
                if (urlParts.length > 1) {
                    let page = urlParts[1].split('=')[1];
                    formData.append('page', page);
                }
                
                url = "{{ route('contact.filter') }}";
                formData.append('appliedFilters', appliedFilters);
                method = 'POST';
            }
            fetchObject.method = method;
            method == 'POST' ? fetchObject.body = formData : null;

            fetch(url, fetchObject)
            .then(response => response.json())
            .then(data => {
                document.getElementById('contactList').innerHTML = data.html;
            })
            .catch(error => {
                console.error('Error loading contacts:', error);
            });
        }

        function previewProfileImage(input) {
            const preview = document.getElementById('profile_image_preview');
            const placeholder = document.getElementById('profile_image_placeholder');
            const removeBtn = document.getElementById('remove_image_btn');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    removeBtn.classList.remove('hidden');
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        function selectProfileImage() {
            const fileInput = document.getElementById('profile_image');
            fileInput.click();
        }

        function removeProfileImage() {
            const preview = document.getElementById('profile_image_preview');
            const placeholder = document.getElementById('profile_image_placeholder');
            const removeBtn = document.getElementById('remove_image_btn');
            const fileInput = document.getElementById('profile_image');
            
            preview.src = '';
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
            removeBtn.classList.add('hidden');
            fileInput.value = '';
        }
        
        function updateFileName(input) {
            const fileNameField = document.getElementById('additional_file_name');
            if (input.files && input.files[0]) {
                fileNameField.value = input.files[0].name;
            } else {
                fileNameField.value = '';
            }
        }

        function openModal(type) {
            if (type === 'contact') {
                document.getElementById('addContactModal').classList.remove('hidden');
            } else if (type === 'filter') {
                document.getElementById('filterModal').classList.remove('hidden');
            }
        }
        
        function closeModal(type) {
            if (type === 'contact') {
                document.getElementById('addContactModal').classList.add('hidden');
                resetContactForm();
            } else if (type === 'filter') {
                document.getElementById('filterModal').classList.add('hidden');
            }
        }

        function getCustomFields() {
            let url = "{{ route('custom-field.list') }}";
            
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
                    createCustomFields(data.customFields);
                }
            })
            .catch(error => {
                console.error('Error fetching custom fields:', error);
            });
        }

        function createCustomFields(customFields) {
            const customFieldsContainer = document.getElementById('custom_fields_container');
            customFieldsContainer.innerHTML = '';

            const customFieldsFilterContainer = document.getElementById('custom_fields_flter_container');
            customFieldsFilterContainer.innerHTML = '';

            customFields.forEach(customField => {
                const fieldContainer = generateCustomFieldContainer(customField);
                const label = generateCustomFieldLabel(customField);
                const input = generateInputField(customField);
                const errorElement = customField.is_required == 1 ? generateInputFieldError(customField) : '';
                fieldContainer.appendChild(label);
                fieldContainer.appendChild(input);
                if (errorElement) {
                    fieldContainer.appendChild(errorElement);
                }
                customFieldsContainer.appendChild(fieldContainer);

                const filterFieldContainer = generateCustomFieldContainer(customField);
                const filterLabel = generateCustomFieldLabel(customField, true);
                const filterInput = generateInputField(customField, true);
                filterFieldContainer.appendChild(filterLabel);
                filterFieldContainer.appendChild(filterInput);
                customFieldsFilterContainer.appendChild(filterFieldContainer);
            });
        }

        function generateCustomFieldContainer(customField) {
            const fieldContainer = document.createElement('div');
            switch (customField.field_type.name) {
                case 'Long Text':
                    fieldContainer.classList.add('col-span-12');
                    break;
                case 'Date':
                case 'Time':
                    fieldContainer.classList.add('col-span-3');
                    break;
                default:
                    fieldContainer.classList.add('col-span-6');
            }
            return fieldContainer;
        }

        function generateCustomFieldLabel(customField, filter = false) {
            const label = document.createElement('label');
            label.setAttribute('for', customField.name);
            label.classList.add('block', 'text-sm', 'font-medium', 'text-gray-700');
            label.textContent = customField.display_name + ((customField.is_required == 1 && !filter) ? ' *' : '');
            return label;
        }

        function generateInputField(customField, filter = false) {

            let input = '';

            switch (customField.field_type.name) {
                case 'Text':
                    input = document.createElement('input');
                    input.setAttribute('type', 'text');
                    break;
                case 'Long Text':
                    input = document.createElement('textarea');
                    input.setAttribute('rows', '4');
                    break;
                case 'Number':
                    input = document.createElement('input');
                    input.setAttribute('type', 'number');
                    break;
                case 'Date':
                    input = document.createElement('input');
                    input.setAttribute('type', 'date');
                    break;
                case 'Time':
                    input = document.createElement('input');
                    input.setAttribute('type', 'time');
                    break;
                case 'Dropdown':
                    input = document.createElement('select');
                    break;
                case 'CheckBox':
                    input = document.createElement('div');
                    break;
                case 'Radio Button':
                    input = document.createElement('div');
                    break;
                default:
                    input = document.createElement('input');
                    input.setAttribute('type', 'text');
            }

            if (customField.field_type.name != 'CheckBox' && customField.field_type.name != 'Radio Button') {
                input.setAttribute('name', filter ? customField.name + '_filter' : customField.name);
                input.setAttribute('id', filter ? customField.name + '_filter' : customField.name);
            }
            
            if (
                customField.field_type.name != 'CheckBox' 
                && customField.field_type.name != 'Radio Button'
            ) {
                input.setAttribute('class', 'mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md');
            }

            if (
                customField.field_type.name == 'Dropdown'
                || customField.field_type.name == 'CheckBox'
                || customField.field_type.name == 'Radio Button'
            ) {
                input.innerHTML = generateInputFieldOptions(
                    customField.field_type.name, 
                    customField.options, 
                    customField.name,
                    filter
                );
            }

            return input;
        }

        function generateInputFieldOptions(fieldType, options, fieldName, filter = false) {
            let fieldOptions = '';
            switch (fieldType) {
                case 'Dropdown':
                    fieldOptions += '<option value="">-- Select Option --</option>';
                    options.forEach(option => {
                        fieldOptions += `<option value="${option.id}">${option.display_text}</option>`;
                    });
                    break;
                case 'CheckBox':
                    let count = 1;
                    fieldOptions += '<div class="flex mt-1"><div class="flex items-center">';
                    options.forEach(option => {
                        let name = filter ? fieldName + '_filter' : fieldName;
                        fieldOptions += `<input type="checkbox" name="${name+'[]'}" id="${name+'_'+option.id}" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 " value="${option.id}">
                                            <label for="${name+'_'+option.id}" class="ml-1 mr-3 block text-sm font-medium text-gray-700">
                                                ${option.display_text}
                                            </label>`;
                    });
                    fieldOptions += '</div></div>';
                    break;
                case 'Radio Button':
                    fieldOptions += '<div class="flex mt-1"><div class="flex items-center">';
                    options.forEach(option => {
                        let name = filter ? fieldName + '_filter' : fieldName;
                        fieldOptions += `<input type="radio" name="${name}" id="${name+'_'+option.id}" value="${option.id}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="${name+'_'+option.id}" class="ml-1 mr-3 block text-sm font-medium text-gray-700">
                                                ${option.display_text}
                                            </label>`;
                    });
                    break;
                default:
                    return null;
            }

            return fieldOptions;
        }

        function generateInputFieldError(customField) {
            const errorElement = document.createElement('p');
            errorElement.setAttribute('id', customField.name + 'Error');
            errorElement.classList.add('text-red-500', 'text-sm', 'mt-1', 'hidden');
            return errorElement;
        }

        function saveContact() {

            showSpinner('saveContactBtn', 'Saving...');

            resetErrorMessages();

            const form = document.getElementById('addContactForm');
            const formData = new FormData(form);
            
            let url = "{{ route('contact.store') }}";
            const edit = document.getElementById('edit').value;
            if (edit == 1) {
                formData.append('_method', 'PUT');
                url = "{{ route('contact.update', ':id') }}";
                url = url.replace(':id', document.getElementById('contact_id').value);
            }
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw err;
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    loadContacts();
                    closeModal('contact')
                    form.reset();
                    showNotification('success', 'Contact '+(edit == 1 ? 'updated' : 'created')+' successfully!');
                }
            })
            .catch(error => {
                console.error('Error saving contact:', error);
                if (error.errors) {
                    // If validation errors exist, show them
                    showFieldErrorMessages(error.errors);
                } else if (error.message) {
                    // If a general error message exists, show it
                    showNotification('error', error.message);
                } else {
                    showNotification('error', 'An error occurred while '+(edit == 1 ? 'updating' : 'creating')+' the contact.');
                }
            })
            .finally(() => {
                hideSpinner('saveContactBtn');
            });
        }

        function showFieldErrorMessages(errors) {
            for (const key in errors) {
                if (errors.hasOwnProperty(key)) {
                    const field = document.getElementById(key);
                    if (field) {
                        field.classList.add('border-red-500');
                    }
                    const errorElement = document.getElementById(key + 'Error');
                    if (errorElement) {
                        errorElement.classList.remove('hidden');
                        errorElement.textContent = errors[key][0];
                    }
                }
            }
        }

        document.addEventListener('click', function (e) {
            if (e.target.closest('#pagination-container a')) {
                e.preventDefault();
                const url = e.target.closest('a').getAttribute('href');
                if (url) {
                    loadContacts(url);
                }
            }
        });

        function applyFilters() {

            showSpinner('applyFiltersBtn', 'Processing...');

            const filterInputs = document.querySelectorAll('input[id$="_filter"]');
            const filterInputsCheckboxesAndRadios = document.querySelectorAll('input[id*="_filter_"]');
            const filterSelects = document.querySelectorAll('select[id$="_filter"]');
            const filterTextareas = document.querySelectorAll('textarea[id$="_filter"]');
            
            const filterData = [];
            const filterDataToSend = {};

            filterInputs.forEach(input => {
                const key = input.id.replace('_filter', '');
                if (input.value) {
                    filterData[key] = input.value;
                    filterDataToSend[key] = input.value;
                }
            });

            filterInputsCheckboxesAndRadios.forEach(input => {
                let key = (input.id.split('_filter_')[0]);
                if (input.checked) {
                    if (!filterData[key]) {
                        filterData[key] = [];
                        filterDataToSend[key] = [];
                    }
                    const label = document.querySelector(`label[for="${input.id}"]`);
                    filterData[key].push(label.textContent.trim());
                    filterDataToSend[key].push(input.value);
                }
            });

            filterSelects.forEach(select => {
                const key = select.id.replace('_filter', '');
                if (select.value) {
                    filterData[key] = select.options[select.selectedIndex].text;
                    filterDataToSend[key] = select.value;
                }
            });
            
            filterTextareas.forEach(textarea => {
                const key = textarea.id.replace('_filter', '');
                console.log(textarea.value);
                if (textarea.value) {
                    filterData[key] = textarea.value;
                    filterDataToSend[key] = textarea.value;
                }
            });
            
            let filterCount = Object.keys(filterData).length;
            
            const filterCountBadge = document.getElementById('filterCount');
            if (filterCount > 0) {
                filterCountBadge.textContent = filterCount;
                filterCountBadge.classList.remove('hidden');
            } else {
                filterCountBadge.classList.add('hidden');
            }

            const appliedFiltersContainer = document.getElementById('appliedFilters');
            appliedFiltersContainer.innerHTML = '';
            
            if (filterCount > 0) {
                appliedFiltersContainer.classList.remove('hidden');
                for (const [key, value] of Object.entries(filterData)) {
                    if (value) {
                        addFilterChip((key.charAt(0).toUpperCase() + key.slice(1)).replaceAll('_',' ') + ': ' + value, key);
                    }
                }
            } else {
                appliedFiltersContainer.classList.add('hidden');
            }
            
            document.getElementById('filterModal').classList.add('hidden');
            document.getElementById('applied_filters').value = JSON.stringify(filterDataToSend);

            loadContacts();
            hideSpinner('applyFiltersBtn');
        }
    
        function addFilterChip(label, key) {
            const chip = document.createElement('div');
            chip.className = 'bg-blue-200 text-blue-900 text-sm font-bold px-3 py-1 rounded-full flex items-center shadow-sm';
            chip.innerHTML = `
                ${label}
                <button onclick="removeFilter('${key}')" class="ml-1 text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            `;
            document.getElementById('appliedFilters').appendChild(chip);
        }
    
        window.removeFilter = function(key) {
            const filterInputs = document.querySelectorAll(`input[id*="${key}_filter"]`);
            const filterSelects = document.querySelectorAll(`select[id*="${key}_filter"]`);
            const filterTextareas = document.querySelectorAll(`textarea[id*="${key}_filter"]`);
            
            filterInputs.forEach(input => {
                if (input.type === 'checkbox' || input.type === 'radio') {
                    input.checked = false;
                } else {
                    input.value = '';
                }
            });

            filterSelects.forEach(select => {
                select.selectedIndex = 0;
            });

            filterTextareas.forEach(textarea => {
                textarea.value = '';
            });

            document.getElementById('applyFiltersBtn').click();
        };
    
        function resetModalFilters() {
            const filterInputs = document.querySelectorAll(`input[id*="_filter"]`);
            const filterSelects = document.querySelectorAll(`select[id*="_filter"]`);
            const filterTextareas = document.querySelectorAll(`textarea[id*="_filter"]`);
            
            filterInputs.forEach(input => {
                if (input.type === 'checkbox' || input.type === 'radio') {
                    input.checked = false;
                } else {
                    input.value = '';
                }
            });

            filterSelects.forEach(select => {
                select.selectedIndex = 0;
            });

            filterTextareas.forEach(textarea => {
                textarea.value = '';
            });
        }

        function resetContactForm() {
            resetErrorMessages();
            document.getElementById('addContactForm').reset();
            
            removeProfileImage();

            showAdditionalFileContainer();
            hideExistingAdditionalFileContainer();
            disableEnableFieldElements(1);

            document.getElementById('edit').value = 0;
            document.getElementById('addContactModalTitle').innerText = 'Add New Contact';
            document.getElementById('saveContactBtn').innerText = 'Save';
            document.getElementById('saveContactBtn').classList.remove('hidden');
        }

        function resetErrorMessages() {
            const errorElements = document.querySelectorAll('[id$="Error"]');
            errorElements.forEach(element => {
                element.classList.add('hidden');
                element.innerText = '';
            });

            const elements = document.querySelectorAll('.border-red-500');
            elements.forEach(element => {
                element.classList.remove('border-red-500');
            });
        }

        function getContactDetails(id, edit = 0) {
            let url = "{{ route('contact.show', ':id') }}";
            url = url.replace(':id', id);
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
                    displayContactDetails(data.contact, edit);
                } else {
                    showNotification('error', 'Error loading contact details.' + data.message);
                }
            })
            .catch(error => {
                showNotification('error', 'Error loading contact details.' + error.message);
            });
        }

        function displayContactDetails(data, edit = 0) {
            
            document.getElementById('addContactModal').classList.remove('hidden');
            document.getElementById('contact_id').value = data.id;
            
            displayStandardFields(data, edit);
            displayCustomFields(data.custom_fields, edit);
            disableEnableFieldElements(edit);

            if (edit == 0) {
                document.getElementById('addContactModalTitle').innerText = 'Contact Details';
                document.getElementById('saveContactBtn').classList.add('hidden');
            } else {
                document.getElementById('edit').value = 1;
                document.getElementById('addContactModalTitle').innerText = 'Edit Contact';
                document.getElementById('saveContactBtn').innerText = 'Update';
                document.getElementById('saveContactBtn').classList.remove('hidden');
            }
        }

        function displayStandardFields(data, edit) {
            for (const [key, value] of Object.entries(data)) {
                if (document.getElementById(key)) {
                    document.getElementById(key).value = value;
                } else if (document.getElementById('gender_' + value)) {
                    document.getElementById('gender_' + value).checked = true;
                }
            }
            displayExistingProfileImage(data.profile_image_url, edit);
            displayExistingAdditionalFile(data.additional_file_path, data.additional_file_url, edit);
        }

        function displayCustomFields(data) {
            console.log(data);
            
            for (const [key, element] of Object.entries(data)) {
                if (document.getElementById(element.name)) 
                {
                    document.getElementById(element.name).value = element.pivot.value; 
                } else {
                    if (!element.pivot.value) {
                        continue;
                    }
                    
                    const checkboxes = document.getElementsByName(element.name + '[]');  
                    for (let i = 0; i < checkboxes.length; i++) {
                        if (element.pivot.value.split(',').includes(checkboxes[i].value)) {
                            checkboxes[i].checked = true;
                        }
                    }

                    const radios = document.getElementsByName(element.name);
                    for (let i = 0; i < radios.length; i++) {
                        if (radios[i].value == element.pivot.value) {
                            radios[i].checked = true;
                        }
                    }
                }
            }
        }

        function displayExistingProfileImage(url, edit) {
            document.getElementById('profile_image_preview').src = url ? url : '';
            document.getElementById('profile_image_preview').classList.remove('hidden');
            document.getElementById('profile_image_placeholder').classList.add('hidden');
            if (edit == 0) {
                document.getElementById('remove_image_btn').classList.add('hidden');
            } else {
                document.getElementById('remove_image_btn').classList.remove('hidden');
            }
        }

        function displayExistingAdditionalFile(path, url, edit) {
            
            hideAdditionalFileContainer();

            if (!path) {
                hideExistingAdditionalFileContainer();
                showAdditionalFileContainer();
                return;
            }

            document.getElementById('existing_file_container').classList.remove('hidden');
            document.getElementById('existing_file_placeholder').innerText = extractFileNameFromPath(path);
            document.getElementById('existing_file_placeholder').href = url ? url : '';
            if (edit == 0) {
                document.getElementById('remove_existing_file_btn').classList.add('hidden');
            } else {
                document.getElementById('remove_existing_file_btn').classList.remove('hidden');
            }
        }

        function hideAdditionalFileContainer() {
            document.getElementById('additional_file_container').classList.add('hidden');
        }

        function showAdditionalFileContainer() {
            document.getElementById('additional_file_container').classList.remove('hidden');
        }

        function hideExistingAdditionalFileContainer() {
            document.getElementById('existing_file_container').classList.add('hidden');
            document.getElementById('remove_existing_file_btn').classList.add('hidden');
        }

        function removeExistingAdditionalFile() {
            hideExistingAdditionalFileContainer();
            showAdditionalFileContainer();
        }

        function disableEnableFieldElements(enable = 0) {
            const elements = document.querySelectorAll('#addContactForm input, #addContactForm select, #addContactForm textarea');
            elements.forEach(element => {
                element.disabled = enable === 0 ? true : false;
            });
        }

        function extractFileNameFromPath(path) {
            const filename = path.split('/').pop();
            const [id, ...rest] = filename.split('_');
            const title = rest.join('_');
            return title;
        }

        function deleteContact(id) {

            if (!confirm('Are you sure you want to delete this contact?')) {
                return;
            }

            let url = "{{ route('contact.destroy', ':id') }}";
            url = url.replace(':id', id);
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadContacts();
                    showNotification('success', 'Contact deleted successfully.');
                } else {
                    showNotification('error', 'Error deleting contact.');
                }
            })
            .catch(error => {
                showNotification('error', 'Error deleting contact.');
            });
        }

    </script>

</x-app-layout>