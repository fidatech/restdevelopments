@extends('admin.layouts.master')
@section('title', 'Why Choose Us – Items')

@section('content')
    <div class="container-fluid">
        <div class="card page-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Items</h5>
                <a href="{{ route('admin.items.create') }}" class="btn btn-sm btn-primary">Add New</a>
            </div>
            <div class="card-body table-responsive">
                <table class="table-bordered table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Title</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td>{{ $item->order }}</td>
                                <td>{{ $item->t()?->title }}</td>
                                <td>
                                    <a href="{{ route('admin.items.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>

                                    <button data-url="{{ route('admin.items.destroy', $item) }}" class="btn btn-sm btn-danger btn-delete-item">
                                        Delete
                                    </button>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">No items yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('page-script')
    <script>
        // Delete item (AJAX + Swal)
        $(document).on('click', '.btn-delete-item', async function(e) {
            e.preventDefault();

            const url = $(this).data('url');

            const ok = await Swal.fire({
                icon: 'warning',
                title: 'Delete this item?',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete'
            });

            if (!ok.isConfirmed) return;

            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    _method: 'DELETE'
                })
            });

            if (res.ok) {
                Swal.fire({
                        icon: 'success',
                        title: 'Deleted',
                        timer: 900,
                        showConfirmButton: false
                    })
                    .then(() => location.reload());
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Could not delete'
                });
            }
        });
    </script>
@endsection
