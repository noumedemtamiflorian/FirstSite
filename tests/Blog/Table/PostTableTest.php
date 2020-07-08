<?php


namespace Tests\Blog\Table;

use App\Blog\Entity\Post;
use App\Blog\Table\PostTable;
use Tests\DatabaseTestCase;

require dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";


class PostTableTest extends DatabaseTestCase
{
    /**
     * @var PostTable
     */
    private $postTable;

    public function setUp(): void
    {
        parent::setUp();
        $pdo = $this->getPDO();
        $this->migrateDatabase($pdo);
        $this->postTable = new PostTable($pdo);
    }

    public function testFind()
    {
        $this->seedDatabase($this->postTable->getPdo());
        $post = $this->postTable->find(1);
        $this->assertInstanceOf(Post::class, $post);
    }

    public function testFindNotFoundRecord()
    {
        $post = $this->postTable->find(1);
        $this->assertNull($post);
    }

    public function testUpdate()
    {
        $this->seedDatabase($this->postTable->getPdo());
        $this->postTable->update(
            1,
            [
                'name' => 'salut',
                'slug' => 'demo'
            ]);
        $post = $this->postTable->find(1);
        $this->assertEquals('salut', $post->name);
        $this->assertEquals('demo', $post->slug);
    }

    public function testInsert()
    {
        $this->postTable->insert([
            'name' => 'Salut',
            'slug' => 'demo',
            'content' => 'contenue',
            'created_at' => '2007-06-28 05:21:40',
            'updated_at' => '2007-06-28 05:21:40'
        ]);
        $post = $this->postTable->find(1);
        $this->assertEquals('Salut', $post->name);
        $this->assertEquals('demo', $post->slug);
        $this->assertEquals('contenue', $post->content);
    }

    public function delete()
    {
        $this->postTable->insert([
            'name' => 'Salut',
            'slug' => 'demo',
            'content' => 'contenue',
            'created_at' => '2007-06-28 05:21:40',
            'updated_at' => '2007-06-28 05:21:40'
        ]);
        $this->postTable->insert([
            'name' => 'Salut',
            'slug' => 'demo',
            'content' => 'contenue',
            'created_at' => '2007-06-28 05:21:40',
            'updated_at' => '2007-06-28 05:21:40'
        ]);
        $count = $this->pdo->query('SELECT COUNT(id) FROM posts ')->fetchColumn();
        $this->assertEquals(2, (int)$count);
        $this->postTable->delete($this->pdo->lastInsertId());
        $count = $this->pdo->query('SELECT COUNT(id) FROM posts ')->fetchColumn();
        $this->assertEquals(1,(int)$count);
    }
}