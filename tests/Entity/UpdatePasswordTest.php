<?php

namespace tests\Entity;

use App\Entity\UpdatePassword;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UpdatePasswordTest extends KernelTestCase
{
    public function getEntity()
    {
        return (new UpdatePassword())
            ->setNewPassword("azertyuio")
            ->setConfirmNewPassword("azertyuio");
    }

    public function assertHasErrors(UpdatePassword $password, int $number)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($password);
        $messages = [];
        foreach($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testUpdatePasswordValid()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testUpdatePasswordNewPasswordBlank()
    {
        $this->assertHasErrors($this->getEntity()->setNewPassword(""), 3);
    }

    public function testUpdatePasswordNewPasswordTooShort()
    {
        $this->assertHasErrors($this->getEntity()->setNewPassword('aze'), 2);
    }

    public function testUpdatePasswordPasswordsNotEquals()
    {
        $this->assertHasErrors($this->getEntity()->setConfirmNewPassword("poiuytre"), 1);
    }

}