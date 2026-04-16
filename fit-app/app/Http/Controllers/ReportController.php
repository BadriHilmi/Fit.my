<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;


class ReportController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'lunch_total' => 'required|numeric',
            'breakfast_total' => 'required|numeric',
            'dinner_total' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        // Create a new report instance and save it to the database
        $report = new Report([
            'date' => $validatedData['date'],
            'lunch_total' => $validatedData['lunch_total'],
            'breakfast_total' => $validatedData['breakfast_total'],
            'dinner_total' => $validatedData['dinner_total'],
            'total' => $validatedData['total'],
        ]);

        $report->save();

        return redirect()->back()->with('success', 'Report saved successfully.');
    }
}
