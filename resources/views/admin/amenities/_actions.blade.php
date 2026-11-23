<div class="act-group">
    <button type="button" class="btn-act is-amber btn-edit-amenity" data-url="{{ route('admin.amenities.update', $a->id) }}" data-payload='@json(["order" => $a->order, "translations" => $a->translations->pluck("title","locale")])' title="Edit" aria-label="Edit"><x-icon name="pen" /></button>
    <button type="button" class="btn-act is-red btn-delete-amenity" data-url="{{ route('admin.amenities.destroy', $a->id) }}" title="Delete" aria-label="Delete"><x-icon name="trash" /></button>
</div>
