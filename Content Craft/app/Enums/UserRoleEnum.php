<?php
namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserRoleEnum extends Enum
{
    public const ADMIN = 'ADMIN';
    public const MANAGER = 'MANAGER';
    public const USER = 'USER';
}
