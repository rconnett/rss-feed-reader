<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RSSFeedControllerTest extends WebTestCase
{

    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        // Create a new entry in the database
        $crawler = $client->request('GET', '/rss/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /rss/");
        $crawler = $client->click($crawler->selectLink('Add RSS Feed')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Save')->form(array(
            'appbundle_rssfeed[name]'  => 'Test',
            'appbundle_rssfeed[url]'  => 'http://slashdot.org/rss/slashdot.rss'
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('h1:contains("Test")')->count(), 'Missing element h1:contains("Test")');

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Edit')->link());

        $form = $crawler->selectButton('Save')->form(array(
            'appbundle_rssfeed[name]'  => 'Test2',
            'appbundle_rssfeed[url]'  => 'http://slashdot.org/rss/slashdot.rss'
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Test2"
        $this->assertGreaterThan(0, $crawler->filter('h1:contains("Test2")')->count(), 'Missing element h1:contains("Test2")');

        // Delete the entity
        $client->submit($crawler->selectButton('Delete')->form());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/Foo/', $client->getResponse()->getContent());
    }


}
