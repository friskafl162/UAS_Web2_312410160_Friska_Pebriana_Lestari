<?php

namespace App\Models;

use CodeIgniter\Model;

class PeminjamanModel extends Model
{
    protected $table = 'peminjaman';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nama_peminjam',
        'buku_id',
        'tanggal_pinjam',
        'status'
    ];
}