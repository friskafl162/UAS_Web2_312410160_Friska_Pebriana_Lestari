<?php

namespace App\Controllers;

use App\Models\PeminjamanModel;
use CodeIgniter\RESTful\ResourceController;

class Peminjaman extends ResourceController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $data = $db->table('peminjaman')
            ->select('peminjaman.*, buku.judul')
            ->join(
                'buku',
                'buku.id = peminjaman.buku_id'
            )
            ->get()
            ->getResult();

        return $this->respond($data);
    }

    public function create()
    {
        $model = new PeminjamanModel();

        $data =
            $this->request->getJSON(true);

        $model->insert([
            'nama_peminjam'
                => $data['nama_peminjam'],
            'buku_id'
                => $data['buku_id'],
            'tanggal_pinjam'
                => $data['tanggal_pinjam'],
            'status'
                => $data['status']
        ]);

        return $this->respondCreated([
            'message'
                => 'Peminjaman berhasil'
        ]);
    }

    public function update($id = null)
    {
        $model = new PeminjamanModel();

        $data =
            $this->request->getJSON(true);

        $model->update($id, [
            'status' => $data['status']
        ]);

        return $this->respond([
            'message' =>
                'Status berhasil diupdate'
        ]);
    }
}
