<?php

namespace App\Repositories\Interfaces;

interface UserInterface
{
    public function signIn($credentiols);
    public function find($userId);
    public function update($userId,$data);
    public function create($data);
    public function destroy();
    public function changeUserStatus($userId, $data);
    public function delete($userId);
}
