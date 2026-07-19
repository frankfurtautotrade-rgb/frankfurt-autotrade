<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $title ?? 'Frankfurt AutoTrade Dealer Portal' ?></title>

    <link rel="stylesheet" href="/assets/css/app.css">

</head>

<body>

    <div class="app">

        <?php require __DIR__ . '/../partials/sidebar.php'; ?>

        <div class="main">

            <?php require __DIR__ . '/../partials/header.php'; ?>

            <main class="content">

                <?= $content ?>

            </main>

        </div>

    </div>

    <script src="/assets/js/app.js"></script>

</body>

</html>