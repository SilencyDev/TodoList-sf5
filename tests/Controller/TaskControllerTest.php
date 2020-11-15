<?php

namespace tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testListAction()
    {
        $this->logInAdmin();
        $crawler = $this->client->request('GET', '/');

        $link = $crawler->selectLink('Liste des tâches à faire')->link();
        $crawler = $this->client->click($link);

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testListDoneAction()
    {
        $this->logInAdmin();
        $crawler = $this->client->request('GET', '/');

        $link = $crawler->selectLink('Liste des tâches terminées')->link();
        $crawler = $this->client->click($link);

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->logInAdmin();
        $random = mt_rand(1, 10000);
        $crawler = $this->client->request('GET', '/tasks/create');

        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = $random . ' test';
        $form['task[content]'] = 'test';
        $crawler = $this->client->submit($form);

        $crawler = $this->client->followRedirect();
        $this->assertEquals(1, $crawler->filter('.alert-success')->count());
        $link = str_replace("http://localhost", "", $crawler->selectLink($random . ' test')->link()->getUri());

        return [$random,$link];
    }

    /**
     * @depends testCreateAction
     */
    public function testEditAction($randomAndLink)
    {
        $this->logInAdmin();

        $crawler = $this->client->request('GET', $randomAndLink[1]);

        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = $randomAndLink[0] . ' test';
        $form['task[content]'] = 'test modifié';
        $crawler = $this->client->submit($form);

        $crawler = $this->client->followRedirect();

        $this->assertEquals(1, $crawler->filter('.alert-success')->count());

        return str_replace("/tasks/edit/", "", $randomAndLink[1]);
    }

    /**
     * @depends testEditAction
     */
    public function testDeleteTaskBadTokenAction($IdTask)
    {
        $this->logInAdmin();

        $crawler = $this->client->request('GET', '/tasks');

        $form = $crawler->filter(sprintf('#%s > form', $IdTask))->form();
        $form['token'] = "badtoken";
        $crawler = $this->client->submit($form);

        $crawler = $this->client->followRedirect();

        $this->assertEquals(1, $crawler->filter('.alert-danger')->count());
    }


    /**
     * @depends testEditAction
     */
    public function testDeleteTaskBadUserAction($IdTask)
    {
        $this->logInUser();

        $crawler = $this->client->request('GET', '/tasks');

        $form = $crawler->filter(sprintf('#%s > form', $IdTask))->form();
        $form['token'] = $form->get('token')->getValue();
        $crawler = $this->client->submit($form);

        $crawler = $this->client->followRedirect();

        $this->assertEquals(1, $crawler->filter('.alert-danger')->count());
    }

    /**
     * @depends testEditAction
     */
    public function testDeleteTaskAction($IdTask)
    {
        $this->logInAdmin();

        $crawler = $this->client->request('GET', '/tasks');

        $form = $crawler->filter(sprintf('#%s > form', $IdTask))->form();
        $form['token'] = $form->get('token')->getValue();
        $crawler = $this->client->submit($form);

        $crawler = $this->client->followRedirect();

        $this->assertEquals(1, $crawler->filter('.alert-success')->count());
    }

    public function testToggleTaskDoneAction()
    {
        $this->logInAdmin();

        $crawler = $this->client->request('GET', '/tasks');
        $form = $crawler->selectButton('Marquer comme faite')->form();
        $crawler = $this->client->submit($form);

        $crawler = $this->client->followRedirect();

        $this->assertEquals(1, $crawler->filter('.alert-success')->count());
    }

    public function testToggleTaskUndoneAction()
    {
        $this->logInAdmin();

        $crawler = $this->client->request('GET', '/tasks/done');
        $form = $crawler->selectButton('Marquer non terminée')->form();
        $crawler = $this->client->submit($form);

        $crawler = $this->client->followRedirect();

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
