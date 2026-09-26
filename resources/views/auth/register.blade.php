@extends('layouts.guest', [
    'title' => 'Sign Up — FacilityHub',
    'header' => 'Daftar Akun',
    'subheader' => 'Lengkapi data untuk mengajukan akses sistem'
])

@section('content')
    @include('auth.auth-page', ['initialTab' => 'signup'])
@endsection
