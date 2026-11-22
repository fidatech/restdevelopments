@extends('admin.layouts.master')
@section('title', 'About Sections')

@section('vendor-style')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<style>
  :root{
    --pri: 120,75,155; --gold: 195,155,110; --ink: 30,30,45; --cream: 248,245,242;
    --ok: 37,160,110; --danger: 200,70,85;
    --r-card: 18px; --sh-sm: 0 6px 20px rgba(0,0,0,.06); --sh-md: 0 12px 30px rgba(0,0,0,.12);
  }
  body{ background: rgb(var(--cream)); }
  .page-card{ border:0; border-radius:var(--r-card); background:#fff; box-shadow:var(--sh-sm); overflow:hidden; }
  .page-card .card-header{ background:linear-gradient(135deg, rgba(var(--pri),.10), rgba(var(--gold),.08));
    border-bottom:1px dashed rgba(0,0,0,.08); padding:1.1rem 1.25rem; }
  .page-title{ font-weight:700; letter-spacing:.3px; color:rgb(var(--ink)); }
  .page-sub{ color:rgba(var(--ink),.65); font-size:.9rem; }

  .btn-premium{ border:0; border-radius:999px; padding:.6rem 1.2rem; font-weight:600; letter-spacing:.2px;
    background:linear-gradient(135deg, rgb(var(--pri)), rgba(var(--pri),.92)); color:#fff;
    box-shadow:0 10px 22px rgba(var(--pri), .25); transition:transform .15s, box-shadow .15s, filter .15s; }
  .btn-premium:hover{ transform:translateY(-2px); filter:brightness(1.02); box-shadow:0 14px 26px rgba(var(--pri), .3); }

  .pane{ border:1px solid rgba(0,0,0,.06); border-radius:16px; background:#fff; box-shadow:0 6px 16px rgba(0,0,0,.05); }
  .pane-head{ padding:.9rem 1rem; border-bottom:1px dashed rgba(0,0,0,.08);
    background:linear-gradient(135deg, rgba(var(--pri),.05), rgba(var(--gold),.04));
    border-radius:16px 16px 0 0; font-weight:700; color:rgb(var(--ink)); }
  .pane-body{ padding:1rem; }

  .upload-tile{ border:1px dashed rgba(0,0,0,.2); border-radius:14px; padding:1rem; background:linear-gradient(135deg,#fbf8f4,#f4efe9); }
  .preview{ margin-top:.6rem; border-radius:12px; overflow:hidden; background:#f6f6f8; border:1px solid rgba(0,0,0,.06);}
  .preview.ratio{ --bs-aspect-ratio: 56.25%; }
  .preview img{ width:100%; height:100%; object-fit:cover; }

  .icon-preview i{ font-size:1.2rem; }
  .btn-icon{ width:40px; height:40px; display:inline-flex; align-items:center; justify-content:center;
    border-radius:12px; border:1px solid rgba(0,0,0,.08); background:#fff; color:rgba(var(--ink),.8); transition:.18s; }
  .btn-icon:hover{ border-color:rgba(var(--pri),.45); color:rgb(var(--pri)); background:rgba(var(--pri),.06); }
  .btn-icon.delete:hover{ border-color:rgba(var(--danger),.4); color:rgb(var(--danger)); background:rgba(var(--danger),.06); }
</style>
@endsection

@section('content')
@php
  $locales = config('app.locales', ['en','fa','ps','uz']);
  $default = config('app.fallback_locale', 'en');
@endphp
<div class="container-fluid">
  <div class="card page-card mb-2">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div>
        <h2 class="page-title mb-0">About Sections</h2>
        <div class="page-sub">Manage Vision, Mission, Values, or custom blocks for the About page.</div>
      </div>
      <button class="btn btn-sm btn-outline-secondary rounded-pill d-none" id="btnCancelEdit">
        <i class="fas fa-rotate-left me-1"></i> Cancel Edit
      </button>
    </div>
  </div>

  <div class="row g-4">
    {{-- LEFT: FORM CARD --}}
    <div class="col-lg-5">
      <div class="card page-card">
        <div class="card-header">
          <h5 class="mb-0" id="formTitle">Create Section</h5>
        </div>
        <div class="card-body">
          <form id="sectionForm" enctype="multipart/form-data" novalidate>
            @csrf
            <input type="hidden" id="editId" value="">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Type</label>
                <select name="type" class="form-select form-control">
                  <option value="vision">Vision</option>
                  <option value="mission">Mission</option>
                  <option value="values">Values</option>
                  <option value="custom" selected>Custom</option>
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select form-control">
                  <option value="1">Active</option>
                  <option value="0">Inactive</option>
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label">Order</label>
                <input type="number" name="order" class="form-control" min="0" value="0">
              </div>

              <div class="col-md-6">
                <label class="form-label">Icon Set</label>
                <select name="icon_set" id="icon_set" class="form-select form-control" required>
                  <option value="feather">Feather</option>
                  <option value="bi">Bootstrap Icons</option>
                  <option value="image">Image</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Icon (name/class)</label>
                <input type="text" name="icon" id="icon" class="form-control" placeholder="fe-award or bi-building">
              </div>

              <div class="col-12 d-none" id="imageRow">
                <div class="upload-tile">
                  <div class="fw-semibold">Image <small>(526x370)</small></div>
                  <input type="file" name="image" id="image" class="form-control" accept="image/*">
                  <div class="preview ratio mt-2" id="imgPreview"></div>
                </div>
              </div>

              {{-- Locale tabs --}}
              <div class="col-12 pt-3">
                <ul class="nav nav-tabs" role="tablist">
                  @foreach($locales as $i=>$loc)
                  <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $i===0?'active':'' }}"
                            id="tab-{{ $loc }}-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#tab-{{ $loc }}"
                            type="button" role="tab"
                            aria-controls="tab-{{ $loc }}"
                            aria-selected="{{ $i===0?'true':'false' }}">
                      {{ strtoupper($loc) }}
                    </button>
                  </li>
                  @endforeach
                </ul>
                <div class="tab-content border rounded-bottom border-top-0 p-3">
                  @foreach($locales as $i=>$loc)
                  <div class="tab-pane fade {{ $i===0?'show active':'' }}" id="tab-{{ $loc }}" role="tabpanel" aria-labelledby="tab-{{ $loc }}-tab" tabindex="0">
                    <div class="mb-3">
                      <label class="form-label">Title @if($loc===$default)<span class="text-danger">*</span>@endif</label>
                      <input type="text" name="translations[{{ $loc }}][title]" class="form-control" @if($loc===$default) required @endif>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Body</label>
                      <textarea name="translations[{{ $loc }}][body]" class="form-control" rows="3"></textarea>
                    </div>
                  </div>
                  @endforeach
                </div>
              </div>

              <div class="col-12 pt-1">
                <button class="btn btn-premium w-100" id="btnSave">
                  <i class="fas fa-save me-2"></i><span id="saveText">Save Section</span>
                </button>
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
          <h5 class="mb-0">Sections List</h5>
          <small class="text-muted">Edit or delete entries. Order controls display sequence.</small>
        </div>
        <div class="card-body">
          <table class="table table-striped table-bordered w-100" id="dtAboutSections">
            <thead>
              <tr>
                <th>#</th>
                <th>Icon</th>
                <th>Title</th>
                <th>Type</th>
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
{{-- Feather icons for admin preview --}}
<script src="https://unpkg.com/feather-icons"></script>
<script>
(function(){
  const locales = @json($locales);
  const defaultLoc = @json($default);
  const form = document.getElementById('sectionForm');
  const editIdEl = document.getElementById('editId');
  const saveText = document.getElementById('saveText');
  const formTitle = document.getElementById('formTitle');
  const btnCancelEdit = document.getElementById('btnCancelEdit');
  const iconSet = document.getElementById('icon_set');
  const imageRow = document.getElementById('imageRow');
  const imgInput = document.getElementById('image');
  const imgPreview = document.getElementById('imgPreview');

  // base URL for assets (no trailing /)
  const ASSET_BASE = "{{ rtrim(asset(''), '/') }}";

  function toggleImageRow(){
    imageRow.classList.toggle('d-none', iconSet.value !== 'image');
  }
  iconSet.addEventListener('change', toggleImageRow); toggleImageRow();

  function preview(input, box){
    if(!input?.files?.[0] || !box) return;
    const url = URL.createObjectURL(input.files[0]);
    const img = box.querySelector('img') || (()=>{ const el=document.createElement('img'); box.innerHTML=''; box.appendChild(el); return el;})();
    img.src = url; img.onload = () => URL.revokeObjectURL(url);
  }
  imgInput?.addEventListener('change', ()=>preview(imgInput, imgPreview));

  function clearTranslations(){
    locales.forEach(loc=>{
      const tTitle = document.querySelector(`[name="translations[${loc}][title]"]`);
      const tBody  = document.querySelector(`[name="translations[${loc}][body]"]`);
      if (tTitle) tTitle.value = '';
      if (tBody)  tBody.value  = '';
    });
  }

  function toCreateMode(){
    editIdEl.value = '';
    formTitle.textContent = 'Create Section';
    saveText.textContent  = 'Save Section';
    btnCancelEdit.classList.add('d-none');
    form.reset();
    clearTranslations();
    toggleImageRow();
    imgPreview.innerHTML='';
  }
  btnCancelEdit.addEventListener('click', toCreateMode);

  // DataTable
  const dt = $('#dtAboutSections').DataTable({
    processing:true, serverSide:true, responsive:true,
    ajax: '{{ route('admin.about.sections.index') }}',
    columns: [
      { data:'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false },
      { data:'icon_preview', name:'icon_preview', orderable:false, searchable:false,
        render: function(data){ return `<div class="icon-preview">${data||''}</div>`; } },
      { data:'title', name:'title' },
      { data:'type', name:'type' },
      { data:'order', name:'order' },
      { data:'status', name:'status', orderable:false, searchable:false },
      { data:'actions', name:'actions', orderable:false, searchable:false }
    ],
    order: [[4,'asc']],
    drawCallback: function(){ if(window.feather){ feather.replace(); } }
  });

  // Create/Update
  form.addEventListener('submit', async function(e){
    e.preventDefault();
    const fd = new FormData(form);
    const id = editIdEl.value.trim();
    let url = '{{ route('admin.about.sections.store') }}';
    if (id){ fd.append('_method','PUT'); url = '{{ url('admin/about/sections') }}/' + id; }

    const btn = document.getElementById('btnSave');
    btn.disabled = true;

    try{
      const res = await fetch(url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'X-Requested-With':'XMLHttpRequest' },
        body: fd
      });
      const text = await res.text(); let data; try{ data = JSON.parse(text);}catch(_){ data=null; }
      if(res.ok){
        Swal.fire({icon:'success', title: data?.message || 'Saved', timer:900, showConfirmButton:false});
        dt.ajax.reload(null,false);
        toCreateMode();
      }else{
        const msg = (data && (data.message || (data.errors && Object.values(data.errors).flat().join('<br>')))) || 'Error';
        Swal.fire({icon:'error', title:'Validation error', html: msg});
      }
    }catch(err){
      console.error(err);
      Swal.fire({icon:'error', title:'Error', text:'Please try again.'});
    }finally{
      btn.disabled = false;
    }
  });

  // Edit
  document.addEventListener('click', async (e)=>{
    const el = e.target.closest('.btn-edit');
    if(!el) return;
    const id = el.dataset.id;

    try{
      const res = await fetch(`{{ url('admin/about/sections') }}/${id}/edit`, { headers: {'X-Requested-With':'XMLHttpRequest'} });
      const row = await res.json();

      editIdEl.value = row.id;
      formTitle.textContent = 'Edit Section';
      saveText.textContent  = 'Update Section';
      btnCancelEdit.classList.remove('d-none');

      document.querySelector('[name="type"]').value   = row.type || 'custom';
      document.querySelector('[name="status"]').value = row.status ? 1 : 0;
      document.querySelector('[name="order"]').value  = row.order ?? 0;

      document.getElementById('icon_set').value = row.icon_set || 'feather';
      document.getElementById('icon').value     = row.icon || '';
      toggleImageRow();

      // preview existing image if any (now stored as e.g. 'uploads/...', no 'storage/' prefix)
      imgPreview.innerHTML = '';
      if (row.icon_set === 'image' && row.image){
        const img = document.createElement('img');
        img.src = ASSET_BASE + '/' + row.image;
        imgPreview.appendChild(img);
      }

      // translations
      clearTranslations();
      (row.translations || []).forEach(t=>{
        const tTitle = document.querySelector(`[name="translations[${t.locale}][title]"]`);
        const tBody  = document.querySelector(`[name="translations[${t.locale}][body]"]`);
        if (tTitle) tTitle.value = t.title || '';
        if (tBody)  tBody.value  = t.body  || '';
      });

      window.scrollTo({ top: form.offsetTop - 80, behavior:'smooth' });
    }catch(err){
      console.error(err);
      Swal.fire({icon:'error', title:'Load failed', text:'Could not load the section.'});
    }
  });

  // Delete
  document.addEventListener('click', async (e)=>{
    const btn = e.target.closest('.btn-delete');
    if(!btn) return;
    e.preventDefault();
    const id = btn.dataset.id;

    const conf = await Swal.fire({icon:'warning', title:'Delete this section?', showCancelButton:true, confirmButtonText:'Yes, delete'});
    if(!conf.isConfirmed) return;

    try{
      const res = await fetch(`{{ url('admin/about/sections') }}/${id}`, {
        method:'POST',
        headers:{ 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'X-Requested-With':'XMLHttpRequest', 'Accept':'application/json' },
        body: new URLSearchParams({ _method:'DELETE' })
      });
      const data = await res.json().catch(()=>null);
      if(res.ok){
        Swal.fire({icon:'success', title: data?.message || 'Deleted', timer:900, showConfirmButton:false});
        dt.ajax.reload(null,false);
        if (editIdEl.value === String(id)) toCreateMode();
      }else{
        Swal.fire({icon:'error', title:'Delete failed', text: data?.message || 'Please try again.'});
      }
    }catch(err){
      console.error(err);
      Swal.fire({icon:'error', title:'Error', text:'Please try again.'});
    }
  });

})();
</script>
@endsection
