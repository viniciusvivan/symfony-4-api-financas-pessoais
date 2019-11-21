<?php

namespace App\Controller;

use App\Entity\Category;
use App\Factory\CategoryFactory;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class CategoryController extends BaseController
{
    public function __construct(
        CategoryRepository $categoryRepository,
        EntityManagerInterface $entityManager,
        CategoryFactory $categoryFactory
    ) {
        parent::__construct($categoryRepository, $entityManager, $categoryFactory);
    }

    /**
     * Busca a entidade no banco de dados pelo $id,
     * atualiza seus dados com base na $requestEntity
     * e a retorna
     *
     * @param int $id
     * @param Category $requestEntity
     * @return mixed
     */
    public function refreshEntity(int $id, $requestEntity)
    {
        $entityDataBase = $this->repository->find($id);

        if (is_null($entityDataBase)) {
            throw new \InvalidArgumentException();
        }

        $entityDataBase
                ->setName($requestEntity->getName())
                ->setDescription($requestEntity->getDescription())
                ->setType($requestEntity->getType())
                ->setIsActive($requestEntity->getIsActive());

        return $entityDataBase;
    }
}
