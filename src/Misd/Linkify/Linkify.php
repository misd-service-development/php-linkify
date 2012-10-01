<?php

/*
 * This file is part of the Linkify library.
 *
 * (c) University of Cambridge
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Misd\Linkify;

/**
 * Linkify.
 *
 *
 *
 * @author Chris Wilkinson <chris.wilkinson@admin.cam.ac.uk>
 */
class Linkify
{
    /**
     * Process text.
     *
     * Adds in links to both URLs and email addresses.
     *
     * @param string $text Text to process
     * @return string Processed text
     */
    public function process($text)
    {
        return $this->processEmails($this->processUrls($text));
    }

    /**
     * Process URLs.
     *
     * @param string $text Text to process
     * @return string Processed text
     */
    public function processUrls($text)
    {
        $pattern = <<<EOT
~(?xi)
\b
(
  (?:
    ((https?|ftps?)://)                     # scheme://
    (?:
      /{1,3}                                # 1-3 slashes
      |                                     #   or
      [a-z0-9%]                             # Single letter or digit or '%'
                                            # (Trying not to match e.g. "URI::Escape")
    )
    |                                       #   or
    www\d{0,3}\.                            # "www.", "www1.", "www2." … "www999."
    |                                       #   or
    www\-                                   # "www-"
    |                                       #   or
    [a-z0-9.\-]+\.[a-z]{2,4}/               # looks like domain name followed by a slash
  )
  (?:                                       # One or more:
    [^\s()<>]+                              # Run of non-space, non-()<>
    |                                       #   or
    \(([^\s()<>]+|(\([^\s()<>]+\)))*\)      # balanced parens, up to 2 levels
  )+
  (?:                                       # End with:
    \(([^\s()<>]+|(\([^\s()<>]+\)))*\)      # balanced parens, up to 2 levels
    |                                       #   or
    [^\s`!\-()\[\]{};:'".,<>?«»“”‘’]         # not a space or one of these punct chars
  )
)
~
EOT;
        $text = preg_replace($pattern, '<a href="$0">$0</a>', $text);

        // fix any links missing a scheme
        $pattern = "~<a href=\"[^((https?|ftps?)://)](.*?)\">(.*?)<\/a>~";

        return preg_replace($pattern, "<a href=\"http://$2\">$2</a>", $text);
    }

    /**
     * Process email addresses.
     *
     * @param string $text Text to process
     * @return string Processed text
     */
    public function processEmails($text)
    {
        $pattern = <<<EOT
~(?xi)
\b
(
    (?<!=)                                  # Not part of a query string
    [A-Z0-9._'%+-]+                         # Username
    @                                       # At
    [A-Z0-9.-]+                             # Domain
    \.                                      # Dot
    [A-Z]{2,4}                              # Something
)
~
EOT;

        return preg_replace($pattern, '<a href="mailto:\0">\0</a>', $text);
    }
}