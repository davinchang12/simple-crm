@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <a href="{{ route('home.tasks.create') }}" class="btn btn-primary mb-3">Add</a>

                <div class="card">
                    <div class="card-header">{{ __('Tasks') }}</div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (count($tasks) > 0)
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Deadline</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tasks as $task)
                                        <tr>
                                            <td class="align-middle">{{ $loop->iteration }}</td>
                                            <td class="align-middle">{{ $task->title }}</td>
                                            <td class="align-middle">{{ $task->deadline }}</td>
                                            <td class="align-middle">{{ ucfirst($task->status) }}</td>
                                            <td class="align-middle">
                                                <a href="{{ route('home.tasks.show', $task) }}"
                                                    class="btn btn-primary">Show</a>
                                                <a href="{{ route('home.tasks.edit', $task) }}"
                                                    class="btn btn-secondary">Edit</a>
                                                <form action="{{ route('home.tasks.destroy', $task) }}" method="POST" style="display: inline-block;">
                                                    @method('delete')
                                                    @csrf
                                                    <button class="btn btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{ $tasks->links() }}
                        @else
                            No task found.
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
