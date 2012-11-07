<?php

/*
 * This file is part of the Linkify library.
 *
 * (c) University of Cambridge
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Misd\Linkify\Test;

/**
 * This makes sure that Linkify::process() converts both URLs and email
 * addresses into links.
 *
 * @author Chris Wilkinson <chris.wilkinson@admin.cam.ac.uk>
 */
class ProcessTest extends LinkifyTest
{
    /**
     * Convert URLs into links test.
     *
     * @test
     */
    public function makeUrlLinks()
    {
        foreach ($this->urlTests as $test) {
            $this->assertEquals($test->expected, $this->linkify->process($test->test));
        }
    }

    /**
     * Convert email addresses into links test.
     *
     * @test
     */
    public function makeEmailLinks()
    {
        foreach ($this->emailTests as $test) {
            $this->assertEquals($test->expected, $this->linkify->process($test->test));
        }
    }

    /**
     * Avoid turning non-URLs and email addresses into links.
     *
     * This makes sure that things that look like either URLs or email
     * addresses are not turned into links by Linkify::processUrls().
     *
     * @test
     */
    public function avoidNonLinks()
    {
        foreach ($this->ignoreTests as $test) {
            $this->assertEquals($test, $this->linkify->process($test));
        }
    }
}
