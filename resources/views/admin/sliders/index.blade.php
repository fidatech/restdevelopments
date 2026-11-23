@extends('admin.layouts.master')
@section('title', 'Sliders')

@section('vendor-style')
@endsection

@section('content')
<div class="page-header card-soft is-inline">
    <div class="page-header-main">
        <span class="page-header-icon"><x-icon name="images" /></span>
        <div class="page-header-text">
            <h1>Sliders</h1>
            <p class="page-header-sub">Craft premium, high-impact sliders for your website.</p>
        </div>
    </div>
    <div class="page-header-actions"><a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">
        <x-icon name="plus" class="me-2" /> Create Slider
      </a></div>
</div>
<div class="container-fluid">
  <div class="card page-card">
    

    <div class="card-body p-4">
      @if($sliders->count())
        <div class="row g-4">
          @foreach($sliders as $s)
            <div class="col-xl-3 col-lg-4 col-md-6" id="slider-card-{{ $s->id }}">
              <div class="slider-card h-100">
                <div class="slider-media ratio ratio-16x9">
                  @if($s->image)
                    <img src="{{ media_url($s->image) }}" alt="Slider image">
                  @endif

                  <span class="ribbon {{ $s->status === 'published' ? '' : 'draft' }}">
                    {{ strtoupper($s->status) }}
                  </span>

                  <span class="chip"><x-icon name="sort" /> {{ $s->sort }}</span>

                  @if($s->video_url)
                    <span class="video-pill"><x-icon name="circle-play" /> Video</span>
                  @endif
                </div>

                <div class="slider-body">
                  <div class="slider-title" title="{{ $s->translation()?->headline ?? '—' }}">
                    {{ $s->translation()?->headline ?? 'Untitled Slider' }}
                  </div>
                  <div class="slider-sub" title="{{ $s->translation()?->subheadline ?? '—' }}">
                    {{ $s->translation()?->subheadline ?? 'No description provided.' }}
                  </div>

                  <div class="slider-footer">
                    <span class="slider-date">{{ $s->created_at->format('M d, Y') }}</span>
                    <div class="d-flex align-items-center gap-2">
                      @if ($s->translation()?->button_text && $s->translation()?->button_url)
                        <a href="{{ $s->translation()->button_url }}" target="_blank" class="btn btn-sm btn-outline-primary px-3">
                          <x-icon name="link" class="me-1" />{{ \Illuminate\Support\Str::limit($s->translation()->button_text, 12) }}
                        </a>
                      @endif
                      <a href="{{ route('admin.sliders.edit', $s) }}" class="btn-icon" title="Edit">
                        <x-icon name="pen" />
                      </a>
                      <button class="btn-icon delete btn-delete" data-url="{{ route('admin.sliders.destroy', $s) }}" title="Delete">
                        <x-icon name="trash" />
                      </button>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          @endforeach
        </div>

        <div class="mt-4">
          {{ $sliders->withQueryString()->links() }}
        </div>
      @else
        <div class="empty">
          <div class="mb-2">
            <i class="fas fa-images fa-3x" style="color: rgba(var(--gold),.7)"></i>
          </div>
          <h4 class="mb-2" style="font-weight:700;color:rgb(var(--ink))">No Sliders Yet</h4>
          <p class="mb-3" style="color:rgba(var(--ink),.65)">Add your first premium slider to showcase products, projects, or campaigns.</p>
          <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary px-4">
            <x-icon name="plus" class="me-2" /> Create First Slider
          </a>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
function parseJsonSafe(raw){ try{ return JSON.parse(raw) }catch(e){ return null } }
async function confirmDelete(){
  return Swal.fire({
    icon: 'warning',
    title: 'Delete this slider?',
    text: 'This action cannot be undone.',
    showCancelButton: true,
    confirmButtonText: 'Yes, delete it',
  });
}
async function showModal(icon, title, text, timer=1200){
  return Swal.fire({ icon, title, html: text||'', timer, showConfirmButton:false, allowOutsideClick:false, allowEscapeKey:false });
}

document.addEventListener('click', async (e) => {
  const btn = e.target.closest('.btn-delete');
  if (!btn) return;
  e.preventDefault();

  const { isConfirmed } = await confirmDelete();
  if (!isConfirmed) return;

  try {
    const res = await fetch(btn.dataset.url, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      },
      body: new URLSearchParams({ _method: 'DELETE' })
    });

    const raw  = await res.text();
    const data = parseJsonSafe(raw);

    if (!res.ok || !data?.ok) {
      const msg = (data && (data.message || (data.errors && Object.values(data.errors).flat().join('<br>')))) || 'Delete failed';
      await showModal('error', 'Delete failed', msg, 1600);
      return;
    }

    await showModal('success', 'Deleted', data.message || 'Slider removed', 900);
    window.location.reload(); // keep pagination & counts correct
  } catch (err) {
    console.error(err);
    await showModal('error', 'Delete failed', 'Please try again.', 1600);
  }
});
</script>
@endsection
