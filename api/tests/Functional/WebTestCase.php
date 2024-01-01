<?php

declare(strict_types=1);

namespace Test\Functional;

use App\Http\Authentication\IdentityInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as SymfonyWebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @internal
 */
abstract class WebTestCase extends SymfonyWebTestCase
{
    protected KernelBrowser $client;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
        $client = static::createClient();
        $client->disableReboot();
        $this->client = $client;
    }

    final public function jsonRequest(string $method, string $path, array $body = [], array $headers = []): Response
    {
        $this->client->jsonRequest($method, $path, $body, $headers);
        return $this->client->getResponse();
    }

    final public function loginUser(IdentityInterface&UserInterface $identity, string $firewall = 'main'): void
    {
        $this->client->loginUser($identity, $firewall);
    }

    /**
     * @param array<array-key, class-string<AbstractFixture>> $fixtures
     * @psalm-suppress PossiblyUnusedMethod
     */
    protected function loadFixtures(array $fixtures): void
    {
        /** @var ContainerInterface $container */
        $container = self::getContainer();
        $loader = new Loader();
        foreach ($fixtures as $class) {
            /** @var AbstractFixture $fixture */
            $fixture = $container->get($class);
            $loader->addFixture($fixture);
        }
        $em = $container->get(EntityManagerInterface::class);
        $executor = new ORMExecutor($em, new ORMPurger($em));
        $executor->execute($loader->getFixtures());
    }
}
