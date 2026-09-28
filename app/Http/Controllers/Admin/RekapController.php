<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RekapController extends Controller
{
    /**
     * Tampilkan halaman rekapitulasi operasional fasilitas, reservasi, dan pelaporan.
     */
    public function index(Request $request): Response
    {
        return response('Rekapitulasi Operasional');
    }

    /**
     * Ekspor data rekapitulasi ke format CSV.
     */
    public function exportCsv(Request $request): Response
    {
        return response('Export CSV');
    }

    /**
     * Ekspor data rekapitulasi ke format Excel.
     */
    public function exportExcel(Request $request): Response
    {
        return response('Export Excel');
    }

    /**
     * Pratinjau cetak dokumen rekapitulasi.
     */
    public function print(Request $request): Response
    {
        return response('Print Preview');
    }
}
