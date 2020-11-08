<?php

namespace tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testListAction()
    {
        $this->logInAdmin();
        $crawler = $this->client->request('GET', '/users');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testListActionBadRoles()
    {
        $this->logInUser();
        $crawler = $this->client->request('GET', '/users');

        $this->assertEquals(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $crawler = $this->client->request('GET', '/users/create');
        $random = mt_rand(1,10000);

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = $random.'Silency';
        $form['user[password][first]'] = 'test';
        $form['user[password][second]'] = 'test';
        $form['user[email]'] = mt_rand(1,10000).'test@test.fr';
        $form['user[roles]'] = "ROLE_ADMIN";


        $crawler = $this->client->submit($form);

        $crawler = $this->client->followRedirect();

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('.alert-success')->count());
    }

    public function testCreateWhileConnectedAction()
    {
        $this->logInAdmin();

        $crawler = $this->client->request('GET', '/users/create');
        $random = mt_rand(1,10000);

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = $random.'Silency';
        $form['user[password][first]'] = 'test';
        $form['user[password][second]'] = 'test';
        $form['user[email]'] = mt_rand(1,10000).'test@test.fr';
        $form['user[roles]'] = "ROLE_ADMIN";


        $crawler = $this->client->submit($form);

        $crawler = $this->client->followRedirect();

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('.alert-success')->count());
    }

    /**
     * This test must be ran with a database starting at id = 1 with the fixtures provided
     * 
     * php bin/console cache:clear --env=test  
     * 
     * php bin/console doctrine:database:drop --force --env=test
     * php bin/console doctrine:database:create --env=test
     * php bin/console doctrine:migrations:migrate --env=test --no-interaction
     * 
     * php bin/console doctrine:fixtures:load --env=test 
     */
    public function testEditAction()
    {
        $this->logInAdmin();
        $crawler = $this->client->request('GET', '/users/edit/3');

        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = 'Silency2';
        $form['user[password][first]'] = 'test2';
        $form['user[password][second]'] = 'test2';
        $form['user[email]'] = 'test2@test.fr';
        $form['user[roles]'] = "ROLE_ADMIN";

        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('.alert-success')->count());
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

    private function logInUser()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'Silency1';
        $form['_password'] = 'test';
        $crawler = $this->client->submit($form);

        $crawler = $this->client->followRedirect();
    }
}