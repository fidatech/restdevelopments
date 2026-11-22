@extends('admin.layouts.master')
@section('title', 'Careers')

@section('vendor-style')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote.min.css" rel="stylesheet">

<style>
    :root {
        --pri: 120, 75, 155;
        --gold: 195, 155, 110;
        --ink: 30, 30, 45;
        --cream: 248, 245, 242;
        --danger: 200, 70, 85;
        --r-card: 12px;
        --sh-sm: 0 2px 8px rgba(0, 0, 0, .05);
        --sh-md: 0 4px 12px rgba(0, 0, 0, .08);
        --transition: all 0.25s cubic-bezier(0.3, 0, 0.4, 1);
    }

    body {background: rgb(var(--cream));}

    .page-card {
        border: 0;
        border-radius: var(--r-card);
        background: #fff;
        box-shadow: var(--sh-sm);
        overflow: hidden;
        transition: var(--transition);
    }

    .page-card:hover {
        box-shadow: var(--sh-md);
    }

    .page-card .card-header {
        background: linear-gradient(135deg, rgba(var(--pri),.05), rgba(var(--gold),.03));
        border-bottom: 1px solid rgba(0,0,0,.05);
        padding: 1.25rem 1.5rem;
    }

    .btn-primary {
        background: rgba(var(--pri), 0.9);
        border-color: rgba(var(--pri), 0.9);
    }

    .btn-primary:hover {
        background: rgba(var(--pri), 1);
        border-color: rgba(var(--pri), 1);
    }

    .btn-gold {
        background: rgba(var(--gold), 0.9);
        border-color: rgba(var(--gold), 0.9);
        color: white;
    }

    .btn-gold:hover {
        background: rgba(var(--gold), 1);
        border-color: rgba(var(--gold), 1);
        color: white;
    }

    .form-container, .table-container {
        transition: var(--transition);
    }

    .collapsed {
        height: 0 !important;
        overflow: hidden;
        opacity: 0;
        margin-bottom: 0 !important;
    }

    .nav-tabs .nav-link {
        color: rgba(var(--ink), 0.7);
        border: none;
        padding: 0.75rem 1.25rem;
        font-weight: 500;
    }

    .nav-tabs .nav-link.active {
        color: rgba(var(--pri), 1);
        border-bottom: 2px solid rgba(var(--pri), 1);
        background: transparent;
    }

    .form-section {
        background: rgba(0,0,0,.02);
        border-radius: var(--r-card);
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .form-section-title {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: rgba(var(--ink), 0.6);
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .form-control, .form-select {
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
        border: 1px solid rgba(0,0,0,.1);
    }

    .form-control:focus, .form-select:focus {
        border-color: rgba(var(--pri), 0.5);
        box-shadow: 0 0 0 0.2rem rgba(var(--pri), 0.1);
    }

    .note-editor.note-frame {
        border-radius: 6px;
        border-color: rgba(0,0,0,.1);
    }

    .status-badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .status-badge.active {
        background: rgba(40, 180, 110, 0.1);
        color: #28a745;
    }

    .status-badge.inactive {
        background: rgba(220, 60, 70, 0.1);
        color: #dc3545;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        background: rgba(var(--ink), 0.05);
        color: rgba(var(--ink), 0.7);
        border: none;
        transition: var(--transition);
    }

    .action-btn:hover {
        background: rgba(var(--pri), 0.1);
        color: rgba(var(--pri), 1);
    }

    .action-btn.delete:hover {
        background: rgba(var(--danger), 0.1);
        color: rgba(var(--danger), 1);
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.5rem 0.75rem;
        border-radius: 6px !important;
        border: none !important;
        margin-left: 0.25rem !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: rgba(var(--pri), 0.1) !important;
        color: rgba(var(--pri), 1) !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: rgba(var(--pri), 0.1) !important;
        color: rgba(var(--pri), 1) !important;
    }

    .tab-content {
        border: 1px solid rgba(0,0,0,.05);
        border-top: none;
        border-radius: 0 0 var(--r-card) var(--r-card);
        background: #fff;
    }
</style>
@endsection

@section('content')
@php
    $locales  = ['en','fa','ps','uz'];
    $default  = 'en';
@endphp
<div class="container-fluid">

    <div class="card page-card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">Careers Management</h2>
                <small class="text-muted">Manage job vacancies and applications</small>
            </div>
            <button class="btn btn-gold" id="toggleFormBtn">
                <i class="fas fa-plus me-2"></i> New Vacancy
            </button>
        </div>
    </div>

    <!-- Form CARD -->
    <div class="form-container collapsed" id="formContainer">
        <div class="card page-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0" id="formTitle">Create New Vacancy</h5>
                <button class="btn btn-sm btn-outline-secondary rounded-pill d-none" id="btnCancelEdit">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
            </div>
            <div class="card-body">
                <form id="vacancyForm">
                    @csrf
                    <input type="hidden" id="editId">

                    <!-- Tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        @foreach($locales as $i => $lc)
                            <li class="nav-item">
                                <button class="nav-link {{ $i==0?'active':'' }}" data-toggle="tab"
                                        data-target="#tab-{{ $lc }}" type="button">
                                    {{ strtoupper($lc) }}
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content p-4">
                        @foreach($locales as $i => $lc)
                            <div class="tab-pane fade {{ $i==0?'show active':'' }}" id="tab-{{ $lc }}">
                                <div class="form-section">
                                    <h6 class="form-section-title">Basic Information</h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Title ({{ strtoupper($lc) }}) @if($lc==$default)<span class="text-danger">*</span>@endif</label>
                                            <input type="text" name="title[{{ $lc }}]" class="form-control form-control-sm" @if($lc==$default) required @endif>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Department ({{ strtoupper($lc) }})</label>
                                            <input type="text" name="department[{{ $lc }}]" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Location ({{ strtoupper($lc) }})</label>
                                            <input type="text" name="location[{{ $lc }}]" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Job Type ({{ strtoupper($lc) }})</label>
                                            <input type="text" name="type[{{ $lc }}]" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-section">
                                    <h6 class="form-section-title">Job Details</h6>
                                    <div class="mb-3">
                                        <label class="form-label">Description ({{ strtoupper($lc) }})</label>
                                        <textarea name="description[{{ $lc }}]" class="form-control summernote"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Requirements ({{ strtoupper($lc) }})</label>
                                        <textarea name="requirements[{{ $lc }}]" class="form-control summernote"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Responsibilities ({{ strtoupper($lc) }})</label>
                                        <textarea name="responsibilities[{{ $lc }}]" class="form-control summernote"></textarea>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="form-section mt-4">
                        <h6 class="form-section-title">Additional Information</h6>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Application Deadline</label>
                                <input type="date" name="deadline" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Salary Range</label>
                                <input type="text" name="salary" class="form-control form-control-sm" placeholder="e.g. $50,000 - $60,000">
                            </div>
                            <div class="col-md-3 mb-3 d-flex align-items-end">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="statusSwitch" name="status" checked>
                                    <label class="form-check-label" for="statusSwitch">Active Listing</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-outline-secondary me-3 rounded-pill" id="btnResetForm">
                            <i class="fas fa-undo me-2"></i> Reset
                        </button>
                        <button class="btn btn-primary rounded-pill" id="btnSave">
                            <i class="fas fa-save me-2"></i> <span id="saveText">Save Vacancy</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- TABLE CARD -->
    <div class="table-container" id="tableContainer">
        <div class="card page-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Current Vacancies</h5>
                <div class="d-flex">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search..." style="max-width: 200px;">
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover w-100" id="vacancyTable">
                    <thead>
                        <tr>
                            <th width="35%">Position</th>
                            <th width="20%">Department</th>
                            <th width="15%">Deadline</th>
                            <th width="10%">Status</th>
                            <th width="20%" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@section('page-script')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function(){
    const formContainer   = document.getElementById('formContainer');
    const tableContainer  = document.getElementById('tableContainer');
    const toggleBtn       = document.getElementById('toggleFormBtn');
    const btnCancelEdit   = document.getElementById('btnCancelEdit');
    const btnResetForm    = document.getElementById('btnResetForm');
    const form            = document.getElementById('vacancyForm');
    const editId          = document.getElementById('editId');
    const saveText        = document.getElementById('saveText');
    const formTitle       = document.getElementById('formTitle');
    const searchInput     = document.getElementById('searchInput');
    let visible = false;

    // Summernote init
    function initSummernote(){
        $('.summernote').summernote('destroy');
        $('.summernote').summernote({
            height: 180,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
                ['view', ['codeview']]
            ]
        });
    }

    // Initial state => hide form
    formContainer.classList.add('collapsed');
    tableContainer.classList.remove('collapsed');

    toggleBtn.addEventListener('click', () => {
        visible = !visible;

        if(visible){
            formContainer.classList.remove('collapsed');
            tableContainer.classList.add('collapsed');
            toggleBtn.innerHTML = '<i class="fas fa-table me-2"></i> View Listings';
            initSummernote();
        } else {
            formContainer.classList.add('collapsed');
            tableContainer.classList.remove('collapsed');
            toggleBtn.innerHTML = '<i class="fas fa-plus me-2"></i> New Vacancy';
        }
    });

    btnCancelEdit.addEventListener('click', () => {
        toCreateMode();
        visible = false;
        formContainer.classList.add('collapsed');
        tableContainer.classList.remove('collapsed');
        toggleBtn.innerHTML = '<i class="fas fa-plus me-2"></i> New Vacancy';
    });

    btnResetForm.addEventListener('click', () => {
        form.reset();
        $('.summernote').summernote('reset');
    });

    function toCreateMode(){
        btnCancelEdit.classList.add('d-none');
        editId.value = '';
        formTitle.textContent = 'Create New Vacancy';
        saveText.textContent  = 'Save Vacancy';
        form.reset();
        $('.summernote').summernote('reset');
    }

    // DataTable
    const dt = $('#vacancyTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.careers.index") }}',
        columns: [
            {data: 'title', name: 'title', render: function(data, type, row) {
                return `<div class="fw-semibold">${data}</div><small class="text-muted">${row.type?.en || ''}</small>`;
            }},
            {data: 'department', name: 'department'},
            {data: 'deadline', name: 'deadline', render: function(data) {
                return data ? new Date(data).toLocaleDateString() : 'N/A';
            }},
            {data: 'status', name: 'status', render: function(data) {
                return `<span class="status-badge ${data}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
            }},
            {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end', render: function(data, type, row) {
                return `
                    <div class="d-flex justify-content-end">
                        <button onclick="editVacancy(${row.id})" class="action-btn me-2" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button onclick="deleteVacancy(${row.id})" class="action-btn delete" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
            }}
        ],
        language: {
            emptyTable: "No vacancies found",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            search: "_INPUT_",
            searchPlaceholder: "Search vacancies..."
        }
    });

    // Search functionality
    searchInput.addEventListener('keyup', function() {
        dt.search(this.value).draw();
    });

    // Submit (create/update)
    form.addEventListener('submit', function(e){
        e.preventDefault();
        const id  = editId.value;
        const url = id ? '{{ url("admin/careers") }}/'+id : '{{ route("admin.careers.store") }}';
        const type= id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: type,
            data: $(form).serialize(),
            beforeSend: function() {
                $('#btnSave').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Saving...');
            },
            success: function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Saved Successfully',
                    timer: 1500,
                    showConfirmButton: false,
                    position: 'top-end',
                    toast: true
                });
                dt.ajax.reload();
                toCreateMode();
                visible = false;
                formContainer.classList.add('collapsed');
                tableContainer.classList.remove('collapsed');
                toggleBtn.innerHTML = '<i class="fas fa-plus me-2"></i> New Vacancy';
            },
            complete: function() {
                $('#btnSave').prop('disabled', false).html(`<i class="fas fa-save me-2"></i> ${id ? 'Update' : 'Save'} Vacancy`);
            }
        });
    });

    // Edit
    window.editVacancy = function(id){
        $.get('{{ url("admin/careers") }}/'+id+'/edit', function(res){
            editId.value = res.id;
            formTitle.textContent = 'Edit Vacancy';
            saveText.textContent = 'Update Vacancy';
            btnCancelEdit.classList.remove('d-none');

            $('[name="deadline"]').val(res.deadline ? res.deadline.substring(0,10) : '');
            $('[name="salary"]').val(res.salary);
            $('#statusSwitch').prop('checked', res.status === 'active');

            // reset summernote + fill
            $('.summernote').summernote('reset');
            ['en','fa','ps','uz'].forEach(function(lc){
                $('[name="title['+lc+']"]').val(getVal(res, lc, 'title'));
                $('[name="department['+lc+']"]').val(getVal(res, lc, 'department'));
                $('[name="location['+lc+']"]').val(getVal(res, lc, 'location'));
                $('[name="type['+lc+']"]').val(getVal(res, lc, 'type'));
                $('[name="description['+lc+']"]').summernote('code', getVal(res, lc, 'description') || '');
                $('[name="requirements['+lc+']"]').summernote('code', getVal(res, lc, 'requirements') || '');
                $('[name="responsibilities['+lc+']"]').summernote('code', getVal(res, lc, 'responsibilities') || '');
            });

            // show form
            visible = true;
            formContainer.classList.remove('collapsed');
            tableContainer.classList.add('collapsed');
            toggleBtn.innerHTML = '<i class="fas fa-table me-2"></i> View Listings';
            initSummernote();

            // Scroll to form
            $('html, body').animate({
                scrollTop: formContainer.offsetTop - 20
            }, 300);
        });
    };

    // Delete
    window.deleteVacancy = function(id){
        Swal.fire({
            icon: 'question',
            title: 'Confirm Deletion',
            text: 'Are you sure you want to delete this vacancy?',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#dc3545'
        }).then(r => {
            if(!r.isConfirmed) return;
            $.ajax({
                url: '{{ url("admin/careers") }}/'+id,
                type: 'DELETE',
                data: {_token: '{{ csrf_token() }}'},
                success: () => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted',
                        text: 'The vacancy has been deleted',
                        timer: 1500,
                        showConfirmButton: false,
                        position: 'top-end',
                        toast: true
                    });
                    dt.ajax.reload();
                }
            });
        });
    }

    function getVal(res, lc, field){
        const t = (res.translations||[]).find(x => x.locale === lc);
        return t ? t[field] : '';
    }
})();
</script>
@endsection
