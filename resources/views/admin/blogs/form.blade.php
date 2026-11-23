@extends('admin.layouts.master')
@section('title', $blog->exists ? 'Edit Blog' : 'Create Blog')

@section('vendor-style')
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="page-header card-soft is-inline">
    <div class="page-header-main">
        <span class="page-header-icon"><x-icon name="newspaper" /></span>
        <div class="page-header-text">
            <h1>Admin</h1>
        </div>
    </div>
</div>

    @php
        $locales = config('app.locales', ['en', 'fa', 'ps', 'uz']);
        $default = config('app.fallback_locale', 'en');
    @endphp

    <div class="container-fluid">

        <form action="{{ $blog->exists ? route('admin.blogs.update', $blog->id) : route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">

            @csrf
            @if ($blog->exists)
                @method('PUT')
            @endif

            <div class="row mb-3">

                <div class="col-md-4">
                    <label class="form-label">Category</label>

                    <select name="blog_category_id" class="form-control">

                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $blog->blog_category_id == $cat->id ? 'selected' : '' }}>

                                {{ $cat->t()?->title }}

                            </option>
                        @endforeach

                    </select>

                </div>

                <div class="col-md-2">
                    <label class="form-label">Order</label>

                    <input type="number" name="order" value="{{ old('order', $blog->order ?? 0) }}" class="form-control">

                </div>

                <div class="col-md-2">

                    <label class="form-label">Status</label>

                    <select name="status" class="form-control">

                        <option value="1" {{ old('status', $blog->status ?? 1) ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="0" {{ old('status', $blog->status ?? 1) ? '' : 'selected' }}>
                            Inactive
                        </option>

                    </select>

                </div>

            </div>

            <div class="mb-3">

                <label class="form-label">Image</label>

                <input type="file" name="image" class="form-control" accept="image/*">

                @if ($blog->thumb_small)
                    <div class="mt-2">

                        <img src="{{ asset('storage/' . $blog->thumb_small) }}" style="height:80px;">

                    </div>
                @endif

            </div>

            {{-- LANG TABS --}}

            <ul class="nav nav-tabs">

                @foreach ($locales as $i => $loc)
                    <li class="nav-item">

                        <button class="nav-link {{ $i === 0 ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#tab-{{ $loc }}" type="button">

                            {{ strtoupper($loc) }}

                        </button>

                    </li>
                @endforeach

            </ul>

            <div class="tab-content border-start border-end border-bottom rounded-bottom p-3">

                @foreach ($locales as $i => $loc)
                    @php
                        $t = $blog->translations->where('locale', $loc)->first();
                    @endphp

                    <div class="tab-pane fade {{ $i === 0 ? 'show active' : '' }}" id="tab-{{ $loc }}">

                        <div class="mb-2">

                            <label>Title ({{ strtoupper($loc) }})</label>

                            <input name="translations[{{ $loc }}][title]" class="form-control" value="{{ old('translations.' . $loc . '.title', $t->title ?? '') }}"
                                {{ $loc === $default ? 'required' : '' }}>

                        </div>

                        <div class="mb-2">

                            <label>Excerpt</label>

                            <textarea name="translations[{{ $loc }}][excerpt]" class="form-control" rows="2">{{ old('translations.' . $loc . '.excerpt', $t->excerpt ?? '') }}</textarea>

                        </div>

                        <div class="mb-2">

                            <label>Content</label>

                            <textarea name="translations[{{ $loc }}][content]" class="form-control editor" rows="8">{{ old('translations.' . $loc . '.content', $t->content ?? '') }}</textarea>

                        </div>

                    </div>
                @endforeach

            </div>

            <button class="btn btn-primary mt-3">

                {{ $blog->exists ? 'Update Blog' : 'Create Blog' }}

            </button>

        </form>

    </div>

@endsection

@section('vendor-script')

    <script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>

@endsection

@section('page-script')

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            document.querySelectorAll('.editor').forEach(function(el) {

                CKEDITOR.replace(el, {

                    height: 300,

                    removeButtons: 'PasteFromWord',

                    extraPlugins: 'image2,uploadimage',

                    toolbar: [{
                            name: 'clipboard',
                            items: ['Undo', 'Redo']
                        },
                        {
                            name: 'basicstyles',
                            items: ['Bold', 'Italic', 'Underline']
                        },
                        {
                            name: 'paragraph',
                            items: ['NumberedList', 'BulletedList', 'Blockquote']
                        },
                        {
                            name: 'links',
                            items: ['Link', 'Unlink']
                        },
                        {
                            name: 'insert',
                            items: ['Image', 'Table', 'HorizontalRule']
                        },
                        {
                            name: 'styles',
                            items: ['Format', 'FontSize']
                        },
                        {
                            name: 'tools',
                            items: ['Maximize']
                        }
                    ]

                });

            });

        });
    </script>

@endsection
