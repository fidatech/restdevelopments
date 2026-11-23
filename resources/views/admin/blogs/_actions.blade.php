{{-- resources/views/admin/blogs/_actions.blade.php --}}
@php
    // Build a compact payload for the edit form
    $translations = $b->translations->map(function($t){
        return [
            'locale'   => $t->locale,
            'title'    => $t->title,
            'excerpt'  => $t->excerpt,
            'content'  => $t->content,
        ];
    });

    $payload = [
        'id'           => $b->id,
        'category_id'  => $b->blog_category_id,
        'status'       => $b->status,
        'order'        => $b->order,
        'thumb_small'  => $b->thumb_small,
        'translations' => $translations,
    ];
@endphp
<div class="act-group">
    <button type="button" class="btn-act is-amber btn-edit" data-id="{{ $b->id }}" data-url="{{ route('admin.blogs.update', $b->id) }}" data-payload='@json($payload)' title="Edit" aria-label="Edit"><x-icon name="pen" /></button>
    <button type="button" class="btn-act is-red btn-delete" data-id="{{ $b->id }}" data-url="{{ route('admin.blogs.destroy', $b->id) }}" title="Delete" aria-label="Delete"><x-icon name="trash" /></button>
</div>
