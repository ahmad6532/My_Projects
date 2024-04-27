<?php

namespace App\Repositories\Interfaces;

interface AdminInterface
{
    public function find($adminId);
    public function update($adminId, $data);

}
