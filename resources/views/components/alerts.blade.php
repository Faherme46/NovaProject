<div class="z-3 position-fixed " >

    @session('success1')
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success1') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endsession

    @session('error1')
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error1') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endsession


    @session('warning1')
        <div class="alert alert-warning alert-dismissible" role="alert">
            {{ session('warning1') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endsession

    @session('info1')
        <div class="alert alert-info alert-dismissible" role="alert">
            {{ session('info1') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endsession

</div>
