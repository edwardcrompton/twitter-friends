<?php

class FrontPageTest extends TestCase
{
    /**
     * @test
     */
    public function FrontPageLoadsWithTitle()
    {
        $this->visit('/')
             ->see('Twitter Friends');
    }
}
