{{-- resources/views/admin/amenities/index.blade.php --}}
@extends('admin.layouts.master')
@section('title', 'Amenities')

@section('vendor-style')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('content')
@php $locales = config('app.locales',['en', 'fa', 'ps', 'uz']); @endphp
<div class="container-fluid">
  <div class="page-header card-soft is-inline">
    <div class="page-header-main">
        <span class="page-header-icon"><x-icon name="person-swimming" /></span>
        <div class="page-header-text">
            <h1>Amenities</h1>
            <p class="page-header-sub">Manage global amenities (image 196×240)</p>
        </div>
    </div>
    <div class="page-header-actions"><button class="btn btn-primary" id="toggleFormBtn">
        <x-icon name="plus" class="me-1" /> Add New Amenity
      </button></div>
</div>

  {{-- Form --}}
  <div class="form-container collapsed" id="formContainer">
    <div class="card page-card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 id="formTitle" class="mb-0">Add Amenity</h5>
        <button class="btn btn-sm btn-light-secondary d-none" id="btnCancelEdit">
          <x-icon name="rotate-left" class="me-1" /> Cancel Edit
        </button>
      </div>
      <div class="card-body">
        <form id="amenityForm" enctype="multipart/form-data" novalidate>
          @csrf
          <input type="hidden" id="formMethod" value="POST">
          <input type="hidden" id="editUrl">

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Image (196x240)</label>
              <div class="dropzone" data-preview-target="preview-image">
                <x-icon name="cloud-arrow-up" class="mb-2" />
                <p class="mb-0"><strong>Drag & drop</strong> or click to upload</p>
                <small class="text-muted d-block">196×240 (webp/jpg/png)</small>
                <input type="file" name="image" accept="image/*" hidden>
              </div>
              <div class="preview mt-2" id="preview-image" style="display:none;"><img /></div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Icon (SVG or PNG)</label>
              <div class="dropzone" data-preview-target="preview-icon">
                <x-icon name="cloud-arrow-up" class="mb-2" />
                <p class="mb-0"><strong>Drag & drop</strong> or click to upload</p>
                <small class="text-muted d-block">SVG or PNG</small>
                <input type="file" name="icon" accept=".svg,.png,.jpg,.jpeg" hidden>
              </div>
              <div class="preview mt-2" id="preview-icon" style="display:none;"><img /></div>
            </div>

            <div class="col-md-3">
              <label class="form-label">Order</label>
              <input class="form-control" name="order" type="number" value="0">
            </div>
          </div>

          <hr>

          <ul class="nav nav-tabs">
            @foreach($locales as $i=>$loc)
              <li class="nav-item">
                <button class="nav-link @if($i===0) active @endif" data-bs-toggle="tab" data-bs-target="#tab-{{ $loc }}" type="button">{{ strtoupper($loc) }}</button>
              </li>
            @endforeach
          </ul>

          <div class="tab-content pt-3">
            @foreach($locales as $i=>$loc)
              <div class="tab-pane fade @if($i===0) show active @endif" id="tab-{{ $loc }}">
                <div class="mb-3">
                  <label>Title ({{ strtoupper($loc) }})</label>
                  <input class="form-control" type="text" name="title[{{ $loc }}]">
                </div>
              </div>
            @endforeach
          </div>

          <button id="saveBtn" class="btn btn-primary mt-2"><x-icon name="floppy-disk" class="me-2" /> Save Amenity</button>
        </form>
      </div>
    </div>
  </div>

  {{-- Table --}}
  <div id="tableContainer" class="table-container">
    <div class="card page-card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Amenity List</h5>
        <small class="text-muted">Edit / delete. Order controls display sequence.</small>
      </div>
      <div class="card-body">
        <table id="amenitiesTable" class="table table-striped table-hover w-100">
          <thead>
            <tr>
              <th>#</th><th>Title</th><th>Image</th><th>Icon</th><th>Order</th><th style="width:110px">Actions</th>
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
  const dtUrl    = "{{ route('admin.amenities.datatable') }}";
  const storeUrl = "{{ route('admin.amenities.store') }}";

  const formContainer = document.getElementById('formContainer');
  const tableContainer= document.getElementById('tableContainer');
  const toggleFormBtn = document.getElementById('toggleFormBtn');
  const amenityForm   = document.getElementById('amenityForm');
  const formMethod    = document.getElementById('formMethod');
  const editUrlEl     = document.getElementById('editUrl');
  const saveBtn       = document.getElementById('saveBtn');
  const btnCancelEdit = document.getElementById('btnCancelEdit');

  // preview / dragdrop
  document.querySelectorAll('.dropzone').forEach(function(zone){
    const input = zone.querySelector('input');
    const prev  = document.getElementById(zone.getAttribute('data-preview-target'));
    zone.onclick = () => input.click();
    zone.ondragover = (e)=>{ e.preventDefault(); zone.classList.add('dragover') };
    zone.ondragleave= ()=> zone.classList.remove('dragover');
    zone.ondrop = (e)=>{ e.preventDefault(); input.files=e.dataTransfer.files; trigger(); zone.classList.remove('dragover') };
    input.onchange = trigger;
    function trigger(){
      if(input.files && input.files[0]){
        const r=new FileReader();
        r.onload=(e)=>{ prev.querySelector('img').src=e.target.result; prev.style.display='block'; };
        r.readAsDataURL(input.files[0]);
      }
    }
  });

  // Toggle form
  let formVisible=false;
  toggleFormBtn.addEventListener('click',()=>{
    formVisible=!formVisible;
    if(formVisible){
      formContainer.classList.remove('collapsed');
      tableContainer.classList.add('collapsed');
      toggleFormBtn.innerHTML='<i class="fas fa-table me-1"></i> Show Table';
    }else{
      formContainer.classList.add('collapsed');
      tableContainer.classList.remove('collapsed');
      toggleFormBtn.innerHTML='<i class="fas fa-plus me-1"></i> Add New Amenity';
    }
  });
  btnCancelEdit.addEventListener('click',toCreateMode);
  function toCreateMode(){
    amenityForm.reset(); Array.from(document.querySelectorAll('.preview')).forEach(p=>p.style.display='none');
    formMethod.value='POST'; editUrlEl.value=''; btnCancelEdit.classList.add('d-none');
    saveBtn.innerHTML='<i class="fas fa-save me-2"></i> Save Amenity';
  }

  // DataTable
  const table = $('#amenitiesTable').DataTable({
    processing:true,serverSide:true,responsive:true,
    ajax: dtUrl,
    columns:[
      {data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false},
      {data:'title',     name:'title'},
      {data:'image',     name:'image',orderable:false,searchable:false},
      {data:'icon',      name:'icon',orderable:false,searchable:false},
      {data:'order',     name:'order'},
      {data:'actions',   name:'actions',orderable:false,searchable:false}
    ],
    order:[[4,'asc']]
  });

  // Submit
  amenityForm.addEventListener('submit',async function(e){
    e.preventDefault();
    const fd=new FormData(amenityForm);
    let url=storeUrl; if(formMethod.value==='PUT'){ url=editUrlEl.value; fd.append('_method','PUT'); }
    saveBtn.disabled=true;
    try{
      const res = await fetch(url,{ method:'POST', headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}, body:fd });
      const txt = await res.text(); let data; try{ data=JSON.parse(txt);}catch(_){}
      if(res.ok){
        Swal.fire({icon:'success',title:data?.message||'Saved',timer:900,showConfirmButton:false});
        table.ajax.reload(null,false); toCreateMode();
        formVisible=false; formContainer.classList.add('collapsed'); tableContainer.classList.remove('collapsed');
        toggleFormBtn.innerHTML='<i class="fas fa-plus me-1"></i> Add New Amenity';
      }else{
        const msg=(data && (data.message||Object.values(data.errors||{}).flat().join('<br>'))) || 'Error';
        Swal.fire({icon:'error',title:'Validation error',html:msg});
      }
    }catch(err){ Swal.fire({icon:'error',title:'Error',text:'Please try again.'}); }
    finally{ saveBtn.disabled=false; }
  });

  // Edit
 $(document).on('click','.btn-edit-amenity', function(){
  const p = $(this).data('payload');
  amenityForm.reset();
  Array.from(document.querySelectorAll('.preview')).forEach(c => c.style.display='none');

  formMethod.value = 'PUT';
  editUrlEl.value  = $(this).data('url');
  btnCancelEdit.classList.remove('d-none');
  saveBtn.innerHTML = '<i class="fas fa-save me-2"></i> Update Amenity';

  // fill translated titles
  for (const loc in p.translations) {
    const field = amenityForm.querySelector(`[name="title[${loc}]"]`);
    if (field) {
      field.value = p.translations[loc] ?? '';
    }
  }

  amenityForm.querySelector('[name="order"]').value = p.order || 0;

  if(!formVisible) toggleFormBtn.click();
  window.scrollTo({ top: amenityForm.offsetTop - 80, behavior: 'smooth' });
});



  // Delete
  $(document).on('click','.btn-delete-amenity', async function(){
    const url=$(this).data('url');
    const ok = await Swal.fire({icon:'warning',title:'Delete?',showCancelButton:true,confirmButtonText:'Yes'});
    if(!ok.isConfirmed) return;
    const res = await fetch(url,{method:'POST',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},body:new URLSearchParams({_method:'DELETE'})});
    if(res.ok){ Swal.fire({icon:'success',title:'Deleted',timer:900,showConfirmButton:false}); table.ajax.reload(null,false);}
    else{ Swal.fire({icon:'error',title:'Delete failed'}); }
  });

})();
</script>
@endsection
