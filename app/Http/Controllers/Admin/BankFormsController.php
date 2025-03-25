<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BankFormsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BankForm::query();
    
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('bank_name', 'ILIKE', "%{$search}%");
        }
    
        $bankForms = $query->orderBy('created_at', 'desc')
                          ->paginate(10);
    
        return Inertia::render('Admin/BankForms/Index', [
            'bankForms' => $bankForms,
            'filters' => [
                'search' => $request->get('search'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/BankForms/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'form_name' => 'required|string|max:255',
            'form_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        if ($request->hasFile('form_file')) {
            $path = $request->file('form_file')->store('bank-forms', 'public');
            $validated['form_file'] = $path;
        }

        BankForm::create($validated);

        return redirect()->route('bank-forms.index')
            ->with('success', 'Bank form created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BankForm $bankForm)
    {
        return Inertia::render('Admin/BankForms/Show', [
            'bankForm' => $bankForm,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BankForm $bankForm)
    {
        return Inertia::render('Admin/BankForms/Edit', [
            'bankForm' => $bankForm,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BankForm $bankForm)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'form_name' => 'required|string|max:255',
            'form_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        if ($request->hasFile('form_file')) {
            // Delete old file if exists
            if ($bankForm->form_file && Storage::disk('public')->exists($bankForm->form_file)) {
                Storage::disk('public')->delete($bankForm->form_file);
            }

            $path = $request->file('form_file')->store('bank-forms', 'public');
            $validated['form_file'] = $path;
        } else {
            // Keep the existing file
            unset($validated['form_file']);
        }

        $bankForm->update($validated);

        return redirect()->route('bank-forms.index')
            ->with('success', 'Bank form updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankForm $bankForm)
    {
        // Delete the file if exists
        if ($bankForm->form_file && Storage::disk('public')->exists($bankForm->form_file)) {
            Storage::disk('public')->delete($bankForm->form_file);
        }

        $bankForm->delete();

        return redirect()->route('bank-forms.index')
            ->with('success', 'Bank form deleted successfully.');
    }

    /**
     * Download the specified bank form file.
     */
    public function download(BankForm $bankForm)
    {
        if (!$bankForm->form_file || !Storage::disk('public')->exists($bankForm->form_file)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        return Storage::disk('public')->download($bankForm->form_file, $bankForm->form_name);
    }
}
