<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\PermissionCheckResponseModel;
use App\Provider\TokenDataProvider;

class PermissionService
{
    public const NO_TOKEN = "kein_token";
    public const PERMISSION_READ = "read";

    public function __construct(private TokenDataProvider $tokenDataProvider)
    {

    }

    public function checkPermission(string $tokenId): PermissionCheckResponseModel
    {
        if ($tokenId === self::NO_TOKEN) {
            return new PermissionCheckResponseModel(false);
        }

        $tokens = $this->tokenDataProvider->getTokens();

        $foundToken = $this->findTokenById($tokens, $tokenId);

        if (!$foundToken) {
            return new PermissionCheckResponseModel(false);
        }

        if ($this->readPermissionExists($foundToken)) {
            return new PermissionCheckResponseModel(true);
        }

        return new PermissionCheckResponseModel(false);
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