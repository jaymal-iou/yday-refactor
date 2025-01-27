<?php

namespace App\Model;

class PermissionCheckResponseModel
{
    public function __construct(private bool $hasPermission)
    {
    }

    public function hasPermission(): bool
    {
        return $this->hasPermission;
    }
}