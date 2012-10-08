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

use Misd\Linkify\Linkify;

/**
 * Abstract Linkify test.
 *
 * @author Chris Wilkinson <chris.wilkinson@admin.cam.ac.uk>
 */
abstract class LinkifyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Linkify
     */
    protected $linkify;

    /**
     * @var array
     */
    protected $urlTests;

    /**
     * @var array
     */
    protected $emailTests;

    /**
     * @var array
     */
    protected $ignoreTests;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->linkify = new Linkify();
        $data = json_decode(file_get_contents(__DIR__ . '/../../../data/email.json'));
        $this->emailTests = $data->tests;
        $data = json_decode(file_get_contents(__DIR__ . '/../../../data/url.json'));
        $this->urlTests = $data->tests;
        $data = json_decode(file_get_contents(__DIR__ . '/../../../data/ignore.json'));
        $this->ignoreTests = $data->tests;
    }
}
