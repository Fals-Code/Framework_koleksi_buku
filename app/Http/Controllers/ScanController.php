<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScanController extends Controller
{
    /**
     * Praktikum 1: Scan Barcode
     */
    public function barcode()
    {
        return view('scan.barcode');
    }
}
