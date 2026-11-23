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
<div class="act-group">
    <button type="button" class="btn-act is-amber btn-edit-category" data-url="{{ route('admin.blog-categories.update', $c->id) }}" data-payload='@json($payload)' title="Edit" aria-label="Edit"><x-icon name="pen" /></button>
    <button type="button" class="btn-act is-red btn-delete-category" data-url="{{ route('admin.blog-categories.destroy', $c->id) }}" title="Delete" aria-label="Delete"><x-icon name="trash" /></button>
</div>
