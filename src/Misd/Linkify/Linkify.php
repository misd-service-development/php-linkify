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
 * Converts URLs and/or email addresses into HTML links.
 *
 * @author Chris Wilkinson <chris.wilkinson@admin.cam.ac.uk>
 */
class Linkify implements LinkifyInterface
{
    /**
     * {@inheritdoc}
     */
    public function process($text)
    {
        return $this->linkify($text);
    }

    /**
     * {@inheritdoc}
     */
    public function processUrls($text)
    {
        return $this->linkify($text, true, false);
    }

    /**
     * {@inheritdoc}
     */
    public function processEmails($text)
    {
        return $this->linkify($text, false, true);
    }

    /**
     * Add links to text.
     *
     * @param string $text   Text to linkify.
     * @param bool   $urls   Linkify URLs?
     * @param bool   $emails Linkify email addresses?
     *
     * @return string Linkified text.
     */
    protected function linkify($text, $urls = true, $emails = true)
    {
        if (false === $urls && false === $emails) {
            // nothing to do...
            return $text;
        }

        $ignoreTags = array('head', 'link', 'a', 'script', 'style', 'code', 'pre', 'select', 'textarea', 'button');

        $chunks = preg_split('/(<.+?>)/is', $text, 0, PREG_SPLIT_DELIM_CAPTURE);

        $openTag = null;

        for ($i = 0; $i < count($chunks); $i++) {
            if ($i % 2 === 0) { // even numbers are text
                // Only process this chunk if there are no unclosed $ignoreTags
                if (null === $openTag) {
                    if (true === $urls) {
                        $chunks[$i] = $this->linkifyUrls($chunks[$i]);
                    }
                    if (true === $emails) {
                        $chunks[$i] = $this->linkifyEmails($chunks[$i]);
                    }
                }
            } else { // odd numbers are tags
                // Only process this tag if there are no unclosed $ignoreTags
                if (null === $openTag) {
                    // Check whether this tag is contained in $ignoreTags and is not self-closing
                    if (preg_match("`<(" . implode('|', $ignoreTags) . ").*(?<!/)>$`is", $chunks[$i], $matches)) {
                        $openTag = $matches[1];
                    }
                } else {
                    // Otherwise, check whether this is the closing tag for $openTag.
                    if (preg_match('`</\s*' . $openTag . '>`i', $chunks[$i], $matches)) {
                        $openTag = null;
                    }
                }
            }
        }

        $text = implode($chunks);

        return $text;
    }

    /**
     * Add HTML links to URLs in plain text.
     *
     * @param string $text Text to linkify.
     *
     * @return string Linkified text.
     */
    protected function linkifyUrls($text)
    {
        $pattern = '
            ~(?xi)
              (?:
                ((ht|f)tps?://)                    # scheme://
                |                                  #   or
                www\d{0,3}\.                       # "www.", "www1.", "www2." ... "www999."
                |                                  #   or
                www\-                              # "www-"
                |                                  #   or
                [a-z0-9.\-]+\.[a-z]{2,4}(?=/)      # looks like domain name followed by a slash
              )
              (?:                                  # Zero or more:
                [^\s()<>]+                         # Run of non-space, non-()<>
                |                                  #   or
                \(([^\s()<>]+|(\([^\s()<>]+\)))*\) # balanced parens, up to 2 levels
              )*
              (?:                                  # End with:
                \(([^\s()<>]+|(\([^\s()<>]+\)))*\) # balanced parens, up to 2 levels
                |                                  #   or
                [^\s`!\-()\[\]{};:\'".,<>?«»“”‘’]   # not a space or one of these punct chars
              )
            ~
        ';

        $callback = function ($match) {
            $caption = $match[0];
            $pattern = "~^(ht|f)tps?://~";

            if (0 === preg_match($pattern, $match[0])) {
                $match[0] = 'http://' . $match[0];
            }

            return '<a href="' . $match[0] . '">' . $caption . '</a>';
        };

        return preg_replace_callback($pattern, $callback, $text);
    }

    /**
     * Add HTML links to email addresses in plain text.
     *
     * @param string $text Text to linkify.
     *
     * @return string Linkified text.
     */
    protected function linkifyEmails($text)
    {
        $pattern = '
            ~(?xi)
            \b
                (?<!=)           # Not part of a query string
                [A-Z0-9._\'%+-]+ # Username
                @                # At
                [A-Z0-9.-]+      # Domain
                \.               # Dot
                [A-Z]{2,4}       # Something
            ~
        ';

        $callback = function ($match) {
            return '<a href="mailto:' . $match[0] . '">' . $match[0] . '</a>';
        };

        return preg_replace_callback($pattern, $callback, $text);
    }
}
