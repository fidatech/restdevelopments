@extends('admin.layouts.master')
@section('title', $team->exists ? 'Edit Team Member' : 'Create Team Member')

@section('content')
<div class="page-header card-soft is-inline">
    <div class="page-header-main">
        <span class="page-header-icon"><x-icon name="users" /></span>
        <div class="page-header-text">
            <h1>{{ $team->exists ? 'Edit' : 'Create' }} Team Member</h1>
        </div>
    </div>
</div>
<div class="container-fluid">
  <div class="card page-card">
    
    <div class="card-body">

      <form method="POST"
        action="{{ $team->exists ? route('admin.team.update',$team) : route('admin.team.store') }}"
        enctype="multipart/form-data">
        @csrf
        @if($team->exists) @method('PUT') @endif

        <div class="mb-3">
          <label class="form-label">Image (416×550)</label>
          <input type="file" name="image" class="form-control">
          @if($team->image)
            <div class="mt-1"><img src="{{ media_url($team->image) }}" height="80"></div>
          @endif
        </div>

        {{-- Locale Tabs --}}
        <ul class="nav nav-tabs" role="tablist">
          @foreach($locales as $i=>$loc)
            <li class="nav-item">
              <button class="nav-link {{ $i===0?'active':'' }}"
                data-bs-toggle="tab" data-bs-target="#tab-{{ $loc }}" type="button">
                {{ strtoupper($loc) }}
              </button>
            </li>
          @endforeach
        </ul>

        <div class="tab-content border-start border-end border-bottom p-3">
          @foreach($locales as $i=>$loc)
            <div class="tab-pane fade {{ $i===0?'show active':'' }}" id="tab-{{ $loc }}">
              <div class="mb-3">
                <label>Name ({{ strtoupper($loc) }})</label>
                <input type="text" name="translations[{{ $loc }}][name]"
                  value="{{ $team->t($loc)?->name }}" class="form-control">
              </div>
              <div class="mb-3">
                <label>Position ({{ strtoupper($loc) }})</label>
                <input type="text" name="translations[{{ $loc }}][position]"
                  value="{{ $team->t($loc)?->position }}" class="form-control">
              </div>
            </div>
          @endforeach
        </div>

        <hr>

        {{-- Meta --}}
        <div class="row">
          <div class="col-md-3 mb-2">
            <label class="form-label">Order</label>
            <input type="number" name="order" value="{{ old('order',$team->order ?? 0) }}" class="form-control">
          </div>
          <div class="col-md-3 mb-2">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" value="{{ old('phone',$team->phone) }}" class="form-control">
          </div>
          <div class="col-md-3 mb-2">
            <label class="form-label">Facebook</label>
            <input type="text" name="facebook" value="{{ old('facebook',$team->facebook) }}" class="form-control">
          </div>
          <div class="col-md-3 mb-2">
            <label class="form-label">Twitter</label>
            <input type="text" name="twitter" value="{{ old('twitter',$team->twitter) }}" class="form-control">
          </div>
          <div class="col-md-3 mb-2">
            <label class="form-label">LinkedIn</label>
            <input type="text" name="linkedin" value="{{ old('linkedin',$team->linkedin) }}" class="form-control">
          </div>
          <div class="col-md-3 mb-2">
            <label class="form-label">Instagram</label>
            <input type="text" name="instagram" value="{{ old('instagram',$team->instagram) }}" class="form-control">
          </div>
        </div>

        <button class="btn btn-primary mt-3">Save</button>
      </form>
    </div>
  </div>
</div>
@endsection
