<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Contact 
{
    /**
     * @var string
     * @Assert\NotBlank(message="Le champ prénom ne peut pas être vide")
     */
    private $firstname;

    /** 
     * @var string
     * @Assert\NotBlank(message="Le champ nom ne peut pas être vide")
     */
    private $lastname;

    /**
     * @var string
     * @Assert\NotBlank(message="Le champ nom ne peut pas être vide")
     * @Assert\Email(message="Ce champ doit être de type exemple@exemple.com")
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
     * @Assert\Length(min=15, minMessage="Votre message doit contenir au moins 15 caractères.")
     */
    private $message;

    /**
     * @var \DateTime
     */
    private $sendAt;

    public function __construct()
    {
        $this->sendAt = new \DateTime;
    }

    public function getFirstname(): ?String
    {
        return $this->firstname;
    }

    public function getLastname(): ?String
    {
        return $this->lastname;
    }

    public function getEmail(): ?String
    {
        return $this->email;
    }

    public function getMessage(): ?String
    {
        return $this->message;
    }

    public function getSendAt()
    {
        return $this->sendAt;
    }

    public function setFirstname(String $firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function setLastname(String $lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function setEmail(String $email)
    {
        $this->email = $email;

        return $this;
    }

    public function setMessage(String $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * 
     */
    public function setSendAt(?String $sendAt)
    {
        if (empty($this->sendAt)) {
            $this->sendAt = new \DateTime;
        }
    } 
}