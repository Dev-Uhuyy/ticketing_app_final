<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketType;
use Illuminate\Http\Request;

class TicketTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ticketTypes = TicketType::all();
        return view('admin.ticket_types.index', compact('ticketTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        TicketType::create($request->only('name', 'description'));

        return redirect()->route('admin.ticket-types.index')->with('success', 'Tipe tiket berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $ticketType = TicketType::findOrFail($id);
        $ticketType->update($request->only('name', 'description'));

        return redirect()->route('admin.ticket-types.index')->with('success', 'Tipe tiket berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        TicketType::destroy($id);
        return redirect()->route('admin.ticket-types.index')->with('success', 'Tipe tiket berhasil dihapus.');
    }
}
