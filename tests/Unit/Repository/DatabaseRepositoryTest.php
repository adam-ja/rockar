<?php

declare(strict_types=1);

namespace Tests\Unit\Repository;

use App\Repository\DatabaseRepository;
use App\Repository\SimpleResourceFetcher;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Mockery;
use Mockery\MockInterface;
use stdClass;
use Tests\TestCase;

class DatabaseRepositoryTest extends TestCase
{
    private MockInterface|QueryBuilder $queryBuilder;
    private MockInterface $dbMock;
    private DatabaseRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->queryBuilder = $this->mock(Builder::class);
        $this->dbMock       = DB::partialMock();
        $this->repo         = new DatabaseRepository('customers');

        $this->queryBuilder->shouldIgnoreMissing($this->queryBuilder);
    }

    public function testIsSimpleResourceFetcher(): void
    {
        $this->assertInstanceOf(SimpleResourceFetcher::class, $this->repo);
    }

    public function testSelectsFromExpectedTable(): void
    {
        $this->dbMock->expects()->table('customers')->andReturns($this->queryBuilder);

        $this->repo->byIdentifier('forename', 'Adam', ['surname']);
    }

    public function testFindsRecordMatchingGivenIdentifier(): void
    {
        $this->dbMock->allows()->table(Mockery::any())->andReturns($this->queryBuilder);
        $this->queryBuilder->expects()->where('email', 'john.smith@gmail.com')->andReturns($this->queryBuilder);

        $this->repo->byIdentifier('email', 'john.smith@gmail.com', ['forename', 'surname']);
    }

    public function testSelectsOnlyTheRequestedFieldsFromTheFirstMatchingRecord(): void
    {
        $this->dbMock->allows()->table(Mockery::any())->andReturns($this->queryBuilder);
        $this->queryBuilder->expects()->first(['forename', 'surname']);

        $this->repo->byIdentifier('email', 'john.smith@gmail.com', ['forename', 'surname']);
    }

    public function testReturnsNullIfNoRecordsMatchIdentifier(): void
    {
        $this->dbMock->allows()->table(Mockery::any())->andReturns($this->queryBuilder);
        $this->queryBuilder->expects()->first(Mockery::any())->andReturns(null);

        $this->assertNull($this->repo->byIdentifier('email', 'john.smith@gmail.com', ['forename', 'surname']));
    }

    public function testReturnsMatchingRecordAsArray(): void
    {
        $match = new stdClass();
        $match->forename = 'John';
        $match->surname  = 'Smith';

        $this->dbMock->allows()->table(Mockery::any())->andReturns($this->queryBuilder);
        $this->queryBuilder->expects()->first(Mockery::any())->andReturns($match);

        $this->assertEquals([
            'forename' => 'John',
            'surname'  => 'Smith',
        ], $this->repo->byIdentifier('email', 'john.smith@gmail.com', ['forename', 'surname']));
    }
}
