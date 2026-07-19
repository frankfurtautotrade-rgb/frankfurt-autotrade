<?php

session_start();

if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="de">

<head>

    <meta charset="UTF-8">

    <title>Admin Login</title>

    <link rel="stylesheet" href="/assets/css/admin.css">

</head>

<body class="login-page">

<div class="login-box">

    <h1>Frankfurt AutoTrade</h1>

    <h2>Dealer Portal</h2>

    <form action="authenticate.php" method="POST">

        <label>Email</label>

        <input
            type="email"
            name="email"
            required
        >

        <label>Password</label>

        <input
            type="password"
            name="password"
            required
        >

        <button type="submit">
            Login
        </button>

    </form>

</div>

</body>

</html>