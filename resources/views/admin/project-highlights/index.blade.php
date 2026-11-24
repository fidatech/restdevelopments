@extends('admin.layouts.master')
@php
    $default = config('app.fallback_locale', 'en');
    $projectTitle = $project->t()?->title ?? 'Project';
@endphp
@section('title', 'Highlights')

@section('content')
<div class="page-header card-soft is-inline">
    <div class="page-header-main">
        <span class="page-header-icon"><x-icon name="star" /></span>
        <div class="page-header-text">
            <h1>Highlights</h1>
            <p class="page-header-sub">{{ $projectTitle }}: amenities shown with a photo in the “Life at …” section of the project page.</p>
        </div>
    </div>
    <div class="page-header-actions">
        <a class="btn btn-outline-secondary" href="{{ route('admin.projects.index') }}"><x-icon name="arrow-left" class="me-2" />Projects</a>
        <a class="btn btn-outline-secondary" href="{{ route('projects.show', ['locale' => 'en', 'slug' => $project->slug]) }}#highlights" target="_blank" rel="noopener"><x-icon name="eye" class="me-2" />View page</a>
    </div>
</div>

<div class="container-fluid">
  @if(session('ok'))<div class="alert alert-success">{{ session('ok') }}</div>@endif
  @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

  <div class="row g-4">
    <div class="col-xl-7">
      <div class="card page-card">
        <div class="card-body">
          <h2 class="h6 mb-3">{{ $project->features->count() }} {{ \Illuminate\Support\Str::plural('highlight', $project->features->count()) }}</h2>
          @forelse($project->features as $feature)
            @php $ft = $feature->t(); @endphp
            <div class="d-flex gap-3 align-items-start py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
              <div class="flex-shrink-0 rounded overflow-hidden bg-light" style="width:150px;aspect-ratio:4/3">
                @if($feature->image)<img src="{{ media_url($feature->image) }}" alt="" style="width:100%;height:100%;object-fit:cover">@endif
              </div>
              <div class="flex-grow-1 min-w-0">
                <div class="d-flex align-items-center gap-2 mb-1">
                  <span class="badge bg-light text-dark">#{{ $feature->order }}</span>
                  <strong>{{ $ft?->title ?: 'Untitled' }}</strong>
                </div>
                <p class="text-muted small mb-2">{{ \Illuminate\Support\Str::limit($ft?->description, 150) ?: 'No description' }}</p>
                <small class="text-muted">Languages: {{ $feature->translations->pluck('locale')->map(fn ($l) => strtoupper($l))->implode(', ') }}</small>
              </div>
              <div class="act-group flex-shrink-0">
                <a class="btn-act is-amber" href="{{ route('admin.projects.highlights.index', ['project' => $project, 'edit' => $feature->id]) }}#form" title="Edit"><x-icon name="pen" /></a>
                <form method="post" action="{{ route('admin.projects.highlights.destroy', [$project, $feature]) }}" data-confirm="Delete this highlight?">
                  @csrf @method('DELETE')
                  <button class="btn-act is-red" type="submit" title="Delete"><x-icon name="trash" /></button>
                </form>
              </div>
            </div>
          @empty
            <p class="text-muted mb-0">No highlights yet. Add the first one with the form.</p>
          @endforelse
        </div>
      </div>
    </div>

    <div class="col-xl-5" id="form">
      <div class="card page-card">
        <div class="card-body">
          <h2 class="h6 mb-3">{{ $edit ? 'Edit highlight' : 'Add a highlight' }}</h2>
          <form method="post" enctype="multipart/form-data" action="{{ $edit ? route('admin.projects.highlights.update', [$project, $edit]) : route('admin.projects.highlights.store', $project) }}">
            @csrf
            @if($edit) @method('PUT') @endif
            <div class="mb-3">
              <label class="form-label">Photo @unless($edit)<span class="text-danger">*</span>@endunless <small class="text-muted">(cropped to 1440×1080, 4:3)</small></label>
              <input type="file" name="image" class="form-control" accept="image/*" @unless($edit) required @endunless>
              @if($edit?->image)<img src="{{ media_url($edit->image) }}" alt="" class="rounded mt-2" style="width:100%;aspect-ratio:4/3;object-fit:cover">@endif
            </div>
            <div class="mb-3">
              <label class="form-label">Order</label>
              <input type="number" name="order" min="0" class="form-control" value="{{ old('order', $edit->order ?? ($project->features->max('order') + 1)) }}">
            </div>
            <ul class="nav nav-tabs" role="tablist">
              @foreach($locales as $i => $loc)
                <li class="nav-item" role="presentation"><button class="nav-link {{ $i === 0 ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#h-{{ $loc }}" type="button" role="tab">{{ strtoupper($loc) }}</button></li>
              @endforeach
            </ul>
            <div class="tab-content border rounded-bottom border-top-0 p-3 mb-3">
              @foreach($locales as $i => $loc)
                @php $tr = $edit?->translations->firstWhere('locale', $loc); @endphp
                <div class="tab-pane fade {{ $i === 0 ? 'show active' : '' }}" id="h-{{ $loc }}" role="tabpanel">
                  <label class="form-label">Title @if($loc === $default)<span class="text-danger">*</span>@endif</label>
                  <input type="text" name="translations[{{ $loc }}][title]" class="form-control mb-3" value="{{ old("translations.$loc.title", $tr->title ?? '') }}" @if($loc === $default) required @endif>
                  <label class="form-label">Short description</label>
                  <textarea name="translations[{{ $loc }}][description]" class="form-control" rows="3" maxlength="600">{{ old("translations.$loc.description", $tr->description ?? '') }}</textarea>
                </div>
              @endforeach
            </div>
            <div class="d-flex gap-2">
              <button class="btn btn-primary flex-grow-1" type="submit"><x-icon name="floppy-disk" class="me-2" />{{ $edit ? 'Save changes' : 'Add highlight' }}</button>
              @if($edit)<a class="btn btn-outline-secondary" href="{{ route('admin.projects.highlights.index', $project) }}">Cancel</a>@endif
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
