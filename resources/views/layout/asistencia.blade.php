@extends('layout.app')

@section('content')
    <div class="row g-2">
        <div class="col-7">
            {{$slot}}
        </div>
        <div class="col-5">
            @livewire('all-predios')
        </div>
    </div>
@endsection
