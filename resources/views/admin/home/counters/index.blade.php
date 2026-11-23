@extends('admin.layouts.master')
@section('title', 'Home Counters')

@section('vendor-style')
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="page-header card-soft is-inline">
    <div class="page-header-main">
        <span class="page-header-icon"><x-icon name="chart-simple" /></span>
        <div class="page-header-text">
            <h1>Home Counters</h1>
        </div>
    </div>
</div>
    @php $locales = config('app.locales', ['en','fa','ps', 'uz']); @endphp
    <div class="container-fluid">

        <div class="row g-4">
            {{-- LEFT: FORM CARD --}}
            <div class="col-lg-5">
                <div class="card page-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-0" id="formTitle">Create Counter</h5>
                            <small class="text-muted">Use icon set or upload an image. Titles are translatable.</small>
                        </div>
                        <button class="btn btn-sm btn-light-secondary d-none" id="btnCancelEdit">
                            <x-icon name="rotate-left" class="me-1" /> Cancel Edit
                        </button>
                    </div>
                    <div class="card-body">
                        <form id="counterForm" enctype="multipart/form-data" novalidate>
                            @csrf
                            <input type="hidden" id="editId" value="">
                            <div class="row g-3">

                                <div class="col-6">
                                    <label class="form-label">Number</label>
                                    <input type="number" name="number" class="form-control" min="0" value="0" required>
                                </div>

                                <div class="col-6">
                                    <label class="form-label">Order</label>
                                    <input type="number" name="order" class="form-control" value="0" min="0">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>

                                {{-- Locale tabs --}}
                                <div class="col-12">
                                    <ul class="nav nav-tabs" role="tablist">
                                        @foreach ($locales as $i => $loc)
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link {{ $i === 0 ? 'active' : '' }}" id="tab-{{ $loc }}-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab-{{ $loc }}" type="button" role="tab" aria-controls="tab-{{ $loc }}"
                                                    aria-selected="{{ $i === 0 ? 'true' : 'false' }}">
                                                    {{ strtoupper($loc) }}
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <div class="tab-content border-top-0 rounded-bottom border p-3">
                                        @php
                                            $locales = config('app.locales', ['en','fa','ps', 'uz']);
                                            $default = config('app.fallback_locale', 'en');
                                        @endphp
                                        @foreach ($locales as $i => $loc)
                                            <div class="tab-pane fade {{ $i === 0 ? 'show active' : '' }}" id="tab-{{ $loc }}" role="tabpanel"
                                                aria-labelledby="tab-{{ $loc }}-tab" tabindex="0">
                                                <div class="mb-3">
                                                    <label class="form-label">Title ({{ strtoupper($loc) }})</label>
                                                    <input type="text" name="translations[{{ $loc }}][title]" class="form-control"
                                                        @if ($loc === $default) required @endif>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>

                                <div class="col-12">
                                    <button class="btn btn-primary w-100" id="btnSave"><x-icon name="floppy-disk" class="me-2" /><span id="saveText">Save Counter</span></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- RIGHT: TABLE CARD --}}
            <div class="col-lg-7">
                <div class="card page-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Counters List</h5>
                        <small class="text-muted">Drag order with field, or edit/delete from actions.</small>
                    </div>
                    <div class="card-body">
                        <table class="table-striped table-hover w-100 table" id="dtCounters">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Number</th>
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
    </div>
@endsection

@section('page-script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
  document.querySelectorAll('[data-bs-toggle="tab"]').forEach(el => {
    // No need to call new bootstrap.Tab(el) for default behavior,
    // but this ensures programmatic control if you ever need it.
    el.addEventListener('shown.tab', () => {});
  });
</script>

    <script>
        (function() {
            const locales = @json($locales);
            const form = document.getElementById('counterForm');
            const editIdEl = document.getElementById('editId');
            const saveText = document.getElementById('saveText');
            const formTitle = document.getElementById('formTitle');
            const btnCancelEdit = document.getElementById('btnCancelEdit');



            function toCreateMode() {
                editIdEl.value = '';
                formTitle.textContent = 'Create Counter';
                saveText.textContent = 'Save Counter';
                btnCancelEdit.classList.add('d-none');
                form.reset();
            }

            btnCancelEdit.addEventListener('click', toCreateMode);

            // DataTable
            const dt = $('#dtCounters').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ route('admin.home-counters.index') }}',
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
                        data: 'number',
                        name: 'number'
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
                drawCallback: function() {
                    if (window.feather) {
                        feather.replace();
                    }
                }
            });

            // Create/Update submit
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                const fd = new FormData(form);
                const id = editIdEl.value.trim();
                let url = '{{ route('admin.home-counters.store') }}';
                if (id) {
                    fd.append('_method', 'PUT');
                    url = '{{ url('admin/home-counters') }}/' + id;
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
                    } catch (_) {
                        data = null;
                    }

                    if (res.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: data?.message || 'Saved',
                            timer: 900,
                            showConfirmButton: false
                        });
                        dt.ajax.reload(null, false);
                        toCreateMode();
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

            // Edit
            document.addEventListener('click', async (e) => {
                const el = e.target.closest('.btn-edit');
                if (!el) return;
                const id = el.dataset.id;
                try {
                    const res = await fetch(`{{ url('admin/home-counters') }}/${id}/edit`);
                    const row = await res.json();

                    editIdEl.value = row.id;
                    formTitle.textContent = 'Edit Counter';
                    saveText.textContent = 'Update Counter';
                    btnCancelEdit.classList.remove('d-none');

                    // base fields
                    document.querySelector('[name="number"]').value = row.number ?? 0;
                    document.querySelector('[name="order"]').value = row.order ?? 0;
                    document.querySelector('[name="status"]').value = row.status ? 1 : 0;


                    // translations
                    (row.translations || []).forEach(t => {
                        const tTitle = document.querySelector(`[name="translations[${t.locale}][title]"]`);
                        if (tTitle) tTitle.value = t.title || '';
                    });

                    window.scrollTo({
                        top: form.offsetTop - 80,
                        behavior: 'smooth'
                    });
                } catch (err) {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Load failed',
                        text: 'Could not load the counter.'
                    });
                }
            });

            // Delete
            document.addEventListener('click', async (e) => {
                const btn = e.target.closest('.btn-delete');
                if (!btn) return;
                e.preventDefault();
                const id = btn.dataset.id;

                const conf = await Swal.fire({
                    icon: 'warning',
                    title: 'Delete this counter?',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete'
                });
                if (!conf.isConfirmed) return;

                try {
                    const res = await fetch(`{{ url('admin/home-counters') }}/${id}`, {
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
    {{-- Feather icons for admin preview (optional) --}}
    <script src="https://unpkg.com/feather-icons"></script>
@endsection
