@extends('admin.layouts.master')
@section('title', 'Why Choose Us Settings')

@section('content')
    <div class="container-fluid">

        <div class="card page-card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Why Choose Us – Settings</h5>
                    <a href="{{ route('admin.items.index') }}" class="btn btn-sm btn-primary">Manage Items</a>

                </div>

            <div class="card-body">

                <form method="POST" action="{{ route('admin.why-us.update') }}">
                    @csrf

                    @foreach ($locales as $loc)
                        <div class="mb-3">
                            <label class="form-label">Title ({{ strtoupper($loc) }})</label>
                            <input type="text" name="translations[{{ $loc }}][title]" class="form-control" value="{{ $setting->t($loc)?->title }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description ({{ strtoupper($loc) }})</label>
                            <textarea name="translations[{{ $loc }}][description]" class="form-control" rows="4">{{ $setting->t($loc)?->description }}</textarea>
                        </div>
                        <hr>
                    @endforeach

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>

    </div>
@endsection
@section('page-script')
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '{{ session('success') }}',
        timer: 1200,
        showConfirmButton: false
    });
</script>
@endif
@endsection
