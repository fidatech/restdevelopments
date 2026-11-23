@extends('admin.layouts.master')
@section('title', 'Careers')

@section('vendor-style')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
@endsection

@section('content')
@php
    $locales  = ['en','fa','ps','uz'];
    $default  = 'en';
@endphp
<div class="container-fluid">

    <div class="page-header card-soft is-inline">
    <div class="page-header-main">
        <span class="page-header-icon"><x-icon name="user-tie" /></span>
        <div class="page-header-text">
            <h1>Careers Management</h1>
            <p class="page-header-sub">Manage job vacancies and applications</p>
        </div>
    </div>
    <div class="page-header-actions"><button class="btn btn-primary" id="toggleFormBtn">
                <x-icon name="plus" class="me-2" /> New Vacancy
            </button></div>
</div>

    <!-- Form CARD -->
    <div class="form-container collapsed" id="formContainer">
        <div class="card page-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0" id="formTitle">Create New Vacancy</h5>
                <button class="btn btn-sm btn-light-secondary d-none" id="btnCancelEdit">
                    <x-icon name="xmark" class="me-1" /> Cancel
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
                                <button class="nav-link {{ $i==0?'active':'' }}" data-bs-toggle="tab"
                                        data-bs-target="#tab-{{ $lc }}" type="button">
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
                        <button type="button" class="btn btn-light-secondary me-3" id="btnResetForm">
                            <x-icon name="arrow-rotate-left" class="me-2" /> Reset
                        </button>
                        <button class="btn btn-primary" id="btnSave">
                            <x-icon name="floppy-disk" class="me-2" /> <span id="saveText">Save Vacancy</span>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
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
                    <div class="act-group">
                        <button type="button" onclick="editVacancy(${row.id})" class="btn-act is-amber" title="Edit" aria-label="Edit">${ERP.icon('s-pen')}</button>
                        <button type="button" onclick="deleteVacancy(${row.id})" class="btn-act is-red" title="Delete" aria-label="Delete">${ERP.icon('s-trash')}</button>
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
