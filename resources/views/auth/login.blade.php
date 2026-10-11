@extends('layouts.guest', [
    'title' => 'Sign In — FacilityHub',
    'header' => 'Masuk ke Sistem'
])

@section('content')
    @include('auth.auth-page', ['initialTab' => 'signin'])
@endsection
