@extends('front.layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Submit KYC Document</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('kyc.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="document_type" class="form-label">Document Type</label>
                            <select name="document_type" id="document_type" class="form-select @error('document_type') is-invalid @enderror" required>
                                <option value="">Select Document Type</option>
                                <option value="National ID">National ID</option>
                                <option value="Passport">Passport</option>
                                <option value="Driving License">Driving License</option>
                            </select>
                            @error('document_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="document_number" class="form-label">Document Number</label>
                            <input type="text" name="document_number" id="document_number" 
                                class="form-control @error('document_number') is-invalid @enderror" 
                                required>
                            @error('document_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="document_file" class="form-label">Document File</label>
                            <input type="file" name="document_file" id="document_file" 
                                class="form-control @error('document_file') is-invalid @enderror" 
                                accept=".pdf,.jpg,.jpeg,.png" required>
                            <small class="text-muted">Accepted formats: PDF, JPG, JPEG, PNG (max 2MB)</small>
                            @error('document_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Submit Document</button>
                            <a href="{{ route('kyc.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 