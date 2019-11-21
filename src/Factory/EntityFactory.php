<?php

namespace App\Factory;

use JsonSerializable;

interface EntityFactory
{
    /**
     * @param string $json
     * @return JsonSerializable
     */
    public function build(string $json): JsonSerializable;
}