<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\PermissionCheckResponseModel;
use App\Provider\TokenDataProvider;

class PermissionService
{
    public const PERMISSION_READ = "read";

    public function __construct(private TokenDataProvider $tokenDataProvider)
    {

    }

    public function checkPermission(string $tokenId): PermissionCheckResponseModel
    {
        $foundToken = $this->findTokenById($this->tokenDataProvider->getTokens(), $tokenId);

        return new PermissionCheckResponseModel(
            $foundToken &&
            $this->readPermissionExists($foundToken)
        );
    }

    private function findTokenById(array $tokens, string $tokenId): ?array
    {
        if (!$tokens) {
            return null;
        }

        foreach ($tokens as $token) {
            if ($token["token"] === $tokenId) {
                return $token;
            }
        }
        return null;
    }

    private function readPermissionExists(array $token): bool
    {
        return in_array(self::PERMISSION_READ, $token["permissions"]);
    }
}