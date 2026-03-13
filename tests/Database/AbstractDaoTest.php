<?php

declare(strict_types=1);

namespace Tests\Database;

use App\Dao\UserDao;
use Database\Connection;
use PDO;
use PHPUnit\Framework\TestCase;

/**
 * Tests d'intégration du DAO en mémoire (SQLite :memory:).
 */
final class AbstractDaoTest extends TestCase
{
    private PDO     $pdo;
    private UserDao $dao;

    protected function setUp(): void
    {
        Connection::reset();

        $this->pdo = Connection::getInstance([
            'driver'   => 'sqlite',
            'database' => ':memory:',
        ]);

        $this->dao = new UserDao($this->pdo);
        $this->dao->createTableIfNotExists();
    }

    public function testInsertAndFindById(): void
    {
        $id   = $this->dao->createUser('Alice', 'alice@example.com', 'secret123');
        $user = $this->dao->findById($id);

        $this->assertNotNull($user);
        $this->assertSame('Alice', $user->name);
        $this->assertSame('alice@example.com', $user->email);
    }

    public function testFindAll(): void
    {
        $this->dao->createUser('Bob', 'bob@example.com', 'pass1');
        $this->dao->createUser('Carol', 'carol@example.com', 'pass2');

        $users = $this->dao->findAll();
        $this->assertCount(2, $users);
    }

    public function testUpdate(): void
    {
        $id = $this->dao->createUser('Dave', 'dave@example.com', 'pass3');
        $this->dao->update($id, ['name' => 'David']);

        $user = $this->dao->findById($id);
        $this->assertSame('David', $user->name);
    }

    public function testDelete(): void
    {
        $id      = $this->dao->createUser('Eve', 'eve@example.com', 'pass4');
        $deleted = $this->dao->delete($id);

        $this->assertSame(1, $deleted);
        $this->assertNull($this->dao->findById($id));
    }

    public function testAuthenticate(): void
    {
        $this->dao->createUser('Frank', 'frank@example.com', 'mypassword');

        $user = $this->dao->authenticate('frank@example.com', 'mypassword');
        $this->assertNotNull($user);
        $this->assertSame('Frank', $user->name);

        $bad = $this->dao->authenticate('frank@example.com', 'wrongpassword');
        $this->assertNull($bad);
    }

    protected function tearDown(): void
    {
        Connection::reset();
    }
}
