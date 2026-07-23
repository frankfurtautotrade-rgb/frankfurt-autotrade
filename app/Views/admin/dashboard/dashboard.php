<?php

declare(strict_types=1);

/** @var object|array|null $user */

$name = 'Administrator';

if (is_object($user) && isset($user->name)) {
    $name = $user->name;
} elseif (is_array($user) && isset($user['name'])) {
    $name = $user['name'];
}
?>
<!DOCTYPE html>
<html lang="de">
<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Dashboard | Frankfurt AutoTrade</title>

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

    font-family:Montserrat,sans-serif;

    background:#F5F6F8;

}

header{

    background:#1F2937;

    color:#fff;

    padding:20px 40px;

    display:flex;

    justify-content:space-between;

    align-items:center;

}

.logo{

    color:#C8A85A;

    font-size:24px;

    font-weight:700;

}

.logout a{

    color:#fff;

    text-decoration:none;

    padding:10px 18px;

    border:1px solid #C8A85A;

    border-radius:8px;

}

.logout a:hover{

    background:#C8A85A;

}

.container{

    max-width:1200px;

    margin:50px auto;

    padding:0 20px;

}

.card{

    background:#fff;

    border-radius:12px;

    padding:40px;

    box-shadow:0 10px 25px rgba(0,0,0,.08);

}

h1{

    margin-bottom:15px;

}

p{

    color:#666;

    line-height:1.8;

}

.grid{

    display:grid;

    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));

    gap:20px;

    margin-top:40px;

}

.box{

    background:#fff;

    border-left:5px solid #C8A85A;

    border-radius:10px;

    padding:25px;

    box-shadow:0 6px 20px rgba(0,0,0,.05);

}

.box h3{

    margin-bottom:10px;

}

.box p{

    color:#777;

}

</style>

</head>

<body>

<header>

<div class="logo">

Frankfurt AutoTrade

</div>

<div class="logout">

<a href="/logout">

Logout

</a>

</div>

</header>

<div class="container">

<div class="card">

<h1>

Welcome,
<?= htmlspecialchars($name) ?>

</h1>

<p>

Welcome to the Frankfurt AutoTrade Dealer Management System.

</p>

</div>

<div class="grid">

<div class="box">

<h3>🚗 Vehicles</h3>

<p>Manage your vehicle inventory.</p>

</div>

<div class="box">

<h3>👥 Customers</h3>

<p>Manage customer information.</p>

</div>

<div class="box">

<h3>💰 Sales</h3>

<p>View sales and invoices.</p>

</div>

<div class="box">

<h3>⚙ Website</h3>

<p>Manage website content.</p>

</div>

</div>

</div>

</body>

</html>