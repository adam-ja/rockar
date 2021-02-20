<?php

declare(strict_types=1);

namespace Tests\Unit\Repository;

use App\Repository\CsvRepository;
use App\Repository\SimpleResourceFetcher;
use Illuminate\Support\Facades\Config;
use League\Csv\Exception;
use Tests\TestCase;

class CsvRepositoryTest extends TestCase
{
    private CsvRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repo = new CsvRepository(base_path('tests/fixtures/example_csv_data.csv'));
    }

    public function testIsSimpleResourceFetcher(): void
    {
        $this->assertInstanceOf(SimpleResourceFetcher::class, $this->repo);
    }

    public function testThrowsExceptionIfFileDoesNotExist(): void
    {
        $repo = new CsvRepository(base_path('no/such/file.csv'));

        $this->expectException(Exception::class);

        $repo->byIdentifier('some_field', 'some identifier', ['some_field']);
    }

    public function testReturnsNullIfNoRecordsMatchIdentifier(): void
    {
        $this->assertNull($this->repo->byIdentifier('alias', 'Hulk', ['forename', 'surname']));
    }

    /**
     * @dataProvider provideMatches
     *
     * @param string $identifierField
     * @param string $identifier
     * @param array $record
     */
    public function testReturnsRecordMatchingIdentifier(
        string $identifierField,
        string $identifier,
        array $record
    ): void {
        $this->assertEquals($record, $this->repo->byIdentifier($identifierField, $identifier, [
            'email',
            'forename',
            'surname',
            'alias',
        ]));
    }

    public function provideMatches(): array
    {
        $ironMan = [
            'email'    => 'tony.stark@avengers.com',
            'forename' => 'Tony',
            'surname'  => 'Stark',
            'alias'    => 'Iron Man',
        ];

        $thor = [
            'email'    => 'hammer_time@asgard.net',
            'forename' => 'Thor',
            'surname'  => 'Odinson',
            'alias'    => 'God of Thunder',
        ];

        return [
            'Iron Man by email' => ['email', 'tony.stark@avengers.com', $ironMan],
            'Iron Man by alias' => ['alias', 'Iron Man', $ironMan],
            'Thor by forename'  => ['forename', 'Thor', $thor],
            'Thor by surname'   => ['surname', 'Odinson', $thor],
        ];
    }

    public function testReturnsFirstOfMultipleMatches(): void
    {
        $this->assertEquals([
            'email'    => 'pparker616@esu.edu',
            'forename' => 'Peter',
            'surname'  => 'Parker',
            'alias'    => 'Spider-Man',
        ], $this->repo->byIdentifier('alias', 'Spider-Man', [
            'email',
            'forename',
            'surname',
            'alias',
        ]));
    }

    public function testReturnsOnlyRequestedFields(): void
    {
        $this->assertEquals([
            'forename' => 'Natasha',
            'surname'  => 'Romanoff',
        ], $this->repo->byIdentifier('alias', 'Black Widow', ['forename', 'surname']));
    }
}
