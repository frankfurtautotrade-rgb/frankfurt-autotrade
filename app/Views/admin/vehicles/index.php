<div class="page-header">

    <div>
        <h2>Vehicles</h2>
        <p>Manage your vehicle inventory.</p>
    </div>

    <a href="/admin/vehicles/create" class="btn btn-primary">
        + Add Vehicle
    </a>

</div>

<table class="table">

    <thead>

        <tr>

            <th>Stock No.</th>
            <th>Make</th>
            <th>Model</th>
            <th>Year</th>
            <th>Mileage</th>
            <th>Price</th>
            <th>Actions</th>

        </tr>

    </thead>

    <tbody>

    <?php if (empty($vehicles)): ?>

        <tr>

            <td colspan="7" style="text-align:center;padding:30px;">
                No vehicles found.
            </td>

        </tr>

    <?php else: ?>

        <?php foreach ($vehicles as $vehicle): ?>

            <tr>

                <td><?= htmlspecialchars($vehicle['stock_number'] ?? '-'); ?></td>

                <td><?= htmlspecialchars($vehicle['make']); ?></td>

                <td><?= htmlspecialchars($vehicle['model']); ?></td>

                <td><?= (int)$vehicle['year']; ?></td>

                <td><?= number_format((int)$vehicle['mileage']); ?> km</td>

                <td>€<?= number_format((float)$vehicle['price'], 2); ?></td>

                <td>

                    <a href="#" class="btn btn-primary">
                        Edit
                    </a>

                </td>

            </tr>

        <?php endforeach; ?>

    <?php endif; ?>

    </tbody>

</table>