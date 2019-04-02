<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LivreRepository")
 * @ApiResource(
 *      attributes={
 *          "order"={
 *              "titre":"ASC"
 *          }
 *      },
 *      collectionOperations={
 *          "get_coll_role_adherent"={
 *              "method"="GET",
 *              "path"="/adherent/livres",
 *              "access_control"="is_granted('ROLE_MANAGER'),
 *              "access_control_message"="Vous n'avez pas les droits d'accéder à cette ressource"
 *              "normalization_context"= {
 *                  "groups"={"get_role_adherent"}
 *               }
 *              
 * 
 *          },
 *          "get_coll_role_manager"={
 *              "method"="GET",
 *              "path"="/manager/livres"
 *              "access_control"="is_granted('ROLE_MANAGER'),
 *              "access_control_message"="Vous n'avez pas les droits d'accéder à cette ressource"
 *          },
 *          "post"={
 *                  "method"="POST",
 *                  "access_control"="is_granted('ROLE_MANAGER'),
 *                  "access_control_message"="Vous n'avez pas les droits d'accéder à cette ressource"
 *              }
 *      },
 *      ItemOperation={
    *      "get_item_role_adherent"={
    *              "method"="GET",
    *              "path"="/adherent/livres/{id}",
    *              "access_control"="is_granted('ROLE_MANAGER'),
    *              "access_control_message"="Vous n'avez pas les droits d'accéder à cette ressource"
    *              "normalization_context"= {
    *                  "groups"={"get_role_adherent"}
    *               }
    *              
    * 
    *          },
    *          "get_item_role_manager"={
    *              "method"="GET",
    *              "path"="/manager/livres"
    *              "access_control"="is_granted('ROLE_MANAGER'),
    *              "access_control_message"="Vous n'avez pas les droits d'accéder à cette ressource"
    *          },
 *      }
 * )
 * @ApiFilter(
 *      SearchFilter::class,
 *      properties={
 *          "titre": "ipartial",
 *          "auteur": "exact",
 *          "genre": "exact"
 *      }
 * )
 * 
 *  @ApiFilter(
 *      OrderFilter::class,
 *      properties={
 *          "titre",
 *          "prix",
 *          "auteur.nom"
 *      }
 * )
 *   @ApiFilter(
 *      PropertyFilter::class,
 *      arguments={
 *          "parameterName":"properties",
 *          "overrideDefaultProperties":false,
 *          "whitelist"={
 *                  "isbn",
 *                  "titre",
 *                  "prix"
 *          }
 *      }
 * )
 * 
 */
class Livre
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_role_adherent"})
     */
    private $isbn;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_role_adherent"})
     */
    private $titre;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Genre", inversedBy="livres")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get_role_adherent"})
     */
    private $genre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Editeur", inversedBy="livres")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get_role_adherent"})
     */
    private $editeur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Auteur", inversedBy="livres")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get_role_adherent"})
     */
    private $auteur;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"get_role_adherent"})
     */
    private $annee;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"get_role_adherent"})
     */
    private $langue;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Pret", mappedBy="livre")
     */
    private $prets;

    public function __construct()
    {
        $this->prets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getEditeur(): ?Editeur
    {
        return $this->editeur;
    }

    public function setEditeur(?Editeur $editeur): self
    {
        $this->editeur = $editeur;

        return $this;
    }

    public function getAuteur(): ?Auteur
    {
        return $this->auteur;
    }

    public function setAuteur(?Auteur $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(?int $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): self
    {
        $this->langue = $langue;

        return $this;
    }
    public function __toString()
    {
        return (string) $this->titre;
    }

    /**
     * @return Collection|Pret[]
     */
    public function getPrets(): Collection
    {
        return $this->prets;
    }

    public function addPret(Pret $pret): self
    {
        if (!$this->prets->contains($pret)) {
            $this->prets[] = $pret;
            $pret->setLivre($this);
        }

        return $this;
    }

    public function removePret(Pret $pret): self
    {
        if ($this->prets->contains($pret)) {
            $this->prets->removeElement($pret);
            // set the owning side to null (unless already changed)
            if ($pret->getLivre() === $this) {
                $pret->setLivre(null);
            }
        }

        return $this;
    }
}