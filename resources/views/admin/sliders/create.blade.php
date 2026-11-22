@extends('admin.layouts.master')
@section('title', 'Add Slider')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0"><i class="ft ft-image"></i> Create Slider</h4>
            </div>
            <div class="card-body p-1">
                <form id="sliderCreateForm" method="post" action="{{ route('admin.sliders.store') }}" enctype="multipart/form-data" novalidate>
                    @csrf
                    @include('admin.sliders._form')
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        function parseJsonSafe(raw) {
            try {
                return JSON.parse(raw)
            } catch (e) {
                return null
            }
        }
        async function showModal(icon, title, text, timer = 1200) {
            await Swal.fire({
                icon,
                title,
                html: text || '',
                timer,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        }

        document.getElementById('sliderCreateForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const form = e.currentTarget;
            const btn = form.querySelector('button[type="submit"], .btn-primary');
            const fd = new FormData(form);
            btn?.setAttribute('disabled', 'disabled');

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest', // <<< IMPORTANT
                        'Accept': 'application/json'
                    },
                    body: fd
                });

                const raw = await res.text();
                const data = parseJsonSafe(raw);

                if (!res.ok || !data?.ok) {
                    // collect validation/errors
                    const msg = (data && (data.message || (data.errors && Object.values(data.errors).flat().join('<br>')))) ||
                        (res.status === 413 ? 'Upload too large (413). Increase Herd Nginx/PHP limits.' : 'Request failed');
                    await showModal('error', 'Error', msg, 1800);
                    btn?.removeAttribute('disabled');
                    return;
                }

                await showModal('success', data.message || 'Saved');
                window.location.href = data.redirect || "{{ route('admin.sliders.index') }}";
            } catch (err) {
                console.error(err);
                await showModal('error', 'Server error', 'Please try again.');
                btn?.removeAttribute('disabled');
            }
        });
    </script>
@endsection
