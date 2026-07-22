CREATE TABLE variants (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    generation_id BIGINT UNSIGNED NOT NULL,

    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL,

    fuel_type ENUM(
        'Petrol',
        'Diesel',
        'Hybrid',
        'Plug-in Hybrid',
        'Electric',
        'LPG',
        'CNG',
        'Hydrogen'
    ) DEFAULT NULL,

    transmission ENUM(
        'Manual',
        'Automatic',
        '9G-TRONIC',
        '8G-DCT',
        'CVT',
        'Other'
    ) DEFAULT NULL,

    drivetrain ENUM(
        'FWD',
        'RWD',
        'AWD',
        '4MATIC'
    ) DEFAULT NULL,

    engine_code VARCHAR(50) DEFAULT NULL,

    displacement DECIMAL(3,1) DEFAULT NULL,

    power_hp SMALLINT UNSIGNED DEFAULT NULL,

    power_kw SMALLINT UNSIGNED DEFAULT NULL,

    is_active TINYINT(1) NOT NULL DEFAULT 1,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_variants_generation
        FOREIGN KEY (generation_id)
        REFERENCES generations(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT uq_variant
        UNIQUE (generation_id, slug),

    INDEX idx_variants_generation (generation_id),
    INDEX idx_variants_active (is_active)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;