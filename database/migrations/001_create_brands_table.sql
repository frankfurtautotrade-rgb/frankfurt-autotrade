CREATE TABLE brands (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL,
    logo VARCHAR(255) DEFAULT NULL,
    country VARCHAR(100) DEFAULT NULL,

    is_active TINYINT(1) NOT NULL DEFAULT 1,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT uq_brands_name UNIQUE (name),
    CONSTRAINT uq_brands_slug UNIQUE (slug),

    INDEX idx_brands_active (is_active),
    INDEX idx_brands_country (country)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;