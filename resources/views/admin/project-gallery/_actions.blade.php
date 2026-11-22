@php
  $payload = [
    'id'    => $g->id,
    'type'  => $g->type,
    'order' => $g->order,
    'image' => $g->image,
  ];
@endphp

<div class="btn-group btn-group-sm" role="group">
  <button type="button"
          class="btn btn-outline-primary btn-edit-img"
          data-url="{{ route('admin.projects.gallery.update', [$g->project_id, $g->id]) }}"
          data-payload='@json($payload)'
          title="Edit">
    <i class="ft ft-edit"></i>
  </button>

  <button type="button"
          class="btn btn-outline-danger btn-delete-img"
          data-url="{{ route('admin.projects.gallery.destroy', [$g->project_id, $g->id]) }}"
          title="Delete">
    <i class="ft ft-trash"></i>
  </button>
</div>
