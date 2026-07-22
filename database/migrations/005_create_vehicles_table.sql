CREATE TABLE vehicles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    uuid CHAR(36) DEFAULT NULL UNIQUE,

    brand_id BIGINT UNSIGNED NOT NULL,
    model_id BIGINT UNSIGNED NOT NULL,
    generation_id BIGINT UNSIGNED DEFAULT NULL,
    variant_id BIGINT UNSIGNED DEFAULT NULL,

    stock_number VARCHAR(30) NOT NULL,
    vin CHAR(17) NOT NULL,

    first_registration DATE DEFAULT NULL,
    model_year SMALLINT UNSIGNED DEFAULT NULL,

    mileage INT UNSIGNED DEFAULT 0,

    exterior_color VARCHAR(100) DEFAULT NULL,
    interior_color VARCHAR(100) DEFAULT NULL,

    body_type VARCHAR(50) DEFAULT NULL,

    doors TINYINT UNSIGNED DEFAULT NULL,
    seats TINYINT UNSIGNED DEFAULT NULL,

    purchase_price DECIMAL(12,2) DEFAULT NULL,
    additional_costs DECIMAL(12,2) DEFAULT 0.00,
    sale_price DECIMAL(12,2) DEFAULT NULL,

    vat_type ENUM(
        'Gross',
        'Net',
        'VAT Deductible'
    ) DEFAULT 'Gross',

    status ENUM(
        'Draft',
        'Available',
        'Reserved',
        'Sold',
        'Export',
        'Archived'
    ) DEFAULT 'Draft',

    description_de TEXT,
    description_en TEXT,

    is_featured TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_vehicle_brand
        FOREIGN KEY (brand_id)
        REFERENCES brands(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT fk_vehicle_model
        FOREIGN KEY (model_id)
        REFERENCES models(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT fk_vehicle_generation
        FOREIGN KEY (generation_id)
        REFERENCES generations(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT fk_vehicle_variant
        FOREIGN KEY (variant_id)
        REFERENCES variants(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT uq_vehicle_stock UNIQUE (stock_number),
    CONSTRAINT uq_vehicle_vin UNIQUE (vin),

    INDEX idx_status (status),
    INDEX idx_brand (brand_id),
    INDEX idx_model (model_id),
    INDEX idx_variant (variant_id),
    INDEX idx_price (sale_price),
    INDEX idx_mileage (mileage),
    INDEX idx_featured (is_featured)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;