<?php

use PHPUnit\Framework\TestCase;

class DbConnectionTest extends TestCase
{
    private $pdo;
    private $dbConn;

    protected function setUp()
    {
        $this->pdo = \Mockery::mock(PDO::class);
        $this->dbConn = new DbConnection($this->pdo);
    }

    public function testExec() {
        $sth = Mockery::mock('sth');
        $this->pdo->shouldReceive('prepare')
            ->with('query1')
            ->once()
            ->andReturn($sth);
        $sth->shouldReceive('execute')
            ->with(['a' => 123])
            ->once();
        $sth->shouldReceive('fetchAll')
            ->with(5)
            ->once()
            ->andReturn([1,2,3]);
        $actual = $this->dbConn->exec('query1', ['a' => 123], 5);
        $this->assertEquals([1,2,3], $actual);
        Mockery::close();
    }
}