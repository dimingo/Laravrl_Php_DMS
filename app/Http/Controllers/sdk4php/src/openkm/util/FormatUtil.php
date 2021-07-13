<?php

namespace openkm\util;

/**
 * FormatUtil
 *
 * @author sochoa
 */
class FormatUtil {

    public static function redondear($numero, $decimales) {
        $factor = pow(10, $decimales);
        return (round($numero * $factor) / $factor);
    }

    public static function formatSize($size) {
        $srt = "BIG";
        if ($size / 1024 < 1) {
            $srt = $size . " Bytes";
        } else if ($size / 1048576 < 1) {
            $srt = self::redondear(($size / 1024), 1) . " KB";
        } else if ($size / 1073741824 < 1) {
            $srt = self::redondear(($size / 1048576), 1) . " MB";
        } else if ($size / 1099511627776 < 1) {
            $srt = self::redondear(($size / 1073741824), 1) . " GB";
        }
        return $srt;
    }

    /**
     * Clean HTML input
     */
    public static function cleanXSS($value) {
        $value = str_replace('&', '&#38;', $value);
        $value = str_replace('<', '&lt;', $value);
        $value = str_replace('>', '&gt;', $value);
        $value = str_replace("'", "&#39;", $value);
        return $value;
    }

}
