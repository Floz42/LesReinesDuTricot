<?php 

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;


class Newsletter 
{
    /**
     * @var array|null
     * @Assert\NotBlank(message="Vous n'avez aucun destinataire dans votre liste")
     */
    private $destinations;

    /**
     * @var string
     * @Assert\NotBlank(message="Vous devez indiquer un titre à votre e-mail")
     */
    private $title;

    /**
     * @var string
     * @Assert\NotBlank(message="Vous devez remplir la zone de message avant de l'envoyer")
     * @Assert\Length(min=50, minMessage="Pour ne pas perdre en crédibilité veuillez être plus explicite dans votre message")
     */
    private $message;

    public function getDestinations(): ?array
    {
        return $this->destinations;
    }

    public function setDestinations($destinations): self
    {
        $this->destinations = $destinations;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }
}