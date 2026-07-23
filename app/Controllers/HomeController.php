<?php

declare(strict_types=1);

namespace App\Controllers;

class HomeController
{
    public function index(): void
    {
        echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAT Framework</title>
    <style>
        body{
            margin:0;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
            background:#f5f5f5;
            font-family:Arial,sans-serif;
        }
        .card{
            background:#fff;
            padding:40px;
            border-radius:12px;
            box-shadow:0 10px 30px rgba(0,0,0,.1);
            text-align:center;
        }
        h1{
            margin:0;
            color:#333;
        }
        p{
            color:#666;
            margin-top:15px;
        }
    </style>
</head>
<body>

<div class="card">
    <h1>🚗 FAT Framework v1.0</h1>
    <p>Framework is running successfully.</p>
    <p>Welcome to Frankfurt AutoTrade Dealer Management System.</p>
</div>

</body>
</html>';
    }
}