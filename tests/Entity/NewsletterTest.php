<?php

namespace tests\Entity;

use App\Entity\Newsletter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class NewsletterTest extends KernelTestCase
{
    public function getEntity(): Newsletter
    {
        return (new Newsletter())
                ->setDestinations(["flo.lol@gmail.com"])
                ->setTitle("Une petite news de notre site")
                ->setMessage("Aujourd'hui nous vous présentons un nouveau produit révolutionnaire qui va vraiment changer votre vie");
    }

    public function assertHasErrors(Newsletter $newsletter, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($newsletter);
        $messages = [];
        foreach($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }

        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidNewsletter()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testNewsletterDestinationsBlank()
    {
        $this->assertHasErrors($this->getEntity()->setDestinations([]), 1);
    }

    public function testNewsletterTitleBlank()
    {
        $this->assertHasErrors($this->getEntity()->setTitle(""), 2);
    }

    public function testNewsLetterTitleTooShort()
    {
        $this->assertHasErrors($this->getEntity()->setTitle("yoyo"), 1);
    }

    public function testNewsLetterMessageBlank()
    {
        $this->assertHasErrors($this->getEntity()->setMessage(""), 2);
    }

    public function testNewsletterMessageTooShort()
    {
        $this->assertHasErrors($this->getEntity()->setMessage("yoyo"), 1);
    }
}