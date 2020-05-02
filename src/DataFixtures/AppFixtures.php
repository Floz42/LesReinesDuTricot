<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

/*         $category = [];

        $category1 = new Category;
        $category1->setName("Eponges");
        $manager->persist($category1);
        $category[] = $category1;

        $category2 = new Category;
        $category2->setName("Corbeilles");
        $manager->persist($category2);
        $category[] = $category2;

        $category3 = new Category;
        $category3->setName("Attrapes rêves");
        $manager->persist($category3);
        $category[] = $category3;

        for ($i=0; $i < 30; $i++) {
            $oneCategory = $category[mt_rand(0, count($category) - 1)];
            $product = new Product();
            $product->setTitle($faker->sentence(mt_rand(5,9)))
                    ->setDescription($faker->paragraph(mt_rand(2,5)))
                    ->setPrice($faker->numberBetween(10,80))
                    ->setPicture("https://picsum.photos/300/200")
                    ->setQuantity($faker->numberBetween(2,15))
                    ->setCategory($oneCategory);
            $manager->persist($product);
        }
 */        
        for($u = 0; $u < 50; $u++) {
            $user = new User;
            $postal = mt_rand('1000', '99000');
            $user->setFirstname($faker->firstName)
                 ->setLastname($faker->lastName)
                 ->setEmail($faker->email)
                 ->setPhoneNumber("0618111315")
                 ->setPassword($this->passwordEncoder->encodePassword($user, 'newpass'))
                 ->setAddress($faker->streetAddress)
                 ->setPostalCode($postal)
                 ->setCity($faker->city)
                 ->setUsername($faker->userName)
                 ;
                 $manager->persist($user);
        }
         $manager->flush();
    }
}
