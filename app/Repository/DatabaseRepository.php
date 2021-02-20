<?php

declare(strict_types=1);

namespace App\Repository;

use Illuminate\Support\Facades\DB;

class DatabaseRepository implements SimpleResourceFetcher
{
    public function __construct(private string $table)
    {
    }

    public function byIdentifier(string $identifierField, string $identifier, array $fields): ?array
    {
        $result = DB::table($this->table)
            ->where($identifierField, $identifier)
            ->first($fields);

        return $result ? (array) $result : null;
    }
}
