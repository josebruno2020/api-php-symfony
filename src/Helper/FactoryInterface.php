<?php

namespace App\Helper;

use App\Entity\BaseEntity;

interface FactoryInterface
{
    public function make(string $json): BaseEntity;
}