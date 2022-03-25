<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Internal\TentativeType;

/**
 * @ORM\Entity()
 * @ORM\Table(name="medicos")
 */
class Medico implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\Column(type="integer")
     */
    private int $crm;
    /**
     * @ORM\Column(type="string", nullable=true)
     *
     */
    private string $nome;

    /**
     * @ORM\ManyToOne(targetEntity=Especialidade::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $especialidade;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    public function getCrm(): int
    {
        return $this->crm;
    }


    public function setCrm(int $crm): Medico
    {
        $this->crm = $crm;
        return $this;
    }

    /**
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     * @return Medico
     */
    public function setNome(string $nome): Medico
    {
        $this->nome = $nome;
        return $this;
    }



    public function getEspecialidade(): ?Especialidade
    {
        return $this->especialidade;
    }

    public function setEspecialidade(?Especialidade $especialidade): self
    {
        $this->especialidade = $especialidade;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'nome' => $this->getNome(),
            'crm' => $this->getCrm(),
            'especialidadeId' => $this->getEspecialidade()->getId(),
            'especialidade' => $this->getEspecialidade()->getDescricao()
        ];
    }
}