<?php
/**
 * Part of windwalker project.
 *
 * @copyright  Copyright (C) 2014 - 2015 LYRASOFT. All rights reserved.
 * @license    GNU Lesser General Public License version 3 or later.
 */

namespace Windwalker\Test\TestCase;

use PHPUnit\Framework\TestCase;
use Windwalker\Test\Helper\TestStringHelper;

/**
 * The AbstractBaseTestCase class.
 *
 * @since  2.0
 */
abstract class AbstractBaseTestCase extends TestCase
{
    /**
     * assertStringDataEquals
     *
     * @param string $expected
     * @param string $actual
     * @param string $message
     * @param int    $delta
     * @param int    $maxDepth
     * @param bool   $canonicalize
     * @param bool   $ignoreCase
     *
     * @return  void
     */
    public static function assertStringDataEquals(
        $expected,
        $actual,
        $message = '',
        $delta = 0,
        $maxDepth = 10,
        $canonicalize = false,
        $ignoreCase = false
    ) {
        static::assertEquals(
            TestStringHelper::clean($expected),
            TestStringHelper::clean($actual),
            $message,
            $delta,
            $maxDepth,
            $canonicalize,
            $ignoreCase
        );
    }

    /**
     * assertStringDataEquals
     *
     * @param string $expected
     * @param string $actual
     * @param string $message
     * @param int    $delta
     * @param int    $maxDepth
     * @param bool   $canonicalize
     * @param bool   $ignoreCase
     *
     * @return  void
     */
    public static function assertStringSafeEquals(
        $expected,
        $actual,
        $message = '',
        $delta = 0,
        $maxDepth = 10,
        $canonicalize = false,
        $ignoreCase = false
    ) {
        static::assertEquals(
            trim(TestStringHelper::removeCRLF($expected)),
            trim(TestStringHelper::removeCRLF($actual)),
            $message,
            $delta,
            $maxDepth,
            $canonicalize,
            $ignoreCase
        );
    }

    /**
     * assertExpectedException
     *
     * @param callable $closure
     * @param string   $class
     * @param string   $msg
     * @param int      $code
     * @param string   $message
     *
     * @return  void
     */
    public static function assertExpectedException(
        callable $closure,
        $class = \Throwable::class,
        $msg = null,
        $code = null,
        $message = ''
    ) {
        if (is_object($class)) {
            $class = get_class($class);
        }

        try {
            $closure();
        } catch (\Throwable $t) {
            static::assertInstanceOf($class, $t, $message);

            if ($msg !== null) {
                static::assertStringStartsWith($msg, $t->getMessage(), $message);
            }

            if ($code !== null) {
                static::assertEquals($code, $t->getCode(), $message);
            }

            return;
        }

        static::fail('No exception or throwable caught.');
    }
}
