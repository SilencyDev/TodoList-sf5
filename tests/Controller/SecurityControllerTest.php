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

        $crawler = $this->client->request('GET', '/');

        $link = $crawler->selectLink('Se déconnecter')->link();
        $crawler = $this->client->click($link);

        $crawler = $this->client->followRedirect();
        $crawler = $this->client->followRedirect();

        $this->assertEquals(1, $crawler->filter('input[name="_username"]')->count());
        $this->assertEquals(1, $crawler->filter('input[name="_password"]')->count());
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
