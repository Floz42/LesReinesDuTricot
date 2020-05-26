<?php

namespace tests\Entity;

use App\Entity\Faq;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FaqTest extends KernelTestCase
{
    public function getEntity()
    {
        return (new Faq)
            ->setQuestion("Quel est votre couleur préférée ?")
            ->setAnswer("Ma couleur préférée est le vert.");
    }

    public function assertHasErrors(Faq $faq, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($faq);
        $messages = [];

        foreach($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }

        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidFaq()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testFaqQuestionBlank()
    {
        $this->assertHasErrors($this->getEntity()->setQuestion(""), 2);
    }

    public function testFaqQuestionTooShort()
    {
        $this->assertHasErrors($this->getEntity()->setQuestion("haha"), 1);
    }

    public function testFaqAnswerBlank()
    {
        $this->assertHasErrors($this->getEntity()->setAnswer(""), 2);
    }

    public function testFaqAnswerTooShort()
    {
        $this->assertHasErrors($this->getEntity()->setAnswer("hihi"), 1);
    }
}