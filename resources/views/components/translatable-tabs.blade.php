{{-- resources/views/components/translatable-tabs.blade.php --}}
@props(['model' => null, 'fields' => [], 'locales' => null])

@php
  $locales = $locales ?? config('app.available_locales');
  $prefix = 'tabs_'.uniqid(); // avoid ID collisions if component appears multiple times
@endphp

<ul class="nav nav-tabs" role="tablist">
  @foreach($locales as $i => $loc)
    <li class="nav-item">
      <a
        class="nav-link {{ $i===0 ? 'active' : '' }}"
        data-toggle="tab"                   {{-- BS4 attribute --}}
        href="#{{ $prefix }}_{{ $loc }}"    {{-- BS4 uses href, not data-bs-target --}}
        role="tab"
        aria-controls="{{ $prefix }}_{{ $loc }}"
        aria-selected="{{ $i===0 ? 'true' : 'false' }}"
      >
        {{ strtoupper($loc) }} @if(function_exists('is_rtl') && is_rtl($loc)) (RTL) @endif
      </a>
    </li>
  @endforeach
</ul>

<div class="tab-content border border-top-0 p-3 rounded-bottom">
  @foreach($locales as $i => $loc)
    @php $tr = optional($model?->translations->firstWhere('locale',$loc)); @endphp
    <div
      id="{{ $prefix }}_{{ $loc }}"
      class="tab-pane fade {{ $i===0 ? 'show active' : '' }}"
      role="tabpanel"
    >
      @foreach($fields as $name => $type)
        @php $val = old("translations.$loc.$name", $tr?->$name); @endphp
        <div class="form-group">
          <label>{{ ucwords(str_replace('_',' ', $name)) }} ({{ strtoupper($loc) }})</label>
          @if($type === 'textarea')
            <textarea name="translations[{{ $loc }}][{{ $name }}]" rows="4" class="form-control">{{ $val }}</textarea>
          @else
            <input type="text" name="translations[{{ $loc }}][{{ $name }}]" value="{{ $val }}" class="form-control">
          @endif
        </div>
      @endforeach
    </div>
  @endforeach
</div>
