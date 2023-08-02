<?php

namespace App\Tests\Security\Voter;

use App\Entity\User;
use App\Security\Voter\AuthenticationVoter;
use App\Tests\KernelTestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class AuthenticationVoterTest extends KernelTestCase
{
	private TokenInterface $token;

	private AuthenticationVoter $voter;

	public function setUp(): void
	{
		$this->token = $this->createMock(TokenInterface::class);
		$this->voter = new AuthenticationVoter();
	}

	public function testAccessGranted(): void
	{
		$this->token->method('getUser')->willReturn($this->getValidUser());
		$this->assertSame(VoterInterface::ACCESS_GRANTED, $this->voter->vote($this->token, null, [AuthenticationVoter::ACCESS]));
	}

	public function testAccessDenied(): void
	{
		$this->token->method('getUser')->willReturn(null);
		$this->assertSame(VoterInterface::ACCESS_DENIED, $this->voter->vote($this->token, null, [AuthenticationVoter::ACCESS]));
	}

	private function getValidUser(): User
	{
		return (new User())
			->setEmail('validuser@email.com')
			->setPassword('validpassword');
	}
}