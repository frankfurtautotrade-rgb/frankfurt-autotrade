<h2>Add Vehicle</h2>

<form action="/admin/vehicles/store" method="POST">

    <p>
        <label>Make</label><br>
        <input type="text" name="make" required>
    </p>

    <p>
        <label>Model</label><br>
        <input type="text" name="model" required>
    </p>

    <p>
        <label>Year</label><br>
        <input type="number" name="year" min="1900" max="2100" required>
    </p>

    <p>
        <label>Mileage (km)</label><br>
        <input type="number" name="mileage" min="0" required>
    </p>

    <p>
        <label>Price (€)</label><br>
        <input type="number" name="price" step="0.01" required>
    </p>

    <p>
        <button type="submit">Save Vehicle</button>
    </p>

</form>