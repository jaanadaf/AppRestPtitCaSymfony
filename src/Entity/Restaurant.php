<?php

namespace App\Entity;

use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RestaurantRepository::class)]
class Restaurant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $Description = null;

    #[ORM\Column]
    private ?int $heuresOuverture = null;

    #[ORM\Column]
    private ?int $Nombivtmaximum = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $Datedecreation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $Datedemiseajour = null;

    #[ORM\OneToMany(targetEntity: Picture::class, mappedBy: 'restaurant', orphanRemoval: true)]
    private Collection $pictures;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): static
    {
        $this->Description = $Description;

        return $this;
    }

    public function getHeuresOuverture(): ?int
    {
        return $this->heuresOuverture;
    }

    public function setHeuresOuverture(int $heuresOuverture): static
    {
        $this->heuresOuverture = $heuresOuverture;

        return $this;
    }

    public function getNombivtmaximum(): ?int
    {
        return $this->Nombivtmaximum;
    }

    public function setNombivtmaximum(int $Nombivtmaximum): static
    {
        $this->Nombivtmaximum = $Nombivtmaximum;

        return $this;
    }

    public function getDatedecreation(): ?\DateTimeInterface
    {
        return $this->Datedecreation;
    }

    public function setDatedecreation(\DateTimeInterface $Datedecreation): static
    {
        $this->Datedecreation = $Datedecreation;

        return $this;
    }

    public function getDatedemiseajour(): ?\DateTimeInterface
    {
        return $this->Datedemiseajour;
    }

    public function setDatedemiseajour(\DateTimeInterface $Datedemiseajour): static
    {
        $this->Datedemiseajour = $Datedemiseajour;

        return $this;
    }

    /**
     * @return Collection<int, Picture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): static
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures->add($picture);
            $picture->setRestaurant($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): static
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getRestaurant() === $this) {
                $picture->setRestaurant(null);
            }
        }

        return $this;
    }
}
