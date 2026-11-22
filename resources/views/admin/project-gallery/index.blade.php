@extends('admin.layouts.master')
@section('title', 'Project Gallery: '.$project->translations()->where('locale', app()->getLocale())->first()->title)

@section('vendor-style')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<style>
    :root { --pri: 120,75,155; --gold:195,155,110; --ink:30,30,45; --cream:248,245,242; --danger:200,70,85; --r-card:18px; --sh-sm:0 6px 20px rgba(0,0,0,.06); }
    body { background: rgb(var(--cream)); }
    .page-card { border:0; border-radius:var(--r-card); background:#fff; box-shadow:var(--sh-sm); overflow:hidden; }
    .page-card .card-header { background:linear-gradient(135deg, rgba(var(--pri),.10), rgba(var(--gold),.08)); border-bottom:1px dashed rgba(0,0,0,.08); padding:1rem 1.25rem; }
    .btn-premium{ border:0;border-radius:999px;padding:.6rem 1.2rem;font-weight:600;background:linear-gradient(135deg,rgb(var(--pri)),rgba(var(--pri),.92));color:#fff;}
    .btn-toggle-form{ background:linear-gradient(135deg, rgba(var(--gold),.9), rgba(var(--gold),.8)); color:#fff; border:0; border-radius:999px; padding:.6rem 1.2rem; font-weight:600;}
    .form-container{transition:all .3s ease;margin-bottom:20px;}
    .form-container.collapsed{height:0;overflow:hidden;opacity:0;margin-bottom:0;}
    .table-container{transition:all .3s ease;}
    .table-container.collapsed{height:0;overflow:hidden;opacity:0;}
    .upload-tile{border:1px dashed rgba(0,0,0,.2);border-radius:14px;padding:1rem;background:linear-gradient(135deg,#fbf8f4,#f4efe9);}
</style>
@endsection

@section('content')
<div class="container-fluid">
  <div class="card page-card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div>
        <h2 class="mb-0">Gallery for: {{ $project->translations()->where('locale', app()->getLocale())->first()->title }}</h2>
        <small class="text-muted">Upload interior/exterior images (recommended 1200×660 webp)</small>
      </div>
      <button class="btn btn-toggle-form" id="toggleFormBtn">
        <i class="fas fa-plus me-1"></i> Add New Image
      </button>
    </div>
  </div>

  {{-- Form --}}
  <div class="form-container collapsed" id="formContainer">
    <div class="card page-card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0" id="formTitle">Add Image</h5>
        <button class="btn btn-sm btn-outline-secondary rounded-pill d-none" id="btnCancelEdit">
          <i class="fas fa-rotate-left me-1"></i> Cancel Edit
        </button>
      </div>
      <div class="card-body">
        <form id="galForm" enctype="multipart/form-data" novalidate>
          @csrf
          <input type="hidden" id="formMethod" value="POST">
          <input type="hidden" id="editUrl" value="">
          <div class="row g-3">
            <div class="col-md-3">
              <label class="form-label">Type</label>
              <select name="type" class="form-control" required>
                <option value="">Select</option>
                <option value="interior">Interior</option>
                <option value="exterior">Exterior</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Order</label>
              <input type="number" min="0" class="form-control" name="order" value="0">
            </div>
            <div class="col-md-6">
              <label class="form-label">Image <small class="text-muted">(webp/jpg/png)</small></label>
              <div class="upload-tile">
                <input type="file" name="image" accept="image/webp,image/png,image/jpeg" class="form-control" id="imgInput" required>
              </div>
            </div>
          </div>

          <div class="d-grid mt-3">
            <button class="btn btn-premium" type="submit" id="saveBtn">
              <i class="fas fa-save me-2"></i> Save Image
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Table --}}
  <div class="table-container" id="tableContainer">
    <div class="card page-card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Gallery List</h5>
        <small class="text-muted">Edit / delete. Order controls display sequence.</small>
      </div>
      <div class="card-body">
        <table id="galTable" class="table table-striped table-bordered w-100">
          <thead>
            <tr>
              <th>#</th>
              <th>Preview</th>
              <th>Type</th>
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
  const dtUrl    = "{{ route('admin.projects.gallery.datatable', $project->id) }}";
  const storeUrl = "{{ route('admin.projects.gallery.store', $project->id) }}";

  const formContainer = document.getElementById('formContainer');
  const tableContainer= document.getElementById('tableContainer');
  const toggleFormBtn = document.getElementById('toggleFormBtn');
  const galForm       = document.getElementById('galForm');
  const formMethod    = document.getElementById('formMethod');
  const editUrlEl     = document.getElementById('editUrl');
  const formTitle     = document.getElementById('formTitle');
  const btnCancelEdit = document.getElementById('btnCancelEdit');
  const saveBtn       = document.getElementById('saveBtn');

  let formVisible = false;
  toggleFormBtn.addEventListener('click', function(){
    formVisible = !formVisible;
    if (formVisible){
      formContainer.classList.remove('collapsed');
      tableContainer.classList.add('collapsed');
      toggleFormBtn.innerHTML = '<i class="fas fa-table me-1"></i> Show Gallery Table';
    } else {
      formContainer.classList.add('collapsed');
      tableContainer.classList.remove('collapsed');
      toggleFormBtn.innerHTML = '<i class="fas fa-plus me-1"></i> Add New Image';
    }
  });

  function toCreateMode(){
    galForm.reset();
    formMethod.value = 'POST';
    editUrlEl.value  = '';
    formTitle.textContent = 'Add Image';
    saveBtn.innerHTML = '<i class="fas fa-save me-2"></i> Save Image';
    btnCancelEdit.classList.add('d-none');
    galForm.querySelector('#imgInput').required = true;
  }
  btnCancelEdit.addEventListener('click', toCreateMode);

  const table = $('#galTable').DataTable({
    processing:true, serverSide:true, responsive:true,
    ajax: dtUrl,
    columns:[
      {data:'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false},
      {data:'preview', name:'preview', orderable:false, searchable:false},
      {data:'type', name:'type'},
      {data:'order', name:'order'},
      {data:'actions', name:'actions', orderable:false, searchable:false}
    ],
    order:[[3,'asc']]
  });

  // Create / Update
  galForm.addEventListener('submit', async function(e){
    e.preventDefault();
    const fd = new FormData(galForm);
    let url = storeUrl;
    let method = formMethod.value || 'POST';

    if (method === 'PUT'){
      url = editUrlEl.value;
      fd.append('_method', 'PUT');
      // image optional on update
      galForm.querySelector('#imgInput').required = false;
    }

    saveBtn.disabled = true;

    try{
      const res = await fetch(url, {
        method:'POST',
        headers:{
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: fd
      });

      const text = await res.text();
      let data; try { data = JSON.parse(text); } catch(_){}

      if (res.ok){
        Swal.fire({icon:'success', title:data?.message || 'Saved', timer:900, showConfirmButton:false});
        table.ajax.reload(null,false);
        toCreateMode();
        // Hide form
        formVisible = false;
        formContainer.classList.add('collapsed');
        tableContainer.classList.remove('collapsed');
        toggleFormBtn.innerHTML = '<i class="fas fa-plus me-1"></i> Add New Image';
      } else {
        const msg = (data && (data.message || (data.errors && Object.values(data.errors).flat().join('<br>')))) || 'Error';
        Swal.fire({icon:'error', title:'Validation error', html:msg});
      }
    }catch(err){
      console.error(err);
      Swal.fire({icon:'error', title:'Error', text:'Please try again.'});
    }finally{
      saveBtn.disabled = false;
    }
  });

  // Edit
  $(document).on('click','.btn-edit-img', function(){
    const payload = $(this).data('payload');
    galForm.reset();
    formMethod.value = 'PUT';
    editUrlEl.value  = $(this).data('url');
    formTitle.textContent = 'Edit Image';
    saveBtn.innerHTML = '<i class="fas fa-save me-2"></i> Update Image';
    btnCancelEdit.classList.remove('d-none');

    galForm.querySelector('[name="type"]').value  = payload.type || '';
    galForm.querySelector('[name="order"]').value = payload.order ?? 0;
    galForm.querySelector('#imgInput').required   = false;

    if (!formVisible){
      formVisible = true;
      formContainer.classList.remove('collapsed');
      tableContainer.classList.add('collapsed');
      toggleFormBtn.innerHTML = '<i class="fas fa-table me-1"></i> Show Gallery Table';
    }
    window.scrollTo({ top: galForm.offsetTop - 80, behavior:'smooth' });
  });

  // Delete
  $(document).on('click','.btn-delete-img', async function(){
    const url = $(this).data('url');
    const conf = await Swal.fire({icon:'warning', title:'Delete this image?', showCancelButton:true, confirmButtonText:'Yes, delete'});
    if (!conf.isConfirmed) return;

    try{
      const res = await fetch(url, {
        method:'POST',
        headers:{
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'X-Requested-With':'XMLHttpRequest',
          'Accept': 'application/json'
        },
        body: new URLSearchParams({_method:'DELETE'})
      });
      const data = await res.json().catch(()=>null);
      if (res.ok){
        Swal.fire({icon:'success', title:data?.message || 'Deleted', timer:900, showConfirmButton:false});
        table.ajax.reload(null,false);
      } else {
        Swal.fire({icon:'error', title:'Delete failed', text:data?.message || 'Please try again.'});
      }
    }catch(e){
      Swal.fire({icon:'error', title:'Error', text:'Please try again.'});
    }
  });

})();
</script>
@endsection
