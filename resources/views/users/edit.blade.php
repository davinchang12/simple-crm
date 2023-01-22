@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <a href="{{ route('home.users.index') }}" class="btn btn-primary mb-3">Back</a>

                <div class="card">
                    <div class="card-header">{{ __('Edit User Role') }}</div>

                    <div class="card-body">
                        <form action="{{ route('home.users.update', $user->id) }}" method="POST">
                            @method('put')
                            @csrf

                            <div class="mb-3">
                                <label for="inputName" class="form-label">Name :</label>
                                <input type="text" class="form-control" id="inputName" value="{{ $user->name }}"
                                    disabled>
                            </div>
                            <div class="mb-3">
                                <label for="inputEmail" class="form-label">Email :</label>
                                <input type="email" class="form-control" id="inputEmail" value="{{ $user->email }}"
                                    disabled>
                            </div>
                            <div class="mb-3">
                                <label for="inputRole" class="form-label">Role :</label>
                                <select name="role" id="inputRole" class="form-control">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}"
                                            {{ $user->getRoleNames()->toArray()[0] == $role->name ? 'selected' : '' }}>
                                            {{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
