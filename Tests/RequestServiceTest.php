<?php

use Hanwoolderink88\ApiForm\Src\ApiFormBadRequestException;
use Hanwoolderink88\ApiForm\Src\RequestService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestServiceTest extends TestCase
{
    public function testBodyRequest(): void
    {
        $content = (string)json_encode([
            'foo' => 'bar',
        ]);
        $request = new Request([], [], [], [], [], [], $content);
        $requestStack = new  RequestStack();
        $requestStack->push($request);

        $service = new RequestService($requestStack);
        $value = $service->getBody('foo');

        $this->assertSame($value, 'bar', 'RequestService does not return the right value');
    }

    public function testParsedBodyRequest(): void
    {
        $content = [
            'foo' => 'bar',
        ];
        /** @phpstan-ignore-next-line */
        $request = new Request([], [], [], [], [], [], $content);
        $requestStack = new  RequestStack();
        $requestStack->push($request);

        $service = new RequestService($requestStack);
        $value = $service->getBody('foo');

        $this->assertSame($value, 'bar', 'RequestService does not return the right value');
    }

    public function testQueryString(): void
    {
        $qs = [
            'limit' => 100,
            'page' => 1,
            'foo' => 'bar'
        ];
        $request = new Request($qs, [], [], [], [], [], null);
        $requestStack = new  RequestStack();
        $requestStack->push($request);

        $service = new RequestService($requestStack);
        $page = $service->getPage();
        $page2 = $service->get('page');
        $limit = $service->getLimit();
        $foo = $service->getQuery('foo');

        $this->assertSame($page, 1, 'RequestService does not return the right page value');
        $this->assertSame($page2, 1, 'RequestService does not return the right page value');
        $this->assertSame($limit, 100, 'RequestService does not return the right limit value');
        $this->assertSame($foo, 'bar', 'RequestService does not return the right query string value');
    }

    public function testLimitToHigh(): void
    {
        $qs = ['limit' => 500];
        $request = new Request($qs, [], [], [], [], [], null);
        $requestStack = new  RequestStack();
        $requestStack->push($request);

        $service = new RequestService($requestStack);

        $this->expectException(ApiFormBadRequestException::class);
        $service->getLimit();
    }
}
