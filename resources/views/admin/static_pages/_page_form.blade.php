@php
    $tr    = $page?->translations->keyBy('locale');
    $slugId = str_replace('-', '_', $slug);
@endphp

<form id="form-{{ $slug }}">
    @csrf
    <ul class="nav nav-tabs small" role="tablist">
        @foreach($locales as $i => $lc)
            <li class="nav-item">
                <button class="nav-link {{ $i==0?'active':'' }}" data-bs-toggle="tab"
                        data-bs-target="#{{ $slugId }}-{{ $lc }}" type="button">
                    {{ strtoupper($lc) }}
                </button>
            </li>
        @endforeach
    </ul>

    <div class="tab-content border p-3 mt-2">
        @foreach($locales as $i => $lc)
            <div class="tab-pane fade {{ $i==0?'show active':'' }}" id="{{ $slugId }}-{{ $lc }}">
                <textarea class="form-control summernote"
                    name="content[{{ $lc }}]">
                    {!! $tr[$lc]->content ?? '' !!}
                </textarea>
            </div>
        @endforeach
    </div>

    <button type="button" class="btn btn-primary btn-save-page" data-slug="{{ $slug }}">
        Save {{ ucfirst(str_replace('-', ' ', $slug)) }}
    </button>
</form>
