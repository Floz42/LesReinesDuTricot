<?php

namespace tests\Entity;

use App\Entity\User;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    use FixturesTrait;

    public function getEntity()
    {
        return (new User())
            ->setFirstname("Florian")
            ->setLastname("Thiébaud")
            ->setEmail("flo.carreclub@gmail.com")
            ->setPhoneNumber("07.82.87.13.10")
            ->setPassword("azertyuio")
            ->setVerifPassword("azertyuio")
            ->setAddress("35 allée de Symfony")
            ->setCity("Saint-Etienne")
            ->setPostalCode("42100")
            ->setUsername("Floz89")
            ;
    }

    public function assertHasErrors(User $user, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($user);
        $messages = [];
        foreach($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testUserIsValid()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testNotUniqueUsername()
    {
        $this->loadFixtureFiles([dirname(__DIR__) . '/Fixtures/User.yaml']);
        $this->assertHasErrors($this->getEntity()->setUsername("Floz42"), 1);
    }

    public function testNotUniqueEmail()
    {
        $this->loadFixtureFiles([dirname(__DIR__) . '/Fixtures/User.yaml']);
        $this->assertHasErrors($this->getEntity()->setEmail("admin@lol.com"), 1);
    }

    public function testUserFirstnameTooShort()
    {
        $this->assertHasErrors($this->getEntity()->setFirstname("a"), 1);
    }

    public function testUserLastnameTooShort()
    {
        $this->assertHasErrors($this->getEntity()->setLastname("a"), 1);
    }

    public function testUserEmailNotValid()
    {
        $this->assertHasErrors($this->getEntity()->setEmail("flo.lol"), 1);
    }

    public function testUserPhoneNumberNotValid()
    {
        $this->assertHasErrors($this->getEntity()->setPhoneNumber("09.99.00"), 1);
    }

    public function testUserPasswordTooShort() 
    {
        $this->assertHasErrors($this->getEntity()->setPassword("ooo")->setVerifPassword("ooo"), 1);
    }

    public function testUserPasswordsNotEquals()
    {
        $this->assertHasErrors($this->getEntity()->setVerifPassword("popopopopo"), 1);
    }

    public function testUserUsernameTooShort()
    {
        $this->assertHasErrors($this->getEntity()->setUsername("Plop"), 1);
    }

    public function testUserUsernameTooLong()
    {
        $this->assertHasErrors($this->getEntity()->setUsername("Plopploploploplplplpopoo"), 1);
    }

    public function testUserAddresseNotBlank()
    {
        $this->assertHasErrors($this->getEntity()->setAddress(""), 1);
    }

    public function testUserCityNotBlank()
    {
        $this->assertHasErrors($this->getEntity()->setCity(""), 1);
    }

    public function testUserPostalCodeNotBlank()
    {
        $this->assertHasErrors($this->getEntity()->setPostalCode(""), 1);
    }
}