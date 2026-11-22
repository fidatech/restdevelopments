@if ($counters->count())
    <div class="counter-area-7 bg-luxury space-top overflow-hidden pb-60 pt-80">
        <div class="container">
            <div class="row counter-card-wrap counter-border-top gy-4 pt-80">
                @foreach ($counters as $c)
                    @php $tr = $c->t(); @endphp
                    <div class="col-sm-6 col-lg-3">
                        <div class="counter-card style4 text-center">

                            {{-- Icon --}}
                            <div class="counter-icon mb-3 animate-icon">
  @switch($tr->home_counter_id)
      @case(2) {{-- Elegant Apartments --}}
          <i class="fa-solid fa-building fa-3x text-theme"></i>
          @break

      @case(3) {{-- Luxury Houses --}}
          <i class="fa-solid fa-house-chimney fa-3x text-theme"></i>
          @break

      @case(4) {{-- Satisfied Guests --}}
          <i class="fa-solid fa-user-check fa-3x text-theme"></i>
          @break

      @case(5) {{-- Happy Owners --}}
          <i class="fa-solid fa-house-user fa-3x text-theme"></i>
          @break

      @default
          <i class="fa-solid fa-circle-check fa-3x text-theme"></i>
  @endswitch
</div>


                            {{-- Number --}}
                            <h2 class="box-number text-theme mb-1">
                                <span class="counter-number" data-target="{{ (int) $c->number }}">0</span>
                                <span class="suffix">+</span>
                            </h2>

                            {{-- Title --}}
                            <p class="box-text text-theme fw-semibold">{{ $tr?->title }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
