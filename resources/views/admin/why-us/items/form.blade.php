@extends('admin.layouts.master')
@section('title', $item->exists ? 'Edit Why Us Item' : 'Create Why Us Item')

@section('content')
<div class="container-fluid">
    <div class="card page-card">
        <div class="card-header">
            <h5 class="mb-0">{{ $item->exists ? 'Edit' : 'Create' }}</h5>
        </div>
        <div class="card-body">
            <form id="itemForm"
                  method="POST"
                  action="{{ $item->exists ? route('admin.items.update',$item) : route('admin.items.store') }}"
                  enctype="multipart/form-data">
                @csrf
                @if($item->exists) @method('PUT') @endif

                {{-- Uploads --}}
                <div class="mb-2">
                    <label class="form-label">Icon (upload)</label>
                    <input type="file" name="icon" class="form-control">
                    @if($item->icon)
                        <div class="mt-1"><img src="{{ asset('storage/'.$item->icon) }}" height="40"></div>
                    @endif
                </div>

                <div class="mb-2">
                    <label class="form-label">Image (upload)</label>
                    <input type="file" name="image" class="form-control">
                    @if($item->image)
                        <div class="mt-1"><img src="{{ asset('storage/'.$item->image) }}" height="40"></div>
                    @endif
                </div>

                <div class="mb-2">
                    <label class="form-label">Order</label>
                    <input type="number" name="order" value="{{ old('order',$item->order ?? 0) }}" class="form-control">
                </div>

                <hr>

                {{-- Locale Tabs --}}
                <ul class="nav nav-tabs" role="tablist">
                    @foreach($locales as $i=>$loc)
                        <li class="nav-item">
                            <button class="nav-link {{ $i===0?'active':'' }}" data-toggle="tab" data-target="#tab-{{ $loc }}" type="button">
                                {{ strtoupper($loc) }}
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content mt-3">
                    @foreach($locales as $i=>$loc)
                        <div class="tab-pane fade {{ $i===0?'show active':'' }}" id="tab-{{ $loc }}">
                            <div class="mb-2">
                                <label>Title ({{ strtoupper($loc) }})</label>
                                <input type="text" name="translations[{{ $loc }}][title]"
                                       value="{{ $item->t($loc)?->title }}" class="form-control">
                            </div>
                            <div class="mb-2">
                                <label>Description ({{ strtoupper($loc) }})</label>
                                <textarea name="translations[{{ $loc }}][description]"
                                          class="form-control" rows="4">{{ $item->t($loc)?->description }}</textarea>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button class="btn btn-primary mt-3">Save</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    $('#itemForm').on('submit', async function(e){
        e.preventDefault();

        const fd  = new FormData(this);
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
                timer:1200,
                showConfirmButton:false
            }).then(()=>{
                window.location = "{{ route('admin.items.index') }}";
            });
        } else {
            Swal.fire({icon:'error',title:'Error',text:'Please try again.'});
        }
    });
</script>
@endsection
