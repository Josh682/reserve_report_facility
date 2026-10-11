@extends('layouts.guest', [
    'title' => 'Sign Up — FacilityHub',
    'header' => 'Daftar Akun'
])

@section('content')
    @include('auth.auth-page', ['initialTab' => 'signup'])
@endsection
