<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="grid grid-cols-12 gap-4 px-6 py-3 bg-gray-100 border-b border-gray-200">
        <div class="col-span-3 font-medium text-gray-700">Name</div>
        <div class="col-span-5 font-medium text-gray-700">Description</div>
        <div class="col-span-2 font-medium text-gray-700">Required</div>
        <div class="col-span-2 font-medium text-gray-700 text-right">Actions</div>
    </div>

    <div id="customFieldList">
        @if ($customFields->isEmpty())
        <div class="px-6 py-4 text-center text-gray-500">
            No custom fields found.
        </div>
        @endif
        @foreach($customFields as $field)
        <div class="grid grid-cols-12 gap-4 px-6 py-4 border-b border-gray-200 hover:bg-gray-50">
            <div class="col-span-3 flex items-center gap-4">
                <div>
                    <p class="font-medium text-gray-800">{{ $field->display_name }}</p>
                </div>
            </div>
            <div class="col-span-5 flex items-center text-gray-600">{{ $field->description }}</div>
            <div class="col-span-2 flex items-center text-gray-600">
                <label class="inline-flex items-center pointer-events-none">
                    <input type="checkbox" class="sr-only peer" {{ $field->is_required ? 'checked' : '' }} disabled>
                    <div
                        class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 peer-checked:bg-blue-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all opacity-50">
                    </div>
                    </span>
                </label>
            </div>
            <div class="col-span-2 flex items-center justify-end gap-2">
                <a class="text-blue-600 hover:text-blue-800 p-1 cursor-pointer" onclick="getCustomFieldDetails({{ $field->id }})">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </a>
                <a class="text-green-600 hover:text-green-800 p-1 cursor-pointer" onclick="getCustomFieldDetails({{ $field->id }}, 1)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>
            </div>
        </div>
        @endforeach
    </div>

    @if ($customFields->hasPages())
    <div class="px-6 py-3" id="pagination-container">
        {{ $customFields->links() }}
    </div>
    @endif
</div>