<?php

namespace App\Controller;

use App\Entity\BaseEntity;
use App\Entity\Especialidade;
use App\Helper\EspecialidadeFactory;
use App\Helper\RequestDataExtract;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\InvalidArgumentException;
use stdClass;

class EspecialidadeController extends BaseController
{

    private EspecialidadeRepository $especialidadeRepository;
    private EspecialidadeFactory $factory;

    public function __construct(EspecialidadeRepository $especialidadeRepository,
                                EntityManagerInterface $entityManager,
                                EspecialidadeFactory $especialidadeFactory,
    RequestDataExtract $requestDataExtract)
    {
        $this->especialidadeRepository = $especialidadeRepository;
        parent::__construct($especialidadeRepository, 'Especialidades', $entityManager, $especialidadeFactory, $requestDataExtract);
        $this->factory = $especialidadeFactory;
    }


    /**
     * @param Especialidade|null $existentEntity
     * @param stdClass $newEntity
     * @return void
     */
    protected function updateEntity(?BaseEntity $existentEntity, stdClass $newEntity): void
    {
        if (!$existentEntity) {
            throw new InvalidArgumentException("Recurso não encontrado");
        }
        $existentEntity->setDescricao($newEntity->descricao);
    }
}
