@extends('admin.layouts.master')
@section('title', 'Team Members')

@section('content')
    <div class="container-fluid">
        <div class="card page-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Team Members</h5>
                <a href="{{ route('admin.team.create') }}" class="btn btn-primary btn-sm">Add New</a>
            </div>
            <div class="card-body table-responsive">
                <table class="table-bordered table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teams as $team)
                            <tr>
                                <td>{{ $team->order }}</td>
                                <td>
                                    <img src="{{ asset('storage/' . $team->image) }}" alt="" width="50">
                                </td>
                                <td>{{ $team->t()?->name }}</td>
                                <td>{{ $team->t()?->position }}</td>
                                <td>
                                    <a href="{{ route('admin.team.edit', $team) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form method="POST" action="{{ route('admin.team.destroy', $team) }}" class="d-inline-block">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Delete?')" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No team members yet.</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>
@endsection
