<?php

namespace App\Factory;

use App\Entity\Transaction;
use App\Repository\CategoryRepository;
use App\Repository\TransactionRepository;
use JsonSerializable;

class TransactionFactory implements EntityFactory
{
    /**
     * @var TransactionRepository
     */
    private $categoryRepository;

    /**
     * TransactionFactory constructor.
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param string $json
     * @return JsonSerializable
     * @throws \Exception
     */
    public function build(string $json): JsonSerializable
    {
        $jsonData = json_decode($json);
        $categoryId = $jsonData->categoryId;
        $category = $this->categoryRepository->find($categoryId);

        $transaction = new Transaction();
        $transaction
            ->setCategory($category)
            ->setDescription($jsonData->description)
            ->setType($jsonData->type)
            ->setValue($jsonData->value)
            ->setRealValue($jsonData->realValue)
            ->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

        return $transaction;
    }
}