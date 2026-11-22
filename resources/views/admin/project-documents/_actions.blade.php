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
<div class="btn-group btn-group-sm" role="group">
  <button type="button"
          class="btn btn-outline-primary btn-edit-doc"
          data-url="{{ route('admin.projects.documents.update', [$d->project_id, $d->id]) }}"
          data-payload='@json($payload)'
          title="Edit">
    <i class="ft ft-edit"></i>
  </button>

  <button type="button"
          class="btn btn-outline-danger btn-delete-doc"
          data-url="{{ route('admin.projects.documents.destroy', [$d->project_id, $d->id]) }}"
          title="Delete">
    <i class="ft ft-trash"></i>
  </button>
</div>
