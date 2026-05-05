<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->string('range', '7d')->toString();

        $fromDate = match ($range) {
            '30d' => Carbon::today()->subDays(29),
            'all' => null,
            default => Carbon::today()->subDays(6),
        };

        $query = Report::query()->orderByDesc('date')->orderByDesc('id');

        if ($fromDate) {
            $query->whereDate('date', '>=', $fromDate);
        }

        $reports = $query->get();

        $totals = [
            'breakfast' => (int) $reports->sum('breakfast_total'),
            'lunch' => (int) $reports->sum('lunch_total'),
            'dinner' => (int) $reports->sum('dinner_total'),
            'overall' => (int) $reports->sum('total'),
            'days' => $reports->count(),
        ];

        $averageTotal = $totals['days'] > 0 ? (int) round($totals['overall'] / $totals['days']) : 0;

        return view('GenerateReport.report', [
            'reports' => $reports,
            'totals' => $totals,
            'averageTotal' => $averageTotal,
            'selectedRange' => $range,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'lunch_total' => 'required|numeric',
            'breakfast_total' => 'required|numeric',
            'dinner_total' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        Report::create($validatedData);

        return redirect()->back()->with('success', 'Report saved successfully.');
    }
}
