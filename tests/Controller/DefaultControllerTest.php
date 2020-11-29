<?php

namespace tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DefaultControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testGetHomepageUnauthorized()
    {
        $this->client->request('GET', '/');

        $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $this->assertEquals(1, $crawler->filter(('input[name="_username"]'))->count());
        $this->assertEquals(1, $crawler->filter(('input[name="_password"]'))->count());
    }

    public function testGetHomepageAuthorized()
    {
        $this->logInAdmin();

        $this->client->request('GET', '/users');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function test404Page()
    {
        $this->logInAdmin();

        $this->client->request('GET', '/sdasdf');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    private function logInAdmin()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'Silency0';
        $form['_password'] = 'test';
        $crawler = $this->client->submit($form);

        $crawler = $this->client->followRedirect();
    }
}
