<?php

namespace App\Controller;

use App\Entity\BaseEntity;
use App\Helper\FactoryInterface;
use App\Helper\RequestDataExtract;
use App\Helper\ResponseFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractController
{
    protected ObjectRepository $repository;
    private string $entity;
    protected EntityManagerInterface $entityManager;
    private FactoryInterface $factory;
    private RequestDataExtract $requestDataExtract;

    public function __construct(ObjectRepository $repository ,
                                string $entity,
                                EntityManagerInterface $entityManager,
                                FactoryInterface $factory,
                                RequestDataExtract $requestDataExtract)
    {
        $this->repository = $repository;
        $this->entity = $entity;
        $this->entityManager = $entityManager;
        $this->factory = $factory;
        $this->requestDataExtract = $requestDataExtract;
    }

    public function index(Request $request): Response
    {
        $orderInformation = $this->requestDataExtract->searchFilterData($request);
        $searchData = $this->requestDataExtract->searchFilterData($request);
        [$page, $itemsPerPage] = $this->requestDataExtract->searchPageData($request);
        $content = $this->repository->findBy(
            criteria: $searchData,
            orderBy:  $orderInformation,
            limit: $itemsPerPage,
            offset: ($page -1) * $itemsPerPage
        );
        $response = new ResponseFactory(
            true,
            $content,
            Response::HTTP_OK,
            $page,
            $itemsPerPage
        );

        return $response->getResponse();
    }

    public function create(Request $request): Response
    {
        $entity = $this->factory->make($request->getContent());
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return new JsonResponse(data:  $entity, status:  Response::HTTP_CREATED);
    }


    public function show(int $id): JsonResponse
    {
        $entity = $this->repository->find($id);
        $statusCode = $entity ? Response::HTTP_OK : Response::HTTP_NO_CONTENT;
        $response = new ResponseFactory(true, $entity, $statusCode);
        return $response->getResponse();
    }

    public function update(int $id, Request $request): Response
    {
        $data = json_decode($request->getContent());
        $entity = $this->repository->find($id);
        try {
            $this->updateEntity($entity, $data);

            $this->entityManager->flush();
            $response = new ResponseFactory(
                true,
                $entity,
                Response::HTTP_OK
            );
            return new JsonResponse($entity);
        } catch (\InvalidArgumentException $e) {
            $response = new ResponseFactory(
                false,
                $e->getMessage(),
                Response::HTTP_NOT_FOUND
            );

            return $response->getResponse();
        }


    }

    public function delete(int $id): Response
    {
        $entity = $this->repository->find($id);
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }

    abstract protected function updateEntity(?BaseEntity $existentEntity, \stdClass $newEntity): void;
}