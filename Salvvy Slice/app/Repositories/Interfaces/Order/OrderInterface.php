<?php

namespace App\Repositories\Interfaces\Order;

interface OrderInterface 
{
    public function find($orderId);
    public function create($data);
    public function updateStatus($orderId, $data);
    public function getAllUserOrders($status);
    public function getAllOrders();
    public function update($orderId,$data);
    public function findOrder($orderId);
    public function delete($orderId);

}
