{{-- resources/views/admin/project-documents/_actions.blade.php --}}
@php
  $payload = [
    'id'            => $d->id,
    'type'          => $d->type,
    'title'         => $d->title,
    'file'          => $d->file,
    'preview_image' => $d->preview_image,
    'order'         => $d->order,
  ];
@endphp
<div class="act-group">
    <button type="button" class="btn-act is-amber btn-edit-doc" data-url="{{ route('admin.projects.documents.update', [$d->project_id, $d->id]) }}" data-payload='@json($payload)' title="Edit" aria-label="Edit"><x-icon name="pen" /></button>
    <button type="button" class="btn-act is-red btn-delete-doc" data-url="{{ route('admin.projects.documents.destroy', [$d->project_id, $d->id]) }}" title="Delete" aria-label="Delete"><x-icon name="trash" /></button>
</div>
