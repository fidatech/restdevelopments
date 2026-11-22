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

<div class="btn-group btn-group-sm" role="group">
  <button type="button"
          class="btn btn-outline-primary btn-edit-unit"
          data-url="{{ route('admin.projects.units.update', [$u->project_id, $u->id]) }}"
          data-payload='@json($payload)'
          title="Edit">
    <i class="ft ft-edit"></i>
  </button>

  <button type="button"
          class="btn btn-outline-danger btn-delete-unit"
          data-url="{{ route('admin.projects.units.destroy', [$u->project_id, $u->id]) }}"
          title="Delete">
    <i class="ft ft-trash"></i>
  </button>
</div>
