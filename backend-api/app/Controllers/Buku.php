<?php

namespace App\Controllers;

use App\Models\BukuModel;
use CodeIgniter\RESTful\ResourceController;

class Buku extends ResourceController
{
    public function index()
    {
        $model = new BukuModel();
        return $this->respond($model->findAll());
    }

    public function create()
    {
        $model = new BukuModel();

        $data = [
            'judul' => $this->request->getPost('judul'),
            'penulis' => $this->request->getPost('penulis'),
            'stok' => $this->request->getPost('stok'),
            'kategori_id' => $this->request->getPost('kategori_id')
        ];

        $model->insert($data);

        return $this->respondCreated([
            'message' => 'Buku berhasil ditambahkan'
        ]);
    }

    public function update($id = null)
    {
        $data = $this->request->getRawInput();

        $model = new BukuModel();

        $model->update($id, [
            'judul' => $data['judul'],
            'penulis' => $data['penulis'],
            'stok' => $data['stok'],
            'kategori_id' => $data['kategori_id']
        ]);

        return $this->respond([
            'message' => 'Buku berhasil diupdate'
        ]);
    }
    
    public function delete($id = null)
    {
        $model = new BukuModel();

        $model->delete($id);

        return $this->respond([
            'message' => 'Buku berhasil dihapus'
        ]);
    }
}
