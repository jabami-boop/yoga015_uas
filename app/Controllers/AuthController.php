<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use App\Libraries\JWTLibrary;

class AuthController extends ResourceController
{
    protected $modelName = 'App\Models\UserModel';
    protected $format = 'json';
    protected $jwt;

    public function __construct()
    {
        $this->jwt = new JWTLibrary();
    }

    public function register()
    {
        $validation = \Config\Services::validation();
        $rules = [
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'email'    => 'required|valid_email|is_unique[users.email]',
        ];
        if (!$this->validate($rules)) {
            return $this->fail($validation->getErrors());
        }

        $data = $this->request->getPost();

        // Bug #6: No input validation
        $userModel = new UserModel();
        $validation = \Config\Services::validation();
    $rules = [
        'username' => 'required|min_length[3]|is_unique[users.username]',
        'password' => 'required|min_length[6]',
        'email'    => 'required|valid_email|is_unique[users.email]',
    ];
    if (!$this->validate($rules)) {
        return $this->fail($validation->getErrors());
    }

        // Bug #7: Password not hashed
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
             'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
        ];

        $userId = $userModel->insert($userData);

        if ($userId) {
            unset($userData['password']);
            return $this->respond([
                'status' => 'success',
                'message' => 'User registered successfully',
                'data' => $userData
            ]);
        }

        return $this->failServerError('Registration failed');
    }

    public function login()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Bug #9: No input validation
        $validation = \Config\Services::validation();
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];
        if (!$this->validate($rules)) {
            return $this->fail($validation->getErrors());
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        // Bug #10: Plain text password comparison
        if ($user && password_verify($password, $user['password'])) {
            $payload = [
                'user_id' => $user['id'],
                'email' => $user['email'],
                'exp' => time() + 3600
            ];

            $token = $this->jwt->encode($payload);

            return $this->respond([
                'status' => 'success',
                'token' => $token,
                'user' => $user
            ]);
        }

        return $this->failUnauthorized('Invalid credentials');
    }

    public function refresh()
    {
        // Bug #11: Missing implementation
        return $this->respond(['message' => 'Not implemented']);
    }
}
