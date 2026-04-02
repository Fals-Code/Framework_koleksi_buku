<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = ['nama_warung', 'owner_name', 'email', 'password', 'image'];

    protected $hidden = ['password'];

    public function menu()
    {
        return $this->hasMany(Menu::class);
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class);
    }
}
