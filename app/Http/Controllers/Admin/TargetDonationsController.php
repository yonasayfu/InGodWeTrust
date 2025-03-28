<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TargetDonation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TargetDonationsController extends Controller
{
    public function index(Request $request)
    {
        $query = TargetDonation::query();
        
        // Apply search if provided
        if ($request->has('search') && $request->filled('search')) {
            $searchTerm = $request->search;
            $searchField = $request->field ?? 'period';
            
            if ($searchField === 'period') {
                $query->where('period', 'ILIKE', "%{$searchTerm}%");
            } elseif ($searchField === 'target_amount') {
                $query->where('target_amount', 'ILIKE', "%{$searchTerm}%");
            }
        }
        
        // Apply sorting
        $sortField = $request->sort ?? 'start_date';
        $sortDirection = $request->direction ?? 'desc';
        $query->orderBy($sortField, $sortDirection);
        
        $targetDonations = $query->paginate(10)->withQueryString();
        
        return Inertia::render('Admin/TargetDonations/Index', [
            'targetDonations' => $targetDonations,
            'filters' => [
                'search' => $request->search ?? '',
                'field' => $request->field ?? 'period',
                'sort' => $sortField,
                'direction' => $sortDirection,
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/TargetDonations/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'period' => 'required|string|max:255',
            'target_amount' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        TargetDonation::create($data);
        return redirect()->route('target-donations.index')->with('success', 'Target Donation created successfully.');
    }

    public function edit(TargetDonation $targetDonation)
    {
        return Inertia::render('Admin/TargetDonations/Edit', [
            'targetDonation' => $targetDonation,
        ]);
    }

    public function update(Request $request, TargetDonation $targetDonation)
    {
        $data = $request->validate([
            'period' => 'required|string|max:255',
            'target_amount' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $targetDonation->update($data);
        return redirect()->route('target-donations.index')->with('success', 'Target Donation updated successfully.');
    }

    public function destroy(TargetDonation $targetDonation)
    {
        $targetDonation->delete();
        return redirect()->route('target-donations.index')->with('success', 'Target Donation deleted successfully.');
    }

    public function print()
    {
        $targetDonations = TargetDonation::orderBy('start_date', 'desc')->get();
        return view('admin.target-donations.print-index', [
            'targetDonations' => $targetDonations,
        ]);
    }

    public function printShow(TargetDonation $targetDonation)
    {
        return view('admin.target-donations.print-show', [
            'targetDonation' => $targetDonation,
        ]);
    }

    public function show(TargetDonation $targetDonation)
    {
        return Inertia::render('Admin/TargetDonations/Show', [
            'targetDonation' => $targetDonation,
        ]);
    }

    public function export()
    {
        $targetDonations = TargetDonation::all();
        
        return response()->streamDownload(function() use ($targetDonations) {
            $handle = fopen('php://output', 'w');
            
            fputcsv($handle, [
                'Period',
                'Target Amount',
                'Start Date',
                'End Date'
            ]);
            
            foreach ($targetDonations as $donation) {
                fputcsv($handle, [
                    $donation->period,
                    $donation->target_amount,
                    $donation->start_date,
                    $donation->end_date
                ]);
            }
            
            fclose($handle);
        }, 'target_donations_export.csv');
    }
}
