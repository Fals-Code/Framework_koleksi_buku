<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use App\Notifications\SystemNotification;

class VendorController extends Controller
{
    private function getVendorId()
    {
        return session('vendor_id') ?? optional(Vendor::first())->id ?? 1;
    }

    public function dashboard()
    {
        $vendor_id = $this->getVendorId();
        $vendor = Vendor::findOrFail($vendor_id);
        
        // Basic Stats
        $total_orders = Pesanan::where('vendor_id', $vendor_id)->count();
        $total_revenue = Pesanan::where('vendor_id', $vendor_id)->where('status', 'completed')->sum('total_harga');
        
        // Analytics
        $today_revenue = Pesanan::where('vendor_id', $vendor_id)
                                ->where('status', 'completed')
                                ->whereDate('created_at', Carbon::today())
                                ->sum('total_harga');

        $top_items = DetailPesanan::whereHas('pesanan', function($q) use ($vendor_id) {
                                    $q->where('vendor_id', $vendor_id)->where('status', 'completed');
                                })
                                ->select('menu_id', \Illuminate\Support\Facades\DB::raw('SUM(qty) as total_qty'))
                                ->groupBy('menu_id')
                                ->orderBy('total_qty', 'desc')
                                ->take(5)
                                ->with('menu')
                                ->get();

        $pending_orders = Pesanan::where('vendor_id', $vendor_id)
                                ->whereIn('status', ['paid', 'cooking', 'ready'])
                                ->with(['detailPesanan.menu'])
                                ->orderBy('created_at', 'desc')
                                ->get();

        return view('kantin.vendor.dashboard', compact(
            'vendor', 'total_orders', 'total_revenue', 'today_revenue', 'top_items', 'pending_orders'
        ));
    }

    public function menuIndex()
    {
        $vendor_id = $this->getVendorId();
        $menus = Menu::where('vendor_id', $vendor_id)->get();
        return view('kantin.vendor.menu.index', compact('menus'));
    }

    public function menuCreate()
    {
        return view('kantin.vendor.menu.create');
    }

    public function menuStore(Request $request)
    {
        $request->validate([
            'nama_makanan' => 'required',
            'harga'        => 'required|numeric',
            'stok'         => 'required|numeric',
            'foto'         => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();
        $data['vendor_id'] = $this->getVendorId();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('menu', 'public');
        }

        Menu::create($data);
        return redirect()->route('vendor.menu.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function menuEdit($id)
    {
        $vendor_id = $this->getVendorId();
        $menu = Menu::where('vendor_id', $vendor_id)->findOrFail($id);
        return view('kantin.vendor.menu.edit', compact('menu'));
    }

    public function menuUpdate(Request $request, $id)
    {
        $vendor_id = $this->getVendorId();
        $menu = Menu::where('vendor_id', $vendor_id)->findOrFail($id);
        $request->validate([
            'nama_makanan' => 'required',
            'harga'        => 'required|numeric',
            'stok'         => 'required|numeric',
            'foto'         => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();
        if ($request->hasFile('foto')) {
            if ($menu->foto) Storage::disk('public')->delete($menu->foto);
            $data['foto'] = $request->file('foto')->store('menu', 'public');
        }

        $menu->update($data);
        return redirect()->route('vendor.menu.index')->with('success', 'Menu berhasil diupdate.');
    }

    public function menuDestroy($id)
    {
        $vendor_id = $this->getVendorId();
        $menu = Menu::where('vendor_id', $vendor_id)->findOrFail($id);
        if ($menu->foto) Storage::disk('public')->delete($menu->foto);
        $menu->delete();
        return redirect()->route('vendor.menu.index')->with('success', 'Menu berhasil dihapus.');
    }

    public function orders()
    {
        $vendor_id = $this->getVendorId();
        $orders = Pesanan::where('vendor_id', $vendor_id)
                        ->with(['detailPesanan.menu'])
                        ->orderBy('created_at', 'desc')
                        ->get();
        return view('kantin.vendor.orders.index', compact('orders'));
    }

    public function orderUpdateStatus(Request $request, $id)
    {
        $vendor_id = $this->getVendorId();
        $pesanan = Pesanan::where('vendor_id', $vendor_id)->findOrFail($id);
        $pesanan->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan diperbarui.');
    }

    public function getNewOrdersCount()
    {
        $vendor_id = $this->getVendorId();
        $count = Pesanan::where('vendor_id', $vendor_id)
                        ->where('status', 'lunas')
                        ->count();
        
        return response()->json(['count' => $count]);
    }

    /**
     * Praktikum 2: Scan QR Code Pesanan
     */
    public function scanQR()
    {
        return view('kantin.vendor.scan_qr');
    }

    /**
     * API for QR Scanner to fetch order details
     */
    public function getOrderDetail($id)
    {
        $vendor_id = $this->getVendorId();
        
        // Cari pesanan secara global (mendukung ID numerik atau Nomor Pesanan string)
        $pesanan_global = Pesanan::where('id', $id)
                                 ->orWhere('nomor_pesanan', $id)
                                 ->first();
        
        if (!$pesanan_global) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Pesanan tidak ditemukan. Pastikan QR Code benar.'
            ], 404);
        }

        // Jika ada tapi beda vendor
        if ($pesanan_global->vendor_id != $vendor_id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Pesanan ini milik warung lain. Silakan arahkan pelanggan ke warung yang tepat.'
            ], 403);
        }

        // Load detail pesanan
        $pesanan = Pesanan::where('id', $pesanan_global->id)
                         ->with(['detailPesanan.menu'])
                         ->first();

        return response()->json([
            'status' => 'success',
            'data'   => [
                'id' => $pesanan->id,
                'nomor_pesanan' => $pesanan->nomor_pesanan,
                'nama_pelanggan' => $pesanan->nama_pelanggan,
                'total_harga' => (int) $pesanan->total_harga,
                'status' => $pesanan->status,
                'status_label' => strtoupper($pesanan->status),
                'items' => $pesanan->detailPesanan->map(function($detail) {
                    return [
                        'nama' => $detail->menu->nama_makanan ?? 'Menu Terhapus',
                        'qty' => $detail->qty
                    ];
                })
            ]
        ]);
    }
}
