@extends('layout.app')

@section('content')

    <div class="col-12">

        <x-alerts/>
        {{ $slot }}
    </div>
@endsection
