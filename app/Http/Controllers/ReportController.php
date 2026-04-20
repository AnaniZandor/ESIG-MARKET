<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{

public function store(Request $request)
{
    \App\Models\Report::create([
        'reporter_id' => auth()->id(),
        'article_id'  => $request->article_id,
        'reason'      => $request->reason,
        'status'      => 'en_attente',
    ]);

    return back()->with('success', 'Signalement envoyé à l\'administrateur.');
}
    //
}
