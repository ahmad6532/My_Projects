<?php

namespace App\Repositories\Interfaces;

interface PlanInterface
{
    public function all();
    public function find($planId);
    public function showUserPlan();
    public function purchasePlan($data);
    public function planHistory();
    public function receipt($userId);
}