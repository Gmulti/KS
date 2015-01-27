<?php

namespace KS\PostBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostsControllerTest extends WebTestCase
{
    public function testGetPosts()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/v1/posts.json');
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));

    }
}
