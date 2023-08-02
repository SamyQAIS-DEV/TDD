<?php

namespace App\Tests\Entity;

use App\DataFixtures\UserFixtures;
use App\Entity\User;
use App\Tests\ValidatorTestCase;

class UserTest extends ValidatorTestCase
{
	public function testValidEntity(): void
	{
		$entity = $this->getValidEntity();
		$errors = $this->validator->validate($entity);
		$this->assertCount(0, $errors);
	}

	public function testInvalidBlankEmailEntity(): void
	{
		$entity = $this->getValidEntity()->setEmail('');
		$errors = $this->validator->validate($entity);
		$this->assertCount(2, $errors);
	}

	public function testInvalidUsedEmailEntity(): void
	{
		$this->loadFixtures([UserFixtures::class]);
		$entity = $this->getValidEntity()->setEmail('user1@email.com');
		$errors = $this->validator->validate($entity);
		$this->assertCount(1, $errors);
        $this->assertSame('email', $errors[0]->getPropertyPath());
        $this->assertSame('This value is already used.', $errors[0]->getMessage());
	}

	private function getValidEntity(): User
	{
		return (new User())
			->setEmail('validuser@email.com')
			->setPassword('validpassword');
	}
}
