<?php


namespace Tests;

use App\Blog\Table\PostTable;
use App\Framework\Database\Table;
use DateTime;
use PDO;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class DatabaseTestCase extends TestCase
{

    /**
     * @var PDO
     */
    protected static $pdo;
     /**
     * @var PostTable
     */
    protected static $table;

    protected function setUp(): void
    {
        self::$pdo = new  PDO('sqlite::memory:', null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ]);
        self::$pdo->exec("CREATE TABLE test (
           id integer primary key autoincrement ,
           name varchar(255)
        )");
        self::$table = new PostTable(self::$pdo);
    }

    public static function CreatePostsTable()
    {
        self::$pdo->query("CREATE TABLE posts (
                                'id' SMALLINT UNSIGNED NOT NULL,
                                'name' VARCHAR(40),
                                'slug' VARCHAR(40),
                                'content' VARCHAR(250),
                                'created_at' DATETIME,
                                'updated_at' DATETIME
                                )"
        );
    }

    public static function CreateCategoriesTable()
    {
        self::$pdo->query("CREATE TABLE categories (
                                'id' SMALLINT UNSIGNED NOT NULL,
                                'name' VARCHAR(40),
                                'slug' VARCHAR(40)   )"
        );
    }

    public static function FillPostsTable(int $taille = 1)
    {
        for ($i = 1; $i <= $taille; $i++) {
            $date = new DateTime("200$i-01-01");
            self::$table->insert([
                'id' => $i,
                'name' => "name $i",
                'slug' => "slug $i",
                'created_at' => $date->format("Y-m-d h:i:s"),
                'updated_at' => $date->format("Y-m-d h:i:s"),
                'content' => "contenue $i"
                ]);
        }
    }
}