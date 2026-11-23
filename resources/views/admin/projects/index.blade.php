@extends('admin.layouts.master')
@section('title', 'Projects')

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
        <span class="page-header-icon"><x-icon name="building" /></span>
        <div class="page-header-text">
            <h1>Projects</h1>
            <p class="page-header-sub">Ongoing & Completed</p>
        </div>
    </div>
    <div class="page-header-actions"><button class="btn btn-primary" id="toggleFormBtn">
                    <x-icon name="plus" class="me-1" /> Create New Project
                </button></div>
</div>

        <!-- Form Container -->
        <div class="form-container" id="formContainer">
            <div class="card page-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0" id="formTitle">Create Project</h5>
                    <button class="btn btn-sm btn-light-secondary d-none" id="btnCancelEdit">
                        <x-icon name="rotate-left" class="me-1" /> Cancel Edit
                    </button>
                </div>
                <div class="card-body">
                    <form id="projectForm" enctype="multipart/form-data" novalidate>
                        @csrf
                        <input type="hidden" id="editId" value="">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">State</label>
                                <select name="state" class="form-control form-select" required>
                                    <option value="ongoing" selected>Ongoing</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control form-select">
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Order</label>
                                <input type="number" name="order" class="form-control" value="0" min="0">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-8">
                                <label class="form-label">Location</label>
                                <input type="text" name="location" class="form-control" placeholder="Termiz" required value="{{ old('location') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Area (m²)</label>
                                <input type="number" name="area" class="form-control" min="0">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3">
                                <label class="form-label">Beds</label>
                                <input type="number" name="beds" class="form-control" min="0">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Baths</label>
                                <input type="number" name="baths" class="form-control" min="0">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">{{ __('Price Starts From') }}</label>
                                <input type="text" class="form-control" name="price_starts_from" min="0"
                                    value="{{ old('price_starts_from', $project->price_starts_from ?? '') }}">
                                <small class="text-muted">{{ __('Enter numeric amount (e.g. 2,500,000 USD)') }}</small>
                            </div>

                            <div class="col-md-3 form-check">
                                <input type="hidden" name="featured" value="0">
                                <input type="checkbox" class="form-check-input" id="featured" name="featured" value="1" @checked(old('featured', $project->featured ?? false))>
                                <label class="form-check-label" for="featured">{{ __('Featured') }}</label>
                            </div>

                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="upload-tile">
                                    <div class="fw-semibold">Image <small class="text-muted">(800×440)</small></div>
                                    <input type="file" name="image" id="image" class="form-control" accept="image/*">
                                    <div class="preview ratio mt-2" id="imgPreview"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            {{-- Translations --}}
                            <div class="col-12">
                                <ul class="nav nav-tabs" role="tablist">
                                    @foreach ($locales as $i => $loc)
                                        <li class="nav-item">
                                            <button class="nav-link {{ $i === 0 ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#tab-{{ $loc }}" type="button"
                                                role="tab">
                                                {{ strtoupper($loc) }}
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="tab-content rounded-bottom border-top-0 border p-3">
                                    @foreach ($locales as $i => $loc)
                                        <div class="tab-pane fade {{ $i === 0 ? 'show active' : '' }}" id="tab-{{ $loc }}" role="tabpanel">
                                            <div class="mb-3">
                                                <label class="form-label">Title @if ($loc === $default)
                                                        <span class="text-danger">*</span>
                                                    @endif
                                                </label>
                                                <input type="text" name="translations[{{ $loc }}][title]" class="form-control"
                                                    @if ($loc === $default) required @endif>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea name="translations[{{ $loc }}][description]" class="form-control" rows="3"></textarea>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-12">
                                <button class="btn btn-primary w-100 mt-1" id="btnSave">
                                    <x-icon name="floppy-disk" class="me-2" /><span id="saveText">Save Project</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Container -->
        <div class="table-container" id="tableContainer">
            <div class="card page-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Projects List</h5>
                    <small class="text-muted">Edit / delete. Order controls display sequence.</small>
                </div>
                <div class="card-body">
                    <table class="table-striped table-hover w-100 table" id="dtProjects">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Thumb</th>
                                <th>Title</th>
                                <th>State</th>
                                <th>Meta</th>
                                <th>Order</th>
                                <th>Status</th>
                                <th width="110">Actions</th>
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
    <script>
        (function() {
            const locales = @json($locales);
            const form = document.getElementById('projectForm');
            const editIdEl = document.getElementById('editId');
            const saveText = document.getElementById('saveText');
            const formTitle = document.getElementById('formTitle');
            const btnCancelEdit = document.getElementById('btnCancelEdit');
            const imgInput = document.getElementById('image');
            const imgPreview = document.getElementById('imgPreview');
            const toggleFormBtn = document.getElementById('toggleFormBtn');
            const formContainer = document.getElementById('formContainer');
            const tableContainer = document.getElementById('tableContainer');

            // Toggle form visibility
            let formVisible = false;
            toggleFormBtn.addEventListener('click', function() {
                formVisible = !formVisible;

                if (formVisible) {
                    formContainer.classList.remove('collapsed');
                    tableContainer.classList.add('collapsed');
                    toggleFormBtn.innerHTML = '<i class="fas fa-table me-1"></i> Show Projects Table';
                } else {
                    formContainer.classList.add('collapsed');
                    tableContainer.classList.remove('collapsed');
                    toggleFormBtn.innerHTML = '<i class="fas fa-plus me-1"></i> Create New Project';
                }
            });

            // Initialize with form hidden
            formContainer.classList.add('collapsed');

            function preview(input, box) {
                if (!input?.files?.[0] || !box) return;
                const url = URL.createObjectURL(input.files[0]);
                const img = box.querySelector('img') || (() => {
                    const el = document.createElement('img');
                    el.style.width = '800px';
                    el.style.height = '440px';
                    box.innerHTML = '';
                    box.appendChild(el);
                    return el;
                })();
                img.src = url;
                img.onload = () => URL.revokeObjectURL(url);
            }
            imgInput?.addEventListener('change', () => preview(imgInput, imgPreview));

            function clearTranslations() {
                locales.forEach(loc => {
                    const tTitle = document.querySelector(`[name="translations[${loc}][title]"]`);
                    const tDesc = document.querySelector(`[name="translations[${loc}][description]"]`);
                    if (tTitle) tTitle.value = '';
                    if (tDesc) tDesc.value = '';
                });
            }

            function toCreateMode() {
                editIdEl.value = '';
                formTitle.textContent = 'Create Project';
                saveText.textContent = 'Save Project';
                btnCancelEdit.classList.add('d-none');
                form.reset();
                clearTranslations();
                imgPreview.innerHTML = '';
            }
            btnCancelEdit.addEventListener('click', toCreateMode);

            const dt = $('#dtProjects').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ route('admin.projects.index') }}',
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
                        data: 'state',
                        name: 'state'
                    },
                    {
                        data: 'meta',
                        name: 'meta',
                        orderable: false,
                        searchable: false
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
                        searchable: false,
                        className: 'text-nowrap'
                    }
                ],
                order: [
                    [5, 'asc']
                ],
            });

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                const fd = new FormData(form);
                const id = editIdEl.value.trim();
                let url = '{{ route('admin.projects.store') }}';
                if (id) {
                    fd.append('_method', 'PUT');
                    url = '{{ url('admin/projects') }}/' + id;
                }
                const btn = document.getElementById('btnSave');
                btn.disabled = true;

                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: fd
                    });
                    const text = await res.text();
                    let data;
                    try {
                        data = JSON.parse(text);
                    } catch (_) {}
                    if (res.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: data?.message || 'Saved',
                            timer: 900,
                            showConfirmButton: false
                        });
                        dt.ajax.reload(null, false);
                        toCreateMode();

                        // Hide form after successful submission
                        formVisible = false;
                        formContainer.classList.add('collapsed');
                        tableContainer.classList.remove('collapsed');
                        toggleFormBtn.innerHTML = '<i class="fas fa-plus me-1"></i> Create New Project';
                    } else {
                        const msg = (data && (data.message || (data.errors && Object.values(data.errors).flat().join('<br>')))) || 'Error';
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation error',
                            html: msg
                        });
                    }
                } catch (err) {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Please try again.'
                    });
                } finally {
                    btn.disabled = false;
                }
            });

            document.addEventListener('click', async (e) => {
                const el = e.target.closest('.btn-edit');
                if (!el) return;
                const id = el.dataset.id;
                try {
                    const res = await fetch(`{{ url('admin/projects') }}/${id}/edit`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const row = await res.json();

                    editIdEl.value = row.id;
                    formTitle.textContent = 'Edit Project';
                    saveText.textContent = 'Update Project';
                    btnCancelEdit.classList.remove('d-none');

                    document.querySelector('[name="state"]').value = row.state || 'ongoing';
                    document.querySelector('[name="status"]').value = row.status ? 1 : 0;
                    document.querySelector('[name="order"]').value = row.order ?? 0;
                    document.querySelector('[name="location"]').value = row.location ?? '';
                    document.querySelector('[name="beds"]').value = row.beds ?? '';
                    document.querySelector('[name="baths"]').value = row.baths ?? '';
                    document.querySelector('[name="area"]').value = row.area ?? '';

                    imgPreview.innerHTML = '';
                    if (row.image) {
                        const img = document.createElement('img');
                        img.src = mediaUrl(row.image);
                        img.style.width = '800px';
                        img.style.height = '440px';
                        imgPreview.appendChild(img);
                    }

                    clearTranslations();
                    (row.translations || []).forEach(t => {
                        const tTitle = document.querySelector(`[name="translations[${t.locale}][title]"]`);
                        const tDesc = document.querySelector(`[name="translations[${t.locale}][description]"]`);
                        if (tTitle) tTitle.value = t.title || '';
                        if (tDesc) tDesc.value = t.description || '';
                    });

                    // Show form when editing
                    if (!formVisible) {
                        formVisible = true;
                        formContainer.classList.remove('collapsed');
                        tableContainer.classList.add('collapsed');
                        toggleFormBtn.innerHTML = '<i class="fas fa-table me-1"></i> Show Projects Table';
                    }

                    window.scrollTo({
                        top: form.offsetTop - 80,
                        behavior: 'smooth'
                    });
                } catch (err) {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Load failed',
                        text: 'Could not load the project.'
                    });
                }
            });

            document.addEventListener('click', async (e) => {
                const btn = e.target.closest('.btn-delete');
                if (!btn) return;
                e.preventDefault();
                const id = btn.dataset.id;

                const conf = await Swal.fire({
                    icon: 'warning',
                    title: 'Delete this project?',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete'
                });
                if (!conf.isConfirmed) return;

                try {
                    const res = await fetch(`{{ url('admin/projects') }}/${id}`, {
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
                    const data = await res.json().catch(() => null);
                    if (res.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: data?.message || 'Deleted',
                            timer: 900,
                            showConfirmButton: false
                        });
                        dt.ajax.reload(null, false);
                        if (editIdEl.value === String(id)) toCreateMode();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Delete failed',
                            text: data?.message || 'Please try again.'
                        });
                    }
                } catch (err) {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Please try again.'
                    });
                }
            });

        })();
    </script>
@endsection
