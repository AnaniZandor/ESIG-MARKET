<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        Report::create([
            'reporter_id' => auth()->id(),
            'article_id'  => $request->article_id,
            'reason'      => $request->reason,
            'status'      => 'pending',
        ]);

        return back()->with('success', 'Signalement envoyé à l\'administrateur.');
    }
}