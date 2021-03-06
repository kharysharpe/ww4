<?php
/**
 * Part of ww4 project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT.
 * @license    Please see LICENSE file.
 */
declare(strict_types = 1);

namespace Windwalker\String\Test;

use PHPUnit\Framework\TestCase;
use Windwalker\String\StringHelper;

/**
 * The StringHelperTest class.
 *
 * @since  {DEPLOY_VERSION}
 */
class StringHelperTest extends TestCase
{
    /**
     * testGetChar
     *
     * @param int    $pos
     * @param string $expected
     *
     * @dataProvider getCharProvider
     */
    public function testGetChar(int $pos, string $expected)
    {
        self::assertEquals($expected, StringHelper::getChar('白日依山盡', $pos));
    }

    /**
     * getCharProvider
     *
     * @return  array
     */
    public function getCharProvider()
    {
        return [
            [0, '白'],
            [3, '山'],
            [10, ''],
            [-1, '盡'],
            [-2, '山'],
            [-5, '白'],
            [-16, ''],
        ];
    }

    /**
     * testBetween
     *
     * @param $string
     * @param $expected
     * @param $left
     * @param $right
     *
     * @return  void
     *
     * @dataProvider betweenProvider
     */
    public function testBetween($string, $expected, $left, $right, $offset = 0)
    {
        self::assertEquals($expected, StringHelper::between($string, $left, $right, $offset));
    }

    /**
     * betweenProvider
     *
     * @return  array
     */
    public function betweenProvider()
    {
        return [
            ['fòôbàř', 'ôb', 'ò', 'à'],
            ['To {be} or {not} to be', 'be', '{', '}'],
            ['To {be} or {not} to be', 'not', '{', '}', 4],
            ['To {{be} or {not} to be', '{be', '{', '}'],
            ['{foo} and {bar}', 'bar', '{', '}', 1],
            ['foo and {bar', '', '{', '}', 1],
            ['foo} and bar', '', '{', '}', 1],
        ];
    }

    /**
     * testCollapseWhitespace
     *
     * @return  void
     */
    public function testCollapseWhitespace()
    {
        self::assertEquals('foo bar yoo', StringHelper::collapseWhitespaces('foo  bar    yoo'));
        self::assertEquals('foo bar yoo', StringHelper::collapseWhitespaces('  foo  bar yoo '));
        self::assertEquals('foo bar yoo', StringHelper::collapseWhitespaces("  foo\n \r bar\n\r\n yoo \n"));
    }

    /**
     * testContains
     *
     * @param      $expected
     * @param      $string
     * @param      $search
     * @param bool $caseSensitive
     *
     * @dataProvider  containsProvider
     */
    public function testContains($expected, $string, $search, $caseSensitive = true)
    {
        self::assertSame($expected, StringHelper::contains($string, $search, $caseSensitive));
    }

    /**
     * containsProvider
     *
     * @return  array
     */
    public function containsProvider()
    {
        return [
            [true, 'foobar', 'oba'],
            [true, 'fooBar', 'oba', false],
            [false, 'fooBar', 'oba'],
            [true, 'fòôbàř', 'ôbà'],
            [true, '白日依山盡', '日依'],
            [false, '白日依山盡', '梅友仁'],
            [false, 'FÒÔbàř', 'ôbà'],
            [true, 'FÒÔbàř', 'ôbà', false],
        ];
    }

    /**
     * testEndsWith
     *
     * @return  void
     *
     * @dataProvider endsWithProvider
     */
    public function testEndsWith($string, $search, $caseSensitive, $expected)
    {
        self::assertSame($expected, StringHelper::endsWith($string, $search, $caseSensitive));
    }

    /**
     * endsWithProvider
     *
     * @return  array
     */
    public function endsWithProvider()
    {
        return [
            ['Foo', 'oo', StringHelper::CASE_SENSITIVE, true],
            ['Foo', 'Oo', StringHelper::CASE_SENSITIVE, false],
            ['Foo', 'Oo', StringHelper::CASE_INSENSITIVE, true],
            ['Foo', 'ooooo', StringHelper::CASE_SENSITIVE, false],
            ['Foo', 'uv', StringHelper::CASE_SENSITIVE, false],
            ['黃河入海流', '入海流', StringHelper::CASE_SENSITIVE, true],
            ['黃河入海流', '入海流', StringHelper::CASE_INSENSITIVE, true],
            ['黃河入海流', '依山盡', StringHelper::CASE_SENSITIVE, false],
            ['FÒÔbà', 'ôbà', StringHelper::CASE_SENSITIVE, false],
            ['FÒÔbà', 'ôbà', StringHelper::CASE_INSENSITIVE, true],
        ];
    }

    /**
     * testStartsWith
     *
     * @param string $string
     * @param string $search
     * @param bool   $caseSensitive
     * @param bool   $expected
     *
     * @dataProvider estartsWithProvider
     */
    public function testStartsWith(string $string, string $search, bool $caseSensitive, bool $expected)
    {
        self::assertSame($expected, StringHelper::startsWith($string, $search, $caseSensitive));
    }

    /**
     * endsWithProvider
     *
     * @return  array
     */
    public function estartsWithProvider()
    {
        return [
            ['Foo', 'Fo', StringHelper::CASE_SENSITIVE, true],
            ['Foo', 'fo', StringHelper::CASE_SENSITIVE, false],
            ['Foo', 'fo', StringHelper::CASE_INSENSITIVE, true],
            ['Foo', 'foooo', StringHelper::CASE_SENSITIVE, false],
            ['Foo', 'uv', StringHelper::CASE_SENSITIVE, false],
            ['黃河入海流', '黃河', StringHelper::CASE_SENSITIVE, true],
            ['黃河入海流', '黃河', StringHelper::CASE_INSENSITIVE, true],
            ['黃河入海流', '依山盡', StringHelper::CASE_SENSITIVE, false],
            ['FÒÔbà', 'fò', StringHelper::CASE_SENSITIVE, false],
            ['FÒÔbà', 'fò', StringHelper::CASE_INSENSITIVE, true],
        ];
    }

    /**
     * testEnsureLeft
     *
     * @param string $string
     * @param string $search
     * @param string $expected
     *
     * @dataProvider ensureLeftProvider
     */
    public function testEnsureLeft(string $string, string $search, string $expected)
    {
        self::assertSame($expected, StringHelper::ensureLeft($string, $search));
    }

    /**
     * ensureLeftProvider
     *
     * @return  array
     */
    public function ensureLeftProvider()
    {
        return [
            ['FlowerSakura', 'Flower', 'FlowerSakura'],
            ['Sakura', 'Flower', 'FlowerSakura'],
            ['FlowerSakura', 'flower', 'flowerFlowerSakura'],
            ['黃河入海流', '黃河', '黃河入海流'],
            ['入海流', '黃河', '黃河入海流'],
            ['FÒÔbà', 'FÒÔ', 'FÒÔbà'],
            ['FÒÔbà', 'fòô', 'fòôFÒÔbà']
        ];
    }

    /**
     * testEnsureRight
     *
     * @param string $string
     * @param string $search
     * @param string $expected
     *
     * @return  void
     *
     * @dataProvider ensureRightProvider
     */
    public function testEnsureRight(string $string, string $search, string $expected)
    {
        self::assertSame($expected, StringHelper::ensureRight($string, $search));
    }

    /**
     * ensureRightProvider
     *
     * @return  array
     */
    public function ensureRightProvider()
    {
        return [
            ['FlowerSakura', 'Sakura', 'FlowerSakura'],
            ['Flower', 'Sakura', 'FlowerSakura'],
            ['FlowerSakura', 'sakura', 'FlowerSakurasakura'],
            ['黃河入海流', '海流', '黃河入海流'],
            ['黃河入', '海流', '黃河入海流'],
            ['FÒÔbà', 'Ôbà', 'FÒÔbà'],
            ['FÒÔbà', 'ôbà', 'FÒÔbàôbà']
        ];
    }

    /**
     * testHasLowerCase
     *
     * @param string $string
     * @param bool   $expected
     *
     * @return  void
     *
     * @dataProvider hasLowerCaseProvider
     */
    public function testHasLowerCase(string $string, bool $expected)
    {
        self::assertSame($expected, StringHelper::hasLowerCase($string));
    }

    /**
     * hasLowerCaseProvider
     *
     * @return  array
     */
    public function hasLowerCaseProvider()
    {
        return [
            ['Foo', true],
            ['FOO', false],
            ['FÒô', true],
            ['FÒÔ', false],
            ['白日依山盡', false]
        ];
    }

    /**
     * testHasUpperCase
     *
     * @param string $string
     * @param bool   $expected
     *
     * @return  void
     *
     * @dataProvider hasUpperCaseProvider
     */
    public function testHasUpperCase(string $string, bool $expected)
    {
        self::assertSame($expected, StringHelper::hasUpperCase($string));
    }

    /**
     * hasUpperCaseProvider
     *
     * @return  array
     */
    public function hasUpperCaseProvider()
    {
        return [
            ['Foo', true],
            ['foo', false],
            ['FÒô', true],
            ['fòô', false],
            ['白日依山盡', false]
        ];
    }

    /**
     * testInsert
     *
     * @param string $string
     * @param string $insert
     * @param int    $position
     * @param string $expected
     *
     * @return  void
     *
     * @dataProvider insertProvider
     */
    public function testInsert(string $string, string $insert, int $position, string $expected)
    {
        self::assertEquals($expected, StringHelper::insert($string, $insert, $position));
    }

    /**
     * insertProvider
     *
     * @return  array
     */
    public function insertProvider()
    {
        return [
            ['FlowerSakura', 'And', 6, 'FlowerAndSakura'],
            ['fòàř', 'ôb', 2, 'fòôbàř'],
            ['白日山盡', '依', 2, '白日依山盡'],
            ['白日山盡', '依', 6, '白日山盡'],
        ];
    }

    /**
     * testIsLowerCase
     *
     * @param string $string
     * @param bool   $expected
     *
     * @return  void
     *
     * @dataProvider  isLowerCaseProvider
     */
    public function testIsLowerCase(string $string, bool $expected)
    {
        self::assertSame($expected, StringHelper::isLowerCase($string));
    }

    /**
     * isLowerCase
     *
     * @return  array
     */
    public function isLowerCaseProvider()
    {
        return [
            ['flower', true],
            ['Flower', false],
            ['fòôbàř', true],
            ['fòÔbàř', false],
            ['白日依山盡', false]
        ];
    }

    /**
     * testIsUpperCase
     *
     * @param string $string
     * @param bool   $expected
     *
     * @dataProvider isUpperCaseProvider
     */
    public function testIsUpperCase(string $string, bool $expected)
    {
        self::assertSame($expected, StringHelper::isUpperCase($string));
    }

    /**
     * isUpperCaseProvider
     *
     * @return  array
     */
    public function isUpperCaseProvider()
    {
        return [
            ['FLOWER', true],
            ['Flower', false],
            ['FÒÔBÀŘ', true],
            ['fòÔbàř', false],
            ['白日依山盡', false]
        ];
    }

    /**
     * testFirst
     *
     * @param string $string
     * @param int    $length
     * @param string $expected
     *
     * @return  void
     *
     * @dataProvider firstProvider
     */
    public function testFirst(string $string, int $length, string $expected)
    {
        self::assertEquals($expected, StringHelper::first($string, $length));
    }

    /**
     * firstProvider
     *
     * @return  array
     */
    public function firstProvider()
    {
        return [
            ['Foobar', 1, 'F'],
            ['Foobar', 3, 'Foo'],
            ['fòôbàř', 1, 'f'],
            ['fòôbàř', 3, 'fòô'],
            ['白日依山盡', 1, '白'],
            ['白日依山盡', 3, '白日依'],
            ['白日依山盡', 8, '白日依山盡'],
            ['白日依山盡', 0, ''],
            ['白日依山盡', -3, ''],
            ['', 5, ''],
        ];
    }

    /**
     * testLast
     *
     * @param string $string
     * @param int    $length
     * @param string $expected
     *
     * @return  void
     *
     * @dataProvider lastProvider
     */
    public function testLast(string $string, int $length, string $expected)
    {
        self::assertEquals($expected, StringHelper::last($string, $length));
    }

    /**
     * lastProvider
     *
     * @return  array
     */
    public function lastProvider()
    {
        return [
            ['Foobar', 1, 'r'],
            ['Foobar', 3, 'bar'],
            ['fòôbàř', 1, 'ř'],
            ['fòôbàř', 3, 'bàř'],
            ['白日依山盡', 1, '盡'],
            ['白日依山盡', 3, '依山盡'],
            ['白日依山盡', 8, '白日依山盡'],
            ['白日依山盡', 0, ''],
            ['白日依山盡', -8, ''],
            ['', 5, ''],
        ];
    }

    /**
     * testIntersectLeft
     *
     * @return  void
     *
     * @dataProvider intersectLeftProvider
     */
    public function testIntersectLeft(string $string1, string $string2, string $expected)
    {
        self::assertEquals($expected, StringHelper::intersectLeft($string1, $string2));
    }

    /**
     * intersectLeftProvider
     *
     * @return  array
     */
    public function intersectLeftProvider()
    {
        return [
            ['foobar', 'foo bar', 'foo'],
            ['foobar', 'frank', 'f'],
            ['foobar', 'sakura', ''],
            ['foobar', '', ''],
            ['fòôbàř', 'fòô bàř', 'fòô'],
            ['fòôbàř', 'fòôbàř', 'fòôbàř'],
            ['fòôbàř', 'fbàř òô', 'f'],
            ['白日依山盡', '白日夢', '白日'],
            ['白日依山盡', '', '']
        ];
    }

    /**
     * testIntersectRight
     *
     * @param string $string1
     * @param string $string2
     * @param string $expected
     *
     * @return  void
     *
     * @dataProvider intersectRightProvider
     */
    public function testIntersectRight(string $string1, string $string2, string $expected)
    {
        self::assertEquals($expected, StringHelper::intersectRight($string1, $string2));
    }

    /**
     * intersectRightProvider
     *
     * @return  array
     */
    public function intersectRightProvider()
    {
        return [
            ['foobar', 'foo bar', 'bar'],
            ['foobar', 'lunar', 'ar'],
            ['foobar', 'sakura', ''],
            ['foobar', '', ''],
            ['fòôbàř', 'fòô bàř', 'bàř'],
            ['fòôbàř', 'fòôbàř', 'fòôbàř'],
            ['fòôbàř', 'fbàòôř', 'ř'],
            ['白日依山盡', '無盡', '盡'],
            ['白日依山盡', '', '']
        ];
    }

    /**
     * testIntersect
     *
     * @param string $string1
     * @param string $string2
     * @param string $expected
     *
     * @return  void
     *
     * @dataProvider intersectProvider
     */
    public function testIntersect(string $string1, string $string2, string $expected)
    {
        self::assertEquals($expected, StringHelper::intersect($string1, $string2));
    }

    /**
     * intersectProvider
     *
     * @return  array
     */
    public function intersectProvider()
    {
        return [
            ['foobar', 'f oob ar', 'oob'],
            ['foobar', 'neobike', 'ob'],
            ['foobar', 'uncle', ''],
            ['foobar', '', ''],
            ['fòôbàř', 'f òôb àř', 'òôb'],
            ['fòôbàř', 'fòôbàř', 'fòôbàř'],
            ['fòôbàř', 'fbàòôř', 'òô'],
            ['漢字順序不一定影響閱讀', '漢字序順不一定影閱響讀', '不一定影'],
            ['白日依山盡', '', '']
        ];
    }

    /**
     * testPad
     *
     * @param string $string
     * @param string $substring
     * @param int    $length
     * @param string $expected
     *
     * @dataProvider padProvider
     */
    public function testPad(string $string, string $substring, int $length, string $expected)
    {
        self::assertEquals($expected, StringHelper::pad($string, $length, $substring));
    }

    /**
     * padProvider
     *
     * @return  array
     */
    public function padProvider()
    {
        return [
            ['foobar', '_', -1, 'foobar'],
            ['foobar', '_', 3, 'foobar'],
            ['foobar', '_/', 9, '_foobar_/'],
            ['foobar', '_/', 10, '_/foobar_/'],
            ['fòôbàř', '¬ø', 9, '¬fòôbàř¬ø'],
            ['fòôbàř', '¬øÿ', 11, '¬øfòôbàř¬øÿ'],
            ['妳好', '嗨', 5, '嗨妳好嗨嗨']
        ];
    }

    /**
     * testPadLeft
     *
     * @param string $string
     * @param string $substring
     * @param int    $length
     * @param string $expected
     *
     * @return  void
     *
     * @dataProvider padLeftProvider
     */
    public function testPadLeft(string $string, string $substring, int $length, string $expected)
    {
        self::assertEquals($expected, StringHelper::padLeft($string, $length, $substring));
    }

    /**
     * padLeftProvider
     *
     * @return  array
     */
    public function padLeftProvider()
    {
        return [
            ['foobar', '_', -1, 'foobar'],
            ['foobar', '_', 3, 'foobar'],
            ['foobar', '_/', 9, '_/_foobar'],
            ['foobar', '_/', 10, '_/_/foobar'],
            ['fòôbàř', '¬ø', 9, '¬ø¬fòôbàř'],
            ['fòôbàř', '¬øÿ', 11, '¬øÿ¬øfòôbàř'],
            ['妳好', '嗨', 5, '嗨嗨嗨妳好']
        ];
    }

    /**
     * testPadRight
     *
     * @param string $string
     * @param string $substring
     * @param int    $length
     * @param string $expected
     *
     * @return  void
     *
     * @dataProvider padRightProvider
     */
    public function testPadRight(string $string, string $substring, int $length, string $expected)
    {
        self::assertEquals($expected, StringHelper::padRight($string, $length, $substring));
    }

    /**
     * padRightProvider
     *
     * @return  array
     */
    public function padRightProvider()
    {
        return [
            ['foobar', '_', -1, 'foobar'],
            ['foobar', '_', 3, 'foobar'],
            ['foobar', '_/', 9, 'foobar_/_'],
            ['foobar', '_/', 10, 'foobar_/_/'],
            ['fòôbàř', '¬ø', 9, 'fòôbàř¬ø¬'],
            ['fòôbàř', '¬øÿ', 11, 'fòôbàř¬øÿ¬ø'],
            ['妳好', '嗨', 5, '妳好嗨嗨嗨']
        ];
    }

    /**
     * testRemove
     *
     * @param string $string
     * @param int    $offset
     * @param string $expected
     *
     * @return  void
     *
     * @dataProvider removeProvider
     */
    public function testRemoveChar(string $string, int $offset, string $expected)
    {
        self::assertEquals($expected, StringHelper::removeChar($string, $offset));
    }

    /**
     * removeProvider
     *
     * @return  array
     */
    public function removeProvider()
    {
        return [
            ['Foobar', 3, 'Fooar'],
            ['桃之夭夭', 0, '之夭夭'],
            ['下筆千言', 2, '下筆言'],
            ['白日依山盡', -2, '白日依盡'],
            ['白日依山盡', -5, '日依山盡'],
            ['白日依山盡', 5, '白日依山盡'],
            ['下筆千言', 6, '下筆千言'],
            ['下筆千言', -8, '下筆千言'],
        ];
    }

    /**
     * testRemoveLeft
     *
     * @param $string
     * @param $search
     * @param $expected
     *
     * @return  void
     *
     * @dataProvider removeLeftProvider
     */
    public function testRemoveLeft($string, $search, $expected)
    {
        self::assertEquals($expected, StringHelper::removeLeft($string, $search));
    }

    /**
     * removeRightProvider
     *
     * @return  array
     */
    public function removeLeftProvider()
    {
        return [
            ['foobar', 'hoo', 'foobar'],
            ['foobar', 'fo', 'obar'],
            ['fòôbàř', 'òôb', 'fòôbàř'],
            ['fòôbàř', 'fò', 'ôbàř'],
            ['白日依山盡', '入海流', '白日依山盡'],
            ['白日依山盡', '白日', '依山盡'],
            ['白日依山盡', '', '白日依山盡'],
            ['', '白日', ''],
        ];
    }

    /**
     * testRemoveRight
     *
     * @return  void
     *
     * @dataProvider removeRightProvider
     */
    public function testRemoveRight($string, $search, $expected)
    {
        self::assertEquals($expected, StringHelper::removeRight($string, $search));
    }

    /**
     * removeRightProvider
     *
     * @return  array
     */
    public function removeRightProvider()
    {
        return [
            ['foobar', 'hoo', 'foobar'],
            ['foobar', 'ar', 'foob'],
            ['fòôbàř', 'òôb', 'fòôbàř'],
            ['fòôbàř', 'àř', 'fòôb'],
            ['白日依山盡', '入海流', '白日依山盡'],
            ['白日依山盡', '依山盡', '白日'],
            ['白日依山盡', '', '白日依山盡'],
            ['', '白日', ''],
        ];
    }

    /**
     * testSlice
     *
     * @param string $string
     * @param int    $start
     * @param int    $end
     * @param string $expected
     *
     * @dataProvider sliceProvider
     */
    public function testSlice(string $string, int $start, int $end = null, string $expected = '')
    {
        self::assertEquals($expected, StringHelper::slice($string, $start, $end));
    }

    /**
     * sliceProvider
     *
     * @return  array
     */
    public function sliceProvider()
    {
        return [
            ['Foobar Allstar', 0, 3, 'Foo'],
            ['Foobar Allstar', 3, 10, 'bar All'],
            ['Foobar Allstar', 3, null, 'bar Allstar'],
            ['Foobar Allstar', 3, -10, 'b'],
            ['Foobar Allstar', 10, 3, ''],
            ['fòôbàř àřfòôbf', 0, 3, 'fòô'],
            ['fòôbàř àřfòôbf', 3, 10, 'bàř àřf'],
            ['fòôbàř àřfòôbf', 3, null, 'bàř àřfòôbf'],
            ['fòôbàř àřfòôbf', 3, -10, 'b'],
            ['fòôbàř àřfòôbf', 10, 3, ''],
            ['白日依山盡 黃河入海流', 0, 3, '白日依'],
            ['白日依山盡 黃河入海流', 3, 10, '山盡 黃河入海'],
            ['白日依山盡 黃河入海流', 3, 15, '山盡 黃河入海流'],
        ];
    }

    /**
     * testSubstring
     *
     * @param string $string
     * @param int    $start
     * @param int    $end
     * @param string $expected
     *
     * @return  void
     *
     * @dataProvider substringProvider
     */
    public function testSubstring(string $string, int $start, int $end = null, string $expected = '')
    {
        self::assertEquals($expected, StringHelper::substring($string, $start, $end));
    }

    /**
     * substringProvider
     *
     * @return  array
     */
    public function substringProvider()
    {
        return [
            ['Foobar Allstar', 0, 3, 'Foo'],
            ['Foobar Allstar', 3, 10, 'bar All'],
            ['Foobar Allstar', 3, null, 'bar Allstar'],
            ['Foobar Allstar', 3, -10, 'b'],
            ['Foobar Allstar', 10, 3, 'bar All'],
            ['fòôbàř àřfòôbf', 0, 3, 'fòô'],
            ['fòôbàř àřfòôbf', 3, 10, 'bàř àřf'],
            ['fòôbàř àřfòôbf', 3, null, 'bàř àřfòôbf'],
            ['fòôbàř àřfòôbf', 3, -10, 'b'],
            ['fòôbàř àřfòôbf', 10, 3, 'bàř àřf'],
            ['白日依山盡 黃河入海流', 0, 3, '白日依'],
            ['白日依山盡 黃河入海流', 3, 10, '山盡 黃河入海'],
            ['白日依山盡 黃河入海流', 3, 15, '山盡 黃河入海流'],
        ];
    }

    /**
     * testSurround
     *
     * @param string       $string
     * @param string       $expected
     * @param string|array $substring
     *
     * @return  void
     *
     * @dataProvider surroundProvider
     */
    public function testSurround(string $string, string $expected, $substring = null)
    {
        if ($substring === null) {
            self::assertEquals($expected, StringHelper::surround($string));
        } else {
            self::assertEquals($expected, StringHelper::surround($string, $substring));
        }
    }

    /**
     * surroundProvider
     *
     * @return  array
     */
    public function surroundProvider()
    {
        return [
            ['foo', '"foo"'],
            ['foo', '"foo"', '"'],
            ['foo', '"foo"', ['"', '"']],
            ['foo', '[foo]', ['[', ']']],
        ];
    }

    /**
     * testToggleCase
     *
     * @param string $string
     * @param string $expected
     *
     * @return  void
     *
     * @dataProvider toggleCaseProvider
     */
    public function testToggleCase(string $string, string $expected)
    {
        self::assertEquals($expected, StringHelper::toggleCase($string));
    }

    /**
     * toggleCaseProvider
     *
     * @return  array
     */
    public function toggleCaseProvider()
    {
        return [
            ['FooBar', 'fOObAR'],
            ['foobar', 'FOOBAR'],
            ['FòôBàř', 'fÒÔbÀŘ'],
            ['fòôbàř', 'FÒÔBÀŘ'],
            ['白日依山盡', '白日依山盡']
        ];
    }

    /**
     * testTruncate
     *
     * @param string $string
     * @param int    $length
     * @param bool   $wordBreak
     * @param string $expected
     * @param string $suffix
     *
     * @dataProvider truncateProvider
     */
    public function testTruncate(
        string $string,
        int $length,
        string $expected = '',
        string $suffix = '',
        bool $wordBreak = true
    ) {
        self::assertEquals($expected, StringHelper::truncate($string, $length, $suffix, $wordBreak));
    }

    /**
     * truncateProvider
     *
     * @return  array
     */
    public function truncateProvider()
    {
        return [
            ['Hello foo bar', 5, 'Hello'],
            ['Hello foo bar', 5, 'Hello...', '...'],
            ['Hello foo bar', 6, 'Hello ...', '...'],
            ['Hello foo bar', 8, 'Hello fo...', '...'],
            ['Hello foo bar', 8, 'Hello...', '...', false],
            ['Hello foo bar', 13, 'Hello foo bar'],
            ['Hello foo bar', 15, 'Hello foo bar'],
            ['Hello foo bar', 15, 'Hello foo bar'],
            ['白日依山盡 黃河入海流', 4, '白日依山'],
            ['白日依山盡 黃河入海流', 4, '白日依山...', '...'],
        ];
    }
}
