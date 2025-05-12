<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiResource;



#[ApiResource(mercure: true)]

#[ORM\Entity(repositoryClass: 'App\Repository\BrandRepository')]
#[ORM\Table(name: 'brands')]


class Brand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint', options: ['unsigned' => true])]
    #[Groups(['brand', 'automobile'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 191)]
    #[Groups(['brand', 'automobile'])]
    private ?string $url_hash = null;

    #[ORM\Column(type: 'text')]
    #[Groups(['brand:detail'])]
    private ?string $url = null;

    #[ORM\Column(type: 'string', length: 191)]
    #[Groups(['brand', 'automobile'])]
    private ?string $name = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['brand:detail'])]
    private ?string $logo = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $deleted_at = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['brand:detail'])]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['brand:detail'])]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\OneToMany(targetEntity: 'App\Entity\Automobile', mappedBy: 'brand')]
    private Collection $automobiles;

    public function __construct()
    {
        $this->automobiles = new ArrayCollection();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;
        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(?\DateTimeInterface $deleted_at): self
    {
        $this->deleted_at = $deleted_at;
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
     * @return Collection|Automobile[]
     */
    public function getAutomobiles(): Collection
    {
        return $this->automobiles;
    }

    public function addAutomobile(Automobile $automobile): self
    {
        if (!$this->automobiles->contains($automobile)) {
            $this->automobiles[] = $automobile;
            $automobile->setBrand($this);
        }

        return $this;
    }

    public function removeAutomobile(Automobile $automobile): self
    {
        if ($this->automobiles->contains($automobile)) {
            $this->automobiles->removeElement($automobile);
            // set the owning side to null (unless already changed)
            if ($automobile->getBrand() === $this) {
                $automobile->setBrand(null);
            }
        }

        return $this;
    }



    public function __tostring()
    {
        return $this->name;
    }
}
