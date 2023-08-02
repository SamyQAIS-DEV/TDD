<?php

namespace App\Tests\Service;

use App\Service\CalculatorService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CalculatorServiceTest extends KernelTestCase
{
	private CalculatorService $service;

	public function setUp(): void
	{
		parent::setUp();
		$this->service = self::getContainer()->get(CalculatorService::class);
	}

	public function testAddition(): void
	{
		$result = $this->service->add(1, 2);
		$this->assertSame(3, $result);
	}
}