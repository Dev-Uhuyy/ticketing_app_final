<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
      public function show(Event $event)
    {
        $event->load([
            'tikets',
            'kategori',
            'user',
            'reviews.user'
        ]);

        $averageRating = round($event->reviews()->avg('rate'), 1);
        $totalReviews  = $event->reviews()->count();

        return view('events.show', compact(
            'event',
            'averageRating',
            'totalReviews'
        ));
    }
}
