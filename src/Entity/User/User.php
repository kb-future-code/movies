<?php


namespace App\Entity\User;

use App\Entity\Traits\TimestampsTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @UniqueEntity(fields="email", message="Email is already in use")
 * @ORM\Table("user_user")
 */
class User implements UserInterface
{
    use TimestampsTrait;

    const MAIN_ROLE = 'ROLE_USER';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User\FavouriteMovie", mappedBy="user", cascade={"all"})
     * @ORM\OrderBy({"createdAt" = "ASC"})
     */
    private ?Collection $favouriteMovies;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $facebookId;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $googleId;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=150)
     * @Assert\Email()
     * @ORM\Column(type="string", length=150, unique=true)
     */
    private ?string $email;

    /**
     * Hashed password.
     *
     * @ORM\Column(type="string")
     */
    private ?string $password;

    /**
     * @Assert\Length(min=8, max=4096)
     */
    private ?string $plainPassword;

    /**
     * @Assert\Length(max=255)
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private ?string $firstname;

    /**
     * @Assert\Length(max=255)
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $lastname;

    public function __construct()
    {
        $this->favouriteMovies = new ArrayCollection();
    }

    public function __toString()
    {
        if ($this->getFirstname() and $this->getLastname()) {
            return $this->getFirstname().' '.$this->getLastname();
        } elseif ($this->getEmail()) {
            return $this->getEmail();
        }

        return '#'.$this->getId();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFacebookId(): ?string
    {
        return $this->facebookId;
    }

    public function setFacebookId(?string $facebookId): void
    {
        $this->facebookId = $facebookId;
    }

    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    public function setGoogleId(?string $googleId): void
    {
        $this->googleId = $googleId;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): void
    {
        $this->firstname = $firstname;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getRoles(): array
    {
       $roles = [];
       $roles[] = self::MAIN_ROLE;

       return $roles;
    }

    public function getSalt(): void
    {
    }

    public function getUsername(): void
    {
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    /**
     * @return FavouriteMovie[]|ArrayCollection|Collection|null
     */
    public function getFavouriteMovies(): ?Collection
    {
        return $this->favouriteMovies;
    }

    public function addFavouriteMovie(FavouriteMovie $favouriteMovie): void
    {
        $this->favouriteMovies->add($favouriteMovie);
    }

    public function removeFavouriteMovie(FavouriteMovie $favouriteMovie): bool
    {
        return $this->favouriteMovies->removeElement($favouriteMovie);
    }
}