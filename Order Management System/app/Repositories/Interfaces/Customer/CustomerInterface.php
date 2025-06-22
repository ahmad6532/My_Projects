<?php

namespace App\Repositories\Interfaces\Customer;

interface CustomerInterface
{
    public function all();
    public function create($data, $requestType);
    public function update($customerId, $data);
    public function find($customerId);
    public function delete($customerId);
}
