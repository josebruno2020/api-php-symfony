<?php

namespace App\Controller;

use App\Entity\BaseEntity;
use App\Entity\Medico;
use App\Helper\MedicoFactory;
use App\Helper\RequestDataExtract;
use App\Repository\EspecialidadeRepository;
use App\Repository\MedicoRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\InvalidArgumentException;
use stdClass;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MedicosController extends BaseController
{
    private EspecialidadeRepository $especialidadeRepository;


    public function __construct(EntityManagerInterface $entityManager,
                                MedicoRepository $medicoRepository,
                                MedicoFactory $medicoFactory,
                                EspecialidadeRepository $especialidadeRepository, RequestDataExtract $requestDataExtract)
    {
        $this->factory = $medicoFactory;
        $this->repository = $medicoRepository;
        parent::__construct($medicoRepository, 'Médicos', $entityManager, $medicoFactory, $requestDataExtract);
        $this->especialidadeRepository = $especialidadeRepository;
    }


    /**
     * @Route("/especialidades/{especilidadeId}/medicos", methods={"GET"})
     */
    public function findByEspecialidade(int $especilidadeId): Response
    {
        $medicos = $this->repository->findBy(
            ['especialidade' => $especilidadeId]
        );
        return new JsonResponse($medicos);
    }

    /**
     * @param Medico|null $existentEntity
     * @param stdClass $newEntity
     * @return void
     */
    protected function updateEntity(?BaseEntity $existentEntity, stdClass $newEntity): void
    {
        if (!$existentEntity) {
            throw new InvalidArgumentException("Recurso não encontrado");
        }
        $existentEntity->setNome($newEntity->nome)
            ->setCrm($newEntity->crm)
            ->setEspecialidade(
                $this->especialidadeRepository->find($newEntity->especialidadeId)
            );
    }
}