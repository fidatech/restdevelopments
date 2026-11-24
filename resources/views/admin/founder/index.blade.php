@extends('admin.layouts.master')
@section('title', 'Founder')

@section('vendor-style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="page-header card-soft is-inline">
    <div class="page-header-main">
        <span class="page-header-icon"><x-icon name="user-tie" /></span>
        <div class="page-header-text">
            <h1>Founder</h1>
            <p class="page-header-sub">The founder’s photo, message and biography shown on the “Our founder” page.</p>
        </div>
    </div>
    <div class="page-header-actions">
        <a class="btn btn-outline-secondary" href="{{ route('founder', ['locale' => 'en']) }}" target="_blank" rel="noopener"><x-icon name="eye" class="me-2" />View page</a>
    </div>
</div>
@php $default = config('app.fallback_locale', 'en'); @endphp
<div class="container-fluid">
  <div class="card page-card mb-4">
    <div class="card-body">
      <form id="founderForm" method="post" action="{{ route('admin.founder.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
          <div class="col-lg-8">
            <div class="pane">
              <div class="pane-head">Content (Translations)</div>
              <div class="pane-body">
                <ul class="nav nav-tabs" role="tablist">
                  @foreach($locales as $i => $loc)
                  <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $i === 0 ? 'active' : '' }}" id="f-tab-{{ $loc }}-tab" data-bs-toggle="tab" data-bs-target="#f-tab-{{ $loc }}" type="button" role="tab" aria-controls="f-tab-{{ $loc }}" aria-selected="{{ $i === 0 ? 'true' : 'false' }}">{{ strtoupper($loc) }}</button>
                  </li>
                  @endforeach
                </ul>
                <div class="tab-content border rounded-bottom border-top-0 p-3">
                  @foreach($locales as $i => $loc)
                  @php $tr = $setting?->translations->firstWhere('locale', $loc); @endphp
                  <div class="tab-pane fade {{ $i === 0 ? 'show active' : '' }}" id="f-tab-{{ $loc }}" role="tabpanel" aria-labelledby="f-tab-{{ $loc }}-tab">
                    <div class="row g-3">
                      <div class="col-md-6">
                        <label class="form-label">Name @if($loc === $default)<span class="text-danger">*</span>@endif</label>
                        <input type="text" name="translations[{{ $loc }}][name]" class="form-control" value="{{ old("translations.$loc.name", $tr->name ?? '') }}" @if($loc === $default) required @endif>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Position</label>
                        <input type="text" name="translations[{{ $loc }}][position]" class="form-control" placeholder="Founder & Chairman" value="{{ old("translations.$loc.position", $tr->position ?? '') }}">
                      </div>
                      <div class="col-12">
                        <label class="form-label">Highlighted quote <small class="text-muted">(one or two sentences, shown large)</small></label>
                        <textarea name="translations[{{ $loc }}][quote]" class="form-control" rows="2">{{ old("translations.$loc.quote", $tr->quote ?? '') }}</textarea>
                      </div>
                      <div class="col-12">
                        <label class="form-label">Message @if($loc === $default)<span class="text-danger">*</span>@endif</label>
                        <textarea name="translations[{{ $loc }}][message]" class="form-control summernote">{{ old("translations.$loc.message", $tr->message ?? '') }}</textarea>
                      </div>
                      <div class="col-12">
                        <label class="form-label">Biography</label>
                        <textarea name="translations[{{ $loc }}][biography]" class="form-control summernote">{{ old("translations.$loc.biography", $tr->biography ?? '') }}</textarea>
                      </div>
                    </div>
                  </div>
                  @endforeach
                </div>
                <p class="text-muted small mt-2 mb-0">Empty languages show the English text.</p>
              </div>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="pane mb-3">
              <div class="pane-head">Settings</div>
              <div class="pane-body">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                  <option value="1" @selected(!$setting || $setting->status)>Active (page is public)</option>
                  <option value="0" @selected($setting && !$setting->status)>Hidden</option>
                </select>
              </div>
            </div>

            <div class="pane mb-3">
              <div class="pane-head">Images</div>
              <div class="pane-body">
                <div class="mb-3">
                  <label class="form-label">Portrait <small class="text-muted">(cropped to 900×1125, portrait)</small></label>
                  <input type="file" name="image" class="form-control" accept="image/*">
                  <div class="preview ratio mt-2" style="--bs-aspect-ratio:125%">
                    @if($setting?->image)
                      <img src="{{ media_url($setting->image) }}" alt="">
                    @else
                      <span class="d-flex align-items-center justify-content-center text-muted small">No photo yet, the page shows initials</span>
                    @endif
                  </div>
                </div>
                <div>
                  <label class="form-label">Signature <small class="text-muted">(optional, transparent PNG)</small></label>
                  <input type="file" name="signature" class="form-control" accept="image/png,image/*">
                  @if($setting?->signature)
                    <div class="border rounded mt-2 p-2 bg-white"><img src="{{ media_url($setting->signature) }}" alt="" style="max-height:70px"></div>
                    <div class="form-check mt-2">
                      <input class="form-check-input" type="checkbox" name="remove_signature" value="1" id="removeSignature">
                      <label class="form-check-label" for="removeSignature">Remove signature</label>
                    </div>
                  @endif
                </div>
              </div>
            </div>

            <button type="submit" class="btn btn-primary w-100"><x-icon name="floppy-disk" class="me-2" />Save</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
<script>
  $('.summernote').summernote({
    height: 220,
    toolbar: [
      ['style', ['bold', 'italic', 'underline', 'clear']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['insert', ['link']],
      ['view', ['codeview']]
    ]
  });

  document.getElementById('founderForm')?.addEventListener('submit', async function (e) {
    e.preventDefault();
    const form = e.currentTarget;
    const btn = form.querySelector('button[type="submit"]');
    $('.summernote').each(function () { this.value = $(this).summernote('code'); });
    btn?.setAttribute('disabled', 'disabled');
    try {
      const res = await fetch(form.action, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
        body: new FormData(form)
      });
      const data = await res.json().catch(() => null);
      if (res.ok) {
        Swal.fire({ icon: 'success', title: data?.message || 'Saved', timer: 900, showConfirmButton: false });
        setTimeout(() => location.reload(), 900);
      } else {
        const msg = data?.message && !data?.errors ? data.message : (data?.errors && Object.values(data.errors).flat().join('<br>')) || 'Error';
        Swal.fire({ icon: 'error', title: 'Please check the form', html: msg });
      }
    } catch (err) {
      console.error(err);
      Swal.fire({ icon: 'error', title: 'Error', text: 'Please try again.' });
    } finally {
      btn?.removeAttribute('disabled');
    }
  });
</script>
@endsection
