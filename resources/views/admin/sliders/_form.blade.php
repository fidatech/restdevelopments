

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

        <button class="btn btn-primary w-100">Save</button>
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
