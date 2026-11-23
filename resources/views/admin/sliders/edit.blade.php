@extends('admin.layouts.master')
@section('title', 'Edit Slider')

@section('content')
<div class="page-header card-soft is-inline">
    <div class="page-header-main">
        <span class="page-header-icon"><x-icon name="images" /></span>
        <div class="page-header-text">
            <h1>Edit Slider</h1>
        </div>
    </div>
</div>
    <div class="container-fluid">
        <form id="sliderEditForm" method="post" action="{{ route('admin.sliders.update', $slider) }}" enctype="multipart/form-data" novalidate>
            @csrf @method('PUT')
            @include('admin.sliders._form', ['slider' => $slider])
        </form>
    </div>
@endsection

@section('page-script')
<script>
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 1500,
  timerProgressBar: true
});

document.getElementById('sliderEditForm').addEventListener('submit', async function (e) {
  e.preventDefault();
  const form = e.currentTarget;
  const btn  = form.querySelector('button[type="submit"], .btn-primary');

  const fd = new FormData(form);
  fd.append('_method', 'PUT'); // ✅ force PUT for Laravel

  btn?.setAttribute('disabled', 'disabled');

  try {
    const res = await fetch(form.action, {
      method: 'POST', // Laravel interprets this as PUT because of _method
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json'
      },
      body: fd
    });

    // Handle validation errors (422)
    if (res.status === 422) {
      const data = await res.json();
      const messages = Object.values(data.errors || {}).flat().join('<br>');
      Toast.fire({ icon: 'error', title: 'Validation error', html: messages });
      btn?.removeAttribute('disabled');
      return;
    }

    // Try JSON parse safely
    let data;
    try {
      data = await res.json();
    } catch {
      const text = await res.text();
      console.error('Raw response from server:', text);
      Toast.fire({ icon: 'error', title: 'Unexpected server response' });
      btn?.removeAttribute('disabled');
      return;
    }

    if (data.ok) {
      Toast.fire({ icon: 'success', title: data.message || 'Updated' });
      setTimeout(() => {
        window.location.href = data.redirect || "{{ route('admin.sliders.index') }}";
      }, 900);
    } else {
      Toast.fire({ icon: 'error', title: data.message || 'Failed' });
      btn?.removeAttribute('disabled');
    }

  } catch (err) {
    console.error('JS Fetch error:', err);
    Toast.fire({ icon: 'error', title: 'Server error' });
    btn?.removeAttribute('disabled');
  }
});
</script>
@endsection
