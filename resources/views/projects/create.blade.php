@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <a href="{{ route('home.projects.index') }}" class="btn btn-primary mb-3">Back</a>

                <div class="card">
                    <div class="card-header">{{ __('Add Project') }}</div>

                    <div class="card-body">
                        <form action="{{ route('home.projects.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="inputTitle" class="form-label">Title :</label>
                                <input type="text" class="form-control" id="inputTitle" name="title"
                                    value="{{ old('title') }}">
                                @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="inputDescription" class="form-label">Description :</label><br>
                                <textarea name="description" id="inputDescription" class="form-control" rows="10" style="width: 100%">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="inputDeadline" class="form-label">Deadline :</label>
                                <input type="date" class="form-control" id="inputDeadline" name="deadline"
                                    value="{{ old('deadline') }}">
                                @error('deadline')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="inputWorker" class="form-label">Assigned Worker :</label>
                                <select name="user_id" id="inputWorker" class="form-control">
                                    @foreach ($workers as $worker)
                                        <option value="{{ $worker->id }}" {{ old('user_id') == $worker->id ? 'selected' : '' }}>{{ $worker->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="inputClient" class="form-label">Assigned Client :</label>
                                <select name="client_id" id="inputClient" class="form-control">
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->company_name }}</option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="inputStatus" class="form-label">Status :</label>
                                <select name="status" id="inputStatus" class="form-control">
                                    @foreach (App\Models\Project::STATUS as $status)
                                        <option value="{{ $status }}"
                                            {{ old('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
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
