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
 * URL test.
 *
 * @author Chris Wilkinson <chris.wilkinson@admin.cam.ac.uk>
 */
class UrlTest extends LinkifyTest
{
    /**
     * Convert URLs into links test.
     *
     * @test
     */
    public function makeUrlLinks()
    {
        foreach ($this->urlTests as $test) {
            $this->assertEquals($test->expected, $this->linkify->processUrls($test->test));
        }
    }

    /**
     * Avoid turning email address into URL links test.
     *
     * @test
     */
    public function avoidEmailLinks()
    {
        foreach ($this->emailTests as $test) {
            $this->assertEquals($test->test, $this->linkify->processUrls($test->test));
        }
    }

    /**
     * Avoid turning non-URLs into links.
     *
     * @test
     */
    public function avoidLinks()
    {
        foreach ($this->ignoreTests as $test) {
            $this->assertEquals($test, $this->linkify->processUrls($test));
        }
    }
}