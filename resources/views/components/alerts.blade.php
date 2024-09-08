<div class="z-3 position-fixed " style="max-width: 95%">

    @session('success1')
        <div class="alert alert-success alert-dismissible" role="alert">
            * {{ session('success1') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endsession

    @session('error1')
        <div class="alert alert-danger alert-dismissible" role="alert">
            * {{ session('error1') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endsession



    @session('warning1')
        <div class="alert alert-warning alert-dismissible" role="alert">
            * {{ session('warning1') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endsession

    @session('info1')
        <div class="alert alert-info alert-dismissible" role="alert">
            * {{ session('info1') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endsession


    @if ($errors->any())
        <div class="alert alert-danger alert-dimissible d-flex" role="alert">
            <div class="col-11">

                @foreach ($errors->all() as $error)
                    <p class="mb-0">{{ $error }}</p>
                @endforeach

            </div>
            <div class="col-1 justify-content-end d-flex">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
</div>
