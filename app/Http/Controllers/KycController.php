<?php

namespace App\Http\Controllers;

use App\Models\KycDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KycController extends Controller
{
    public function index()
    {
        $documents = auth()->user()->kycDocuments;
        return view('front.account.kyc.index', compact('documents'));
    }

    public function create()
    {
        return view('front.account.kyc.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'document_type' => 'required|string',
            'document_number' => 'required|string',
            'document_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $path = $request->file('document_file')->store('kyc_documents');

        auth()->user()->kycDocuments()->create([
            'document_type' => $request->document_type,
            'document_number' => $request->document_number,
            'document_file' => $path,
            'status' => 'pending'
        ]);

        return redirect()->route('kyc.index')
            ->with('success', 'Document submitted successfully for verification.');
    }

    public function show(KycDocument $document)
    {
        $this->authorize('view', $document);
        return view('front.account.kyc.show', compact('document'));
    }

    // Admin methods
    public function adminIndex()
    {
        $this->authorize('viewAny', KycDocument::class);
        $documents = KycDocument::with('user')->latest()->paginate(20);
        return view('admin.kyc.index', compact('documents'));
    }

    public function verify(KycDocument $document)
    {
        $this->authorize('verify', $document);
        
        $document->update([
            'status' => 'verified'
        ]);

        $document->user->jobSeekerProfile()->update([
            'is_kyc_verified' => true
        ]);

        return redirect()->back()
            ->with('success', 'Document verified successfully.');
    }

    public function reject(Request $request, KycDocument $document)
    {
        $this->authorize('verify', $document);
        
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $document->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason
        ]);

        return redirect()->back()
            ->with('success', 'Document rejected successfully.');
    }

    public function download(KycDocument $document)
    {
        $this->authorize('view', $document);
        return Storage::download($document->document_file);
    }
} 