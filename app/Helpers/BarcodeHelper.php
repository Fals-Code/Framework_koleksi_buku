<?php

namespace App\Helpers;

/**
 * Pure PHP Code128 Barcode Generator
 * Generates barcode as base64 PNG - compatible with DomPDF
 * 
 * Code128 Subset B encoding (supports A-Z, a-z, 0-9, and common symbols)
 */
class BarcodeHelper
{
    // Code 128 Subset B character table
    private static $code128B = [
        ' ', '!', '"', '#', '$', '%', '&', "'", '(', ')', '*', '+', ',', '-', '.', '/',
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', ':', ';', '<', '=', '>', '?',
        '@', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O',
        'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '[', '\\', ']', '^', '_',
        '`', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o',
        'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '{', '|', '}', '~', "\x7F"
    ];

    // Code 128 bar patterns (11 bars per character)
    private static $patterns = [
        '11011001100', '11001101100', '11001100110', '10010011000', '10010001100',
        '10001001100', '10011001000', '10011000100', '10001100100', '11001001000',
        '11001000100', '11000100100', '10110011100', '10011011100', '10011001110',
        '10111001100', '10011101100', '10011100110', '11001110010', '11001011100',
        '11001001110', '11011100100', '11001110100', '11101101110', '11101001100',
        '11100101100', '11100100110', '11101100100', '11100110100', '11100110010',
        '11011011000', '11011000110', '11000110110', '10100011000', '10001011000',
        '10001000110', '10110001000', '10001101000', '10001100010', '11010001000',
        '11000101000', '11000100010', '10110111000', '10110001110', '10001101110',
        '10111011000', '10111000110', '10001110110', '11101110110', '11010001110',
        '11000101110', '11011101000', '11011100010', '11011101110', '11101011000',
        '11101000110', '11100010110', '11101101000', '11101100010', '11100011010',
        '11101111010', '11001000010', '11110001010', '10100110000', '10100001100',
        '10010110000', '10010000110', '10000101100', '10000100110', '10110010000',
        '10110000100', '10011010000', '10011000010', '10000110100', '10000110010',
        '11000010010', '11001010000', '11110111010', '11000010100', '10001111010',
        '10100111100', '10010111100', '10010011110', '10111100100', '10011110100',
        '10011110010', '11110100100', '11110010100', '11110010010', '11011011110',
        '11011110110', '11110110110', '10101111000', '10100011110', '10001011110',
        '10111101000', '10111100010', '11110101000', '11110100010', '10111011110',
        '10111101110', '11101011110', '11110101110', '11010000100', '11010010000',
        '11010011100', '11000111010', // Stop pattern
    ];

    // Start Code B = value 104
    // Stop = value 106
    const START_B = 104;
    const STOP    = 106;

    /**
     * Generate Code128B barcode as base64 PNG
     *
     * @param string $text  Text to encode
     * @param int    $height Barcode height in pixels
     * @param int    $barWidth Width of each bar unit in pixels
     * @return string base64 encoded PNG
     */
    public static function generateBase64(string $text, int $height = 60, int $barWidth = 2): string
    {
        // Build values array
        $values = [self::START_B];
        $checksum = self::START_B;

        for ($i = 0; $i < strlen($text); $i++) {
            $charIndex = array_search($text[$i], self::$code128B);
            if ($charIndex === false) {
                // Replace unknown chars with space
                $charIndex = 0;
            }
            $values[] = $charIndex;
            $checksum += ($i + 1) * $charIndex;
        }

        $values[] = $checksum % 103; // Check digit
        $values[] = self::STOP;

        // Build full bar pattern
        $barPattern = '';
        foreach ($values as $v) {
            $barPattern .= self::$patterns[$v];
        }
        $barPattern .= '11'; // Final 2 bars for stop

        // Calculate image dimensions
        $totalBars = strlen($barPattern);
        $imgWidth  = $totalBars * $barWidth;
        $imgHeight = $height;

        // Create image
        $img = imagecreatetruecolor($imgWidth, $imgHeight);
        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);

        imagefill($img, 0, 0, $white);

        // Draw bars
        $x = 0;
        for ($i = 0; $i < strlen($barPattern); $i++) {
            $color = $barPattern[$i] === '1' ? $black : $white;
            imagefilledrectangle($img, $x, 0, $x + $barWidth - 1, $imgHeight - 1, $color);
            $x += $barWidth;
        }

        // Capture output
        ob_start();
        imagepng($img);
        $pngData = ob_get_clean();
        imagedestroy($img);

        return base64_encode($pngData);
    }

    /**
     * Generate full <img> HTML tag with barcode
     *
     * @param string $text
     * @param int    $height
     * @param int    $barWidth
     * @param string $style  Additional CSS style
     * @return string HTML img tag
     */
    public static function img(string $text, int $height = 60, int $barWidth = 2, string $style = ''): string
    {
        $base64 = self::generateBase64($text, $height, $barWidth);
        return '<img src="data:image/png;base64,' . $base64 . '" style="' . $style . '" />';
    }
}
