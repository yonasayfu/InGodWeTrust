<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supporter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SupporterController extends Controller
{
    /**
     * Display a paginated list of supporters with search and sort functionality
     */
    public function index(Request $request)
    {
        $query = Supporter::query();

        // Apply search filter if provided
        // Improved search functionality
        if ($request->has('search') && $request->search !== '') {
            $searchTerm = $request->search;
            $searchField = $request->field ?? 'name';

            // Use ILIKE for PostgreSQL case-insensitive search with wildcards
            $query->where($searchField, 'ILIKE', '%' . $searchTerm . '%');
        }

        // Apply sorting
        $sortField = $request->sort ?? 'name';
        $sortDirection = $request->direction ?? 'asc';
        $query->orderBy($sortField, $sortDirection);

        $supporters = $query->paginate(10)->withQueryString();

        return Inertia::render('Admin/Supporters/Index', [
            'supporters' => $supporters,
            'filters' => $request->only(['search', 'field', 'sort', 'direction']),
        ]);
    }

    /**
     * Show the form for creating a new supporter
     */
    public function create()
    {
        return Inertia::render('Admin/Supporters/Create');
    }

    /**
     * Store a newly created supporter in the database
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer',
            'gender' => 'required|string',
            'address' => 'required|string',
            'phone_number' => 'required|string',
            'email' => 'required|email|unique:supporters,email',
            'support_type' => 'required|string',
            'contribution_amount' => 'required|numeric',
            'commit_duration' => 'required|integer',
            'start_date' => 'required|date',
            'bank_details' => 'required|string',
            'testimonial_content' => 'nullable|string',
            'photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle photo upload if provided
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('uploads/supporters/photos', 'public');
            $data['photo_url'] = '/storage/' . $path;
        }

        Supporter::create($data);
        return redirect()->route('supporters.index')->with('success', 'Supporter created successfully.');
    }

    /**
     * Display the specified supporter details
     */
    public function show(Supporter $supporter)
    {
        return Inertia::render('Admin/Supporters/Show', [
            'supporter' => $supporter,
        ]);
    }

    /**
     * Show the form for editing the specified supporter
     */
    public function edit(Supporter $supporter)
    {
        return Inertia::render('Admin/Supporters/Edit', compact('supporter'));
    }

    /**
     * Update the specified supporter in the database
     */
    public function update(Request $request, Supporter $supporter)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer',
            'gender' => 'required|string',
            'address' => 'required|string',
            'phone_number' => 'required|string',
            'email' => 'required|email|unique:supporters,email,' . $supporter->id,
            'support_type' => 'required|string',
            'contribution_amount' => 'required|numeric',
            'commit_duration' => 'required|integer',
            'start_date' => 'required|date',
            'bank_details' => 'required|string',
            'testimonial_content' => 'nullable|string',
            'photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle photo update if a new one is provided
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($supporter->photo_url) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $supporter->photo_url));
            }

            // Store new photo
            $path = $request->file('photo')->store('uploads/supporters/photos', 'public');
            $validated['photo_url'] = '/storage/' . $path;
        }

        // Remove photo from validated data as it's already processed
        if (isset($validated['photo'])) {
            unset($validated['photo']);
        }

        $supporter->update($validated);

        return redirect()->route('supporters.index')->with('success', 'Supporter updated successfully.');
    }

    /**
     * Remove the specified supporter from the database
     */
    public function destroy(Supporter $supporter)
    {
        $supporter->delete();
        return redirect()->route('supporters.index')->with('success', 'Supporter deleted successfully.');
    }

    /**
     * Export supporters data to CSV file
     */
    public function export(): StreamedResponse
    {
        $fileName = 'supporters_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $columns = ['ID', 'Name', 'Age', 'Gender', 'Email', 'Support Type', 'Contribution Amount', 'Start Date'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            Supporter::chunk(200, function ($supporters) use ($file) {
                foreach ($supporters as $supporter) {
                    fputcsv($file, [
                        $supporter->id,
                        $supporter->name,
                        $supporter->age,
                        $supporter->gender,
                        $supporter->email,
                        $supporter->support_type,
                        $supporter->contribution_amount,
                        $supporter->start_date,
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate a printable view of all supporters
     */
    public function printIndex()
    {
        $supporters = Supporter::all();
        $companyName = 'InGodWeTrust';
        $logoUrl = asset('/images/logo.png');

        return view('admin.supporters.print-index', [
            'companyName' => $companyName,
            'logoUrl' => $logoUrl,
            'supporters' => $supporters,
            'generatedDate' => now()->toDateString(),
        ]);
    }

    /**
     * Generate a printable view of a specific supporter
     */
    public function printShow(Supporter $supporter)
    {
        $companyName = 'InGodWeTrust';
        $logoUrl = asset('/images/logo.png');
        $photoUrl = $supporter->photo_url ? asset($supporter->photo_url) : null;

        return view('admin.supporters.print-show', [
            'companyName' => $companyName,
            'logoUrl' => $logoUrl,
            'supporter' => $supporter,
            'photoUrl' => $photoUrl,
            'startDate' => $this->formatDate($supporter->start_date),
            'generatedDate' => now()->toDateString(),
        ]);
    }

    /**
     * Format a date string to a more readable format
     */
    private function formatDate($dateString)
    {
        if (!$dateString) {
            return 'N/A';
        }

        return Carbon::parse($dateString)->format('F j, Y');
    }
}
