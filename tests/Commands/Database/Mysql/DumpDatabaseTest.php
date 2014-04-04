<?php

use BigName\DatabaseBackup\Commands\Database\Mysql\DumpDatabase;
use BigName\DatabaseBackup\Connections\MysqlConnection;
use Mockery as m;

class DumpDatabaseTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $shell = m::mock('BigName\DatabaseBackup\ShellProcessing\ShellProcessor');
        $mysql = new MysqlConnection('host', 'port', 'username', 'password', 'database');

        /** @noinspection PhpParamsInspection */
        $dump = new DumpDatabase($mysql, 'outputPath', $shell);

        $this->assertInstanceOf('BigName\DatabaseBackup\Commands\Database\Mysql\DumpDatabase', $dump);
    }

    public function test_generates_correct_command()
    {
        $shell = m::mock('BigName\DatabaseBackup\ShellProcessing\ShellProcessor');
        $shell->shouldReceive('process')->with("mysqldump --host='host' --port='port' --user='username' --password='password' 'database' > 'outputPath'")->once();

        $mysql = new MysqlConnection('host', 'port', 'username', 'password', 'database');

        /** @noinspection PhpParamsInspection */
        $dump = new DumpDatabase($mysql, 'outputPath', $shell);
        $dump->execute();
    }
}
