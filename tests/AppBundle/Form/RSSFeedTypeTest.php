<?php

namespace Tests\AppBundle\Form;

use AppBundle\Form\RSSFeedType;
use AppBundle\Entity\RSSFeed;
use Symfony\Component\Form\Test\TypeTestCase;

class RSSFeedTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = array(
            'name' => 'Test Name',
            'url' => 'http://www.example.com/feed.rss',
        );

        $form = $this->factory->create(RSSFeedType::class);

        $object = new RSSFeed();
        $object->setName($formData['name']);
        $object->setUrl($formData['url']);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        // We need to make sure the timestamps match otherwise the next test will fail
        $formObject = $form->getData();
        $object->setCreated($formObject->getCreated());
        $object->setUpdated($formObject->getUpdated());

        $this->assertEquals($object, $formObject);

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
