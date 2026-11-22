<div class="d-flex align-items-center gap-2 action-buttons">
    <a href="{{ url('admin/projects/'.$r->id.'/units') }}" class="action-btn btn-units">
        <i class="ft ft-home"></i> Units
    </a>
    <a href="{{ url('admin/projects/'.$r->id.'/documents') }}" class="action-btn btn-documents">
        <i class="ft ft-file"></i> Documents
    </a>
    <a href="{{ url('admin/projects/'.$r->id.'/gallery') }}" class="action-btn btn-gallery">
        <i class="ft ft-image"></i> Gallery
    </a>
    <a class="action-btn btn-edit" data-id="{{ $r->id }}" title="Edit">
        <i class="ft ft-edit"></i> Edit
    </a>
    <a class="action-btn btn-delete" data-id="{{ $r->id }}" title="Delete">
        <i class="ft ft-trash"></i> Delete
    </a>
</div>
