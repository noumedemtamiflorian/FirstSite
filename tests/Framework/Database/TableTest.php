<?php


namespace Tests\Framework\Database;
require dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";


use App\Framework\Database\Table;
use PDO;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use stdClass;

class TableTest extends TestCase
{
    /**
     * @var Table
     */
    private $table;
    /**
     * @var PDO
     */
    private $pdo;

    public function setUp(): void
    {
        $pdo = new  PDO('sqlite::memory:', null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ]);
        $pdo->exec("CREATE TABLE test (
           id integer primary key autoincrement ,
           name varchar(255)
        )");
        $this->pdo = $pdo;
        $this->table = new Table($this->pdo);
        $reflection = new ReflectionClass($this->table);
        $property = $reflection->getProperty('table');
        $property->setAccessible(true);
        $property->setValue($this->table, 'test');
    }

    public function testInsert()
    {
        $this->table->insert(['name' => 'je suis la']);
        $this->table->insert(['name' => 'et toi']);
        $test1 = $this->table->find(1);
        $test2 = $this->table->find(2);
        $this->assertEquals('je suis la', $test1->name);
        $this->assertEquals('et toi', $test2->name);
    }

    public function testFind()
    {
        $this->table->insert(['name' => 'a1']);
        $this->table->insert(['name' => 'a2']);
        $test = $this->table->find(1);
        $this->assertInstanceOf(stdClass::class, $test);
        $this->assertEquals('a1', $test->name);
        $test1 = $this->table->find(2);
        $this->assertInstanceOf(stdClass::class, $test1);
        $this->assertEquals('a2', $test1->name);
    }

    public function testUpadate()
    {
        $this->table->insert(['name' => 'a1']);
        $this->table->insert(['name' => 'a2']);
        $this->table->update(1, ['name' => 'qq1']);
        $this->table->update(2, ['name' => 'qq2']);
        $test = $this->table->find(1);
        $this->assertInstanceOf(stdClass::class, $test);
        $this->assertEquals('qq1', $test->name);
        $test1 = $this->table->find(2);
        $this->assertInstanceOf(stdClass::class, $test1);
        $this->assertEquals('qq2', $test1->name);
    }

    public function testDelete()
    {
        $this->table->insert(['name' => 'a1']);
        $this->table->insert(['name' => 'a2']);
        $this->table->insert(['name' => 'a3']);
        $count = (int)$this->table->getPdo()->query('SELECT COUNT(id) FROM test')->fetchColumn();
        $this->assertEquals(3, $count);
        $this->table->delete(1);
        $test = $this->table->find(1);
        $this->assertEquals(null, $test);
        $count = (int)$this->table->getPdo()->query('SELECT COUNT(id) FROM test')->fetchColumn();
        $this->assertEquals(2, $count);
    }

    public function testFinList()
    {
        $this->table->insert(['name' => 'a1']);
        $this->table->insert(['name' => 'a2']);
        $this->assertEquals(['1' => 'a1', '2' => 'a2'], $this->table->findList());
    }

    public function testExists()
    {
        $this->table->insert(['name' => 'a1']);
        $this->table->insert(['name' => 'a2']);
        $this->assertTrue($this->table->exists(1));
        $this->assertTrue($this->table->exists(2));
        $this->assertFalse($this->table->exists(3));
    }
}