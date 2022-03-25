<?php

namespace App\Helper;

use App\Entity\Medico;
use App\Repository\EspecialidadeRepository;

class MedicoFactory
{
    private EspecialidadeRepository $especialidadeRepository;

    public function __construct(EspecialidadeRepository $especialidadeRepository)
    {
        $this->especialidadeRepository = $especialidadeRepository;
    }

    public function createMedico(string $json): Medico
    {
        $jsonData = json_decode($json);
        $especialidade = $this->especialidadeRepository->find($jsonData->especialidadeId);

        $medico = new Medico();
        $medico->setCrm($jsonData->crm)
            ->setNome($jsonData->nome)
            ->setEspecialidade($especialidade);

        return $medico;
    }
}