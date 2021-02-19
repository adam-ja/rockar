<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\EnforceJson;
use Illuminate\Http\Request;
use Tests\TestCase;

class EnforceJsonTest extends TestCase
{
    public function testAddsAcceptJsonHeaderToRequest(): void
    {
        $request    = new Request();
        $middleware = new EnforceJson();

        $this->assertSame($request, $middleware->handle($request, fn (Request $request) => $request));

        $this->assertTrue($request->headers->has('Accept'));
        $this->assertSame('application/json', $request->headers->get('Accept'));
    }
}
