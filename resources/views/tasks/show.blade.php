@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <a href="{{ route('home.tasks.index') }}" class="btn btn-primary mb-3">Back</a>

                <div class="card">
                    <div class="card-header">{{ __($task->title . '\'s Task') }}</div>

                    <div class="card-body">
                        <div class="mb-3">
                            <label for="inputTitle" class="form-label">Title :</label>
                            <input type="text" class="form-control" id="inputTitle" name="title"
                                value="{{ $task->title }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="inputDescription" class="form-label">Description :</label><br>
                            <textarea name="description" id="inputDescription" class="form-control" rows="10" style="width: 100%" disabled>{{ $task->description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="inputDeadline" class="form-label">Deadline :</label>
                            <input type="date" class="form-control" id="inputDeadline" name="deadline"
                                value="{{ $task->deadline }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="inputClient" class="form-label">Project :</label>
                            <input type="text" class="form-control" id="inputClient" name="client"
                                value="{{ $task->project->title }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="inputStatus" class="form-label">Status :</label>
                            <input type="text" class="form-control" id="inputStatus" name="status"
                                value="{{ ucwords($task->status) }}" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
