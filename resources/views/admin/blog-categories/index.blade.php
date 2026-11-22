@extends('admin.layouts.master')
@section('title', 'Blog Categories')

@section('vendor-style')
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        :root {
            --pri: 120, 75, 155;
            --gold: 195, 155, 110;
            --cream: 248, 245, 242;
            --r-card: 18px;
            --sh-sm: 0 6px 20px rgba(0, 0, 0, .06);
        }

        body {
            background: rgb(var(--cream));
        }

        .page-card {
            border: 0;
            border-radius: var(--r-card);
            background: #fff;
            box-shadow: var(--sh-sm);
            overflow: hidden;
        }

        .page-card .card-header {
            background: linear-gradient(135deg, rgba(var(--pri), .10), rgba(var(--gold), .08));
            border-bottom: 1px dashed rgba(0, 0, 0, .08);
            padding: 1rem 1.25rem;
        }

        .btn-premium {
            border: 0;
            border-radius: 999px;
            padding: .6rem 1.2rem;
            font-weight: 600;
            background: linear-gradient(135deg, rgb(var(--pri)), rgba(var(--pri), .92));
            color: #fff;
        }

        .btn-toggle-form {
            background: linear-gradient(135deg, rgba(var(--gold), .9), rgba(var(--gold), .8));
            color: #fff;
            border: 0;
            border-radius: 999px;
            padding: .6rem 1.2rem;
            font-weight: 600;
        }

        .form-container {
            transition: .3s;
            margin-bottom: 20px;
        }

        .form-container.collapsed {
            height: 0;
            overflow: hidden;
            opacity: 0;
            margin-bottom: 0;
        }

        .table-container {
            transition: .3s;
        }

        .table-container.collapsed {
            height: 0;
            overflow: hidden;
            opacity: 0;
        }
    </style>
@endsection

@section('content')
    @php
        $locales = config('app.locales', ['en', 'fa', 'ps', 'uz']);
        $default = config('app.fallback_locale', 'en');
    @endphp

    <div class="container-fluid">

        <div class="card page-card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">Blog Categories</h2>
                    <small class="text-muted">Manage blog post categories</small>
                </div>
                <button id="toggleFormBtn" class="btn btn-toggle-form"><i class="fas fa-plus me-1"></i> Add New Category</button>
            </div>
        </div>

        <!-- Form -->
        <div class="form-container collapsed" id="formContainer">
            <div class="card page-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 id="formTitle" class="mb-0">Add Category</h5>
                    <button class="btn btn-sm btn-outline-secondary rounded-pill d-none" id="btnCancelEdit"><i class="fas fa-rotate-left me-1"></i> Cancel</button>
                </div>
                <div class="card-body">
                    <form id="catForm" novalidate>
                        @csrf
                        <input type="hidden" id="editId">

                        <div class="row g-3">
                            <div class="col-sm-4">
                                <label class="form-label">Order</label>
                                <input class="form-control" name="order" type="number" value="0">
                            </div>
                        </div>

                        <hr />

                        {{-- Translations --}}
                        <ul class="nav nav-tabs" role="tablist">
                            @foreach ($locales as $i => $loc)
                                <li class="nav-item">
                                    <button class="nav-link {{ $i === 0 ? 'active' : '' }}" data-toggle="tab" data-target="#tab-{{ $loc }}" type="button">
                                        {{ strtoupper($loc) }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content pt-3">
                            @foreach ($locales as $i => $loc)
                                <div class="tab-pane fade {{ $i === 0 ? 'show active' : '' }}" id="tab-{{ $loc }}">
                                    <label>Title ({{ strtoupper($loc) }}) @if ($loc === $default)
                                            <span class="text-danger">*</span>
                                        @endif
                                    </label>
                                    <input class="form-control" name="translations[{{ $loc }}][title]" @if ($loc === $default) required @endif>
                                </div>
                            @endforeach
                        </div>

                        <button id="btnSave" class="btn btn-premium w-100 mt-2"><i class="fas fa-save me-2"></i><span id="saveText">Save Category</span></button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-container" id="tableContainer">
            <div class="card page-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Category List</h5>
                    <small class="text-muted">Edit / delete</small>
                </div>
                <div class="card-body">
                    <table id="dtCategories" class="table-striped table-bordered w-100 table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Order</th>
                                <th width="120">Actions</th>
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
            // ------ DataTable -----
            const dt = $('#dtCategories').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('admin.blog-categories.index') }}?datatable=1",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'order',
                        name: 'order'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [2, 'asc']
                ]
            });

            // ----- toggle form -----
            let formVisible = false;
            const formContainer = document.getElementById('formContainer');
            const tableContainer = document.getElementById('tableContainer');
            const toggleFormBtn = document.getElementById('toggleFormBtn');
            toggleFormBtn.addEventListener('click', () => {
                formVisible = !formVisible;
                if (formVisible) {
                    formContainer.classList.remove('collapsed');
                    tableContainer.classList.add('collapsed');
                    toggleFormBtn.innerHTML = '<i class="fas fa-table me-1"></i> Show Table';
                } else {
                    formContainer.classList.add('collapsed');
                    tableContainer.classList.remove('collapsed');
                    toggleFormBtn.innerHTML = '<i class="fas fa-plus me-1"></i> Add New Category';
                }
            });

            // ----- form -----
            const locales = @json($locales);
            const form = document.getElementById('catForm');
            const editIdEl = document.getElementById('editId');
            const saveText = document.getElementById('saveText');
            const btnCancel = document.getElementById('btnCancelEdit');

            btnCancel.addEventListener('click', toCreateMode);

            function clearTrans() {
                locales.forEach(loc => {
                    const f = document.querySelector(`[name="translations[${loc}][title]"]`);
                    if (f) f.value = '';
                });
            }

            function toCreateMode() {
                form.reset();
                clearTrans();
                editIdEl.value = '';
                saveText.textContent = 'Save Category';
                btnCancel.classList.add('d-none');
            }

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                let url = "{{ route('admin.blog-categories.store') }}";
                const fd = new FormData(form);
                if (editIdEl.value) {
                    fd.append('_method', 'PUT');
                    url = "{{ url('admin/blog-categories') }}/" + editIdEl.value;
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
                    if (res.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Saved',
                            timer: 900,
                            showConfirmButton: false
                        });
                        dt.ajax.reload(null, false);
                        toCreateMode();
                        formVisible = false;
                        formContainer.classList.add('collapsed');
                        tableContainer.classList.remove('collapsed');
                        toggleFormBtn.innerHTML = '<i class="fas fa-plus me-1"></i> Add New Category';
                    }
                } catch (err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Please try again'
                    });
                } finally {
                    btn.disabled = false;
                }
            });

            // Edit
            $(document).on('click', '.btn-edit-category', function() {
                const p = $(this).data('payload');
                editIdEl.value = p.id;
                document.querySelector('[name="order"]').value = p.order;

                clearTrans();
                Object.keys(p.translations).forEach(function(loc) {
                    const f = document.querySelector(`[name="translations[${loc}][title]"]`);
                    if (f) {
                        f.value = p.translations[loc].title;
                    }
                });


                saveText.textContent = 'Update Category';
                btnCancel.classList.remove('d-none');

                if (!formVisible) toggleFormBtn.click();
                window.scrollTo({
                    top: form.offsetTop - 80,
                    behavior: 'smooth'
                });
            });

            // Delete
            $(document).on('click', '.btn-delete-category', async function() {
                const url = $(this).data('url');
                const ok = await Swal.fire({
                    icon: 'warning',
                    title: 'Delete?',
                    showCancelButton: true,
                    confirmButtonText: 'Yes'
                });
                if (!ok.isConfirmed) return;
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: new URLSearchParams({
                        _method: 'DELETE'
                    })
                });
                if (res.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted',
                        timer: 900,
                        showConfirmButton: false
                    });
                    dt.ajax.reload(null, false);
                }
            });

        })();
    </script>
@endsection
