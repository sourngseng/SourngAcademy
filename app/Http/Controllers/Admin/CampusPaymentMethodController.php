<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Core\Models\Campus;
use App\Modules\Core\Models\CampusPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CampusPaymentMethodController extends Controller
{
    /**
     * Index — list all payment methods for a campus
     */
    public function index(Request $request)
    {
        $campuses = Campus::where('is_active', true)->orderBy('name_en')->get();
        $selectedCampus = null;
        $methods = collect();

        if ($request->filled('campus_id')) {
            $selectedCampus = Campus::findOrFail($request->campus_id);
            $methods = $selectedCampus->paymentMethods()->get();
        } elseif ($campuses->count() === 1) {
            $selectedCampus = $campuses->first();
            $methods = $selectedCampus->paymentMethods()->get();
        }

        $types = CampusPaymentMethod::TYPES;

        return view('admin.payment-methods.index', compact('campuses', 'selectedCampus', 'methods', 'types'));
    }

    /**
     * Show create form
     */
    public function create(Request $request)
    {
        $campuses = Campus::where('is_active', true)->orderBy('name_en')->get();
        $types    = CampusPaymentMethod::TYPES;
        $selectedCampusId = $request->campus_id;

        return view('admin.payment-methods.create', compact('campuses', 'types', 'selectedCampusId'));
    }

    /**
     * Store a new payment method
     */
    public function store(Request $request)
    {
        $request->validate([
            'campus_id'      => 'required|exists:campuses,id',
            'type'           => 'required|string',
            'name'           => 'required|string|max:100',
            'provider_name'  => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:100',
            'account_name'   => 'nullable|string|max:100',
            'currency'       => 'nullable|string|max:10',
            'gateway_link'   => 'nullable|url|max:500',
            'instructions'   => 'nullable|string|max:1000',
            'color'          => 'nullable|string|max:20',
            'icon'           => 'nullable|string|max:100',
            'sort_order'     => 'nullable|integer|min:0',
            'qr_image'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'logo_image'     => 'nullable|image|mimes:jpg,jpeg,png,svg,gif|max:1024',
        ]);

        $data = $request->except(['_token', 'qr_image', 'logo_image']);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('qr_image')) {
            $data['qr_image'] = $request->file('qr_image')->store('payment-methods/qr', 'public');
            $data['qr_image'] = 'storage/' . $data['qr_image'];
        }

        if ($request->hasFile('logo_image')) {
            $data['logo_image'] = $request->file('logo_image')->store('payment-methods/logos', 'public');
            $data['logo_image'] = 'storage/' . $data['logo_image'];
        }

        CampusPaymentMethod::create($data);

        return redirect()->route('admin.payment-methods.index', ['campus_id' => $request->campus_id])
            ->with('success', 'Payment method "' . $request->name . '" added successfully!');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $method   = CampusPaymentMethod::findOrFail($id);
        $campuses = Campus::where('is_active', true)->orderBy('name_en')->get();
        $types    = CampusPaymentMethod::TYPES;

        return view('admin.payment-methods.edit', compact('method', 'campuses', 'types'));
    }

    /**
     * Update payment method
     */
    public function update(Request $request, $id)
    {
        $method = CampusPaymentMethod::findOrFail($id);

        $request->validate([
            'campus_id'      => 'required|exists:campuses,id',
            'type'           => 'required|string',
            'name'           => 'required|string|max:100',
            'provider_name'  => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:100',
            'account_name'   => 'nullable|string|max:100',
            'currency'       => 'nullable|string|max:10',
            'gateway_link'   => 'nullable|url|max:500',
            'instructions'   => 'nullable|string|max:1000',
            'color'          => 'nullable|string|max:20',
            'icon'           => 'nullable|string|max:100',
            'sort_order'     => 'nullable|integer|min:0',
            'qr_image'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'logo_image'     => 'nullable|image|mimes:jpg,jpeg,png,svg,gif|max:1024',
        ]);

        $data = $request->except(['_token', '_method', 'qr_image', 'logo_image']);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('qr_image')) {
            // Delete old
            if ($method->qr_image && file_exists(public_path($method->qr_image))) {
                @unlink(public_path($method->qr_image));
            }
            $data['qr_image'] = 'storage/' . $request->file('qr_image')->store('payment-methods/qr', 'public');
        }

        if ($request->hasFile('logo_image')) {
            if ($method->logo_image && file_exists(public_path($method->logo_image))) {
                @unlink(public_path($method->logo_image));
            }
            $data['logo_image'] = 'storage/' . $request->file('logo_image')->store('payment-methods/logos', 'public');
        }

        // Handle removal checkboxes
        if ($request->input('remove_qr')) {
            if ($method->qr_image && file_exists(public_path($method->qr_image))) {
                @unlink(public_path($method->qr_image));
            }
            $data['qr_image'] = null;
        }
        if ($request->input('remove_logo')) {
            if ($method->logo_image && file_exists(public_path($method->logo_image))) {
                @unlink(public_path($method->logo_image));
            }
            $data['logo_image'] = null;
        }

        $method->update($data);

        return redirect()->route('admin.payment-methods.index', ['campus_id' => $method->campus_id])
            ->with('success', 'Payment method updated successfully!');
    }

    /**
     * Destroy payment method
     */
    public function destroy($id)
    {
        $method = CampusPaymentMethod::findOrFail($id);
        $campusId = $method->campus_id;

        if ($method->qr_image   && file_exists(public_path($method->qr_image)))   @unlink(public_path($method->qr_image));
        if ($method->logo_image && file_exists(public_path($method->logo_image))) @unlink(public_path($method->logo_image));

        $method->delete();

        return redirect()->route('admin.payment-methods.index', ['campus_id' => $campusId])
            ->with('success', 'Payment method deleted.');
    }

    /**
     * Toggle active status
     */
    public function toggle($id)
    {
        $method = CampusPaymentMethod::findOrFail($id);
        $method->update(['is_active' => !$method->is_active]);

        return response()->json(['is_active' => $method->is_active, 'message' => 'Status updated']);
    }

    /**
     * Reorder (drag-drop) via AJAX
     */
    public function reorder(Request $request)
    {
        $request->validate(['order' => 'required|array']);
        foreach ($request->order as $index => $id) {
            CampusPaymentMethod::where('id', $id)->update(['sort_order' => $index]);
        }
        return response()->json(['success' => true]);
    }

    /**
     * AJAX: get payment methods for a campus (used on invoice page)
     */
    public function forCampus($campusId)
    {
        $methods = CampusPaymentMethod::where('campus_id', $campusId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id','type','name','icon','provider_name','account_number','account_name',
                   'currency','qr_image','gateway_link','instructions','color','logo_image']);

        return response()->json($methods);
    }
}
