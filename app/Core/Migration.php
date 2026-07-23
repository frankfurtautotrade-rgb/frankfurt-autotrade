<?php

declare(strict_types=1);

namespace App\Core;

abstract class Migration
{
    /**
     * Run the migration.
     */
    abstract public function up(): void;

    /**
     * Reverse the migration.
     */
    abstract public function down(): void;
}