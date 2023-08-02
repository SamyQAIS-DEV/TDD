<?php

namespace App\Tests\Service;

use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use App\Tests\RepositoryTestCase;

class UserRepositoryTest extends RepositoryTestCase
{
	protected $repositoryClass = UserRepository::class;

	public function testRegistration(): void
	{
		$this->loadFixtures([UserFixtures::class]);
		$this->assertEquals(10, $this->repository->count([]));
		$this->repository->saveNewUser();
		$this->assertEquals(11, $this->repository->count([]));
	}
}