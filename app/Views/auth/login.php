<?php

declare(strict_types=1);

/**
 * @var string|null $error
 * @var string|null $success
 * @var string|null $email
 * @var string $csrf
 */

$error ??= null;
$success ??= null;
$email ??= '';
$csrf ??= '';
?>
<!DOCTYPE html>
<html lang="de">
<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Login | Frankfurt AutoTrade</title>

<link rel="preconnect"
      href="https://fonts.googleapis.com">

<link rel="preconnect"
      href="https://fonts.gstatic.com"
      crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap"
      rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{

    font-family:'Montserrat',sans-serif;

    background:#F5F6F8;

    display:flex;

    justify-content:center;

    align-items:center;

    min-height:100vh;

}

.login-card{

    width:420px;

    background:#fff;

    border-radius:12px;

    box-shadow:0 15px 40px rgba(0,0,0,.12);

    padding:45px;

}

.logo{

    text-align:center;

    font-size:30px;

    font-weight:700;

    color:#C8A85A;

    margin-bottom:10px;

}

.subtitle{

    text-align:center;

    color:#666;

    margin-bottom:35px;

    font-size:14px;

}

label{

    display:block;

    margin-bottom:8px;

    font-weight:600;

}

input[type=email],
input[type=password]{

    width:100%;

    padding:13px 15px;

    border:1px solid #ddd;

    border-radius:8px;

    font-size:15px;

    margin-bottom:20px;

    transition:.25s;

}

input:focus{

    outline:none;

    border-color:#C8A85A;

}

.remember{

    display:flex;

    align-items:center;

    gap:8px;

    margin-bottom:25px;

    font-size:14px;

}

button{

    width:100%;

    padding:14px;

    background:#C8A85A;

    color:#fff;

    border:none;

    border-radius:8px;

    font-size:15px;

    font-weight:600;

    cursor:pointer;

    transition:.25s;

}

button:hover{

    background:#b8974d;

}

.alert{

    padding:14px;

    border-radius:8px;

    margin-bottom:20px;

    font-size:14px;

}

.error{

    background:#ffe5e5;

    color:#b00020;

}

.success{

    background:#e7ffe7;

    color:#0b7d0b;

}

.footer{

    text-align:center;

    margin-top:25px;

    color:#777;

    font-size:13px;

}

</style>

</head>

<body>

<div class="login-card">

<div class="logo">

Frankfurt AutoTrade

</div>

<div class="subtitle">

Dealer Management System

</div>

<?php if($error): ?>

<div class="alert error">

<?= htmlspecialchars($error) ?>

</div>

<?php endif; ?>

<?php if($success): ?>

<div class="alert success">

<?= htmlspecialchars($success) ?>

</div>

<?php endif; ?>

<form method="post"
      action="/login">

<input
type="hidden"
name="_token"
value="<?= htmlspecialchars($csrf) ?>">

<label>Email</label>

<input
type="email"
name="email"
value="<?= htmlspecialchars($email) ?>"
required>

<label>Password</label>

<input
type="password"
name="password"
required>

<div class="remember">

<input
type="checkbox"
id="remember"
name="remember">

<label for="remember">

Remember Me

</label>

</div>

<button type="submit">

Login

</button>

</form>

<div class="footer">

© <?= date('Y') ?>

Frankfurt AutoTrade

</div>

</div>

</body>

</html>