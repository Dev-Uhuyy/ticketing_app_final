<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the payment methods.
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::all();
        return view('admin.payment_methods.index', compact('paymentMethods'));
    }

    /**
     * Show the form for creating a new payment method.
     */
    public function create()
    {
        return view('admin.payment_methods.create');
    }

    /**
     * Store a newly created payment method in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Generate code from name
        $code = strtolower(str_replace(' ', '_', $validated['name']));
        $validated['code'] = $code;

        PaymentMethod::create($validated);

        return redirect()->route('superadmin.payment-methods.index')
                        ->with('success', 'Metode Pembayaran berhasil ditambahkan');
    }

    /**
     * Display the specified payment method.
     */
    public function show(PaymentMethod $paymentMethod)
    {
        return view('admin.payment_methods.show', compact('paymentMethod'));
    }

    /**
     * Show the form for editing the specified payment method.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        return view('admin.payment_methods.edit', compact('paymentMethod'));
    }

    /**
     * Update the specified payment method in storage.
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Generate code from name
        $code = strtolower(str_replace(' ', '_', $validated['name']));
        $validated['code'] = $code;

        $paymentMethod->update($validated);

        return redirect()->route('superadmin.payment-methods.index')
                        ->with('success', 'Metode Pembayaran berhasil diperbarui');
    }

    /**
     * Remove the specified payment method from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();

        return redirect()->route('superadmin.payment-methods.index')
                        ->with('success', 'Metode Pembayaran berhasil dihapus');
    }
}
