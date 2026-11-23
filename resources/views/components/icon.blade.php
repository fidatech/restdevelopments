@props(['name', 'set' => 'solid'])
@php($sprite = asset('admin-ui/icons.svg').'?v='.(@filemtime(public_path('admin-ui/icons.svg')) ?: 1))
<i {{ $attributes->class('fi') }}><svg aria-hidden="true"><use href="{{ $sprite }}#{{ $set === 'regular' ? 'r' : 's' }}-{{ $name }}"/></svg></i>
