<?php

declare(strict_types=1);

namespace App\Repository;

interface SimpleResourceFetcher
{
    public function byIdentifier(string $identifierField, string $identifier, array $fields): ?array;
}
