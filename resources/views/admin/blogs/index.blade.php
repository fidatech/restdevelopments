{{-- resources/views/admin/blogs/index.blade.php --}}
@extends('admin.layouts.master')
@section('title', 'Blogs')

@section('vendor-style')
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('content')
    @php
        $locales = config('app.locales', ['en', 'fa', 'ps', 'uz']);
        $default = config('app.fallback_locale', 'en');
    @endphp

    <div class="container-fluid">

        <div class="page-header card-soft is-inline">
    <div class="page-header-main">
        <span class="page-header-icon"><x-icon name="newspaper" /></span>
        <div class="page-header-text">
            <h1>Blogs</h1>
            <p class="page-header-sub">Manage news / blog posts</p>
        </div>
    </div>
    <div class="page-header-actions"><button id="toggleFormBtn" class="btn btn-primary"><x-icon name="plus" class="me-1" /> Create New Blog</button></div>
</div>

        {{-- Form --}}
        <div class="form-container collapsed" id="formContainer">
            <div class="card page-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 id="formTitle" class="mb-0">Create Blog</h5>
                    <button class="btn btn-sm btn-light-secondary d-none" id="btnCancelEdit">
                        <x-icon name="rotate-left" class="me-1" /> Cancel Edit
                    </button>
                </div>
                <div class="card-body">
                    <form id="blogForm" enctype="multipart/form-data" novalidate>
                        @csrf
                        <input type="hidden" id="editId">

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Category</label>
                                <select class="form-control" name="blog_category_id" required>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->t()?->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Order</label>
                                <input class="form-control" type="number" name="order" value="0" min="0">
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="upload-tile">
                                    <div class="fw-semibold">Image <small class="text-muted">(upload once – will be resized)</small></div>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                    <div id="imgPreview" class="preview ratio mt-2"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Translations --}}
                        <ul class="nav nav-tabs mt-3" role="tablist">
                            @foreach ($locales as $i => $loc)
                                <li class="nav-item">
                                    <button class="nav-link {{ $i === 0 ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#tab-{{ $loc }}" type="button">
                                        {{ strtoupper($loc) }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content rounded-top-0 border p-3">
                            @foreach ($locales as $i => $loc)
                                <div class="tab-pane fade {{ $i === 0 ? 'show active' : '' }}" id="tab-{{ $loc }}">
                                    <div class="mb-2">
                                        <label>Title ({{ strtoupper($loc) }}) @if ($loc === $default) <span class="text-danger">*</span> @endif</label>
                                        <input class="form-control" name="translations[{{ $loc }}][title]" @if ($loc === $default) required @endif>
                                    </div>
                                    <div class="mb-2">
                                        <label>Excerpt (short)</label>
                                        <textarea class="form-control" name="translations[{{ $loc }}][excerpt]" rows="2"></textarea>
                                    </div>
                                    <div>
                                        <label>Content (Images uploaded here will be saved as BLOB)</label>
                                        <textarea name="translations[{{ $loc }}][content]" id="editor_{{ $loc }}" class="form-control blog-editor"></textarea>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button id="btnSave" class="btn btn-primary w-100 mt-2"><x-icon name="floppy-disk" class="me-2" /><span id="saveText">Save Blog</span></button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="table-container" id="tableContainer">
            <div class="card page-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Blog List</h5>
                    <small class="text-muted">Edit / delete</small>
                </div>
                <div class="card-body">
                    <table class="table-striped table-hover w-100 table" id="dtBlogs">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Thumb</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Order</th>
                                <th>Status</th>
                                <th style="width:110px">Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('page-script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
    const locales = @json($locales);
    const editors = {};
    const uploadUrl = "{{ route('admin.blogs.ckeditor.upload') }}";

    function createCk5UploadAdapter(loader) {
        return {
            upload: async () => {
                const file = await loader.file;

                const fd = new FormData();
                fd.append('upload', file);

                const res = await fetch(uploadUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: fd
                });

                if (!res.ok) {
                    let msg = 'Upload failed';
                    try {
                        const j = await res.json();
                        if (j?.message) msg = j.message;
                    } catch (e) {}
                    throw new Error(msg);
                }

                const data = await res.json();

                // CKEditor 5 expects: { default: "url" }
                if (!data?.url) {
                    throw new Error('Invalid upload response');
                }

                return {
                    default: data.url
                };
            },
            abort: () => {}
        };
    }

    // Initialize CKEditor5 instances
    async function initEditors() {
        for (const loc of locales) {
            const el = document.getElementById('editor_' + loc);
            if (!el) continue;

            try {
                const editor = await ClassicEditor.create(el, {
                    toolbar: [
                        'heading', '|', 'bold', 'italic', 'underline', 'link', '|',
                        'bulletedList', 'numberedList', '|',
                        'insertTable', 'blockQuote', 'imageUpload', '|', 'undo', 'redo'
                    ],
                    image: {
                        toolbar: [
                            'imageStyle:inline', 'imageStyle:block', 'imageStyle:side', '|',
                            'toggleImageCaption', 'imageTextAlternative'
                        ]
                    }
                });

                editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                    return createCk5UploadAdapter(loader);
                };

                editors[loc] = editor;
            } catch (error) {
                console.error('Error initializing editor for ' + loc, error);
            }
        }
    }

    (function() {
        const dt = $('#dtBlogs').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{ route('admin.blogs.index') }}?datatable=1",

            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'thumb',
                    name: 'thumb',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'category',
                    name: 'category'
                },
                {
                    data: 'order',
                    name: 'order'
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                }
            ],
            order: [
                [5, 'asc']
            ]
        });

        // --- toggle form ---
        let formVisible = false;
        const formContainer = document.getElementById('formContainer');
        const tableContainer = document.getElementById('tableContainer');
        const toggleFormBtn = document.getElementById('toggleFormBtn');
        const form = document.getElementById('blogForm');
        const editIdEl = document.getElementById('editId');

        toggleFormBtn.addEventListener('click', () => {
            formVisible = !formVisible;
            if (formVisible) {
                formContainer.classList.remove('collapsed');
                tableContainer.classList.add('collapsed');
                toggleFormBtn.innerHTML = '<i class="fas fa-table me-1"></i> Show Blogs Table';
            } else {
                toCreateMode();
                formContainer.classList.add('collapsed');
                tableContainer.classList.remove('collapsed');
                toggleFormBtn.innerHTML = '<i class="fas fa-plus me-1"></i> Create New Blog';
            }
        });

        // -------------------------------------------------------
        // helper to preview uploaded image
        function previewImage(input, box) {
            if (!input?.files?.[0] || !box) return;
            const url = URL.createObjectURL(input.files[0]);
            let img = box.querySelector('img');
            if (!img) {
                img = document.createElement('img');
                img.style.width = "50%";
                img.style.maxHeight = "340px";
                box.innerHTML = '';
                box.appendChild(img);
            }
            img.src = url;
            img.onload = () => URL.revokeObjectURL(url);
        }

        document.querySelector('[name="image"]').addEventListener('change', function() {
            previewImage(this, document.getElementById('imgPreview'));
        });

        // reset trans fields
        function clearTranslations(locales) {
            locales.forEach(loc => {
                let t = document.querySelector(`[name="translations[${loc}][title]"]`);
                if (t) t.value = '';
                t = document.querySelector(`[name="translations[${loc}][excerpt]"]`);
                if (t) t.value = '';
                t = document.querySelector(`[name="translations[${loc}][content]"]`);
                if (t) t.value = '';
                if (editors[loc]) editors[loc].setData('');
            });
        }

        const btnSave = document.getElementById('btnSave');
        const saveText = document.getElementById('saveText');
        const formTitle = document.getElementById('formTitle');
        const btnCancelEdit = document.getElementById('btnCancelEdit');

        btnCancelEdit.addEventListener('click', toCreateMode);

        function toCreateMode() {
            editIdEl.value = '';
            formTitle.textContent = 'Create Blog';
            saveText.textContent = 'Save Blog';
            btnCancelEdit.classList.add('d-none');
            form.reset();
            clearTranslations(locales);
            document.getElementById('imgPreview').innerHTML = '';
        }

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            // CRITICAL: Sync CKEditor data to textareas before sending
            locales.forEach(loc => {
                if (editors[loc]) {
                    const content = editors[loc].getData();
                    document.getElementById('editor_' + loc).value = content;
                }
            });

            const fd = new FormData(form);
            let url = "{{ route('admin.blogs.store') }}";
            if (editIdEl.value) {
                fd.append('_method', 'PUT');
                url = "{{ url('admin/blogs') }}/" + editIdEl.value;
            }

            btnSave.disabled = true;

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: fd
                });

                const data = await res.json();

                if (res.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: data.message || 'Saved',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    dt.ajax.reload(null, false);
                    toCreateMode();
                    toggleFormBtn.click();
                } else {
                    const msg = data.errors ? Object.values(data.errors).flat().join("<br>") : (data.message || 'Validation error');
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation error',
                        html: msg
                    });
                }
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong.'
                });
            } finally {
                btnSave.disabled = false;
            }
        });

        // --- EDIT ---
        $(document).on('click', '.btn-edit', async function() {
            const id = $(this).data('id');
            try {
                const res = await fetch("{{ url('admin/blogs') }}/" + id + "/edit", {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                const row = await res.json();

                editIdEl.value = row.id;
                formTitle.textContent = 'Edit Blog';
                saveText.textContent = 'Update Blog';
                btnCancelEdit.classList.remove('d-none');

                document.querySelector('[name="status"]').value = row.status ? 1 : 0;
                document.querySelector('[name="order"]').value = row.order ?? 0;
                document.querySelector('[name="blog_category_id"]').value = row.blog_category_id;

                const box = document.getElementById('imgPreview');
                box.innerHTML = row.thumb_small ? `<img src="/${row.thumb_small}" style="width:50%; max-height:340px;">` : '';

                // Set Translations and Editor Content
                locales.forEach(loc => {
                    const trans = (row.translations || []).find(t => t.locale === loc);
                    const titleEl = document.querySelector(`[name="translations[${loc}][title]"]`);
                    const excerptEl = document.querySelector(`[name="translations[${loc}][excerpt]"]`);

                    if (titleEl) titleEl.value = trans?.title || '';
                    if (excerptEl) excerptEl.value = trans?.excerpt || '';

                    if (editors[loc]) {
                        editors[loc].setData(trans?.content || '');
                    } else {
                        const ta = document.getElementById('editor_' + loc);
                        if (ta) ta.value = trans?.content || '';
                    }
                });

                if (!formVisible) toggleFormBtn.click();
                window.scrollTo({
                    top: formContainer.offsetTop - 20,
                    behavior: 'smooth'
                });

            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Could not load data'
                });
            }
        });

        // --- DELETE ---
        $(document).on('click', '.btn-delete', async function() {
            const id = $(this).data('id');
            const {
                isConfirmed
            } = await Swal.fire({
                title: 'Are you sure?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            });

            if (!isConfirmed) return;

            const res = await fetch("{{ url('admin/blogs') }}/" + id, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: new URLSearchParams({
                    _method: 'DELETE'
                })
            });

            if (res.ok) {
                dt.ajax.reload(null, false);
                Swal.fire('Deleted!', '', 'success');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Delete failed'
                });
            }
        });

        // Init CKEditors on load
        initEditors();

    })();
</script>
@endsection
