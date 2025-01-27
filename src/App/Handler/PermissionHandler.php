<?php

declare(strict_types=1);

namespace App\Handler;

use App\Service\PermissionService;
use ProgPhil1337\SimpleReactApp\HTTP\Response\JSONResponse;
use ProgPhil1337\SimpleReactApp\HTTP\Response\ResponseInterface;
use ProgPhil1337\SimpleReactApp\HTTP\Routing\Attribute\Route;
use ProgPhil1337\SimpleReactApp\HTTP\Routing\Handler\HandlerInterface;
use ProgPhil1337\SimpleReactApp\HTTP\Routing\HttpMethod;
use ProgPhil1337\SimpleReactApp\HTTP\Routing\RouteParameters;
use Psr\Http\Message\ServerRequestInterface;

#[Route(httpMethod: HttpMethod::GET, uri: '/has_permission/{token}')]
class PermissionHandler implements HandlerInterface
{
    /**
     * Dependency Injection would be available here
     */
    public function __construct(private PermissionService $permissionService)
    {

    }

    public function __invoke(ServerRequestInterface $serverRequest, RouteParameters $parameters): ResponseInterface
    {
        $tId = $parameters->get("token", "kein_token");

        $response = $this->permissionService->checkPermission($tId);

        $code = $response->hasPermission() ? 200 : 401;

        return new JSONResponse(["permission" => $response->hasPermission()], $code);
    }
}
