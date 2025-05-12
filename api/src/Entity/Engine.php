<?php
// src/Entity/Engine.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiResource;



#[ApiResource(mercure: true)]

#[ORM\Entity(repositoryClass: 'App\Repository\EngineRepository')]
#[ORM\Table(name: 'engines')]
class Engine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint', options: ['unsigned' => true])]
    #[Groups(['engine', 'automobile:detail'])]
    private ?int $id = null;

    #[ORM\Column(type: 'bigint')]
    #[Groups(['engine:detail'])]
    private ?int $other_id = null;

    #[ORM\ManyToOne(targetEntity: 'App\Entity\Automobile', inversedBy: 'engines')]
    #[ORM\JoinColumn(name: 'automobile_id', referencedColumnName: 'id', nullable: false)]
    #[Groups(['engine:detail'])]
    private ?Automobile $automobile = null;

    #[ORM\Column(type: 'string', length: 191)]
    #[Groups(['engine', 'automobile:detail'])]
    private ?string $name = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['engine', 'engine:detail', 'automobile:detail'])]
    private ?string $specs = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updated_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOtherId(): ?int
    {
        return $this->other_id;
    }

    public function setOtherId(int $other_id): self
    {
        $this->other_id = $other_id;
        return $this;
    }

    public function getAutomobile(): ?Automobile
    {
        return $this->automobile;
    }

    public function setAutomobile(?Automobile $automobile): self
    {
        $this->automobile = $automobile;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getSpecs(): ?array
    {
        return json_decode($this->specs, true);
    }

    public function setSpecs(?string $specs): self
    {
        $this->specs = $specs;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;
        return $this;
    }


    public function __tostring()
    {
        return $this->name;
    }


}
