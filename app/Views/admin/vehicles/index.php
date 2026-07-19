<h2>Vehicles</h2>

<p>
    <a href="/admin/vehicles/create">+ Add Vehicle</a>
</p>

<table border="1" cellpadding="10" cellspacing="0">

    <tr>
        <th>Stock No.</th>
        <th>Make</th>
        <th>Model</th>
        <th>Year</th>
        <th>Mileage</th>
        <th>Price</th>
    </tr>

    <?php foreach ($vehicles as $vehicle): ?>

    <tr>
        <td>
            <?= htmlspecialchars($vehicle['stock_number'] ?? '-'); ?>
        </td>

        <td>
            <?= htmlspecialchars($vehicle['make']); ?>
        </td>

        <td>
            <?= htmlspecialchars($vehicle['model']); ?>
        </td>

        <td>
            <?= $vehicle['year']; ?>
        </td>

        <td>
            <?= number_format($vehicle['mileage']); ?> km
        </td>

        <td>
            €<?= number_format($vehicle['price'], 2); ?>
        </td>
    </tr>

    <?php endforeach; ?>

</table>