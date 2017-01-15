<?php

declare(strict_types = 1);
namespace Tests\AppBundle\Presenter\MasterPresenter;

use AppBundle\Presenter\MasterPresenter;

class ConstructorTest extends \PHPUnit_Framework_TestCase
{
    public function testSetting()
    {
        $presenter = new MasterPresenter('myenv');

        $presenter->set('one', 1, true);
        // overwrite, but keep in feed
        $presenter->set('one', $one = 2);

        // not in feed
        $presenter->set('two', $two = 't');

        $presenter->setTitle($title = 'MyTitle');

        $this->assertSame($one, $presenter->get('one'));
        $this->assertSame($two, $presenter->get('two'));

        $data = $presenter->getData();
        $feed = $presenter->getFeedData();

        $this->assertSame($one, $data['one']);
        $this->assertSame($two, $data['two']);
        $this->assertSame($title, $data['meta']['title']);
        $this->assertSame('MyTitle - hammerspace', $data['meta']['fullTitle']);

        $this->assertSame($one, $feed->one);
        $this->assertFalse(isset($feed->two));
        $this->assertSame($title, $feed->meta->title);
        $this->assertSame('MyTitle - hammerspace', $feed->meta->fullTitle);
    }

    /** @expectedException \InvalidArgumentException */
    public function testNoSuchParam()
    {
        $presenter = new MasterPresenter();
        $presenter->get('noreal');
    }

    public function testFullTitle()
    {
        $presenter = new MasterPresenter();
        $presenter->setFullTitle($title = 'This is full');

        $data = $presenter->getData();

        $this->assertSame($title, $data['meta']['fullTitle']);
    }
}
