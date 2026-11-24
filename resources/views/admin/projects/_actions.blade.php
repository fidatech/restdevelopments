<div class="act-group">
    <a href="{{ url('admin/projects/'.$r->id.'/units') }}" class="btn btn-sm btn-light-secondary"><x-icon name="house" class="me-1" />Units</a>
    <a href="{{ url('admin/projects/'.$r->id.'/documents') }}" class="btn btn-sm btn-light-secondary"><x-icon name="file-lines" class="me-1" />Documents</a>
    <a href="{{ url('admin/projects/'.$r->id.'/gallery') }}" class="btn btn-sm btn-light-secondary"><x-icon name="images" class="me-1" />Gallery</a>
    <a href="{{ url('admin/projects/'.$r->id.'/highlights') }}" class="btn btn-sm btn-light-secondary"><x-icon name="star" class="me-1" />Highlights</a>
    <button type="button" class="btn-act is-amber btn-edit" data-id="{{ $r->id }}" title="Edit" aria-label="Edit"><x-icon name="pen" /></button>
    <button type="button" class="btn-act is-red btn-delete" data-id="{{ $r->id }}" title="Delete" aria-label="Delete"><x-icon name="trash" /></button>
</div>
