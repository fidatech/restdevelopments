@extends('admin.layouts.master')
@section('title','Client Logos')
@section('page-style')
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    @endsection
@section('content')
<div class="page-header card-soft is-inline">
    <div class="page-header-main">
        <span class="page-header-icon"><x-icon name="handshake" /></span>
        <div class="page-header-text">
            <h1>Client Logos</h1>
        </div>
    </div>
    <div class="page-header-actions"><button id="btnAdd" class="btn btn-primary btn-sm">Add New</button></div>
</div>
<div class="container-fluid">
    <div class="card page-card">
        
        <div class="card-body table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Order</th>
                    <th>Image</th>
                    <th>Link</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($clients as $client)
                    <tr data-id="{{ $client->id }}">
                        <td>{{ $client->order }}</td>
                        <td><img src="{{ media_url($client->image) }}" height="50"></td>
                        <td>{{ $client->link }}</td>
                        <td>
                            <button type="button" class="btn-act is-amber btn-edit" data-id="{{ $client->id }}" title="Edit" aria-label="Edit"><x-icon name="pen" /></button>
                            <button type="button" class="btn-act is-red btn-delete" data-id="{{ $client->id }}" title="Delete" aria-label="Delete"><x-icon name="trash" /></button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="clientModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="clientForm" enctype="multipart/form-data">
        @csrf <input type="hidden" name="_method" value="POST" id="formMethod">
        <div class="modal-header"><h5 class="modal-title" id="modalTitle">Create</h5></div>
        <div class="modal-body">
            <div class="mb-2">
              <label>Image (200x200)</label>
              <input type="file" name="image" class="form-control">
              <div id="preview" class="mt-1"></div>
            </div>
            <div class="mb-2">
              <label>Link (optional)</label>
              <input type="url" name="link" class="form-control">
            </div>
            <div class="mb-2">
              <label>Order</label>
              <input type="number" name="order" class="form-control" value="0">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Cancel</button>
            <button class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection


@section('page-script')
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>
<script>
let currentId = null;
const modal   = new bootstrap.Modal(document.getElementById('clientModal'));

// Create
$('#btnAdd').on('click',()=>{
  currentId = null;
  $('#modalTitle').text('Create');
  $('#formMethod').val('POST');
  $('#clientForm')[0].reset();
  $('#preview').html('');
  modal.show();
});

// Edit
$(document).on('click','.btn-edit',async function(){
  currentId = $(this).data('id');
  $('#modalTitle').text('Edit');
  $('#formMethod').val('PUT');
  $('#clientForm')[0].reset();
  $('#preview').html('');

  const res  = await fetch("{{ url('/admin/clients') }}/"+currentId+"/edit");
  const data = await res.json();
  $('[name="link"]').val(data.link);
  $('[name="order"]').val(data.order);
  $('#preview').html(`<img src="${mediaUrl(data.image)}" height="50">`);
  modal.show();
});

// Save (create/update)
$('#clientForm').on('submit',async function(e){
  e.preventDefault();
  let url  = "{{ url('/admin/clients') }}";
  if(currentId) url = url+'/'+currentId;

  const fd = new FormData(this);
  fd.append('_method',$('#formMethod').val());

  const res = await fetch(url,{
     method:'POST',
     headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'},
     body:fd
  });

  if(res.ok){
     Swal.fire({icon:'success',title:'Saved',timer:900,showConfirmButton:false})
         .then(()=>location.reload());
  }else{
     Swal.fire({icon:'error',title:'Error'});
  }
});

// Delete
$(document).on('click','.btn-delete',async function(){
  if(!(await Swal.fire({icon:'warning',title:'Delete?',showCancelButton:true})).isConfirmed) return;
  const id = $(this).data('id');
  await fetch("{{ url('/admin/clients') }}/"+id,{
      method:'POST',
      headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'},
      body:new URLSearchParams({_method:'DELETE'})
  });
  Swal.fire({icon:'success',title:'Deleted',timer:900,showConfirmButton:false})
      .then(()=>location.reload());
});
</script>
@endsection
