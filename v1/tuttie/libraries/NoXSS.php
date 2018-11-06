<?php

/**
 * NoXSS: an anti XSS mitigation class.
 *
 * Copyright (c) 2017 Johannes Ramothale
 * Licensed under the MIT license <http://www.opensource.org/licenses/mit-license.php>
 *
 * @author Johannes Ramothale <johannes@iecon.co.za>
 * @since 28 Aug 2017
 * @version 1.0
 */
final class NoXSS {

    /**
     * prevent - Uses both the internal functions to fend off XSS attacks
     * by striping a string, then escape it.
     *
     * @param string $string - String to clean
     * @return string - Encode string
     */
    public static function prevent($string) {
        return self::escape(self::strip($string));
    }

    /**
     * escape - Deactivates special characters of HTML, and display as string,
     * use it only when display user information that requires no HTML rendering
     *
     * @param string $string - String to clean
     * @return string - Encode string
     */
    public static function escape($string) {
        return htmlspecialchars($string, ENT_QUOTES);
    }

    /**
     * strip - Remove HTML tags in strings before it is used.
     *
     * @param string $string - String to clean
     * @return string - Encode string
     */
    public static function strip($string) {
        return strip_tags(stripcslashes($string));
    }

}
