<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EspecialidadeController extends AbstractController
{

    private EntityManagerInterface $entityManager;
    private EspecialidadeRepository $especialidadeRepository;

    public function __construct(EntityManagerInterface $entityManager, EspecialidadeRepository $especialidadeRepository)
    {

        $this->entityManager = $entityManager;
        $this->especialidadeRepository = $especialidadeRepository;
    }

    /**
     * @Route("/especialidades", methods={"GET"})
     */
    public function index(): Response
    {
        return new JsonResponse(
            [
                'message' => 'Especialidades foram encontradas',
                'content' => $this->especialidadeRepository->findAll()
            ]
        );
    }

    /**
     * @Route("/especialidades/{id}", methods={"GET"})
     */
    public function show(int $id): Response
    {
        $especialidade = $this->especialidadeRepository->find($id);
        $statusCode = $especialidade ? Response::HTTP_OK : Response::HTTP_NO_CONTENT;
        return new JsonResponse(
            $especialidade,
            $statusCode
        );
    }

    /**
     * @Route("/especialidades", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent());

        $especialidade = new Especialidade();
        $especialidade->setDescricao($data->descricao);
        $this->entityManager->persist($especialidade);
        $this->entityManager->flush();

        return new JsonResponse($especialidade, Response::HTTP_CREATED);
    }


    /**
     * @Route("/especialidades/{id}", methods={"PUT"})
     */
    public function update(int $id, Request $request): Response
    {
        $data = json_decode($request->getContent());
        $especialidade = $this->especialidadeRepository->find($id);
        $especialidade->setDescricao($data->descricao);
        $this->entityManager->flush();
        return new JsonResponse($especialidade);
    }

    /**
     * @Route("/especialidades/{id}", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $especialidade = $this->especialidadeRepository->find($id);
        $this->entityManager->remove($especialidade);
        $this->entityManager->flush();
        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }
}
