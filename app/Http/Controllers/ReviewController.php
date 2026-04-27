<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'reviewed_id' => 'required|exists:users,id',
            'article_id'  => 'required|exists:articles,id',
            'rating'      => 'required|integer|min:1|max:5',
            'comment'     => 'nullable|string|max:500',
        ]);

        // Empêcher de se noter soi-même
        if ($request->reviewed_id == auth()->id()) {
            return back()->with('error', 'Action non autorisée.');
        }

        // Un seul avis par article
        $exists = Review::where('reviewer_id', auth()->id())
                        ->where('article_id', $request->article_id)
                        ->exists();

        if ($exists) {
            return back()->with('error', 'Vous avez déjà laissé un avis pour cet article.');
        }

        Review::create([
            'reviewer_id' => auth()->id(),
            'reviewed_id' => $request->reviewed_id,
            'article_id'  => $request->article_id,
            'rating'      => $request->rating,
            'comment'     => $request->comment,
        ]);

        return back()->with('success', '⭐ Avis publié avec succès !');
    }
}