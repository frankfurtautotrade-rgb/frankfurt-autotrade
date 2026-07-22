CREATE TABLE models (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    brand_id BIGINT UNSIGNED NOT NULL,

    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL,

    is_active TINYINT(1) NOT NULL DEFAULT 1,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_models_brand
        FOREIGN KEY (brand_id)
        REFERENCES brands(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT uq_models_brand_name
        UNIQUE (brand_id, name),

    CONSTRAINT uq_models_brand_slug
        UNIQUE (brand_id, slug),

    INDEX idx_models_brand (brand_id),
    INDEX idx_models_active (is_active)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;