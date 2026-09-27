@extends('layouts.guest', [
    'title' => 'Sign In — FacilityHub',
    'header' => 'Masuk ke Sistem',
    'subheader' => 'Sistem Reservasi & Pelaporan Fasilitas Kampus'
])

@section('content')
    @include('auth.auth-page', ['initialTab' => 'signin'])
@endsection
