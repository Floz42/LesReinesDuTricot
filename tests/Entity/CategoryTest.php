<?php

namespace tests\Entity;

use App\Entity\Category;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryTest extends KernelTestCase
{
    use FixturesTrait;

    public function getEntity(): Category
    {
        return (new Category())
            ->setName("Ca");
    }

    public function assertHasErrors(Category $category, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($category);
        $messages = [];
        /** @var ConstraintViolation $error */
        foreach($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }
    public function testValidCategoryEntity()
    {
        $this->assertHasErrors($this->getEntity()->setName("Test entity"), 0);
    }

    public function testInvalidCategoryEntity()
    {
        $this->assertHasErrors($this->getEntity(), 1);
    }

    public function testInvalidBlankCategoryEntity()
    {
        $this->assertHasErrors($this->getEntity()->setName(""), 2);
    }

    public function testNotUniqueCategory()
    {
        $this->loadFixtureFiles([dirname(__DIR__) . '/Fixtures/Category.yaml']);
        $this->assertHasErrors($this->getEntity()->setName("cool"), 1);
    }
}