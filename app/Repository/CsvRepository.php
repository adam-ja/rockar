<?php

declare(strict_types=1);

namespace App\Repository;

use App\Repository\SimpleResourceFetcher;
use League\Csv\Reader;
use League\Csv\Statement;

class CsvRepository implements SimpleResourceFetcher
{
    public function __construct(private string $pathToCsv)
    {
    }

    public function byIdentifier(string $identifierField, string $identifier): ?array
    {
        $reader = Reader::createFromPath($this->pathToCsv);
        $reader->setHeaderOffset(0);

        $result = Statement::create(fn (array $record) => trim($record[$identifierField] ?? null) === $identifier)
            ->process($reader)
            ->fetchOne();

        return empty($result) ? null : array_map('trim', $result);
    }
}
