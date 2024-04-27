<?php

namespace App\Repositories\Interfaces;

interface ManagerInterface
{
    public function find($managerId);
    public function update($managerId,$data);
    public function create($data);
    public function delete( $managerId);
    public function all();
    public function findByDate($date);
}
