<?php

namespace tests\Entity;

use App\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ContactTest extends KernelTestCase
{
    public function getEntity(): Contact
    {
        return (new Contact())
            ->setFirstname("Florian")
            ->setLastname("THIEBAUD")
            ->setEmail("flo.carreclub@gmail.com")
            ->setMessage("Ceci est un message de test.");
    }

    public function assertHasErrors(Contact $contact, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($contact);
        $messages = [];
        /** @var ConstraintViolation $error */
        foreach($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidContactMessage()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidContactFirstname()
    {
        $this->assertHasErrors($this->getEntity()->setFirstname(""), 1);
    }

    public function testInvalidContactLastname()
    {
        $this->assertHasErrors($this->getEntity()->setLastname(""), 1);
    }

    public function testBlankContactEmail()
    {
        $this->assertHasErrors($this->getEntity()->setEmail(""), 1);
    }

    public function testInvalidContactEmail()
    {
        $this->assertHasErrors($this->getEntity()->setEmail("flo.carreclub"), 1);
    }

    public function testBlankContactMessage()
    {
        $this->assertHasErrors($this->getEntity()->setMessage(""), 2);
    }

    public function testTooShortContactMessage()
    {
        $this->assertHasErrors($this->getEntity()->setMessage("yoyo"), 1);
    }
}