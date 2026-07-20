<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anmeldung | Frankfurt AutoTrade DMS</title>

    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>

<div class="login-container">

    <div class="login-card">

        <h1>Frankfurt AutoTrade</h1>
        <p>Dealer Management System</p>

        <form action="/login" method="POST">

            <div class="form-group">
                <label for="email">E-Mail</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                    autocomplete="username">
            </div>

            <div class="form-group">
                <label for="password">Passwort</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    autocomplete="current-password">
            </div>

            <div class="form-group remember">
                <label>
                    <input type="checkbox" name="remember">
                    Angemeldet bleiben
                </label>
            </div>

            <button type="submit">
                Anmelden
            </button>

        </form>

    </div>

</div>

</body>
</html>