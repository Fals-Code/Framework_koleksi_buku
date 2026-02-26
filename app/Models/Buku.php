<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'bukus';
protected $fillable = ['kode', 'judul', 'pengarang', 'idkategori'];

public function kategori() {
    return $this->belongsTo(Kategori::class, 'idkategori');
}
}
