<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *    fields={"email"},
 *    message="Cet e-mail est déjà utilisé."
 * )
 * @UniqueEntity(
 *    fields={"username"},
 *    message="Ce pseudonyme est déjà utilisé, merci d'en choisir un autre."
 * )
 * @Vich\Uploadable
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=2, minMessage="Votre prénom doit comporter au moins deux caractères.")
     * @Groups({"user:update"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=2, minMessage="Votre nom doit comporter au moins deux caractères.")
     * @Groups({"user:update"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Email(message="Votre email doit être un email valide, exemple : florian@exemple.com")
     * @Groups({"user:update"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=10, minMessage="Le format de votre numéro de téléphone n'est pas bon, exemple : 0606060606")
     * @Groups({"user:update"})
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=7, minMessage="Votre mot de passe doit contenir au moins 7 caractères (dont au moins un chiffre)")
     */
    private $password;

    /**
     * Only to compare with the first password
     * @Assert\EqualTo(propertyPath="password", message="Les deux mots de passe ne sont pas identiques")
     */
    private $verifPassword;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
     * @Groups({"user:update"})
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
     * @Groups({"user:update"})
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
     * @Groups({"user:update"})
     */
    private $postalCode;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $roles;
    
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Length(min=5, max=15, minMessage="Votre pseudo doit contenir au moins 5 caractères", maxMessage="Votre pseudo ne peut pas contenir plus de 15 caractères"))
     * @Groups({"user:update"})
     */
    private $username;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\TokenUser", mappedBy="userId", cascade={"persist", "remove"})
     */
    private $tokenUser;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"user:update"})
     */
    private $receiveNewsLetter;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $lastLogin;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ImageProfile", mappedBy="user", cascade={"persist", "remove"})
     */
    private $imageProfile;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Get the value of username
     */ 
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */ 
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }
    
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of roles
     */ 
    public function getRoles()
    {
        return [$this->roles];
    }

    /**
     * Set the value of roles
     *
     * @return  self
     */ 
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }
    
    /**
     * Return fullname of user
     *
     * @return string
     */
    public function fullname():string
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    /**
     * if picture is empty, create automatically oone at "default"
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersit()
    {
        if(empty($this->createdAt)) {
            $this->createdAt = new \DateTime;
        }
        if(empty($this->roles)) {
            $this->roles = "ROLE_USER";
        }
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt() 
    {

    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {

    }

    /**
     * Get only to compare with the first password
     */ 
    public function getVerifPassword()
    {
        return $this->verifPassword;
    }

    /**
     * Set only to compare with the first password
     *
     * @return  self
     */ 
    public function setVerifPassword($verifPassword)
    {
        $this->verifPassword = $verifPassword;

        return $this;
    }

    public function getTokenUser(): ?TokenUser
    {
        return $this->tokenUser;
    }

    public function setTokenUser(TokenUser $tokenUser): self
    {
        $this->tokenUser = $tokenUser;

        // set the owning side of the relation if necessary
        if ($tokenUser->getUserId() !== $this) {
            $tokenUser->setUserId($this);
        }

        return $this;
    }

    public function getReceiveNewsLetter(): ?bool
    {
        return $this->receiveNewsLetter;
    }

    public function setReceiveNewsLetter(?bool $receiveNewsLetter): self
    {
        $this->receiveNewsLetter = $receiveNewsLetter;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTimeInterface $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function getImageProfile(): ?ImageProfile
    {
        return $this->imageProfile;
    }

    public function setImageProfile(ImageProfile $imageProfile): self
    {
        $this->imageProfile = $imageProfile;

        // set the owning side of the relation if necessary
        if ($imageProfile->getUser() !== $this) {
            $imageProfile->setUser($this);
        }

        return $this;
    }

}
