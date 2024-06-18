@extends('layout.app')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/scss/welcome.scss') }}">

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="bg-image ">
        <div class="centered-title">Tecnovis</div>
    </div>

@endsection
