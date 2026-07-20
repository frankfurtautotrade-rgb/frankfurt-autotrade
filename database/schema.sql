CREATE TABLE vehicles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    stock_number VARCHAR(30) NOT NULL UNIQUE,

    vin VARCHAR(17) UNIQUE,

    make VARCHAR(100) NOT NULL,
    model VARCHAR(100) NOT NULL,
    variant VARCHAR(150),

    body_type VARCHAR(100),

    first_registration DATE,
    model_year SMALLINT,

    mileage INT UNSIGNED DEFAULT 0,

    fuel_type VARCHAR(50),
    transmission VARCHAR(50),

    engine_size INT,
    power_kw SMALLINT,
    power_hp SMALLINT,

    exterior_color VARCHAR(100),
    interior_color VARCHAR(100),

    doors TINYINT,
    seats TINYINT,

    emission_class VARCHAR(50),
    co2_emission DECIMAL(6,2),

    previous_owners TINYINT DEFAULT 0,

    accident_free BOOLEAN DEFAULT TRUE,
    service_history BOOLEAN DEFAULT FALSE,

    tuv_until DATE,

    purchase_price DECIMAL(12,2),

    sale_price DECIMAL(12,2),

    export_price DECIMAL(12,2),

    vat_type ENUM(
        'Differenzbesteuerung',
        'MwSt. ausweisbar',
        'Netto Export'
    ) DEFAULT 'Differenzbesteuerung',

    status ENUM(
        'Im Bestand',
        'Reserviert',
        'Verkauft',
        'Export',
        'Ausgeliefert'
    ) DEFAULT 'Im Bestand',

    registration_country CHAR(2) DEFAULT 'DE',

    featured BOOLEAN DEFAULT FALSE,

    published BOOLEAN DEFAULT FALSE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);