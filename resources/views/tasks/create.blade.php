@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <a href="{{ route('home.tasks.index') }}" class="btn btn-primary mb-3">Back</a>

                <div class="card">
                    <div class="card-header">{{ __('Add Task') }}</div>

                    <div class="card-body">
                        <form action="{{ route('home.tasks.store') }}" method="POST">
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
                                <label for="inputProject" class="form-label">Project :</label>
                                <select name="project_id" id="inputProject" class="form-control">
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->title }}</option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="inputStatus" class="form-label">Status :</label>
                                <select name="status" id="inputStatus" class="form-control">
                                    @foreach (App\Models\Task::STATUS as $status)
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
