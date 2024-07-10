<div class="z-3">
    @if (session('success1'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success1') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dimissible" role="alert">
            <div class="row justify-content-between">
                <div class="col-6">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>
                <div class="col-1 offset-md-5">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

            </div>

        </div>
    @endif

    @if (session('warning1'))
        <div class="alert alert-warning alert-dismissible" role="alert">
            {{ session('warning1') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('info1'))
        <div class="alert alert-info alert-dismissible" role="alert">
            {{ session('info1') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

</div>
