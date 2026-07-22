CREATE TABLE generations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    model_id BIGINT UNSIGNED NOT NULL,

    code VARCHAR(30) NOT NULL,
    name VARCHAR(100) DEFAULT NULL,

    production_from SMALLINT UNSIGNED DEFAULT NULL,
    production_to SMALLINT UNSIGNED DEFAULT NULL,

    is_active TINYINT(1) NOT NULL DEFAULT 1,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_generations_model
        FOREIGN KEY (model_id)
        REFERENCES models(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT uq_generations_model_code
        UNIQUE (model_id, code),

    INDEX idx_generations_model (model_id),
    INDEX idx_generations_active (is_active)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;