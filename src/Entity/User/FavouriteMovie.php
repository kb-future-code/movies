<?php


namespace App\Entity\User;

use App\Entity\Traits\TimestampsTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\User\FavouriteMovieRepository")
 * @ORM\Table("user_favourite_movie")
 */
class FavouriteMovie
{
    use TimestampsTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id;

    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User", inversedBy="favouriteMovies", cascade={"persist"})
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private ?User $user;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private ?string $imdbMovieId;

    public function __toString(): string
    {
        if ($this->getUser()) {
            return (string) $this->getUser();
        }

        return '#'.$this->getId();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;

        if (!$user->getFavouriteMovies()->contains($this)) {
            $user->addFavouriteMovie($this);
        }
    }

    public function getImdbMovieId(): ?string
    {
        return $this->imdbMovieId;
    }

    public function setImdbMovieId(?string $imdbMovieId): void
    {
        $this->imdbMovieId = $imdbMovieId;
    }
}