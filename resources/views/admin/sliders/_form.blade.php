<style>
  :root{
    --pri: 120,75,155;        /* deep purple */
    --gold: 195,155,110;      /* soft gold */
    --ink: 30,30,45;          /* dark slate */
    --ok: 37,160,110;         /* premium green */
    --danger: 200,70,85;      /* rose red */
  }
  .form-wrap{
    border:0; border-radius:18px; background:#fff;
    box-shadow:0 6px 20px rgba(0,0,0,.06); overflow:hidden;
  }
  .form-head{
    background: linear-gradient(135deg, rgba(var(--pri),.10), rgba(var(--gold),.08));
    border-bottom:1px dashed rgba(0,0,0,.08);
    padding:1rem 1.25rem; margin-bottom:1rem;
  }
  .form-title{ margin:0; font-weight:700; color:rgb(var(--ink)); }
  .pane{
    border:1px solid rgba(0,0,0,.06); border-radius:16px; background:#fff;
    box-shadow:0 6px 16px rgba(0,0,0,.05);
  }
  .pane-head{
    padding:.9rem 1rem; border-bottom:1px dashed rgba(0,0,0,.08);
    background: linear-gradient(135deg, rgba(var(--pri),.05), rgba(var(--gold),.04));
    border-top-left-radius:16px; border-top-right-radius:16px;
    font-weight:700; color:rgb(var(--ink));
  }
  .pane-body{ padding:1rem; }

  /* status toggle */
  .status-toggle{ display:flex; gap:.5rem; }
  .status-toggle input{ display:none; }
  .status-pill{
    padding:.45rem .9rem; border-radius:999px; cursor:pointer; user-select:none;
    border:1px solid rgba(0,0,0,.08); background:#fff; color:rgba(var(--ink),.8); font-weight:600;
    transition:all .15s ease;
  }
  .status-toggle input:checked + .status-pill.published{
    background: rgba(var(--ok),.12); color: rgb(var(--ok)); border-color: rgba(var(--ok),.35);
  }
  .status-toggle input:checked + .status-pill.draft{
    background: rgba(var(--gold),.20); color: rgb(var(--ink)); border-color: rgba(var(--gold),.45);
  }

  /* upload tiles */
  .upload-tile{
    border:1px dashed rgba(0,0,0,.2); border-radius:14px; padding:1rem;
    background: linear-gradient(135deg, #fbf8f4, #f4efe9);
  }
  .upload-label{ font-weight:700; color:rgb(var(--ink)); margin-bottom:.35rem; }
  .upload-help{ color:rgba(var(--ink),.6); font-size:.85rem; }
  .upload-input{ display:block; margin-top:.6rem; }
  .preview{
    margin-top:.75rem; border-radius:12px; overflow:hidden; background:#f6f6f8;
    border:1px solid rgba(0,0,0,.06);
  }
  .preview.ratio{ --bs-aspect-ratio: 56.25%; } /* 16:9 */
  .preview img{ width:100%; height:100%; object-fit:cover; }

  /* save button */
  .btn-premium{
    border:0; border-radius:999px; padding:.7rem 1.25rem; font-weight:700; letter-spacing:.2px;
    background: linear-gradient(135deg, rgb(var(--pri)), rgba(var(--pri),.92)); color:#fff;
    box-shadow: 0 10px 22px rgba(var(--pri), .25);
    transition: transform .15s ease, box-shadow .15s ease, filter .15s ease;
  }
  .btn-premium:hover{ transform: translateY(-2px); filter: brightness(1.02); box-shadow: 0 14px 26px rgba(var(--pri), .3); }
</style>

<div class="row g-4">
  <!-- LEFT: Translations / Content -->
  <div class="col-lg-8">
    <div class="pane">
      <div class="pane-head">Content (Translations)</div>
      <div class="pane-body">
        {{-- Ensure your component outputs inputs like translations[en][headline], etc. --}}
        <x-translatable-tabs :model="$slider ?? null"
          :fields="[
            'headline' => 'text',
            'subheadline' => 'text',
            'button_text' => 'text',
            'button_url'  => 'text',
          ]" />
      </div>
    </div>
  </div>

  <!-- RIGHT: Settings / Media -->
  <div class="col-lg-4">
    <div class="pane">
      <div class="pane-head">Slider Settings</div>
      <div class="pane-body">
        {{-- Status --}}
        <div class="mb-3">
          <label class="form-label d-block">Status</label>
          @php $st = old('status', $slider->status ?? 'published'); @endphp
          <div class="status-toggle">
            <label>
              <input type="radio" name="status" value="published" @checked($st==='published')>
              <span class="status-pill published">Published</span>
            </label>
            <label>
              <input type="radio" name="status" value="draft" @checked($st==='draft')>
              <span class="status-pill draft">Draft</span>
            </label>
          </div>
        </div>

        {{-- Sort --}}
        <div class="mb-3">
          <label class="form-label">Sort</label>
          <input type="number" name="sort" min="0" step="1"
                 value="{{ old('sort', $slider->sort ?? 0) }}"
                 class="form-control">
        </div>

        {{-- Desktop Image --}}
        <div class="mb-3">
          <div class="upload-tile">
            <div class="upload-label">Desktop Image</div>
            <div class="upload-help">Recommended: 1604×864 (auto-cropped)</div>
            <input type="file" name="image" id="inputDesktop" class="form-control upload-input" accept="image/*">
            <div class="preview ratio mt-2" id="previewDesktop">
              @isset($slider->image)
                <img src="{{ asset('storage/'.$slider->image) }}" alt="Desktop Preview">
              @endisset
            </div>
          </div>
        </div>

        {{-- Mobile Image --}}
        <div class="mb-3">
          <div class="upload-tile">
            <div class="upload-label">Mobile Image (optional)</div>
            <div class="upload-help">We’ll scale down to width 768px while keeping aspect.</div>
            <input type="file" name="image_mobile" id="inputMobile" class="form-control upload-input" accept="image/*">
            <div class="preview ratio mt-2" id="previewMobile">
              @isset($slider->image_mobile)
                <img src="{{ asset('storage/'.$slider->image_mobile) }}" alt="Mobile Preview">
              @endisset
            </div>
          </div>
        </div>

        {{-- Video URL --}}
        <div class="mb-3">
          <label class="form-label">Video URL (optional)</label>
          <input type="url" name="video_url"
                 value="{{ old('video_url', $slider->video_url ?? '') }}"
                 class="form-control" placeholder="https://...">
        </div>

        <button class="btn btn-premium w-100">Save</button>
      </div>
    </div>
  </div>
</div>
<script>
(function(){
  const d = document;
  const desktop = d.getElementById('inputDesktop');
  const mobile  = d.getElementById('inputMobile');

  function preview(input, targetId){
    const box = d.getElementById(targetId);
    if (!box || !input?.files?.[0]) return;
    const url = URL.createObjectURL(input.files[0]);
    const img = box.querySelector('img') || (() => {
      const el = d.createElement('img'); box.innerHTML=''; box.appendChild(el); return el;
    })();
    img.src = url;
    img.onload = () => URL.revokeObjectURL(url);
  }

  desktop?.addEventListener('change', () => preview(desktop, 'previewDesktop'));
  mobile?.addEventListener('change',  () => preview(mobile, 'previewMobile'));
})();
</script>
