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

    @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
