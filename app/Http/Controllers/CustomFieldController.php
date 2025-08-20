<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomFieldRequest;
use App\Http\Requests\UpdateCustomFieldRequest;
use App\Models\CustomField;
use App\Models\FieldType;
use App\Services\CustomFieldService;
use Illuminate\Http\Request;

class CustomFieldController extends Controller
{

    public function __construct(protected CustomFieldService $customFieldService)
    {
    }

    public function index(Request $request): \Illuminate\Contracts\View\View | \Illuminate\Http\JsonResponse
    {
        $customFields = $this->customFieldService->getCustomFields();
        $fieldTypes = FieldType::all();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('custom_field.partials.custom-field-list', compact('customFields'))->render(),
            ]);
        }
        return view('custom_field.index', compact('customFields', 'fieldTypes'));
    }

    public function store(StoreCustomFieldRequest $request): \Illuminate\Http\RedirectResponse | \Illuminate\Http\JsonResponse
    {
        try {
            $validated = $request->validated();

            $customField = $this->customFieldService->createCustomField($validated);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Custom field created successfully',
                    'data' => $customField
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

    public function show($id): \Illuminate\Http\JsonResponse
    {
        try {
            $customField = CustomField::with('fieldType:id,name', 'options:id,custom_field_id,display_text')->findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Custom field not found'], 404);
        }
        return response()->json([
            'success' => true,
            'customField' => $customField
        ]);
    }

    public function update(UpdateCustomFieldRequest $request, $id): \Illuminate\Http\RedirectResponse | \Illuminate\Http\JsonResponse
    {
        try {
            $validated = $request->validated();
            $customField = $this->customFieldService->updateCustomField($id, $validated);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Custom field updated successfully',
                    'data' => $customField
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

    public function list(): \Illuminate\Http\JsonResponse
    {
        $customFields = CustomField::with('fieldType:id,name', 'options:id,custom_field_id,display_text')
            ->orderBy('field_type_id', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'customFields' => $customFields
        ]);
    }
}
