<?php

namespace App\Enums\User;

enum UserRoleEnum: string
{
    case Admin = 'Admin';
    case Rider = 'Rider';
    case Customer = 'Customer';
}
