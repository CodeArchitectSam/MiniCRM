<x-app-layout>
    <div class="container mx-auto px-4 py-8  md:px-8 lg:px-12 xl:px-16">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Custom Fields</h1>
                <p class="text-gray-600">Manage your custom fields</p>
            </div>
            <div class="flex gap-2">
                <button onclick="openModal()" id="newCustomFieldButton"
                    class="flex items-center gap-2 px-4 py-2 border border-green-600 rounded-md text-white bg-green-500 hover:bg-green-600 shadow-md transition">
                    Add New Custom Field
                </button>
            </div>
        </div>
        <div id="customFieldList">
            @include('custom_field.partials.custom-field-list', ['customFields' => $customFields])
        </div>
    </div>

    <div id="addFieldModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden overflow-auto top-0 left-0 right-0 bottom-0 grid place-items-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-sm md:max-w-3xl my-6">
            <div class="flex justify-between items-center border-b border-gray-200 px-6 py-4">
                <h3 class="text-lg font-medium text-gray-900" id="addFieldModalTitle">Add New Custom Field</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="addFieldForm" class="space-y-4">
                @csrf
                <div class="grid grid-cols-12 gap-4 px-6 py-4">
                    <div class="col-span-6 flex items-center gap-4">
                        <div class="w-full">
                            <label for="display_name" class="block text-sm font-medium text-gray-700">Name *</label>
                            <input type="text" id="display_name" name="display_name" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <input type="hidden" name="name" id="name" value="">
                            <input type="hidden" name="field_id" id="field_id" value="">
                            <input type="hidden" name="edit" id="edit" value="0">
                            <p id="nameError" class="text-red-500 text-sm mt-1 hidden"></p>
                        </div>
                    </div>
                    <div class="col-span-6 flex items-center gap-4">
                        <div class="w-full">
                            <label for="is_required" class="block text-sm font-medium text-gray-700">Required</label>
                            <div class="py-2 mt-1"><input type="checkbox" id="is_required" name="is_required"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"></div>
                        </div>
                    </div>
                    <div class="col-span-12 flex items-center gap-4">
                        <div class="w-full">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="description" name="description" rows="3"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                    </div>
                    <div class="col-span-6 flex items-center gap-4">
                        <div class="w-full">
                            <label for="field_type_id" class="block text-sm font-medium text-gray-700">Field
                                Type *</label>
                            <select id="field_type_id" name="field_type_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" onchange="loadFieldOptions()">
                                <option value="">--Select a Field Type--</option>
                                @foreach($fieldTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            <p id="fieldTypeError" class="text-red-500 text-sm mt-1 hidden"></p>
                        </div>
                    </div>
                    <div class="col-span-12 flex items-center gap-4">
                        <div class="w-full hidden" id="select-options-div">
                            <label for="options" class="block text-sm font-medium text-gray-700">Options</label>
                            <textarea id="options" name="options" rows="3"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter options separated by new line"></textarea>
                        </div>
                    </div>
                    <div class="col-span-12 flex pt-4 border-t border-gray-200 justify-end gap-3">
                        <button type="button" onclick="closeModal()"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                            Cancel
                        </button>
                        <button type="button" onclick="saveField()"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition" id="saveFieldBtn">
                            Save
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <script>

        function openModal() {
            document.getElementById('addFieldModal').classList.remove('hidden');
            document.getElementById('display_name').focus();
        }
        
        function closeModal() {
            document.getElementById('addFieldModal').classList.add('hidden');
            resetCustomFieldForm();
            enableFieldElements();
        }

        function saveField() {

            showSpinner('saveFieldBtn', 'Saving...');

            const displayName = document.getElementById('display_name').value;
            const name = displayName.replace(/\s+/g, '_').replace(/[^a-zA-Z0-9_]+/g, '').toLowerCase();
            document.getElementById('name').value = name;

            const form = document.getElementById('addFieldForm');
            const formData = new FormData(form);
            
            let url = "{{ route('custom-field.store') }}";
            const edit = document.getElementById('edit').value;
            if (edit == 1) {
                formData.append('_method', 'PUT');
                url = "{{ route('custom-field.update', ':id') }}";
                url = url.replace(':id', document.getElementById('field_id').value);
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
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeModal();
                    form.reset();
                    showNotification('success', 'Field '+(edit == 1 ? 'updated' : 'created')+' successfully!');
                    loadFields();
                } else {
                    showErrorMessages(data.errors);
                }
            })
            .catch(error => {
                showNotification('error', 'An error occurred while '+(edit == 1 ? 'updating' : 'creating')+' the field.');
            })
            .finally(() => {
                hideSpinner('saveFieldBtn');
            });
        }
        
        function loadFields(url = '') {
            if (url === '') {
                url = "{{ route('custom-field') }}";
            }
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
                document.getElementById('customFieldList').innerHTML = data.html;
            })
            .catch(error => {
                console.error('Error loading fields:', error);
            });;
        }

        function loadFieldOptions() {
            const fieldTypeText = document.querySelector('#field_type_id option:checked').textContent;

            if (fieldTypeText === 'Dropdown' || fieldTypeText === 'CheckBox' || fieldTypeText === 'Radio Button') {
                document.querySelector('#select-options-div').classList.remove('hidden');
            } else {
                document.querySelector('#select-options-div').classList.add('hidden');
            }
        }

        document.addEventListener('click', function (e) {
            if (e.target.closest('#pagination-container a')) {
                e.preventDefault();
                const url = e.target.closest('a').getAttribute('href');
                if (url) {
                    loadFields(url);
                }
            }
        });

        function getCustomFieldDetails(id, edit = 0) {
            let url = "{{ route('custom-field.show', ':id') }}";
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
                    displayFieldDetails(data.customField, edit);
                } else {
                    showNotification('error', 'Error loading field details.');
                }
            })
            .catch(error => {
                showNotification('error', 'Error loading field details.');
            });
        }

        function displayFieldDetails(data, edit = 0) {
            document.getElementById('addFieldModal').classList.remove('hidden');
            document.getElementById('field_id').value = data.id;
            document.getElementById('display_name').value = data.display_name;
            document.getElementById('name').value = data.name;
            document.getElementById('is_required').checked = data.is_required;
            document.getElementById('description').value = data.description;
            document.getElementById('field_type_id').value = data.field_type_id;

            loadFieldOptions();
            let options = '';
            data.options.forEach(element => {
                options += (options != '' ? '\n' : '') + element.display_text;
            });
            document.getElementById('options').value = options ? options : '';

            document.getElementById('field_type_id').disabled = true;
            document.getElementById('options').disabled = true;

            if (edit == 0) {
                document.getElementById('display_name').disabled = true;
                document.getElementById('is_required').disabled = true;
                document.getElementById('description').disabled = true;
                document.getElementById('addFieldModalTitle').innerText = 'Custom Field Details';
                document.getElementById('saveFieldBtn').classList.add('hidden');
            } else {
                document.getElementById('edit').value = 1;
                document.getElementById('addFieldModalTitle').innerText = 'Edit Custom Field';
                document.getElementById('saveFieldBtn').innerText = 'Update';
                document.getElementById('saveFieldBtn').classList.remove('hidden');
            }
        }

        function enableFieldElements() {
            document.getElementById('field_type_id').disabled = false;
            document.getElementById('options').disabled = false;
            document.getElementById('display_name').disabled = false;
            document.getElementById('is_required').disabled = false;
            document.getElementById('description').disabled = false;
        }

        function resetCustomFieldForm() {
            document.getElementById('nameError').classList.add('hidden');
            document.getElementById('nameError').innerText = '';
            document.getElementById('fieldTypeError').classList.add('hidden');
            document.getElementById('fieldTypeError').innerText = '';
            document.getElementById('select-options-div').classList.add('hidden');
            document.getElementById('addFieldForm').reset();
            document.getElementById('field_id').value = '';
            document.getElementById('name').value = '';
            document.getElementById('edit').value = 0;
            document.getElementById('addFieldModalTitle').innerText = 'Add Custom Field';
            document.getElementById('saveFieldBtn').innerText = 'Save';
            document.getElementById('saveFieldBtn').classList.remove('hidden');
        }

        function showErrorMessages(errors) {
            for (let [key, value] of Object.entries(errors)) {
                key = key == 'display_name' ? 'name' : (key == 'field_type_id' ? 'fieldType' : key);
                document.getElementById(key + 'Error').classList.remove('hidden');
                document.getElementById(key + 'Error').innerText = value;
            }
        }
                
    </script>
</x-app-layout>