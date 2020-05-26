<?php

namespace tests\Entity;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductTest extends KernelTestCase
{

    public function getEntity(): Product
    {   
        $category = new Category;

        return (new Product())
            ->setTitle("Nouveau produit")
            ->setDescription("Une superbe description")
            ->setPrice(90)
            ->setQuantity(11)
            ->setCategory($category)
            ->setPicture("/images/lol.png")
            ;
    }

    public function assertHasErrors(Product $product, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($product);
        $messages = [];

        foreach($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }

        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidProduct()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testProductTitleBlank()
    {
        $this->assertHasErrors($this->getEntity()->setTitle(""), 1);
    }

    public function testProductDescriptionBlank()
    {
        $this->assertHasErrors($this->getEntity()->setDescription(""), 2);
    }

    public function testProductDescriptionTooShort()
    {
        $this->assertHasErrors($this->getEntity()->setDescription("hoho"), 1);
    }

    public function testProductPictureBlank()
    {
        $this->assertHasErrors($this->getEntity()->setPicture(""), 1);
    }

    public function testProductQuantityIsInt()
    {
        $this->assertHasErrors($this->getEntity()->setQuantity(9), 0);
    }

}