{{-- resources/views/admin/project-units/_actions.blade.php --}}
@php
  // Build a compact payload to refill the form on edit
  $translations = $u->translations->mapWithKeys(function($t){
      return [$t->locale => ['name'=>$t->name, 'description'=>$t->description]];
  });
  $payload = [
    'id'         => $u->id,
    'bedrooms'   => $u->bedrooms,
    'min_area'   => $u->min_area,
    'max_area'   => $u->max_area,
    'count'      => $u->count,
    'order'      => $u->order,
    'translations' => $translations,
  ];
@endphp
<div class="act-group">
    <button type="button" class="btn-act is-amber btn-edit-unit" data-url="{{ route('admin.projects.units.update', [$u->project_id, $u->id]) }}" data-payload='@json($payload)' title="Edit" aria-label="Edit"><x-icon name="pen" /></button>
    <button type="button" class="btn-act is-red btn-delete-unit" data-url="{{ route('admin.projects.units.destroy', [$u->project_id, $u->id]) }}" title="Delete" aria-label="Delete"><x-icon name="trash" /></button>
</div>
