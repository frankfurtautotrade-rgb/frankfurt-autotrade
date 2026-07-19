<?php

namespace Controllers\Admin;

use Core\Controller;
use Core\Request;
use Core\Database;
use Core\Session;

class LoginController extends Controller
{
    public function index(): void
    {
        $this->view('admin/login/index');
    }

    public function login(): void
    {
        $email = Request::post('email');
        $password = Request::post('password');

        $pdo = Database::connection();

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");

        $stmt->execute([$email]);

        $user = $stmt->fetch();

        if (!$user) {
            die('User not found');
        }

        if (password_verify($password, $user['password'])) {

   Session::start();

Session::set('user_id', $user['id']);

header('Location: /admin/dashboard');

exit;

        } else {

            echo "Invalid password!";

        }
    }

public function logout(): void
{
    Session::start();

    Session::destroy();

    header('Location: /admin/login');

    exit;
}

}