<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dealer Login</title>
</head>
<body>

    <h1>Frankfurt AutoTrade</h1>

    <h2>Dealer Login</h2>

    <form method="POST" action="/admin/login">

        <p>
            <label>Email</label><br>
            <input type="email" name="email" required>
        </p>

        <p>
            <label>Password</label><br>
            <input type="password" name="password" required>
        </p>

        <button type="submit">
            Login
        </button>

    </form>

</body>
</html>