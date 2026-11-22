@extends('admin.layouts.master')
@section('title', 'Sliders')

@section('vendor-style')
<style>
  :root{
    --pri: 120,75,155;        /* deep purple */
    --gold: 195,155,110;      /* soft gold */
    --ink: 30,30,45;          /* dark slate */
    --cream: 248,245,242;     /* page bg */
    --ok: 37,160,110;         /* premium green */
    --danger: 200,70,85;      /* rose red */

    --r-card: 18px;
    --r-media: 14px;
    --sh-sm: 0 6px 20px rgba(0,0,0,.06);
    --sh-md: 0 12px 30px rgba(0,0,0,.12);
  }

  body{ background: rgb(var(--cream)); }

  .page-card{
    border: 0; border-radius: var(--r-card);
    background: #fff; box-shadow: var(--sh-sm); overflow: hidden;
  }
  .page-card .card-header{
    background: linear-gradient(135deg, rgba(var(--pri),.10), rgba(var(--gold),.08));
    border-bottom: 1px dashed rgba(0,0,0,.08);
    padding: 1.25rem 1.5rem;
  }
  .page-title{
    font-weight: 700; letter-spacing:.3px; color: rgb(var(--ink));
  }
  .page-sub{ color: rgba(var(--ink),.65); font-size:.9rem; }

  /* primary CTA */
  .btn-premium{
    border:0; border-radius: 999px; padding:.6rem 1.2rem; font-weight:600; letter-spacing:.2px;
    background: linear-gradient(135deg, rgb(var(--pri)), rgba(var(--pri),.92));
    color:#fff; box-shadow: 0 10px 22px rgba(var(--pri), .25);
    transition: transform .15s ease, box-shadow .15s ease, filter .15s ease;
  }
  .btn-premium:hover{ transform: translateY(-2px); filter: brightness(1.02); box-shadow: 0 14px 26px rgba(var(--pri), .3); }
  .btn-premium i{ opacity:.95; }

  /* slider tile */
  .slider-card{
    border:1px solid rgba(0,0,0,.06); border-radius: 16px; overflow: hidden; background:#fff;
    transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease; height:100%;
  }
  .slider-card:hover{ transform: translateY(-3px); box-shadow: var(--sh-md); border-color: rgba(var(--pri),.25); }
  .slider-media{ background: linear-gradient(135deg, #f5f0eb, #ebe4db); border-radius: 14px; overflow:hidden; position:relative; }
  .slider-media img{ width:100%; height:100%; object-fit:cover; transition: transform .45s ease; }
  .slider-card:hover .slider-media img{ transform: scale(1.045); }

  /* ribbons / chips */
  .ribbon{
    position:absolute; top:10px; left:-32px; width:140px; text-align:center; padding:.28rem 0; color:#fff;
    font-size:.72rem; font-weight:700; letter-spacing:.4px; transform: rotate(-45deg);
    background: rgb(var(--ok)); box-shadow: 0 6px 14px rgba(0,0,0,.15);
  }
  .ribbon.draft{ background: rgb(var(--gold)); color:#1f1a12; }
  .chip{
    position:absolute; top:10px; right:10px; padding:.28rem .6rem; border-radius:999px; font-size:.75rem; font-weight:600;
    background: rgba(255,255,255,.85); border:1px solid rgba(0,0,0,.06); backdrop-filter: blur(4px);
    display:flex; align-items:center; gap:.35rem;
  }
  .video-pill{
    position:absolute; left:10px; bottom:10px; padding:.28rem .55rem; border-radius:8px; font-size:.72rem; color:#fff;
    background: rgba(0,0,0,.55); display:flex; align-items:center; gap:.35rem;
  }

  .slider-body{ padding:1rem; display:flex; flex-direction:column; }
  .slider-title{
    font-weight:700; color: rgb(var(--ink)); line-height:1.35; margin:.25rem 0 .35rem;
    display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
  }
  .slider-sub{
    color: rgba(var(--ink),.7); font-size:.9rem; line-height:1.5; margin-bottom:1rem;
    display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
  }
  .slider-footer{ margin-top:auto; display:flex; align-items:center; justify-content:space-between; }
  .slider-date{ font-size:.8rem; color: rgba(var(--ink),.55); }

  /* icon buttons */
  .btn-icon{
    width:40px; height:40px; display:inline-flex; align-items:center; justify-content:center;
    border-radius:12px; border:1px solid rgba(0,0,0,.08); background:#fff; color: rgba(var(--ink),.8);
    transition: all .18s ease;
  }
  .btn-icon:hover{ border-color: rgba(var(--pri),.45); color: rgb(var(--pri)); background: rgba(var(--pri),.06); }
  .btn-icon.delete:hover{ border-color: rgba(var(--danger),.4); color: rgb(var(--danger)); background: rgba(var(--danger),.06); }

  /* empty state */
  .empty{
    padding:4rem 2rem; text-align:center; background: linear-gradient(135deg, #fcfaf8, #f6f2ed);
    border-radius: 16px; border:1px dashed rgba(var(--gold),.35);
  }
</style>
@endsection

@section('content')
<div class="container-fluid">
  <div class="card page-card">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
      <div>
        <h2 class="page-title mb-0">Slider Management</h2>
        <div class="page-sub">Craft premium, high-impact sliders for your website.</div>
      </div>
      <a href="{{ route('admin.sliders.create') }}" class="btn btn-premium">
        <i class="fas fa-plus me-2"></i> Create Slider
      </a>
    </div>

    <div class="card-body p-4">
      @if($sliders->count())
        <div class="row g-4">
          @foreach($sliders as $s)
            <div class="col-xl-3 col-lg-4 col-md-6" id="slider-card-{{ $s->id }}">
              <div class="slider-card h-100">
                <div class="slider-media ratio ratio-16x9">
                  @if($s->image)
                    <img src="{{ asset('storage/'.$s->image) }}" alt="Slider image">
                  @endif

                  <span class="ribbon {{ $s->status === 'published' ? '' : 'draft' }}">
                    {{ strtoupper($s->status) }}
                  </span>

                  <span class="chip"><i class="fas fa-sort"></i> {{ $s->sort }}</span>

                  @if($s->video_url)
                    <span class="video-pill"><i class="fas fa-play-circle"></i> Video</span>
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
                        <a href="{{ $s->translation()->button_url }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                          <i class="ft ft-link me-1"></i>{{ \Illuminate\Support\Str::limit($s->translation()->button_text, 12) }}
                        </a>
                      @endif
                      <a href="{{ route('admin.sliders.edit', $s) }}" class="btn-icon" title="Edit">
                        <i class="ft ft-edit"></i>
                      </a>
                      <button class="btn-icon delete btn-delete" data-url="{{ route('admin.sliders.destroy', $s) }}" title="Delete">
                        <i class="ft ft-trash"></i>
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
          <a href="{{ route('admin.sliders.create') }}" class="btn btn-premium px-4">
            <i class="fas fa-plus me-2"></i> Create First Slider
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
