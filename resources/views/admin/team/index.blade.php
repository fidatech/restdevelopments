@extends('admin.layouts.master')
@section('title', 'Team Members')

@section('content')
<div class="page-header card-soft is-inline">
    <div class="page-header-main">
        <span class="page-header-icon"><x-icon name="users" /></span>
        <div class="page-header-text">
            <h1>Team Members</h1>
        </div>
    </div>
    <div class="page-header-actions"><a href="{{ route('admin.team.create') }}" class="btn btn-primary btn-sm">Add New</a></div>
</div>
    <div class="container-fluid">
        <div class="card page-card">
            
            <div class="card-body table-responsive">
                <table class="table-hover table">
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
                                    <a href="{{ route('admin.team.edit', $team) }}" class="btn-act is-amber" title="Edit" aria-label="Edit"><x-icon name="pen" /></a>
                                    <form method="POST" action="{{ route('admin.team.destroy', $team) }}" class="d-inline-block" data-confirm="Delete this team member?" data-confirm-ok="Delete">
                                        @csrf @method('DELETE')
                                        <button class="btn-act is-red" title="Delete" aria-label="Delete"><x-icon name="trash" /></button>
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
