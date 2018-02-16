<?php

class FrontPageTest extends DuskTestCase
{
    /**
     * @test
     *
     * https://laracasts.com/discuss/channels/testing/call-to-undefined-method-viewtransactionlisttestvisit
     */
    public function FrontPageLoadsWithTitle()
    {
        $this->browse(function ($browser) {
            $browser->visit('/')
                ->assertSee('Twitter Friends');
        });
    }
}
