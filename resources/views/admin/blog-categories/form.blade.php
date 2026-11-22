@extends('admin.layouts.master')
@section('title',$category->id ? 'Edit Category':'Add Category')

@php $locales = config('app.locales',['en']); @endphp

<form method="POST" action="{{ $category->id ? route('admin.blog_categories.update',$category) : route('admin.blog_categories.store') }}">
  @csrf
  @if($category->id) @method('PUT') @endif
  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3>{{ $category->id?'Edit':'Add' }} Category</h3>
      <a href="{{ route('admin.blog_categories.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="row g-3">
      <div class="col-md-3">
        <label>Order</label>
        <input type="number" name="order" value="{{ old('order',$category->order) }}" class="form-control">
      </div>
    </div>

    <hr>

    <ul class="nav nav-tabs">
      @foreach($locales as $i=>$loc)
        <li class="nav-item">
          <button class="nav-link @if($i===0) active @endif" data-bs-toggle="tab" data-bs-target="#t-{{ $loc }}" type="button">
            {{ strtoupper($loc) }}
          </button>
        </li>
      @endforeach
    </ul>

    <div class="tab-content mt-3">
      @foreach($locales as $i=>$loc)
        @php $tr = $category->translations->where('locale',$loc)->first(); @endphp
        <div class="tab-pane fade @if($i===0) show active @endif" id="t-{{ $loc }}">
          <label>Title ({{ strtoupper($loc) }})</label>
          <input type="text" name="title[{{ $loc }}]" value="{{ old('title.'.$loc,$tr?->title) }}" class="form-control">
        </div>
      @endforeach
    </div>

    <button class="btn btn-primary mt-3">Save</button>
  </div>
</form>
@endsection
