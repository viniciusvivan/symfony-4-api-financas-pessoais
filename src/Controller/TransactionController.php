<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Factory\TransactionFactory;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends BaseController
{
    /**
     * TransactionController constructor.
     * @param TransactionRepository $repository
     * @param EntityManagerInterface $entityManager
     * @param TransactionFactory $transactionFactory
     */
    public function __construct(
        TransactionRepository $repository,
        EntityManagerInterface $entityManager,
        TransactionFactory $transactionFactory)
    {
        parent::__construct($repository, $entityManager, $transactionFactory);
        $this->repository = $repository;
    }

    /**
     * Busca a entidade no banco de dados pelo $id,
     * atualiza seus dados com base na $requestEntity
     * e a retorna
     *
     * @param int $id
     * @param Transaction $requestEntity
     * @return mixed
     */
    public function refreshEntity(int $id, $requestEntity)
    {
        $entityDataBase = $this->repository->find($id);

        if (is_null($entityDataBase)) {
            throw new \InvalidArgumentException();
        }

        $entityDataBase
            ->setCategory($requestEntity->getCategory())
            ->setDescription($requestEntity->getDescription())
            ->setType($requestEntity->getType())
            ->setValue($requestEntity->getValue())
            ->setRealValue($requestEntity->getRealValue())
            ->setCreatedAt($requestEntity->getCreatedAt());

        return $entityDataBase;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function findByMonth(Request $request)
    {
        $ordering = $this->getSort($request);
        $filter = $this->getFilter($request);
        $page = $this->getPage($request);
        $itensByPage = $this->getItensByPage($request);

        $month = $filter['month'];
        $year = $filter['year'];

        $entityList = $this->repository->findByMonth($month, $year);

        $status = empty($entityList)
            ? Response::HTTP_NO_CONTENT
            : Response::HTTP_OK;

        return $this->generateResponse(
            true,
            $entityList,
            $status,
            $page,
            $itensByPage
        );
    }
}
