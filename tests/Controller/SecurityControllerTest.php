<?php

namespace tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testSuccessfullLogin()
    {
        $this->logInAdmin();

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testLogout()
    {
        $this->logInAdmin();

        $crawler = $this->client->request('GET', '/logout');
        $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
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