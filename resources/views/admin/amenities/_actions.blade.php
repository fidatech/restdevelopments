<div class="btn-group btn-group-sm" role="group">
    <button
        type="button"
        class="btn btn-primary btn-edit-amenity"
        data-url="{{ route('admin.amenities.update', $a->id) }}"
        data-payload='@json([
            "order"        => $a->order,
            "translations" => $a->translations->pluck("title","locale")
        ])'
    >
        <i class="ft ft-edit"></i>
    </button>

    <button
        type="button"
        class="btn btn-danger btn-delete-amenity"
        data-url="{{ route('admin.amenities.destroy', $a->id) }}"
    >
        <i class="ft ft-trash"></i>
    </button>
</div>
