{{-- resources/views/admin/blog-categories/_actions.blade.php --}}
@php
    // Build a compact payload for edit modal
    $translations = $c->translations->mapWithKeys(function ($t) {
        return [$t->locale => ['title' => $t->title]];
    });

    $payload = [
        'id'           => $c->id,
        'order'        => $c->order,
        'translations' => $translations,
    ];
@endphp

<div class="btn-group btn-group-sm" role="group">
    <button type="button"
            class="btn btn-outline-primary btn-edit-category"
            data-url="{{ route('admin.blog-categories.update', $c->id) }}"
            data-payload='@json($payload)'
            title="Edit">
        <i class="ft ft-edit"></i>
    </button>

    <button type="button"
            class="btn btn-outline-danger btn-delete-category"
            data-url="{{ route('admin.blog-categories.destroy', $c->id) }}"
            title="Delete">
        <i class="ft ft-trash"></i>
    </button>
</div>
