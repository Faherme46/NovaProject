<div class="col-12">
    <div class="card mt-2">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="col-2">
                <button class="btn btn-primary px-1 rounded-3" wire:click='goBack'>
                    <i class="bi bi-arrow-left-circle-fill fs-3"></i>
                </button>
            </div>
            <div class="col-2">
                <h1>00:00</h1>
            </div>
            <div class="col-2 justify-content-end d-flex">
                <button class="btn btn-warning p-0 rounded-3 me-2">
                    <i class="bi bi-pause-fill fs-2 py-0 px-1"></i>
                </button>
                <button class="btn btn-danger p-0 rounded-3">
                    <i class="bi bi-stop-fill fs-2 px-1" ></i>
                </button>

            </div>
        </div>
        <div class="card-body ">
            @foreach ($controls as $control)
                <span class="btn  @if ($control->state==4) btn-black @else btn-secondary @endif ms-0 mb-1 me-0 fs-2">
                    {{($control->id<10)?'0':''}}{{$control->id}}
                </span>
            @endforeach
        </div>
    </div>
</div>
