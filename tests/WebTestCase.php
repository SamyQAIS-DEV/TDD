<?php

namespace App\Tests;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class WebTestCase extends \Symfony\Bundle\FrameworkBundle\Test\WebTestCase
{
    use FixturesTrait;

    protected KernelBrowser $client;
    protected EntityManagerInterface $em;
    protected SerializerInterface $serializer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();
        /** @var EntityManagerInterface $em */
        $this->em = self::getContainer()->get(EntityManagerInterface::class);
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);
        $this->serializer = self::getContainer()->get(SerializerInterface::class);
        parent::setUp();
    }

    protected function tearDown(): void
    {
        $this->em->clear();
        parent::tearDown();
    }

    public function jsonRequest(string $method, string $url, $data = null): string
    {
        $this->client->request($method, $url, [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json',
        ], $data ? $this->serializer->serialize($data, 'json', [AbstractNormalizer::GROUPS => ['write']]) : null);

        return $this->client->getResponse()->getContent();
    }

    /**
     * Vérifie si on a un message de succès.
     */
    public function expectAlert(string $type, ?string $message = null, int $count = 1): void
    {
        $this->assertEquals($count, $this->client->getCrawler()->filter('#floating-alerts alert-element[type="' . $type . '"]')->count());
        if ($message) {
            $this->assertStringContainsString($message, $this->client->getCrawler()->filter('#floating-alerts alert-element[type="' . $type . '"]')->text());
        }
    }

    /**
     * Vérifie si on a un message d'erreur via le texte.
     */
    public function expectErrorAlertMessage(string $message): void
    {
        $this->assertStringContainsString($message, $this->client->getCrawler()->filter('#floating-alerts alert-element[type="error"], .alerts alert-element[type="error"]')->text());
    }

    /**
     * Vérifie si on a un message de succès.
     */
    public function expectSuccessAlert(?string $message = null): void
    {
        $this->expectAlert('success', $message);
    }

    /**
     * Vérifie si on a un message d'erreur.
     */
    public function expectErrorAlert(?string $message = null): void
    {
        $this->expectAlert('error', $message);
    }

    public function expectFormErrors(?int $expectedErrors = null): void
    {
        if (null === $expectedErrors) {
            $this->assertTrue($this->client->getCrawler()->filter('.form-error')->count() > 0, 'Form errors missmatch.');
        } else {
            $this->assertEquals($expectedErrors, $this->client->getCrawler()->filter('.form-error')->count(), 'Form errors missmatch.');
        }
    }

    public function expectH1(string $title): void
    {
        $crawler = $this->client->getCrawler();
        $this->assertEquals(
            $title,
            $crawler->filter('h1')->text(),
            '<h1> missmatch'
        );
    }

    public function expectH2(string $title): void
    {
        $crawler = $this->client->getCrawler();
        $this->assertEquals(
            $title,
            $crawler->filter('h2')->text(),
            '<h1> missmatch'
        );
    }

    public function expectH3(string $title): void
    {
        $crawler = $this->client->getCrawler();
        $this->assertEquals(
            $title,
            $crawler->filter('h3')->text(),
            '<h1> missmatch'
        );
    }

    public function expectTitle(string $title): void
    {
        $crawler = $this->client->getCrawler();
        $this->assertEquals(
            $title . ' | EuroBudget',
            $crawler->filter('title')->text(),
            '<title> missmatch',
        );
    }

    public function expectBodyContains(string $string): void
    {
        $crawler = $this->client->getCrawler();
        $this->assertStringContainsString(
            $string,
            $crawler->filter('body')->text(),
            '<title> missmatch',
        );
    }

    public function login(?User $user)
    {
        if (null === $user) {
            return;
        }
        $this->client->loginUser($user);
    }

    public function setCsrf(string $key): string
    {
        $csrf = uniqid();
        self::getContainer()->get(TokenStorageInterface::class)->setToken($key, $csrf);

        return $csrf;
    }

    protected function getRequest(): Request
    {
        $this->ensureSessionIsAvailable();
        $this->client->request('GET', '/contact');
        return $this->client->getRequest();
    }

    protected function getSession(): SessionInterface
    {
        $this->ensureSessionIsAvailable();
        $this->client->request('GET', '/contact');
        return $this->client->getRequest()->getSession();
    }

    private function ensureSessionIsAvailable(): void
    {
        $container = self::getContainer();
        $requestStack = $container->get('request_stack');

        try {
            $requestStack->getSession();
        } catch (SessionNotFoundException) {
            $session = $container->has('session')
                ? $container->get('session')
                : $container->get('session.factory')->createSession();

            $masterRequest = new Request();
            $masterRequest->setSession($session);

            $requestStack->push($masterRequest);

            $session->start();
            $session->save();

            $cookie = new Cookie($session->getName(), $session->getId());
            $this->client->getCookieJar()->set($cookie);
        }
    }
}
