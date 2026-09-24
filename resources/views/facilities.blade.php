@extends('layouts.app')

@section('title', 'Dashboard Fasilitas')
@section('page-title', 'Dashboard')

@section('content')


<div class="welcome-section">

    <div>
        <h1>Temukan fasilitas yang kamu butuhkan</h1>
        <p>Cek ketersediaan dan lakukan reservasi fasilitas kampus dengan mudah.</p>
    </div>

</div>


{{-- SEARCH --}}
<div class="search-card">

    <div class="search-bar">

        <span class="search-symbol">⌕</span>

        <input
            type="text"
            placeholder="Cari ruang, laboratorium, aula, lapangan..."
        >

        <button>
            Cari
        </button>

    </div>

    <div class="filter-group">

        <select>
            <option>Semua Tipe</option>
            <option>Ruang Kelas</option>
            <option>Laboratorium</option>
            <option>Aula</option>
            <option>Lapangan</option>
        </select>

        <select>
            <option>Semua Lokasi</option>
            <option>Gedung A</option>
            <option>Gedung B</option>
            <option>Gedung C</option>
        </select>

        <select>
            <option>Semua Kapasitas</option>
            <option>&lt; 30 orang</option>
            <option>30 - 50 orang</option>
            <option>&gt; 50 orang</option>
        </select>

    </div>

</div>


{{-- SUMMARY --}}
<div class="summary-grid">

    <div class="summary-card">
        <div class="summary-icon blue">▦</div>

        <div>
            <span>Total Fasilitas</span>
            <strong>26</strong>
        </div>
    </div>


    <div class="summary-card">
        <div class="summary-icon green">✓</div>

        <div>
            <span>Tersedia</span>
            <strong>18</strong>
        </div>
    </div>


    <div class="summary-card">
        <div class="summary-icon orange">◷</div>

        <div>
            <span>Sedang Digunakan</span>
            <strong>6</strong>
        </div>
    </div>


    <div class="summary-card">
        <div class="summary-icon red">!</div>

        <div>
            <span>Dalam Perbaikan</span>
            <strong>2</strong>
        </div>
    </div>

</div>


{{-- FACILITIES --}}
<div class="section-header">

    <div>
        <h2>Fasilitas</h2>
        <p>Cek status dan jadwal fasilitas yang tersedia.</p>
    </div>

</div>


<div class="facility-grid">


    {{-- RUANG A101 --}}
    <div class="facility-card">

        <div class="facility-cover">

            <span class="status-badge available">
                Tersedia
            </span>

            <span class="room-code">
                A101
            </span>

        </div>


        <div class="facility-body">

            <span class="facility-category">
                Ruang Kelas
            </span>

            <h3>Ruang A101</h3>


            <div class="facility-meta">

                <span>⌖ Gedung A</span>

                <span>♟ 50 orang</span>

            </div>


            <div class="next-slot">

                <span>Slot tersedia berikutnya</span>

                <strong>
                    09.00 - 09.30
                </strong>

            </div>


            <button class="detail-button">
                Cek Ketersediaan
            </button>

        </div>

    </div>



    {{-- LAB --}}
    <div class="facility-card">

        <div class="facility-cover">

            <span class="status-badge busy">
                Digunakan
            </span>

            <span class="room-code">
                LAB C
            </span>

        </div>


        <div class="facility-body">

            <span class="facility-category">
                Laboratorium
            </span>

            <h3>Laboratorium Komputer C</h3>


            <div class="facility-meta">

                <span>⌖ Gedung E</span>

                <span>♟ 30 orang</span>

            </div>


            <div class="next-slot">

                <span>Tersedia kembali</span>

                <strong>
                    11.30
                </strong>

            </div>


            <button class="detail-button">
                Cek Ketersediaan
            </button>

        </div>

    </div>



    {{-- AULA --}}
    <div class="facility-card">

        <div class="facility-cover">

            <span class="status-badge available">
                Tersedia
            </span>

            <span class="room-code">
                AULA
            </span>

        </div>


        <div class="facility-body">

            <span class="facility-category">
                Aula
            </span>

            <h3>Aula A</h3>


            <div class="facility-meta">

                <span>⌖ Gedung AP</span>

                <span>♟ 200 orang</span>

            </div>


            <div class="next-slot">

                <span>Slot tersedia berikutnya</span>

                <strong>
                    08.30 - 09.00
                </strong>

            </div>


            <button class="detail-button">
                Cek Ketersediaan
            </button>

        </div>

    </div>



    {{-- B203 --}}
    <div class="facility-card">

        <div class="facility-cover">

            <span class="status-badge maintenance">
                Dalam Perbaikan
            </span>

            <span class="room-code">
                B203
            </span>

        </div>


        <div class="facility-body">

            <span class="facility-category">
                Ruang Kelas
            </span>

            <h3>Ruang B203</h3>


            <div class="facility-meta">

                <span>⌖ Gedung B</span>

                <span>♟ 35 orang</span>

            </div>


            <div class="next-slot">

                <span>Status fasilitas</span>

                <strong class="danger-text">
                    Tidak dapat digunakan
                </strong>

            </div>


            <button class="detail-button disabled" disabled>
                Tidak Tersedia
            </button>

        </div>

    </div>


</div>

@endsection