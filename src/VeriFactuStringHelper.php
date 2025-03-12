<?php
namespace jdg\Verifactu;

class VeriFactuStringHelper
{
    /**
     * Sanitizes a string by trimming whitespace and replacing '&' and '<' characters.
     *
     * @param string $inputString The string to sanitize.
     * @return string The formatted date/time string in ISO 8601 format (YYYY-MM-DDTHH:MM:SS+HH:MM).
     */
    public static function sanitizeString(string $inputString) :string {
        $trimmedString = trim($inputString);
        $sanitizedString = str_replace(['&', '<'], ['&amp;', '&lt;'], $trimmedString);
        return $sanitizedString;
    }

}
