<?php

namespace App\Helper;

use App\Entity\Especialidade;

class EspecialidadeFactory implements FactoryInterface
{
    public function make(string $json): Especialidade
    {
        $data = json_decode($json);

        $especialidade = new Especialidade();
        $especialidade->setDescricao($data->descricao);

        return $especialidade;
    }
}