<?php


namespace Tests;

use PDO;
use PHPUnit\Framework\TestCase;

class DatabaseTestCase extends TestCase
{
    /**
     * @var PDO
     */
    protected static $pdo;

    protected function setUp(): void
    {
        self::$pdo = new PDO('sqlite::memory:', null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ]);
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
            self::$pdo->query(
                "INSERT INTO  posts ('id','name','slug','created_at','updated_at','content')
                            VALUES (
                            $i,
                            'name',
                            'slug',
                            '1980-10-29 16:49:50',
                            '1980-10-29 16:49:50',
                            'contenue'
                            ) "
            );
        }
    }
}