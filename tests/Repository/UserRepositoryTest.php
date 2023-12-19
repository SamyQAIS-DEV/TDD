<?php

namespace App\Tests\Service;

use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use App\Tests\RepositoryTestCase;

class UserRepositoryTest extends RepositoryTestCase
{
	protected $repositoryClass = UserRepository::class;

    // dce back php bin/phpunit --testdox --filter UserRepositoryTest
	public function testRegistration(): void
	{
        // Chargement des données de base (10 utilisateurs)
		$this->loadFixtures([UserFixtures::class]);

        // Vérification qu'il en existe bien 10 en base de données
		$this->assertEquals(10, $this->repository->count([]));

        // Création d'un nouvel utilisateur
		$this->repository->saveNewUser();

        // Vérification qu'il un nouveau en base de données
		$this->assertEquals(11, $this->repository->count([]));
	}
}
