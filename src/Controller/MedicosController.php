<?php

namespace App\Controller;

use App\Entity\Medico;
use App\Helper\MedicoFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MedicosController extends AbstractController
{
    private EntityManager $entityManager;
    private ObjectRepository $repository;
    private MedicoFactory $medicoFactory;


    public function __construct(EntityManagerInterface $entityManager, MedicoFactory $medicoFactory)
    {
        $this->entityManager = $entityManager;
        $this->medicoFactory = $medicoFactory;
    }

    /**
     * @Route("/medicos", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $this->repository = $this->getDoctrine()->getRepository(Medico::class);
        $medicos = $this->repository->findAll();
        return new JsonResponse($medicos);
    }

    /**
     * @Route("/medicos", methods={"POST"})
     */
    public function novo(Request $request): Response
    {
        $data = $request->getContent();
        $medico = $this->medicoFactory->createMedico($data);
        $this->entityManager->persist(
            $medico
        );
        $this->entityManager->flush();
        return new JsonResponse($medico);
    }

    /**
     * @Route("/medicos/{id}", methods={"GET"})
     */
    public function show(int $id):Response
    {
        $medico = $this->getMedico($id);
        $statusCode = $medico ?  Response::HTTP_OK : Response::HTTP_NO_CONTENT;

        return new JsonResponse($medico, $statusCode);
    }

    /**
     * @Route("/medicos/{id}", methods={"PUT"})
     */
    public function update(Request $request, int $id):Response
    {
        $data = $request->getContent();
        $jsonData = json_decode($data);
        $medico = $this->getMedico($id);

        if (!$medico) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $medico->crm = $jsonData->crm;
        $medico->nome = $jsonData->nome;
        $this->entityManager->flush();

        return new JsonResponse($medico);
    }

    /**
     * @Route("/medicos/{id}", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $medico = $this->getMedico($id);
        $this->entityManager->remove($medico);
        $this->entityManager->flush();
        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param int $id
     * @return mixed|object|null
     */
    private function getMedico(int $id)
    {
        $this->repository = $this->getDoctrine()->getRepository(Medico::class);
        return $this->repository->find($id);
    }
}