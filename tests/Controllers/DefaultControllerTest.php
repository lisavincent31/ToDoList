<?php

namespace App\Tests\Controllers;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();
    }

    /**
     * Test the render of homepage
     */
    public function testIndex(): void
    {
        $client = $this->client;
        $client->enableProfiler();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        // Fetch the profiler
        $profile = $client->getProfile();
        $collector = $profile->getCollector('twig');

        // Verify if it's the right template used
        $templates = array_keys($collector->getTemplates());
        $this->assertContains('default/index.html.twig', $templates);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $this->restoreExceptionHandler();
    }

    protected function restoreExceptionHandler(): void
    {
        while (true) {
            $previousHandler = set_exception_handler(static fn() => null);

            restore_exception_handler();

            if ($previousHandler === null) {
                break;
            }

            restore_exception_handler();
        }
    }
}