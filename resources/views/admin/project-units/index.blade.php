{{-- resources/views/admin/project-units/index.blade.php --}}
@extends('admin.layouts.master')
@section('title', 'Project Units: '.$project->translations()->where('locale', app()->getLocale())->first()->title)

@section('vendor-style')
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        :root {
            --pri: 120, 75, 155;
            --gold: 195, 155, 110;
            --ink: 30, 30, 45;
            --cream: 248, 245, 242;
            --danger: 200, 70, 85;
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

        .action-buttons {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .action-btn {
            border-radius: 12px;
            padding: 12px 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .action-btn i {
            font-size: 1.1em;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.1);
        }

        .btn-units {
            background: linear-gradient(135deg, rgba(var(--pri), 0.9), rgba(var(--pri), 0.8));
            color: white;
        }

        .btn-documents {
            background: linear-gradient(135deg, #4a6cf7, #6a8eff);
            color: white;
        }

        .btn-gallery {
            background: linear-gradient(135deg, #f75a92, #ff7ead);
            color: white;
        }

        .btn-toggle-form {
            background: linear-gradient(135deg, rgba(var(--gold), 0.9), rgba(var(--gold), 0.8));
            color: white;
        }

        .form-container {
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .form-container.collapsed {
            height: 0;
            overflow: hidden;
            opacity: 0;
            margin-bottom: 0;
        }

        .table-container {
            transition: all 0.3s ease;
        }

        .table-container.collapsed {
            height: 0;
            overflow: hidden;
            opacity: 0;
        }

        .upload-tile {
            border: 1px dashed rgba(0, 0, 0, .2);
            border-radius: 14px;
            padding: 1rem;
            background: linear-gradient(135deg, #fbf8f4, #f4efe9);
        }

        .preview {
            margin-top: .6rem;
            border-radius: 12px;
            overflow: hidden;
            background: #f6f6f8;
            border: 1px solid rgba(0, 0, 0, .06);
            --bs-aspect-ratio: 55%;
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
                <h2 class="mb-0">Units for: {{ $project->translations()->where('locale', app()->getLocale())->first()->title }}</h2>
                <small class="text-muted">Manage all units for this project</small>
            </div>
            <button class="btn btn-toggle-form" id="toggleFormBtn">
                <i class="fas fa-plus me-1"></i> Add New Unit
            </button>
        </div>
    </div>

    <!-- Form Container -->
    <div class="form-container collapsed" id="formContainer">
        <div class="card page-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0" id="formTitle">Add Unit</h5>
                <button class="btn btn-sm btn-outline-secondary rounded-pill d-none" id="btnCancelEdit">
                    <i class="fas fa-rotate-left me-1"></i> Cancel Edit
                </button>
            </div>
            <div class="card-body">
                <form id="unitForm" novalidate>
                    @csrf
                    <input type="hidden" id="formMethod" value="POST">
                    <input type="hidden" id="editUrl" value="">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Bedrooms</label>
                            <input type="number" min="0" class="form-control" name="bedrooms" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Min Area (m²)</label>
                            <input type="number" min="0" class="form-control" name="min_area">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Max Area (m²)</label>
                            <input type="number" min="0" class="form-control" name="max_area">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Count</label>
                            <input type="number" min="0" class="form-control" name="count">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Order</label>
                            <input type="number" min="0" class="form-control" name="order" value="0">
                        </div>
                    </div>

                    <hr>

                    {{-- Locale Tabs --}}
                    <ul class="nav nav-tabs" role="tablist">
                        @foreach($locales as $i => $loc)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if($i===0) active @endif" data-toggle="tab" data-target="#tab-{{ $loc }}" type="button" role="tab">
                                {{ strtoupper($loc) }}
                            </button>
                        </li>
                        @endforeach
                    </ul>
                    <div class="tab-content pt-3">
                        @foreach($locales as $i => $loc)
                        <div class="tab-pane fade @if($i===0) show active @endif" id="tab-{{ $loc }}" role="tabpanel">
                            <div class="mb-3">
                                <label class="form-label">Name ({{ strtoupper($loc) }})</label>
                                <input type="text" class="form-control" name="name[{{ $loc }}]">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description ({{ strtoupper($loc) }})</label>
                                <textarea class="form-control" rows="3" name="description[{{ $loc }}]"></textarea>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="d-grid mt-3">
                        <button class="btn btn-premium" type="submit" id="saveBtn">
                            <i class="fas fa-save me-2"></i> Save Unit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="table-container" id="tableContainer">
        <div class="card page-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Units List</h5>
                <small class="text-muted">Edit / delete. Order controls display sequence.</small>
            </div>
            <div class="card-body">
                <table id="unitsTable" class="table-striped table-bordered w-100 table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name ({{ app()->getLocale() }})</th>
                            <th>Bedrooms</th>
                            <th>Area (m²)</th>
                            <th>Count</th>
                            <th>Order</th>
                            <th style="width:120px">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function(){
    const projectId = {{ $project->id }};
    const dtUrl = "{{ route('admin.projects.units.datatable', $project->id) }}";
    const storeUrl = "{{ route('admin.projects.units.store', $project->id) }}";

    const formContainer = document.getElementById('formContainer');
    const tableContainer = document.getElementById('tableContainer');
    const toggleFormBtn = document.getElementById('toggleFormBtn');
    const unitForm = document.getElementById('unitForm');
    const formMethod = document.getElementById('formMethod');
    const editUrlEl = document.getElementById('editUrl');
    const formTitle = document.getElementById('formTitle');
    const btnCancelEdit = document.getElementById('btnCancelEdit');
    const saveBtn = document.getElementById('saveBtn');

    // Toggle form visibility
    let formVisible = false;
    toggleFormBtn.addEventListener('click', function() {
        formVisible = !formVisible;

        if (formVisible) {
            formContainer.classList.remove('collapsed');
            tableContainer.classList.add('collapsed');
            toggleFormBtn.innerHTML = '<i class="fas fa-table me-1"></i> Show Units Table';
        } else {
            formContainer.classList.add('collapsed');
            tableContainer.classList.remove('collapsed');
            toggleFormBtn.innerHTML = '<i class="fas fa-plus me-1"></i> Add New Unit';
        }
    });

    function toCreateMode() {
        unitForm.reset();
        formMethod.value = 'POST';
        editUrlEl.value = '';
        formTitle.textContent = 'Add Unit';
        saveBtn.innerHTML = '<i class="fas fa-save me-2"></i> Save Unit';
        btnCancelEdit.classList.add('d-none');
    }
    btnCancelEdit.addEventListener('click', toCreateMode);

    const table = $('#unitsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: dtUrl,
        columns: [
            {data: 'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false},
            {data: 'name', name:'name'},
            {data: 'bedrooms', name:'bedrooms'},
            {data: 'area', name:'area', orderable:false, searchable:false},
            {data: 'count', name:'count'},
            {data: 'order', name:'order'},
            {data: 'actions', name:'actions', orderable:false, searchable:false},
        ],
        order: [[5, 'asc']]
    });

    // Submit (create or update)
    unitForm.addEventListener('submit', async function(e){
        e.preventDefault();
        const formData = new FormData(unitForm);
        let url = storeUrl;
        let method = formMethod.value || 'POST';

        if (method === 'PUT') {
            url = editUrlEl.value;
            formData.append('_method', 'PUT');
        }

        const btn = saveBtn;
        btn.disabled = true;

        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
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
                table.ajax.reload(null, false);
                toCreateMode();

                // Hide form after successful submission
                formVisible = false;
                formContainer.classList.add('collapsed');
                tableContainer.classList.remove('collapsed');
                toggleFormBtn.innerHTML = '<i class="fas fa-plus me-1"></i> Add New Unit';
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

    // Edit button (delegated)
    $(document).on('click','.btn-edit-unit', function(){
        const payload = $(this).data('payload');
        unitForm.reset();
        formMethod.value = 'PUT';
        editUrlEl.value = $(this).data('url');
        formTitle.textContent = 'Edit Unit';
        saveBtn.innerHTML = '<i class="fas fa-save me-2"></i> Update Unit';
        btnCancelEdit.classList.remove('d-none');

        // Fill base fields
        unitForm.querySelector('[name="bedrooms"]').value = payload.bedrooms ?? '';
        unitForm.querySelector('[name="min_area"]').value = payload.min_area ?? '';
        unitForm.querySelector('[name="max_area"]').value = payload.max_area ?? '';
        unitForm.querySelector('[name="count"]').value = payload.count ?? '';
        unitForm.querySelector('[name="order"]').value = payload.order ?? 0;

        // Fill translations
        if (payload.translations) {
            Object.keys(payload.translations).forEach(function(loc){
                const t = payload.translations[loc];
                const nameInput = unitForm.querySelector(`[name="name[${loc}]"]`);
                const descInput = unitForm.querySelector(`[name="description[${loc}]"]`);
                if (nameInput) nameInput.value = t.name ?? '';
                if (descInput) descInput.value = t.description ?? '';
            });
        }

        // Show form when editing
        if (!formVisible) {
            formVisible = true;
            formContainer.classList.remove('collapsed');
            tableContainer.classList.add('collapsed');
            toggleFormBtn.innerHTML = '<i class="fas fa-table me-1"></i> Show Units Table';
        }

        window.scrollTo({
            top: unitForm.offsetTop - 80,
            behavior: 'smooth'
        });
    });

    // Delete button
    $(document).on('click','.btn-delete-unit', async function(){
        const url = $(this).data('url');
        const conf = await Swal.fire({
            icon: 'warning',
            title: 'Delete this unit?',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete'
        });
        if (!conf.isConfirmed) return;

        try {
            const res = await fetch(url, {
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
                table.ajax.reload(null, false);
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
