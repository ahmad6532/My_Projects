<?php

namespace App\Enums\Order;

enum OrderStatusEnum: string
{
    case ALL = 'ALL';
    case PENDING = 'PENDING';
    case ACCEPTED = 'ACCEPTED';
    case PICKED = 'PICKED';
    case ON_MY_WAY = 'ON_MY_WAY';
    case DELIVERED = 'DELIVERED';
    case COMPLETED = 'COMPLETED';
}
