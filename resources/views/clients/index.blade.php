@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <a href="{{ route('home.clients.create') }}" class="btn btn-primary mb-3">Add</a>

                <div class="card">
                    <div class="card-header">{{ __('Clients') }}</div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (count($clients) > 0)
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Company</th>
                                        <th scope="col">VAT</th>
                                        <th scope="col">Address</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($clients as $client)
                                        <tr>
                                            <td class="align-middle">{{ $loop->iteration }}</td>
                                            <td class="align-middle">{{ $client->company_name }}</td>
                                            <td class="align-middle">{{ $client->company_vat }}</td>
                                            <td class="align-middle">{{ $client->company_address }}</td>
                                            <td class="align-middle">
                                                <a href="{{ route('home.clients.edit', $client) }}"
                                                    class="btn btn-secondary">Edit</a>
                                                <form action="{{ route('home.clients.destroy', $client) }}" method="POST" style="display: inline-block;">
                                                    @method('delete')
                                                    @csrf
                                                    <button class="btn btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            No client found.
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
