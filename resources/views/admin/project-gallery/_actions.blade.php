@php
  $payload = [
    'id'    => $g->id,
    'type'  => $g->type,
    'order' => $g->order,
    'image' => $g->image,
  ];
@endphp
<div class="act-group">
    <button type="button" class="btn-act is-amber btn-edit-img" data-url="{{ route('admin.projects.gallery.update', [$g->project_id, $g->id]) }}" data-payload='@json($payload)' title="Edit" aria-label="Edit"><x-icon name="pen" /></button>
    <button type="button" class="btn-act is-red btn-delete-img" data-url="{{ route('admin.projects.gallery.destroy', [$g->project_id, $g->id]) }}" title="Delete" aria-label="Delete"><x-icon name="trash" /></button>
</div>
