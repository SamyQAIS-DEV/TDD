<?php

namespace App\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

trait FixturesTrait
{
    public function loadFixtures(array $fixtures): void
    {
	    $loader = new Loader();
        foreach ($fixtures as $fixture) {
	        $loader->addFixture(new $fixture());
        }

	    $purger = new ORMPurger($this->em);
	    $executor = new ORMExecutor($this->em, $purger);
	    $executor->execute($loader->getFixtures());
    }
}