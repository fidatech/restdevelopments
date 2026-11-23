@extends('admin.layouts.master')
@section('title','About Settings')

@section('vendor-style')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="page-header card-soft is-inline">
    <div class="page-header-main">
        <span class="page-header-icon"><x-icon name="circle-info" /></span>
        <div class="page-header-text">
            <h1>About Settings</h1>
            <p class="page-header-sub">Controls Home “About” section and the About page intro.</p>
        </div>
    </div>
</div>
@php $default = config('app.fallback_locale', 'en'); @endphp
<div class="container-fluid">
  <div class="card page-card mb-4">
    
    <div class="card-body">
      <form id="aboutSettingsForm" method="post" action="{{ route('admin.about.settings.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
          <div class="col-lg-8">
            <div class="pane">
              <div class="pane-head">Content (Translations)</div>
              <div class="pane-body">
                <ul class="nav nav-tabs" role="tablist">
                  @foreach($locales as $i=>$loc)
                  <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $i===0?'active':'' }}" id="tab-{{ $loc }}-tab" data-bs-toggle="tab" data-bs-target="#tab-{{ $loc }}" type="button" role="tab" aria-controls="tab-{{ $loc }}" aria-selected="{{ $i===0?'true':'false' }}">{{ strtoupper($loc) }}</button>
                  </li>
                  @endforeach
                </ul>
                <div class="tab-content border rounded-bottom border-top-0 p-3">
                  @foreach($locales as $i=>$loc)
                  @php $tr = $setting?->translations->firstWhere('locale',$loc); @endphp
                  <div class="tab-pane fade {{ $i===0?'show active':'' }}" id="tab-{{ $loc }}" role="tabpanel" aria-labelledby="tab-{{ $loc }}-tab">
                    <div class="row g-3">
                      <div class="col-md-6">
                        <label class="form-label">Shadow Title</label>
                        <input type="text" name="translations[{{ $loc }}][shadow_title]" class="form-control" value="{{ old("translations.$loc.shadow_title", $tr->shadow_title ?? 'About') }}">
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Title @if($loc===$default)<span class="text-danger">*</span>@endif</label>
                        <input type="text" name="translations[{{ $loc }}][title]" class="form-control" value="{{ old("translations.$loc.title", $tr->title ?? 'About Us') }}" @if($loc===$default) required @endif>
                      </div>
                      <div class="col-12">
                        <label class="form-label">Subtitle (short intro)</label>
                        <textarea name="translations[{{ $loc }}][subtitle]" class="form-control" rows="2">{{ old("translations.$loc.subtitle", $tr->subtitle ?? '') }}</textarea>
                      </div>
                      <div class="col-12">
                        <label class="form-label">About Text (long line under tag)</label>
                        <textarea name="translations[{{ $loc }}][about_text]" class="form-control" rows="2">{{ old("translations.$loc.about_text", $tr->about_text ?? '') }}</textarea>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Button Text</label>
                        <input type="text" name="translations[{{ $loc }}][button_text]" class="form-control" value="{{ old("translations.$loc.button_text", $tr->button_text ?? 'Learn More') }}">
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Circle Text</label>
                        <input type="text" name="translations[{{ $loc }}][circle_text]" class="form-control" value="{{ old("translations.$loc.circle_text", $tr->circle_text ?? '') }}">
                      </div>
                    </div>
                  </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="pane mb-3">
              <div class="pane-head">Settings</div>
              <div class="pane-body">
                <div class="mb-3">
                  <label class="form-label">Status</label>
                  <select name="status" class="form-select">
                    <option value="1" @selected($setting?->status)>Active</option>
                    <option value="0" @selected(isset($setting) && !$setting->status)>Inactive</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label">Button URL</label>
                  <input type="url" name="button_url" class="form-control" value="{{ old('button_url', $setting->button_url ?? url('admin/about/settings')) }}">
                </div>
              </div>
            </div>

            <div class="pane mb-3">
              <div class="pane-head">Images (Home)</div>
              <div class="pane-body">
                <div class="mb-3">
                  <label class="form-label">Left Image <small class="text-muted">(636×336)</small></label>
                  <input type="file" name="image_left" class="form-control" accept="image/*">
                  <div class="preview ratio mt-2" style="--bs-aspect-ratio:56.25%">
                    @if($setting?->image_left)
                      <img src="{{ media_url($setting->image_left) }}" alt="">
                    @endif
                  </div>
                </div>
                <div class="mb-3">
                  <label class="form-label">Right Image <small class="text-muted">(636×336)</small></label>
                  <input type="file" name="image_right" class="form-control" accept="image/*">
                  <div class="preview ratio mt-2" style="--bs-aspect-ratio:56.25%">
                    @if($setting?->image_right)
                      <img src="{{ media_url($setting->image_right) }}" alt="">
                    @endif
                  </div>
                </div>
                <div class="mb-3">
                  <label class="form-label">Tag Image (Round) <small class="text-muted">(201×200)</small></label>
                  <input type="file" name="tag_image" class="form-control" accept="image/*">
                  <div class="preview ratio mt-2" style="--bs-aspect-ratio:100%">
                    @if($setting?->tag_image)
                      <img src="{{ media_url($setting->tag_image) }}" alt="">
                    @endif
                  </div>
                </div>
              </div>
            </div>

            <button class="btn btn-primary w-100"><x-icon name="floppy-disk" class="me-2" />Save</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.getElementById('aboutSettingsForm')?.addEventListener('submit', async function(e){
    e.preventDefault();
    const form = e.currentTarget;
    const btn = form.querySelector('button[type="submit"], .btn-premium');
    const fd = new FormData(form);
    btn?.setAttribute('disabled','disabled');
    try{
      const res = await fetch(form.action, { method:'POST', headers:{
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'X-Requested-With': 'XMLHttpRequest'
      }, body: fd });
      const data = await res.json().catch(()=>null);
      if(res.ok){
        Swal.fire({icon:'success', title: data?.message || 'Saved', timer: 900, showConfirmButton:false});
        location.reload();
      }else{
        const msg = data?.message || (data?.errors && Object.values(data.errors).flat().join('<br>')) || 'Error';
        Swal.fire({icon:'error', title:'Validation error', html: msg});
      }
    }catch(err){
      console.error(err);
      Swal.fire({icon:'error', title:'Error', text:'Please try again.'});
    }finally{
      btn?.removeAttribute('disabled');
    }
  });
</script>
@endsection
