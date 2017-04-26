<?php

class FrontPageTest extends TestCase
{
    /**
     * A basic functional test for the front page.
     *
     * @return void
     */
    public function testBasicFrontPage()
    {
        $this->visit('/')
             ->see('Twitter Friends');
    }
}
