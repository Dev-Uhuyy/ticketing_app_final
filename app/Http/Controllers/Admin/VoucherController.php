<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vouchers = Voucher::all();
        return view('admin.voucher.index', compact('vouchers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:255|unique:vouchers',
            'deskripsi' => 'nullable|string',
            'diskon' => 'required|numeric|min:0',
            'tipe_diskon' => 'required|in:fixed,percent',
            'penggunaan_maksimal' => 'nullable|integer|min:1',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_kadaluarsa' => 'nullable|date',
            'aktif' => 'nullable|boolean',
        ]);

        if (!isset($validatedData['code'])) {
            return redirect()->route('superadmin.vouchers.index')->with('error', 'Kode voucher wajib diisi.');
        }

        $validatedData['code'] = strtoupper($validatedData['code']);
        $validatedData['deskripsi'] = $validatedData['deskripsi'] ?? null;
        $validatedData['penggunaan_maksimal'] = $validatedData['penggunaan_maksimal'] ?? null;
        $validatedData['tanggal_mulai'] = $validatedData['tanggal_mulai'] ?? null;
        $validatedData['tanggal_kadaluarsa'] = $validatedData['tanggal_kadaluarsa'] ?? null;
        $validatedData['aktif'] = $validatedData['aktif'] ?? true;

        Voucher::create($validatedData);

        return redirect()->route('superadmin.vouchers.index')->with('success', 'Voucher berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:255|unique:vouchers,code,' . $id,
            'deskripsi' => 'nullable|string',
            'diskon' => 'required|numeric|min:0',
            'tipe_diskon' => 'required|in:fixed,percent',
            'penggunaan_maksimal' => 'nullable|integer|min:1',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_kadaluarsa' => 'nullable|date',
            'aktif' => 'nullable|boolean',
        ]);

        if (!isset($validatedData['code'])) {
            return redirect()->route('superadmin.vouchers.index')->with('error', 'Kode voucher wajib diisi.');
        }

        $voucher = Voucher::findOrFail($id);
        $voucher->code = strtoupper($validatedData['code']);
        $voucher->deskripsi = $validatedData['deskripsi'] ?? null;
        $voucher->diskon = $validatedData['diskon'];
        $voucher->tipe_diskon = $validatedData['tipe_diskon'];
        $voucher->penggunaan_maksimal = $validatedData['penggunaan_maksimal'] ?? null;
        $voucher->tanggal_mulai = $validatedData['tanggal_mulai'] ?? null;
        $voucher->tanggal_kadaluarsa = $validatedData['tanggal_kadaluarsa'] ?? null;
        $voucher->aktif = $validatedData['aktif'] ?? true;
        $voucher->save();

        return redirect()->route('superadmin.vouchers.index')->with('success', 'Voucher berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Voucher::destroy($id);
        return redirect()->route('superadmin.vouchers.index')->with('success', 'Voucher berhasil dihapus.');
    }

    /**
     * Update status aktif/nonaktif via AJAX (Toggle).
     */
    public function toggleStatus(Request $request, string $id)
    {
        try {
            $voucher = Voucher::findOrFail($id);
            $voucher->aktif = $request->aktif; // Menerima angka 1 (aktif) atau 0 (nonaktif) dari fetch JS
            $voucher->save();

            return response()->json(['success' => true, 'message' => 'Status berhasil diubah.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengubah status.'], 500);
        }
    }
}
