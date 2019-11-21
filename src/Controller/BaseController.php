<?php

namespace App\Controller;

use App\Factory\EntityFactory;
use App\Helper\RequestDataExtractorTrait;
use App\Helper\ResponseGeneratorTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractController
{
    use RequestDataExtractorTrait;
    use ResponseGeneratorTrait;

    /**
     * @var ObjectRepository
     */
    protected $repository;
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;
    /**
     * @var EntityFactory
     */
    protected $factory;

    /**
     * BaseController constructor.
     * @param ServiceEntityRepositoryInterface $repository
     * @param EntityManagerInterface $entityManager
     * @param EntityFactory $factory
     */
    public function __construct(
        ServiceEntityRepositoryInterface $repository,
        EntityManagerInterface $entityManager,
        EntityFactory $factory
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->factory = $factory;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function insert(Request $request): Response
    {
        $requestBody = $request->getContent();

        $entity = $this->factory->build($requestBody);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $this->successResponse($entity);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function findAll(Request $request): Response
    {
        $ordering = $this->getSort($request);
        $filter = $this->getFilter($request);
        $page = $this->getPage($request);
        $itensByPage = $this->getItensByPage($request);

        $entityList = $this->repository->findBy(
            $filter,
            $ordering,
            $itensByPage,
            ($page - 1) * $itensByPage
        );

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

    /**
     * @param int $id
     * @return Response
     */
    public function findOne(int $id): Response
    {
        $entity = $this->repository->find($id);

        $status = is_null($entity) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;

        return $this->successResponse($entity);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function Delete(int $id): Response
    {
        $entity = $this->repository->find($id);

        if (is_null($entity)) {
            return $this->emptyResponse(Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        return $this->emptyResponse(Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id)
    {
        $requestBody = $request->getContent();
        $requestEntity = $this->factory->build($requestBody);

        try {
            $dataBaseEntity = $this->refreshEntity($id, $requestEntity);
            $this->entityManager->flush();

            return $this->successResponse($dataBaseEntity);
        } catch (\InvalidArgumentException $ex) {
            return $this->failResponse('Recurso não encontrado');
        }
    }

    /**
     * Busca a entidade no banco de dados pelo $id,
     * atualiza seus dados com base na $requestEntity
     * e a retorna
     *
     * @param int $id
     * @param $requestEntity
     * @return mixed
     */
    abstract public function refreshEntity(int $id, $requestEntity);
}
