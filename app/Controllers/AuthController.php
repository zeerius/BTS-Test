<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use Firebase\JWT\JWT;

class AuthController extends BaseController
{
    use ResponseTrait;

    public function register()
    {
        $rules = [
            'email' => 'required|valid_email|is_unique[users.email]',
            'username' => 'required|min_length[5]|max_length[20]|is_unique[users.username]',
            'password' => 'required|min_length[5]|max_length[20]',
        ];

        if ($this->validate($rules)) {
            $model = new UserModel();
            $data = [
                'email' => $this->request->getVar('email'),
                'username' => $this->request->getVar('username'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),
            ];
            $model->save($data);
            $response = [
                'messages' => 'Registrasi berhasil',
            ];
            return $this->respondCreated($response);
        } else {
            $response = [
                'error' => $this->validator->getErrors(),
            ];
            return $this->fail($response);
        }
    }

    public function login()
    {
        $rules = [
            'username' => 'required|min_length[5]|max_length[20]',
            'password' => 'required|min_length[5]|max_length[20]',
        ];

        if ($this->validate($rules)) {
            $username = $this->request->getVar('username');
            $password = $this->request->getVar('password');

            $model = new UserModel();
            $user = $model->where('username', $username)->first();

            if (!$user) {
                return $this->fail(['error' => 'Username atau password salah'], 401);
            }

            if (!password_verify($password, $user['password'])) {
                return $this->fail(['error' => 'Username atau password salah'], 401);
            }

            $key = getenv('JWT_SECRET');
            $payload = [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'iat' => time(),
                'exp' => time() + 3600
            ];
            $token = JWT::encode($payload, $key, 'HS256');

            $response = [
                'messages' => 'Login berhasil',
                'token' => $token
            ];
            return $this->respondCreated($response);
        } else {
            $response = [
                'error' => $this->validator->getErrors(),
            ];
            return $this->fail($response);
        }
    }
}
