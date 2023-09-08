<?php

class StaleContentNotifierTest extends WP_UnitTestCase
{

    function setUp(): void
    {
        parent::setUp();

        // Create some posts
        for ($i = 0; $i < 10; $i++) {
            $this->factory->post->create([
                'post_date' => date('Y-m-d H:i:s', strtotime("-4 months"))
            ]);
        }

        for ($i = 0; $i < 5; $i++) {
            $this->factory->post->create([
                'post_date' => date('Y-m-d H:i:s', strtotime("-2 months"))
            ]);
        }
    }

    function test_scn_get_stale_content()
    {
        $stale_posts = scn_get_stale_content('3 months');
        $this->assertCount(10, $stale_posts);

        $stale_posts = scn_get_stale_content('60 days');
        $this->assertCount(15, $stale_posts);

        $stale_posts = scn_get_stale_content('6 months');
        $this->assertEmpty($stale_posts);
    }

    function test_option_functions()
    {
        update_option('scn_stale_timeframe', '1_year');
        $this->assertEquals('1_year', get_option('scn_stale_timeframe'));

        update_option('scn_enable_email_notifications', '0');
        $this->assertEquals('0', get_option('scn_enable_email_notifications'));

        update_option('scn_notification_email', 'some-admin@somedomain.com');
        $this->assertEquals('some-admin@somedomain.com', get_option('scn_notification_email'));
    }
}
