@extends('admin.layouts.master')
@section('title', $client->exists ? 'Edit Client Logo' : 'Create Client Logo')

@section('content')
<div class="page-header card-soft is-inline">
    <div class="page-header-main">
        <span class="page-header-icon"><x-icon name="handshake" /></span>
        <div class="page-header-text">
            <h1>{{ $client->exists ? 'Edit' : 'Create' }} Client Logo</h1>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="card page-card">
        
        <div class="card-body">
            <form id="clientForm"
                action="{{ $client->exists ? route('admin.clients.update',$client) : route('admin.clients.store') }}"
                method="POST"
                enctype="multipart/form-data">
                @csrf
                @if($client->exists)
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label class="form-label">Image (200×200)</label>
                    <input type="file" name="image" class="form-control" {{ $client->exists ? '' : 'required' }}>
                    @if($client->image)
                        <img src="{{ asset('storage/'.$client->image) }}" height="70" class="mt-1">
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Link (optional)</label>
                    <input type="url" name="link" value="{{ old('link',$client->link) }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Order</label>
                    <input type="number" name="order" value="{{ old('order',$client->order ?? 0) }}" class="form-control">
                </div>

                <button class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
$('#clientForm').on('submit', async function(e){
    e.preventDefault();
    const fd = new FormData(this);
    const url = this.action;

    const res = await fetch(url,{
        method:'POST',
        headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body:fd
    });

    if(res.ok){
        Swal.fire({
            icon:'success',
            title:'Saved successfully',
            timer:1000,
            showConfirmButton:false
        }).then(()=>{
            window.location = "{{ route('admin.clients.index') }}";
        });
    }else{
        Swal.fire({icon:'error',title:'Error',text:'Please try again.'});
    }
});
</script>
@endsection
