@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <a href="{{ route('home.clients.index') }}" class="btn btn-primary mb-3">Back</a>

                <div class="card">
                    <div class="card-header">{{ __('Add Client') }}</div>

                    <div class="card-body">
                        <form action="{{ route('home.clients.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="inputName" class="form-label">Company Name :</label>
                                <input type="text" class="form-control" id="inputName" name="company_name"
                                    value="{{ old('company_name') }}">
                                @error('company_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="inputVAT" class="form-label">Company VAT :</label>
                                <input type="text" class="form-control" id="inputVAT" name="company_vat"
                                    value="{{ old('company_vat') }}">
                                @error('company_vat')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="inputAddress" class="form-label">Company Address :</label>
                                <input type="text" class="form-control" id="inputAddress" name="company_address"
                                    value="{{ old('company_address') }}">
                                @error('company_address')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Add</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
