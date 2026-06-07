<?php

namespace App\Http\Controllers;

use App\Models\WilayahDistrict;
use App\Models\WilayahProvince;
use App\Models\WilayahRegency;
use App\Models\WilayahVillage;

class WilayahController extends Controller
{
    public function index()
    {
        $provinces = WilayahProvince::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('wilayah.index', compact('provinces'));
    }

    public function regencies($provinceId)
    {
        try {
            $data = WilayahRegency::query()
                ->select('id', 'name')
                ->where('province_id', $provinceId)
                ->orderBy('name')
                ->get();

            return $this->jsonResponse($data);
        } catch (\Throwable $th) {
            return $this->jsonError();
        }
    }

    public function districts($regencyId)
    {
        try {
            $data = WilayahDistrict::query()
                ->select('id', 'name')
                ->where('regency_id', $regencyId)
                ->orderBy('name')
                ->get();

            return $this->jsonResponse($data);
        } catch (\Throwable $th) {
            return $this->jsonError();
        }
    }

    public function villages($districtId)
    {
        try {
            $data = WilayahVillage::query()
                ->select('id', 'name')
                ->where('district_id', $districtId)
                ->orderBy('name')
                ->get();

            return $this->jsonResponse($data);
        } catch (\Throwable $th) {
            return $this->jsonError();
        }
    }

    private function jsonResponse($data)
    {
        return response()->json([
            'status' => 'success',
            'message' => $data->isEmpty() ? 'Data kosong' : 'Data berhasil diambil',
            'data' => $data,
        ]);
    }

    private function jsonError()
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Gagal mengambil data',
            'data' => [],
        ], 500);
    }
}
