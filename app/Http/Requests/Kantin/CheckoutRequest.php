<?php

namespace App\Http\Requests\Kantin;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'vendor_id'        => 'required|exists:vendors,id',
            'nama_pelanggan'   => 'required|string|max:255',
            'catatan'          => 'nullable|string',
            'items'            => 'required|array',
            'items.*.id'       => 'required|exists:menus,id',
            'items.*.quantity' => 'required|numeric|min:1',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'vendor_id' => 'Vendor',
            'nama_pelanggan' => 'Nama Pelanggan',
            'items' => 'Item Pesanan',
        ];
    }
}
