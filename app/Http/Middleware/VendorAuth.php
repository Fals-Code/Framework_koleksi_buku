<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VendorAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('vendor_id')) {
            return redirect()->route('vendor.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}
