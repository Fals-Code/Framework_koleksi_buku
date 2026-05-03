<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    /**
     * Tampilkan semua data customer
     */
    public function index()
    {
        $customers = Customer::all();
        return view('customer.index', compact('customers'));
    }

    /**
     * Form tambah customer (Simpan ke BLOB)
     */
    public function create1()
    {
        return view('customer.tambah1');
    }

    /**
     * Simpan customer dengan foto sebagai BLOB
     */
    public function store1(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:100',
            'foto_base64' => 'required',
        ]);

        // Decode base64 string
        $image = $request->foto_base64;
        $image = str_replace('data:image/jpeg;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $binaryData = base64_decode($image);

        Customer::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'provinsi' => $request->provinsi,
            'kota' => $request->kota,
            'kecamatan' => $request->kecamatan,
            'kodepos' => $request->kodepos,
            'foto_blob' => $binaryData,
        ]);

        return redirect()->route('customer.index')->with('success', 'Customer berhasil disimpan ke Database (BLOB)!');
    }

    /**
     * Form tambah customer (Simpan ke File)
     */
    public function create2()
    {
        return view('customer.tambah2');
    }

    /**
     * Simpan customer dengan foto sebagai File
     */
    public function store2(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:100',
            'foto_base64' => 'required',
        ]);

        // Decode base64 string
        $image = $request->foto_base64;
        $image = str_replace('data:image/jpeg;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $binaryData = base64_decode($image);

        // Generate nama file unik
        $fileName = Str::uuid() . '.jpg';
        $filePath = 'customers/' . $fileName;

        // Simpan ke storage/app/public/customers/
        Storage::disk('public')->put($filePath, $binaryData);

        Customer::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'provinsi' => $request->provinsi,
            'kota' => $request->kota,
            'kecamatan' => $request->kecamatan,
            'kodepos' => $request->kodepos,
            'foto_path' => $filePath,
        ]);

        return redirect()->route('customer.index')->with('success', 'Customer berhasil disimpan sebagai File!');
    }

    /**
     * Menampilkan gambar dari data BLOB
     */
    public function showBlob($id)
    {
        $customer = Customer::findOrFail($id);

        if (!$customer->foto_blob) {
            return abort(404);
        }

        return response($customer->foto_blob)
            ->header('Content-Type', 'image/jpeg');
    }
}
