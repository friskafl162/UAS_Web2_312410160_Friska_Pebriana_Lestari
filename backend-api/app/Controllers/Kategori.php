<?php

namespace App\Controllers;

use App\Models\KategoriModel;
use CodeIgniter\RESTful\ResourceController;

class Kategori extends ResourceController
{
    public function index()
    {
        $model = new KategoriModel();

        return $this->respond(
            $model->findAll()
        );
    }
}