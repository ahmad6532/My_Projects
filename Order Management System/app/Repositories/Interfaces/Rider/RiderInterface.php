<?php

namespace App\Repositories\Interfaces\Rider;

interface RiderInterface
{
    public function all();
    public function create($data, $requestType);
    public function find($riderId);
    public function update($riderId,$data);
    public function delete($riderId);
}
