<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiFilter;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AuteurRepository")
 * @ApiResource(
*        attributes = {
*            "pagination_enabled"=false
*        },
 *       collectionOperations = {
 *          "get" = {
 *              "method"="get",
 *              "path" = "/auteurs",
 *              "normalization_context" = {
 *                  "groups"= {"get_role_auteurs"}
 *              }
 *          },
 *          "post"={
 *              "method"="POST",
 *              "access_control"="is_granted('ROLE_MANAGER')",
 *              "access_control_message"="Vous n'avez pas les droits d'accéder à cette ressource"
 *          },
 *      },
 *      itemOperations = {
 *          "get" = {
 *              "method"="get",
 *              "path" = "/auteurs/{id}",
 *              "normalization_context" = {
 *                  "groups"= {"get_role_auteurs"}
 *              }
 *          },
 *          "put"={
 *              "method"="PUT",
 *              "path"="/auteurs/{id}",
 *              "access_control"="is_granted('ROLE_MANAGER')",
 *              "access_control_message"="Vous n'avez pas les droits d'accéder à cette ressource",
 *              "denormalization_context" = {
 *                  "groups"={"put_manager"}
 *              }
 *          },
 *          "delete"={
 *              "method"="DELETE",
 *              "path"="/auteurs/{id}",
 *              "access_control"="is_granted('ROLE_ADMIN')",
 *              "access_control_message"="Vous n'avez pas les droits d'accéder à cette ressource"
 *          }
 *      }
 * )
 * 
 * @ApiFilter(
 *      SearchFilter::class,
 *      properties = {
 *          "nom":"ipartial",
 *          "prenom":"ipartial",
 *          "nationalite":"exact"
 *      }
 * )
 */
class Auteur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get_role_auteurs", "put_manager", "get_role_adherent"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_role_auteurs", "put_manager", "get_role_adherent"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_role_auteurs", "put_manager", "get_role_adherent"})
     */
    private $prenom;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Nationalite", inversedBy="auteurs")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get_role_auteurs", "put_manager", "get_role_adherent"})
     */
    private $nationalite;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Livre", mappedBy="auteur")
     * @Groups({"get_role_auteurs"})
     */
    private $livres;

    public function __construct()
    {
        $this->livres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNationalite(): ?nationalite
    {
        return $this->nationalite;
    }

    public function setNationalite(?nationalite $nationalite): self
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    /**
     * @return Collection|Livre[]
     */
    public function getLivres(): Collection
    {
        return $this->livres;
    }

    public function addLivre(Livre $livre): self
    {
        if (!$this->livres->contains($livre)) {
            $this->livres[] = $livre;
            $livre->setAuteur($this);
        }

        return $this;
    }

    public function removeLivre(Livre $livre): self
    {
        if ($this->livres->contains($livre)) {
            $this->livres->removeElement($livre);
            // set the owning side to null (unless already changed)
            if ($livre->getAuteur() === $this) {
                $livre->setAuteur(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return (string) $this->nom ." ". $this->prenom;
    }
}
