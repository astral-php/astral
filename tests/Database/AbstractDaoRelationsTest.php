<?php

declare(strict_types=1);

namespace Tests\Database;

use App\Dao\ArticleDao;
use App\Dao\CategoryDao;
use App\Models\Article;
use App\Models\Category;
use Database\Connection;
use PDO;
use PHPUnit\Framework\TestCase;

/**
 * Tests d'intégration des helpers de relations de AbstractDao (ORM léger).
 *
 * Vérifie hasMany(), belongsTo() et les méthodes métier qui s'appuient dessus
 * (articlesOf, findWithArticles, categoryOf, findWithCategory) avec SQLite en mémoire.
 */
final class AbstractDaoRelationsTest extends TestCase
{
    private PDO          $pdo;
    private CategoryDao  $categoryDao;
    private ArticleDao   $articleDao;

    protected function setUp(): void
    {
        Connection::reset();

        $this->pdo = Connection::getInstance([
            'driver'   => 'sqlite',
            'database' => ':memory:',
        ]);

        $this->createTables();
        $this->categoryDao = new CategoryDao($this->pdo);
        $this->articleDao  = new ArticleDao($this->pdo);
    }

    private function createTables(): void
    {
        $this->pdo->exec(<<<'SQL'
            CREATE TABLE IF NOT EXISTS categories (
                id         INTEGER  PRIMARY KEY,
                name       TEXT     NOT NULL,
                slug       TEXT     NOT NULL UNIQUE,
                created_at TEXT     NOT NULL DEFAULT (datetime('now'))
            )
        SQL);
        $this->pdo->exec(<<<'SQL'
            CREATE TABLE IF NOT EXISTS articles (
                id          INTEGER  PRIMARY KEY,
                category_id INTEGER  NULL,
                title       TEXT     NOT NULL,
                slug        TEXT     NOT NULL UNIQUE,
                body        TEXT     NOT NULL DEFAULT '',
                status      TEXT     NOT NULL DEFAULT 'draft',
                created_at  TEXT     NOT NULL DEFAULT (datetime('now'))
            )
        SQL);
    }

    // -------------------------------------------------------------------------
    // hasMany — une catégorie possède plusieurs articles
    // -------------------------------------------------------------------------

    public function testHasManyReturnsEmptyWhenNoChildren(): void
    {
        $categoryId = $this->categoryDao->insert([
            'name'       => 'Tech',
            'slug'       => 'tech',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $articles = $this->categoryDao->articlesOf($categoryId);

        $this->assertIsArray($articles);
        $this->assertCount(0, $articles);
    }

    public function testHasManyReturnsRelatedEntities(): void
    {
        $categoryId = $this->categoryDao->insert([
            'name'       => 'News',
            'slug'       => 'news',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->articleDao->insert([
            'category_id' => $categoryId,
            'title'      => 'First',
            'slug'       => 'first',
            'body'       => 'Body 1',
            'status'     => 'published',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $this->articleDao->insert([
            'category_id' => $categoryId,
            'title'      => 'Second',
            'slug'       => 'second',
            'body'       => 'Body 2',
            'status'     => 'draft',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $articles = $this->categoryDao->articlesOf($categoryId);

        $this->assertCount(2, $articles);
        $this->assertContainsOnlyInstancesOf(Article::class, $articles);
        $titles = array_map(fn(Article $a) => $a->title, $articles);
        $this->assertContains('First', $titles);
        $this->assertContains('Second', $titles);
    }

    public function testFindWithArticlesReturnsCategoryAndRelatedArticles(): void
    {
        $categoryId = $this->categoryDao->insert([
            'name'       => 'Blog',
            'slug'       => 'blog',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $this->articleDao->insert([
            'category_id' => $categoryId,
            'title'      => 'Only post',
            'slug'       => 'only-post',
            'body'       => 'Content',
            'status'     => 'published',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $result = $this->categoryDao->findWithArticles($categoryId);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('category', $result);
        $this->assertArrayHasKey('articles', $result);
        $this->assertInstanceOf(Category::class, $result['category']);
        $this->assertSame('Blog', $result['category']->name);
        $this->assertCount(1, $result['articles']);
        $this->assertSame('Only post', $result['articles'][0]->title);
    }

    public function testFindWithArticlesReturnsNullWhenCategoryNotFound(): void
    {
        $result = $this->categoryDao->findWithArticles(99999);

        $this->assertNull($result);
    }

    // -------------------------------------------------------------------------
    // belongsTo — un article appartient à une catégorie
    // -------------------------------------------------------------------------

    public function testBelongsToReturnsNullWhenForeignIdZero(): void
    {
        $articleId = $this->articleDao->insert([
            'category_id' => 0,
            'title'       => 'Orphan',
            'slug'        => 'orphan',
            'body'        => '',
            'status'      => 'draft',
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        $article  = $this->articleDao->findById($articleId);
        $this->assertInstanceOf(Article::class, $article);
        $category = $this->articleDao->categoryOf($article);

        $this->assertNull($category);
    }

    public function testBelongsToReturnsParentEntity(): void
    {
        $categoryId = $this->categoryDao->insert([
            'name'       => 'Dev',
            'slug'       => 'dev',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $articleId = $this->articleDao->insert([
            'category_id' => $categoryId,
            'title'       => 'PHP 8',
            'slug'        => 'php-8',
            'body'        => 'Content',
            'status'      => 'published',
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        $article  = $this->articleDao->findById($articleId);
        $this->assertInstanceOf(Article::class, $article);
        $category = $this->articleDao->categoryOf($article);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertSame('Dev', $category->name);
        $this->assertSame($categoryId, $category->id);
    }

    public function testFindWithCategoryReturnsArticleAndCategory(): void
    {
        $categoryId = $this->categoryDao->insert([
            'name'       => 'Docs',
            'slug'       => 'docs',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $articleId = $this->articleDao->insert([
            'category_id' => $categoryId,
            'title'       => 'Install',
            'slug'        => 'install',
            'body'        => 'Guide',
            'status'      => 'published',
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        $result = $this->articleDao->findWithCategory($articleId);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('article', $result);
        $this->assertArrayHasKey('category', $result);
        $this->assertInstanceOf(Article::class, $result['article']);
        $this->assertInstanceOf(Category::class, $result['category']);
        $this->assertSame('Install', $result['article']->title);
        $this->assertSame('Docs', $result['category']->name);
    }

    public function testFindWithCategoryReturnsNullWhenArticleNotFound(): void
    {
        $result = $this->articleDao->findWithCategory(99999);

        $this->assertNull($result);
    }

    public function testFindWithCategoryReturnsNullCategoryWhenArticleHasNoCategory(): void
    {
        $articleId = $this->articleDao->insert([
            'category_id' => 0,
            'title'       => 'No cat',
            'slug'        => 'no-cat',
            'body'        => '',
            'status'      => 'draft',
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        $result = $this->articleDao->findWithCategory($articleId);

        $this->assertIsArray($result);
        $this->assertSame('No cat', $result['article']->title);
        $this->assertNull($result['category']);
    }

    protected function tearDown(): void
    {
        Connection::reset();
    }
}
