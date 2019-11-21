<?php

namespace App\Factory;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use JsonSerializable;

class CategoryFactory implements EntityFactory
{
    /**
     * @param string $json
     * @return JsonSerializable
     */
    public function build(string $json): JsonSerializable
    {
        $jsonData = json_decode($json);

        $category = new Category();
        $category
            ->setName($jsonData->name)
            ->setDescription($jsonData->description)
            ->setType($jsonData->type)
            ->setIsActive($jsonData->isActive);

        return $category;
    }
}
