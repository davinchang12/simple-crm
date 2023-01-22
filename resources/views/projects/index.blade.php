@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @hasanyrole('admin|manager')
                    <a href="{{ route('home.projects.create') }}" class="btn btn-primary mb-3">Add</a>
                @endhasanyrole
                <div class="card">
                    <div class="card-header">{{ __('Projects') }}</div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (count($projects) > 0)
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
                                    @foreach ($projects as $project)
                                        <tr>
                                            <td class="align-middle">{{ $loop->iteration }}</td>
                                            <td class="align-middle">{{ $project->title }}</td>
                                            <td class="align-middle">{{ $project->deadline }}</td>
                                            <td class="align-middle">{{ ucfirst($project->status) }}</td>
                                            <td class="align-middle">
                                                <a href="{{ route('home.projects.show', $project) }}"
                                                    class="btn btn-primary">Show</a>
                                                @hasanyrole('admin|manager')
                                                    <a href="{{ route('home.projects.edit', $project) }}"
                                                        class="btn btn-secondary">Edit</a>
                                                    <form action="{{ route('home.projects.destroy', $project) }}" method="POST"
                                                        style="display: inline-block;">
                                                        @method('delete')
                                                        @csrf
                                                        <button class="btn btn-danger">Delete</button>
                                                    </form>
                                                @endhasanyrole
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            No project found.
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
