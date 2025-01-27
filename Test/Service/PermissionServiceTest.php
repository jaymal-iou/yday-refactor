<?php

namespace Test\Service;

use App\Provider\TokenDataProvider;
use App\Service\PermissionService;
use PHPUnit\Framework\TestCase;

class PermissionServiceTest extends TestCase
{
    public function testCheckPermissionForValidToken(): void
    {
        $permissionService = new PermissionService(
            $this->TokenDataProviderMock(),
        );

        $response = $permissionService->checkPermission('tokenReadonly');

        $this->assertTrue($response->hasPermission());
    }

    public function testCheckPermissionForInvalidToken(): void
    {
        $permissionService = new PermissionService(
            $this->TokenDataProviderMock(),
        );

        $response = $permissionService->checkPermission('invalidToken');

        $this->assertFalse($response->hasPermission());
    }

    private function TokenDataProviderMock(): TokenDataProvider
    {
        $mock = $this->createMock(TokenDataProvider::class);
        $mock->method('getTokens')->willReturn([['token' => 'tokenReadonly', 'permissions' => ['read']]]);

        return $mock;
    }
}