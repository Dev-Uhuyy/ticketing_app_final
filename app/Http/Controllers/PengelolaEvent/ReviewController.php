<?php

namespace App\Http\Controllers\PengelolaEvent;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Review::with('user', 'event')->get();
        return view('pengelola_event.management_reviews.index', compact('reviews'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function showAnswer(string $id)
    {
        $review = Review::findOrFail($id);
        return view('pengelola_event.management_reviews.answer', compact('review'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function answer(Request $request, string $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'answer' => 'nullable|string',
        ]);

        // Update review di database
        $review = Review::findOrFail($id);
        $review->update($validatedData);

        return redirect()->route('pengelola.reviews.index')->with('success', 'Review berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->route('pengelola.reviews.index')->with('success', 'Review berhasil dihapus.');
    }
}
