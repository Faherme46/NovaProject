<div class="z-3 position-fixed " style="max-width: 95%">

    @session('success')
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endsession

    @session('error')
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endsession



    @session('warning')
        <div class="alert alert-warning alert-dismissible" role="alert">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endsession

    @session('info')
        <div class="alert alert-info alert-dismissible" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endsession


    @if ($errors->any())
        <div class="alert alert-danger alert-dimissible d-flex" role="alert">
            <div class="col-11">

                @foreach ($errors->all() as  $error)
                    <h6 class="mb-0">{{ $error }}</h6>
                @endforeach

            </div>
            <div class="col-1 justify-content-end d-flex">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
</div>
