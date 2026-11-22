@extends('admin.layouts.master')
@section('title', 'Static Pages')

@section('vendor-style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="container-fluid">

        <div class="card page-card">
            <div class="card-header">
                <h5 class="mb-0">Static Pages</h5>
            </div>
            <div class="card-body">

                <!-- MAIN PAGE TABS -->
                <ul class="nav nav-tabs" id="pageTab" role="tablist">
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab-terms">Terms of Service</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-privacy">Privacy Policy</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-cookies">Cookies Policy</a></li>
                </ul>

                <div class="tab-content pt-3">

                    <!-- TERMS TAB -->
                    <div class="tab-pane fade show active" id="tab-terms">
                        @include('admin.static_pages._page_form', [
                            'slug' => 'terms-of-service',
                            'page' => $pages['terms-of-service'] ?? null,
                        ])
                    </div>

                    <!-- PRIVACY TAB -->
                    <div class="tab-pane fade" id="tab-privacy">
                        @include('admin.static_pages._page_form', [
                            'slug' => 'privacy-policy',
                            'page' => $pages['privacy-policy'] ?? null,
                        ])
                    </div>

                    <!-- COOKIES TAB -->
                    <div class="tab-pane fade" id="tab-cookies">
                        @include('admin.static_pages._page_form', [
                            'slug' => 'cookies-policy',
                            'page' => $pages['cookies-policy'] ?? null,
                        ])
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // attach csrf
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        (function() {
        function initEditors() {
            $('.summernote').summernote({
                height: 220
            });
        }

        initEditors();

        // Save buttons
        document.querySelectorAll('.btn-save-page').forEach(btn => {
            btn.addEventListener('click', function() {

                const slug = this.dataset.slug;
                const form = document.querySelector('#form-' + slug);

                const url = "{{ route('admin.static-pages.update', ':slug') }}".replace(':slug', slug);

                $.post(url, $(form).serialize())
                    .done(function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Saved',
                            timer: 1200,
                            showConfirmButton: false
                        });
                    });
            });
        });

        })();
    </script>
@endsection
