<?php


namespace Tests\Framework\Database;

use App\Framework\Database\Query;
use Tests\DatabaseTestCase;


class QueryTest extends DatabaseTestCase
{
    public function testSimpleQuery()
    {
        $query = (new Query())->from('posts')->select('name');
        $this->assertEquals("SELECT name FROM posts", $query->__toString());
    }

    public function testWithWhere()
    {
        $query = (new Query())
            ->from('posts', 'p')
            ->where('a = :a');
        $this->assertEquals("SELECT * FROM posts as p WHERE (a = :a)", $query->__toString());
    }

    public function testWithWhereAnyParams()
    {
        $query = (new Query())
            ->from('posts', 'p')
            ->where("a = :a OR b = :b", "c = :c");
        $this->assertEquals("SELECT * FROM posts as p WHERE (a = :a OR b = :b) AND (c = :c)", $query->__toString());
        $query1 = (new Query())
            ->from('posts', 'p')
            ->where("a = :a OR b = :b")
            ->where("c = :c");
        $this->assertEquals("SELECT * FROM posts as p WHERE (a = :a OR b = :b) AND (c = :c)", $query1->__toString());
    }

    public function testFetchAll()
    {
        self::CreatePostsTable();
        self::FillPostsTable(100);
        $pdo = self::$pdo;
        $posts = (new  Query($pdo))
            ->from('posts', "p")
            ->count();
        $this->assertEquals(100, $posts);
        $posts = (new  Query($pdo))
            ->from('posts', "p")
            ->where('p.id < :number')
            ->params([
                'number' => 30
            ])
            ->count();
        $this->assertEquals(29, $posts);
    }

    public function testHydrateEntity()
    {
        self::CreatePostsTable();
        self::FillPostsTable(10);
        $posts = (new  Query(self::$pdo))
            ->from("posts", "p")
            ->into(Demo::class)
            ->fetchAll();
       $this->assertEquals('slug',$posts[0]->slug);
    }
}