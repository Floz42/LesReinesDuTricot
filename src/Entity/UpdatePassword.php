<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class UpdatePassword 
{

    private $oldPassword;
    
    private $verificationCode;

    /**
     * @Assert\Length(min=8, minMessage="Votre mot de passe doit comporter au moins 8 caractères")
     */
    private $newPassword;

    /**
     * @Assert\EqualTo(propertyPath="newPassword", message="Les deux mots de passe ne sont pas identiques")
     */
    private $confirmNewPassword;


    /**
     * Get the value of verificationCode
     */ 
    public function getVerificationCode()
    {
        return $this->verificationCode;
    }

    /**
     * Set the value of verificationCode
     *
     * @return  self
     */ 
    public function setVerificationCode($verificationCode)
    {
        $this->verificationCode = $verificationCode;

        return $this;
    }

    /**
     * Get the value of newPassword
     */ 
    public function getNewPassword()
    {
        return $this->newPassword;
    }

    /**
     * Set the value of newPassword
     *
     * @return  self
     */ 
    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    /**
     * Get the value of confirmNewPassword
     */ 
    public function getConfirmNewPassword()
    {
        return $this->confirmNewPassword;
    }

    /**
     * Set the value of confirmNewPassword
     *
     * @return  self
     */ 
    public function setConfirmNewPassword($confirmNewPassword)
    {
        $this->confirmNewPassword = $confirmNewPassword;

        return $this;
    }

    /**
     * Get the value of oldPassword
     */ 
    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * Set the value of oldPassword
     *
     * @return  self
     */ 
    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }
}