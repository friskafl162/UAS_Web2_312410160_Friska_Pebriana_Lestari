<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class Auth extends ResourceController
{
    public function login()
    {
        $model = new UserModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $model->where('username', $username)
                      ->where('password', $password)
                      ->first();

        if (!$user) {
            return $this->failUnauthorized('Username atau password salah');
        }

        return $this->respond([
            'message' => 'Login berhasil',
            'token'   => 'admin-token-123'
        ]);
    }
}