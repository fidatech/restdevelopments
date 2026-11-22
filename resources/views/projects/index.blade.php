@extends('layouts.base')
@section('title', __('Projects'))

@section('content')
    <style>
        /* ===== FILTER BAR ===== */
        .filter-card {
            background: #fff;
            border: 1px solid rgba(0, 0, 0, .06);
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .05);
            padding: 16px 20px;
            margin-bottom: 24px;
        }

        .filter-row {
            display: grid;
            gap: 14px;
            grid-template-columns: 1fr;
        }

        @media(min-width:768px) {
            .filter-row {
                grid-template-columns: repeat(4, 1fr) auto;
            }
        }

        .f-select,
        .f-input {
            background: #f7f8fb;
            border-radius: 12px;
            padding: 10px 12px;
            border: 1px solid transparent;
            height: 44px;
            transition: .2s;
        }

        .f-select:focus,
        .f-input:focus-within {
            background: #fff;
            border-color: rgba(0, 0, 0, .12);
            box-shadow: 0 6px 18px rgba(0, 0, 0, .06);
        }

        .f-input input {
            border: none;
            outline: 0;
            background: transparent;
            width: 100%;
        }

        .f-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            align-items: center;
        }

        .f-btn {
            border-radius: 10px;
            font-weight: 700;
            padding: 10px 16px;
        }

        .f-btn-primary {
            background: #111;
            color: #fff;
        }

        .f-btn-clear {
            background: #fff;
            border: 1px solid rgba(0, 0, 0, .12);
            color: #333;
        }

        /* ===== PROJECT CARDS ===== */
        .proj-card {
            background: #fff;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 10px 28px rgba(0, 0, 0, .05);
            transition: .25s;
            border: 1px solid rgba(0, 0, 0, .06);
        }

        .proj-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 40px rgba(0, 0, 0, .08);
        }

        .proj-thumb {
            position: relative;
            aspect-ratio: 16/10;
            background: #eef1f6;
        }

        .proj-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .proj-body {
            padding: 16px 18px 20px;
        }

        .proj-title {
            font-weight: 800;
            font-size: 1.1rem;
            margin-bottom: 4px;
        }

        .proj-type {
            font-size: .9rem;
            color: #666;
            margin-bottom: 8px;
        }

        .proj-stats {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 12px;
        }

        .proj-stats span {
            font-size: .9rem;
            color: #444;
        }

        .proj-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .proj-price {
            font-weight: 700;
            color: #111;
        }

        .proj-btn {
            background: #111;
            color: #fff;
            border-radius: 10px;
            font-weight: 600;
            padding: 8px 14px;
            font-size: .9rem;
        }
    </style>

    <div class="breadcumb-wrapper" data-bg-src="{{ asset('assets/img/bg/about-us-breadcrumb.webp') }}">
        <div class="container">
            <div class="breadcumb-content text-center">
                <h1 class="breadcumb-title">{{ __('Projects') }}</h1>
                <ul class="breadcumb-menu">
                    <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                    <li>{{ __('Projects') }}</li>
                </ul>
            </div>
        </div>
    </div>

    <section class="space-top space-extra-bottom">
        <div class="container">

            {{-- ===== FILTER BAR ===== --}}
            <form method="get" action="{{ route('projects.index') }}" class="filter-card">
                <div class="filter-row">
                    {{-- City --}}
                    <select name="city" class="f-select form-select">
                        <option value="">{{ __('City') }}</option>
                        <option value="Kabul" @selected(request('city') === 'Kabul')>Kabul</option>
                        <option value="Termez" @selected(request('city') === 'Termez')>Termez</option>
                    </select>

                    {{-- Status --}}
                    <select name="status" class="f-select form-select">
                        <option value="">{{ __('Status') }}</option>
                        <option value="under-construction" @selected(request('status') === 'under-construction')>{{ __('Under Construction') }}</option>
                        <option value="ready" @selected(request('status') === 'ready')>{{ __('Ready') }}</option>
                    </select>

                    {{-- Property Type --}}
                    <select name="type" class="f-select form-select">
                        <option value="">{{ __('Property Type') }}</option>
                        <option value="apartments" @selected(request('type') === 'apartments')>{{ __('Apartments') }}</option>
                        <option value="retail" @selected(request('type') === 'retail')>{{ __('Retail') }}</option>
                        <option value="office" @selected(request('type') === 'office')>{{ __('Office') }}</option>
                        <option value="mixed-use" @selected(request('type') === 'mixed-use')>{{ __('Mixed-use') }}</option>
                    </select>

                    {{-- Price Range --}}
                    <select name="price_range" class="f-select form-select">
                        <option value="">{{ __('Price Range') }}</option>
                        <option value="0-50000" @selected(request('price_range') === '0-50000')>Up to $50,000</option>
                        <option value="50000-100000" @selected(request('price_range') === '50000-100000')>$50,000 – $100,000</option>
                        <option value="100000-250000" @selected(request('price_range') === '100000-250000')>$100,000 – $250,000</option>
                        <option value="250000-500000" @selected(request('price_range') === '250000-500000')>$250,000 – $500,000</option>
                        <option value="500000-1000000" @selected(request('price_range') === '500000-1000000')>$500,000 – $1M</option>
                        <option value="1000000+" @selected(request('price_range') === '1000000+')>Above $1M</option>
                    </select>

                    {{-- Actions --}}
                    <div class="f-actions">
                        <a class="f-btn f-btn-clear" href="{{ route('projects.index') }}">{{ __('Clear') }}</a>
                        <button class="f-btn f-btn-primary" type="submit">{{ __('Apply') }}</button>
                    </div>
                </div>
            </form>

            {{-- ===== GRID VIEW ===== --}}
            <div class="row gy-30">
                @forelse($projects as $p)
                    @php
                        $tr = $p->t();
                        $url = route('projects.show', $p->slug);
                        $cover = str_contains($p->image, 'storage/') ? str_replace('storage/', '', $p->image) : $p->image;
                        $cover = asset('storage/' . $cover);
                    @endphp
                    <div class="col-md-6 col-lg-4">
                        <div class="proj-card">
                            <div class="proj-thumb">
                                <img src="{{ $cover }}" alt="{{ $tr?->title ?? 'Project' }}">
                            </div>
                            <div class="proj-body">
                                <h4 class="proj-title"><a href="{{ $url }}">{{ $tr?->title ?? 'Project' }}</a></h4>
                                <div class="proj-type">{{ ucfirst($p->type) ?? __('Mixed-use') }}</div>
                                <div class="proj-stats">
                                    <span><strong>{{ __('Size Range:') }}</strong> {{ $p->size_range ?? '45–220 m²' }}</span>
                                    <span><strong>{{ __('Status:') }}</strong> {{ $p->status_label ?? 'Under Construction – Handover Q4 2026' }}</span>
                                </div>
                                <div class="proj-footer">
                                    <div class="proj-price">{{ __('From') }} ${{ number_format($p->price_starts_from) ?? '0' }}/m²</div>
                                    <a href="{{ $url }}" class="proj-btn">{{ __('Enquire Now') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light border text-center">{{ __('No projects found') }}</div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-60 text-center">
                {{ $projects->withQueryString()->links() }}
            </div>
        </div>
    </section>
@endsection
