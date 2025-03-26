<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankForm;
use App\Models\DonationAgreement;
use App\Models\Supporter;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DonationAgreementsController extends Controller
{
    public function index(Request $request)
    {
        $query = DonationAgreement::query()->with(['supporter', 'bankForm']);

        if ($request->has('search') && $request->search !== '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('supporter', function ($sq) use ($searchTerm) {
                    $sq->where('name', 'ILIKE', '%' . $searchTerm . '%');
                })
                ->orWhere('donation_type', 'ILIKE', '%' . $searchTerm . '%')
                ->orWhere('recurring_interval', 'ILIKE', '%' . $searchTerm . '%');
            });
        }

        $sortField = $request->sort ?? 'created_at';
        $sortDirection = $request->direction ?? 'desc';
        $query->orderBy($sortField, $sortDirection);

        $donationAgreements = $query->paginate(10)->withQueryString();

        return Inertia::render('Admin/DonationAgreements/Index', [
            'donationAgreements' => $donationAgreements,
            'filters' => $request->only(['search', 'sort', 'direction']),
        ]);
    }

    public function create()
    {
        $supporters = Supporter::all()->map(function ($supporter) {
            return ['id' => $supporter->id, 'name' => $supporter->name];
        });
        $bankForms = BankForm::all()->map(function ($bankForm) {
            return ['id' => $bankForm->id, 'name' => $bankForm->bank_name . ' - ' . $bankForm->form_name];
        });
        return Inertia::render('Admin/DonationAgreements/Create', [
            'supporters' => $supporters,
            'bankForms' => $bankForms,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'supporter_id' => 'required|exists:supporters,id',
            'donation_type' => 'nullable|string|max:255',
            'donation_amount' => 'nullable|numeric|min:0',
            'recurring_interval' => 'nullable|string|max:255',
            'bank_id' => 'required|exists:bank_forms,id',
            'signed_agreement_pdf' => 'nullable|file|mimes:pdf|max:2048',
            'summary_pdf' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('signed_agreement_pdf')) {
            $validatedData['signed_agreement_pdf'] = $request->file('signed_agreement_pdf')->store('uploads/donation-agreements', 'public');
        }

        if ($request->hasFile('summary_pdf')) {
            $validatedData['summary_pdf'] = $request->file('summary_pdf')->store('uploads/donation-agreements', 'public');
        }

        DonationAgreement::create($validatedData);

        return redirect()->route('donation-agreements.index')->with('success', 'Donation Agreement created successfully.');
    }

    public function show(DonationAgreement $donationAgreement)
    {
        $donationAgreement->load(['supporter', 'bankForm']);
        return Inertia::render('Admin/DonationAgreements/Show', [
            'donationAgreement' => $donationAgreement,
        ]);
    }

    public function edit(DonationAgreement $donationAgreement)
    {
        $donationAgreement->load(['supporter', 'bankForm']);
        $supporters = Supporter::all()->map(function ($supporter) {
            return ['id' => $supporter->id, 'name' => $supporter->name];
        });
        $bankForms = BankForm::all()->map(function ($bankForm) {
            return ['id' => $bankForm->id, 'name' => $bankForm->bank_name . ' - ' . $bankForm->form_name];
        });
        return Inertia::render('Admin/DonationAgreements/Edit', [
            'donationAgreement' => $donationAgreement,
            'supporters' => $supporters,
            'bankForms' => $bankForms,
        ]);
    }

    public function update(Request $request, DonationAgreement $donationAgreement)
    {
        $validatedData = $request->validate([
            'supporter_id' => 'required|exists:supporters,id',
            'donation_type' => 'nullable|string|max:255',
            'donation_amount' => 'nullable|numeric|min:0',
            'recurring_interval' => 'nullable|string|max:255',
            'bank_id' => 'required|exists:bank_forms,id',
            'signed_agreement_pdf' => 'nullable|file|mimes:pdf|max:2048',
            'summary_pdf' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('signed_agreement_pdf')) {
            // Consider deleting the old file if needed
            $validatedData['signed_agreement_pdf'] = $request->file('signed_agreement_pdf')->store('uploads/donation-agreements', 'public');
        }

        if ($request->hasFile('summary_pdf')) {
            // Consider deleting the old file if needed
            $validatedData['summary_pdf'] = $request->file('summary_pdf')->store('uploads/donation-agreements', 'public');
        }

        $donationAgreement->update($validatedData);

        return redirect()->route('donation-agreements.index')->with('success', 'Donation Agreement updated successfully.');
    }

    // Soft delete preserves data for:
    // - Legal/compliance requirements
    // - Accident recovery
    // - Historical reporting
    public function destroy(DonationAgreement $donationAgreement)
    {
        if (request()->has('permanent_delete')) {
            $donationAgreement->forceDelete();  // Permanent removal
        } else {
            $donationAgreement->delete();  // Soft delete
        }
        
        return redirect()->back();
    }
}