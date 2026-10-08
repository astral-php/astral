<?php

declare(strict_types=1);

namespace Tests\Http;

/**
 * Smokes HTTP du hub astral (démo) — étendu au hub 1.2.5.
 * Nécessite astral-core ≥ 1.2.4 (Router::handle).
 */
final class SmokeRoutesTest extends HttpSmokeTestCase
{
    public function testHomeReturns200(): void
    {
        $response = $this->get('/');
        $this->assertSame(200, $response->getStatus());
    }

    public function testLoginFormReturns200(): void
    {
        $response = $this->get('/login');
        $this->assertSame(200, $response->getStatus());
    }

    public function testDocsReturns200(): void
    {
        $response = $this->get('/docs');
        $this->assertSame(200, $response->getStatus());
    }

    public function testProfileWithoutAuthRedirects(): void
    {
        $response = $this->get('/profile');
        $this->assertSame(302, $response->getStatus());
    }
}
