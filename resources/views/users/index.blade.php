@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card">
                    <div class="card-header">{{ __('Users') }}</div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (count($users) > 0)
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Roles</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td class="align-middle">{{ $loop->iteration }}</td>
                                            <td class="align-middle">{{ $user->name }}</td>
                                            <td class="align-middle">{{ $user->email }}</td>
                                            <td class="align-middle">{{ $user->getRoleNames()->toArray()[0] }}</td>
                                            <td class="align-middle">
                                                <a href="{{ route('home.users.edit', $user->id) }}"
                                                    class="btn btn-secondary">Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{  $users->links()  }}
                        @else
                            No user found.
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
