<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiResource;



#[ApiResource(mercure: true)]

#[ORM\Entity(repositoryClass: 'App\Repository\AutomobileRepository')]
#[ORM\Table(name: 'automobiles')]
class Automobile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint', options: ['unsigned' => true])]
    #[Groups(['automobile', 'engine'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 191)]
    #[Groups(['automobile:detail'])]
    private ?string $url_hash = null;

    #[ORM\Column(type: 'text')]
    #[Groups(['automobile:detail'])]
    private ?string $url = null;

    #[ORM\ManyToOne(targetEntity: 'App\Entity\Brand', inversedBy: 'automobiles')]
    #[ORM\JoinColumn(name: 'brand_id', referencedColumnName: 'id', nullable: false)]
    #[Groups(['automobile', 'automobile:detail'])]
    private ?Brand $brand = null;

    #[ORM\Column(type: 'string', length: 191)]
    #[Groups(['automobile', 'automobile:detail'])]
    private ?string $name = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['automobile:detail'])]
    private ?string $description = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['automobile:detail'])]
    private ?string $press_release = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['automobile:detail'])]
    private ?string $photos = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['automobile:detail'])]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['automobile:detail'])]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\OneToMany(targetEntity: 'App\Entity\Engine', mappedBy: 'automobile')]
    #[Groups(['automobile:detail'])]
    private Collection $engines;

    /**
     * @var string|null
     */
    #[Groups(['automobile:list'])]
    private ?string $vehicle_type = null;

    public function __construct()
    {
        $this->engines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrlHash(): ?string
    {
        return $this->url_hash;
    }

    public function setUrlHash(string $url_hash): self
    {
        $this->url_hash = $url_hash;
        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getPressRelease(): ?string
    {
        return $this->press_release;
    }

    public function setPressRelease(?string $press_release): self
    {
        $this->press_release = $press_release;
        return $this;
    }

    public function getPhotos(): ?string
    {
        return $this->photos;
    }

    public function setPhotos(?string $photos): self
    {
        $this->photos = $photos;
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

    /**
     * @return Collection|Engine[]
     */
    public function getEngines(): Collection
    {
        return $this->engines;
    }

    public function addEngine(Engine $engine): self
    {
        if (!$this->engines->contains($engine)) {
            $this->engines[] = $engine;
            $engine->setAutomobile($this);
        }

        return $this;
    }

    public function removeEngine(Engine $engine): self
    {
        if ($this->engines->contains($engine)) {
            $this->engines->removeElement($engine);
            // set the owning side to null (unless already changed)
            if ($engine->getAutomobile() === $this) {
                $engine->setAutomobile(null);
            }
        }

        return $this;
    }

    public function getVehicleType(): ?string
    {
        // This should be derived from name or description
        // For simplicity, we'll assume it needs to be set manually or populated
        return $this->vehicle_type;
    }

    public function setVehicleType(?string $vehicle_type): self
    {
        $this->vehicle_type = $vehicle_type;
        return $this;
    }



    public function __tostring()
    {
        return $this->name;
    }
}
