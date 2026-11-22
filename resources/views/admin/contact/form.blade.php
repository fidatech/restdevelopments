@extends('admin.layouts.master')
@section('title','Contact Settings')

@section('content')
<div class="container-fluid">

    <form id="contactForm" class="card page-card" method="POST" action="{{ route('admin.contact.update') }}" enctype="multipart/form-data">
        @csrf

        <div class="card-header">
            <h5 class="mb-0">Contact Settings</h5>
        </div>
        <div class="card-body">

            {{-- ========== CONTACT INFO ========= --}}
            <div class="form-section mb-5">
                <h6 class="section-title d-flex align-items-center">
                    <i class="fas fa-address-book me-2"></i>
                    <span>Contact Information</span>
                </h6>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="email" name="email_1" value="{{ $setting->email_1 }}" class="form-control" id="email1" placeholder="Primary Email">
                            <label for="email1">Primary Email</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="email" name="email_2" value="{{ $setting->email_2 }}" class="form-control" id="email2" placeholder="Secondary Email">
                            <label for="email2">Secondary Email</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="phone_1" value="{{ $setting->phone_1 }}" class="form-control" id="phone1" placeholder="Primary Phone">
                            <label for="phone1">Primary Phone</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="phone_2" value="{{ $setting->phone_2 }}" class="form-control" id="phone2" placeholder="Secondary Phone">
                            <label for="phone2">Secondary Phone</label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========== SOCIAL ========= --}}
            <div class="form-section mb-5">
                <h6 class="section-title d-flex align-items-center">
                    <i class="fas fa-share-alt me-2"></i>
                    <span>Social Links</span>
                </h6>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-facebook text-white">
                                <i class="fab fa-facebook-f"></i>
                            </span>
                            <input type="url" name="facebook" value="{{ $setting->facebook }}" class="form-control" placeholder="Facebook URL">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-twitter text-white">
                                <i class="fab fa-twitter"></i>
                            </span>
                            <input type="url" name="twitter" value="{{ $setting->twitter }}" class="form-control" placeholder="Twitter URL">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-linkedin text-white">
                                <i class="fab fa-linkedin-in"></i>
                            </span>
                            <input type="url" name="linkedin" value="{{ $setting->linkedin }}" class="form-control" placeholder="LinkedIn URL">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-instagram text-white">
                                <i class="fab fa-instagram"></i>
                            </span>
                            <input type="url" name="instagram" value="{{ $setting->instagram }}" class="form-control" placeholder="Instagram URL">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-youtube text-white">
                                <i class="fab fa-youtube"></i>
                            </span>
                            <input type="url" name="youtube" value="{{ $setting->youtube }}" class="form-control" placeholder="YouTube URL">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========== LOGOS ========= --}}
            <div class="form-section mb-5">
                <h6 class="section-title d-flex align-items-center">
                    <i class="fas fa-images me-2"></i>
                    <span>Logos</span>
                </h6>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="file-upload-card">
                            <label class="form-label">Dark Logo</label>
                            <div class="d-flex align-items-center">
                                <div class="file-upload-wrapper">
                                    <input type="file" name="logo_dark" class="form-control" id="logoDark">
                                    <label for="logoDark" class="file-upload-label btn btn-outline-primary">
                                        <i class="fas fa-cloud-upload-alt me-2"></i>Choose File
                                    </label>
                                </div>
                                @if($setting->logo_dark)
                                <div class="logo-preview ms-3">
                                    <img src="{{ asset('storage/'.$setting->logo_dark) }}" height="50" class="rounded border">
                                    <a href="{{ asset('storage/'.$setting->logo_dark) }}" target="_blank" class="preview-link">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="file-upload-card">
                            <label class="form-label">White Logo</label>
                            <div class="d-flex align-items-center">
                                <div class="file-upload-wrapper">
                                    <input type="file" name="logo_white" class="form-control" id="logoWhite">
                                    <label for="logoWhite" class="file-upload-label btn btn-outline-primary">
                                        <i class="fas fa-cloud-upload-alt me-2"></i>Choose File
                                    </label>
                                </div>
                                @if($setting->logo_white)
                                <div class="logo-preview ms-3">
                                    <img src="{{ asset('storage/'.$setting->logo_white) }}" height="50" class="rounded border bg-dark p-1">
                                    <a href="{{ asset('storage/'.$setting->logo_white) }}" target="_blank" class="preview-link">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========== MAP ========= --}}
            <div class="form-section mb-5">
                <h6 class="section-title d-flex align-items-center">
                    <i class="fas fa-map-marked-alt me-2"></i>
                    <span>Map Embed</span>
                </h6>
                <div class="form-floating">
                    <textarea name="map_iframe" class="form-control" id="mapIframe" style="height: 120px">{{ $setting->map_iframe }}</textarea>
                    <label for="mapIframe">Google Maps Iframe Code</label>
                </div>
                <div class="form-text">Paste the iframe code from Google Maps</div>
            </div>

            {{-- ========== TRANSLATIONS ========= --}}
            <div class="form-section">
                <h6 class="section-title d-flex align-items-center">
                    <i class="fas fa-language me-2"></i>
                    <span>Translations</span>
                </h6>
                <ul class="nav nav-tabs" id="translationTabs" role="tablist">
                    @foreach($locales as $i=>$loc)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $i===0?'active':'' }}" id="{{ $loc }}-tab" data-toggle="tab" data-target="#tab-{{ $loc }}" type="button" role="tab">
                                <i class="fas fa-globe me-1"></i>{{ strtoupper($loc) }}
                            </button>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content p-4 border border-top-0 rounded-bottom">
                    @foreach($locales as $i=>$loc)
                        <div class="tab-pane fade {{ $i===0?'show active':'' }}" id="tab-{{ $loc }}" role="tabpanel">
                            <div class="mb-4">
                                <div class="form-floating">
                                    <input class="form-control" name="translations[{{ $loc }}][company_intro]" id="intro-{{ $loc }}" value="{{ $setting->t($loc)?->company_intro }}">
                                    <label for="intro-{{ $loc }}">Company Intro</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-floating">
                                    <textarea name="translations[{{ $loc }}][address]" class="form-control" id="address-{{ $loc }}" style="height: 100px">{{ $setting->t($loc)?->address }}</textarea>
                                    <label for="address-{{ $loc }}">Address</label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary btn-lg px-4">
                    <i class="fas fa-save me-2"></i>Save Settings
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('page-script')
<script>
$('#contactForm').on('submit', async function(e){
    e.preventDefault();
    const fd  = new FormData(this);

    const res = await fetch(this.action, {
        method:'POST',
        headers:{ 'X-CSRF-TOKEN':'{{ csrf_token() }}' },
        body: fd
    });

    if(res.ok){
        Swal.fire({
            icon:'success',
            title:'Updated successfully',
            timer:1200,
            showConfirmButton:false
        });
    }else{
        Swal.fire({icon:'error',title:'Something went wrong'});
    }
});
</script>
@endsection

@section('styles')
<style>
    .form-section {
        margin-bottom: 2.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #eee;
    }

    .section-title {
        color: #3a3a3a;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #dee2e6;
    }

    .form-floating > label {
        padding: 1rem 1.25rem;
    }

    .input-group-text {
        min-width: 45px;
        justify-content: center;
    }

    .bg-facebook { background-color: #3b5998 !important; }
    .bg-twitter { background-color: #1da1f2 !important; }
    .bg-linkedin { background-color: #0077b5 !important; }
    .bg-instagram { background: linear-gradient(45deg, #405de6, #5851db, #833ab4, #c13584, #e1306c, #fd1d1d) !important; }
    .bg-youtube { background-color: #ff0000 !important; }

    .file-upload-card {
        background: #f8f9fa;
        padding: 1.25rem;
        border-radius: 0.5rem;
        border: 1px dashed #dee2e6;
    }

    .file-upload-wrapper {
        position: relative;
        overflow: hidden;
    }

    .file-upload-wrapper input[type="file"] {
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .file-upload-label {
        margin-bottom: 0;
        white-space: nowrap;
    }

    .logo-preview {
        position: relative;
    }

    .preview-link {
        position: absolute;
        right: -10px;
        top: -10px;
        background: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        color: #333;
    }

    .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
    }

    .nav-tabs .nav-link.active {
        color: #0d6efd;
        border-bottom: 3px solid #0d6efd;
        background: transparent;
    }

    .tab-content {
        background: #f8f9fa;
    }
</style>
@endsection
