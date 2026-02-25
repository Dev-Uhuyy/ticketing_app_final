<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Kategori;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{

    private function determineStatus($action, $data)
    {
        if ($action === 'draft') {
            return 'draft';
        }

        $now = Carbon::now();

        $publishAt = !empty($data['publish_at']) ? Carbon::parse($data['publish_at']) : $now;

        $mulai = Carbon::parse($data['tanggal_waktu_mulai']);
        $selesai = Carbon::parse($data['tanggal_waktu_selesai']);

        if ($publishAt->isFuture()) {
            return 'scheduled';
        }

        if ($now->isAfter($selesai)) {
            return 'finished';
        }

        if ($now->between($mulai, $selesai)) {
            return 'on_going';
        }

        return 'published';
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::where('user_id', auth()->id())->get();
        return view('admin.event.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Kategori::all();
        return view('admin.event.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $action = $request->input('action', 'draft');

        $rules = [
            'judul' => 'required|string|max:255',
            'publish_at' => 'nullable|date',
            'action' => 'required|in:draft,publish'
        ];

        if ($action === 'publish') {
            $rules['deskripsi'] = 'required|string';
            $rules['lokasi'] = 'required|string|max:255';
            $rules['tanggal_waktu_mulai'] = 'required|date';
            $rules['tanggal_waktu_selesai'] = 'required|date|after_or_equal:tanggal_waktu_mulai';
            $rules['kategori_id'] = 'required|exists:kategoris,id';
            $rules['gambar'] = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5048';
        } else {
            $rules['deskripsi'] = 'nullable|string';
            $rules['lokasi'] = 'nullable|string|max:255';
            $rules['tanggal_waktu_mulai'] = 'nullable|date';
            $rules['tanggal_waktu_selesai'] = 'nullable|date|after_or_equal:tanggal_waktu_mulai';
            $rules['kategori_id'] = 'nullable|exists:kategoris,id';
            $rules['gambar'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5048';
        }

        $validatedData = $request->validate($rules);

        if (empty($validatedData['publish_at']) && $action === 'publish') {
            $validatedData['publish_at'] = Carbon::now();
        }

        $validatedData['status'] = $this->determineStatus($action, $validatedData);

        if ($request->hasFile('gambar')) {
            $imageName = time() . '.' . $request->gambar->extension();
            $request->gambar->move(public_path('images/events'), $imageName);
            $validatedData['gambar'] = $imageName;
        }

        $validatedData['user_id'] = auth()->id();

        $event = Event::create($validatedData);

        return redirect()->route('admin.events.index')->with('success', "Event berhasil disimpan. Status: {$event->status} | Publish At: " . ($event->publish_at ?? '-'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event = Event::findOrFail($id);
        $categories = Kategori::all();
        $tickets = $event->tikets;
        $ticketTypes = TicketType::all();

        return view('admin.event.show', compact('event', 'categories', 'tickets', 'ticketTypes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $event = Event::findOrFail($id);
        $categories = Kategori::all();
        return view('admin.event.edit', compact('event', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $event = Event::findOrFail($id);
            $action = $request->input('action', 'draft');

            $rules = [
                'judul' => 'required|string|max:255',
                'publish_at' => 'nullable|date',
                'action' => 'required|in:draft,publish'
            ];

            if ($action === 'publish') {
                $rules['deskripsi'] = 'required|string';
                $rules['lokasi'] = 'required|string|max:255';
                $rules['tanggal_waktu_mulai'] = 'required|date';
                $rules['tanggal_waktu_selesai'] = 'required|date|after_or_equal:tanggal_waktu_mulai';
                $rules['kategori_id'] = 'required|exists:kategoris,id';
                $rules['gambar'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5048'; 
            } else {
                $rules['deskripsi'] = 'nullable|string';
                $rules['lokasi'] = 'nullable|string|max:255';
                $rules['tanggal_waktu_mulai'] = 'nullable|date';
                $rules['tanggal_waktu_selesai'] = 'nullable|date|after_or_equal:tanggal_waktu_mulai';
                $rules['kategori_id'] = 'nullable|exists:kategoris,id';
                $rules['gambar'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5048';
            }

            $validatedData = $request->validate($rules);

            if (empty($validatedData['publish_at']) && $action === 'publish') {
                $validatedData['publish_at'] = $event->publish_at ?? Carbon::now();
            }

            $validatedData['status'] = $this->determineStatus($action, $validatedData);

            if ($request->hasFile('gambar')) {
                $imageName = time() . '.' . $request->gambar->extension();
                $request->gambar->move(public_path('images/events'), $imageName);
                $validatedData['gambar'] = $imageName;
            }

            $event->update($validatedData);

            return redirect()->route('admin.events.index')->with('success', "Event berhasil diperbarui. Status: {$event->status} | Publish At: " . ($event->publish_at ?? '-'));

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('pengelola.events.index')->with('success', 'Event berhasil dihapus.');
    }
}
