<?php

declare(strict_types=1);

namespace Test\Handler;

use App\Handler\PermissionHandler;
use App\Model\PermissionCheckResponseModel;
use App\Service\PermissionService;
use PHPUnit\Framework\TestCase;
use ProgPhil1337\SimpleReactApp\HTTP\Response\JSONResponse;
use ProgPhil1337\SimpleReactApp\HTTP\Routing\RouteParameters;
use Psr\Http\Message\ServerRequestInterface;

class PermissionHandlerTest extends TestCase
{
    public function testTokenHasPermission(): void
    {
        $permissionService = $this->createPermissionServiceMock(true);

        $handler = new PermissionHandler($permissionService);

        $serverRequest = $this->createServerRequestMock();
        $routeParameters = $this->createRouteParametersMock('token1234');

        $response = $handler($serverRequest, $routeParameters);

        $this->assertInstanceOf(JSONResponse::class, $response);
        $this->assertEquals(200, $response->getCode());

        $this->assertEquals(['permission' => true], json_decode($response->getContent(), true));
    }

    public function testTokenHasNoPermission(): void
    {
        $permissionService = $this->createPermissionServiceMock(false);

        $handler = new PermissionHandler($permissionService);

        $serverRequest = $this->createServerRequestMock();
        $routeParameters = $this->createRouteParametersMock('invalid_token');

        $response = $handler($serverRequest, $routeParameters);

        $this->assertInstanceOf(JSONResponse::class, $response);
        $this->assertEquals(401, $response->getCode());

        $this->assertEquals(['permission' => false], json_decode($response->getContent(), true));
    }

    public function testNoToken(): void
    {
        $permissionService = $this->createPermissionServiceMock(false);

        $handler = new PermissionHandler($permissionService);

        $serverRequest = $this->createServerRequestMock();
        $routeParameters = $this->createRouteParametersMock('kein_token');

        $response = $handler($serverRequest, $routeParameters);

        $this->assertInstanceOf(JSONResponse::class, $response);
        $this->assertEquals(401, $response->getCode());

        $this->assertEquals(['permission' => false], json_decode($response->getContent(), true));
    }

    private function createPermissionServiceMock(bool $value): PermissionService
    {
        $permissionService = $this->createMock(PermissionService::class);
        $permissionService->expects($this->once())
            ->method('checkPermission')
            ->willReturn(new PermissionCheckResponseModel($value));

        return $permissionService;
    }

    private function createServerRequestMock(): ServerRequestInterface
    {
        return $this->createMock(ServerRequestInterface::class);
    }

    private function createRouteParametersMock(string $token): RouteParameters
    {
        $routeParameters = $this->createMock(RouteParameters::class);

        $routeParameters->expects($this->once())
            ->method('get')
            ->willReturn($token);

        return $routeParameters;
    }
}
