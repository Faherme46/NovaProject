<div>
    <x-alerts/>
    <div class="row justify-content-center px-5">

        @foreach ($panels as $panel)
            @if ($panel['visible'])
                <button class="btn p-0 mx-1 my-1 " style="width: 300px;" {{ $panel['directives'] }}
                    @disabled(!$panel['enabled'])>
                    <div class="card ">
                        <div class="row g-0">
                            <div class="col-4">
                                <i class="bi {{ $panel['icon'] }}" style="font-size:80px"></i>
                            </div>
                            <div class="col-8">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $panel['title'] }}</h5>
                                    <p class="card-text"><small class="text-body-secondary">
                                            {{ $panel['body'] }}
                                        </small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </button>
            @endif
        @endforeach

    </div>
</div>
