<?php

namespace CaliCastle;

/**
 * Cuid is a library to create collision resistant ids
 * optimized for horizontal scaling and performance.
 */
class Cuid
{
    /**
     * Base 36 constant.
     */
    const BASE36 = 36;

    /**
     * Decimal constant.
     */
    const DECIMAL = 10;

    /**
     * Normal block size.
     */
    const NORMAL_BLOCK = 4;

    /**
     * Counter used to prevent same machine collision.
     *
     * @param integer $blockSize Block size
     *
     * @return string Return count generated hash
     */
    protected static function count($blockSize = self::NORMAL_BLOCK): string
    {
        static $count = 0;

        return self::pad(
            base_convert(
                ++$count,
                self::DECIMAL,
                self::BASE36
            ),
            $blockSize
        );
    }

    /**
     * Fingerprint are used for process identification.
     *
     * @param integer $blockSize Block size
     *
     * @return string Return fingerprint generated hash
     */
    protected static function fingerprint($blockSize = self::NORMAL_BLOCK): string
    {
        // Generate process id based hash
        $pid = self::pad(
            base_convert(
                getmypid(),
                self::DECIMAL,
                self::BASE36
            ),
            self::NORMAL_BLOCK / 2
        );

        // Generate hostname based hash
        $hostname = self::pad(
            base_convert(
                array_reduce(
                    str_split(gethostname()),
                    function ($carry, $char) {
                        return $carry + ord($char);
                    },
                    strlen(gethostname()) + self::BASE36
                ),
                self::DECIMAL,
                self::BASE36
            ),
            2
        );

        return $pid . $hostname;
    }

    /**
     * Pad the input string into specific size.
     *
     * @param string  $input Input string
     * @param integer $size Input size
     *
     * @return string Return padded string
     */
    protected static function pad($input, $size): string
    {
        $input = str_pad(
            $input,
            self::BASE36,
            '0',
            STR_PAD_LEFT
        );

        return substr($input, strlen($input) - $size);
    }

    /**
     * Generate random hash.
     *
     * @param int $blockSize
     *
     * @return string Return random hash string
     */
    protected static function random($blockSize = self::NORMAL_BLOCK): string
    {
        // Get random integer
        $modifier = pow(self::BASE36, self::NORMAL_BLOCK);
        $random = mt_rand() / mt_getrandmax();

        $random = $random * $modifier;

        // Convert integer to hash
        $hash = self::pad(
            base_convert(
                floor($random),
                self::DECIMAL,
                self::BASE36
            ),
            self::NORMAL_BLOCK
        );

        return $hash;
    }

    /**
     * Generate timestamp based hash.
     *
     * @param int $blockSize
     *
     * @return string Return timestamp based hash string
     */
    protected static function timestamp($blockSize = self::NORMAL_BLOCK): string
    {
        // Convert current time up to micro second to hash
        $hash = base_convert(
            floor(microtime(true) * 1000),
            self::DECIMAL,
            self::BASE36
        );

        return $hash;
    }

    /**
     * Generate full version cuid.
     *
     * @param string $prefix
     *
     * @return string Return generated cuid string
     */
    public static function cuid($prefix = 'c'): string
    {
        $timestamp = self::timestamp();
        $count = self::count();
        $fingerprint = self::fingerprint();
        $random = self::random() . self::random();

        return $prefix .
            $timestamp .
            $count .
            $fingerprint .
            $random;
    }

    /**
     * An alias to cuid method.
     *
     * @param string $prefix
     *
     * @return string Return generate cuid string
     */
    public static function make($prefix = 'c'): string
    {
        return self::cuid($prefix);
    }
}
