<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
	public function load(ObjectManager $manager): void
	{
		for ($i = 1; $i <= 10; $i++) {
			$user = (new User())
				->setEmail("user$i@email.com")
				->setPassword("password$i");
			$manager->persist($user);
		}
		$manager->flush();
	}
}
