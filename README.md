Linkify
=======

Converts URLs and email addresses into HTML links.

There are many regex variations shared on the internet for performing this task, but few are robust. Linkify contains a large number of unit tests to counter this.

It does not cover every possible valid-yet-never-used URLs and email addresses in order to handle 'real world' usage (eg no 'gopher://'). This means, for example, that it copes better with punctuation errors.

Authors
-------

* Chris Wilkinson <chris.wilkinson@admin.cam.ac.uk>

It uses regex based on John Gruber's [Improved Liberal, Accurate Regex Pattern for Matching URLs](http://daringfireball.net/2010/07/improved_regex_for_matching_urls).

Installation
------------

 1. Add Linkify to your dependencies:

        // composer.json

        {
           // ...
           "require": {
               // ...
               "misd/linkify": "dev-master"
           }
        }

 2. Use Composer to download and install Linkify:

        $ php composer.phar update misd/linkify

Usage
-----

        $linkify = new \Misd\Linkify\Linkify();
        $text = 'This is my text containing a link to www.example.com.';

        echo $linkify->process($text);

Will output:

        This is my text containing a link to <a href="http://www.example.com">www.example.com</a>.