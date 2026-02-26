<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;

use App\Models\Event;

use Illuminate\Http\Request;

class EventController extends Controller
{
    public function show(Event $event)
    {
        $event->load(['tikets', 'kategori', 'user']);

        return view('pembeli.events.show', compact('event'));
    }
}
