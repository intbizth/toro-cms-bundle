<?php

namespace Toro\Bundle\CmsBundle\Slug;

class URLify
{
    /**
     * @param string $text
     * @param int $len
     *
     * @return string
     */
    private static function slug($text, $len = 60)
    {
        // https://github.com/bryanbraun/anchorjs/blob/master/anchor.js#L221
        // Regex for finding the nonsafe URL characters (many need escaping): & +$,:;=?@"#{}|^~[`%!']./()*\
        $nonsafeChars = '/[& +$,:;=?@"#{}|^~[`%!\'\]\.\/\(\)\*\\]/';

        $string = trim($text);
        $string = preg_replace("'", '', $string);
        $string = preg_replace($nonsafeChars, '-', $string);
        $string = preg_replace('/-{2,}/', '-', $string);
        $string = substr($string, 0, $len);
        $string = preg_replace('/^-+|-+$/', '', $string);
        $string = strtolower($string);
        $string = trim($text);

        return $string ?: $text;
    }
}
