<?php
/**
 * Part of ww4 project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT.
 * @license    Please see LICENSE file.
 */
declare(strict_types = 1);

namespace Windwalker\String;

use Traversable;
use Windwalker\Utilities\Classes\ImmutableHelperTrait;
use Windwalker\Utilities\Classes\StringableInterface;

/**
 * The StringObject class.
 *
 * @see  StringHelper
 *
 * @method StringObject getChar(int $pos)
 * @method StringObject between(string $start, string $end, int $offset = 0)
 * @method StringObject collapseWhitespaces(string $string)
 * @method StringObject contains(string $search, bool $caseSensitive = true)
 * @method StringObject endsWith(string $search, bool $caseSensitive = true)
 * @method StringObject startsWith(string $target, bool $caseSensitive = true)
 * @method StringObject ensureLeft(string $search)
 * @method StringObject ensureRight(string $search)
 * @method StringObject hasLowerCase()
 * @method StringObject hasUpperCase()
 * @method StringObject match(string $pattern, string $option = 'msr')
 * @method StringObject insert(string $insert, int $position)
 * @method bool         isLowerCase()
 * @method bool         isUpperCase()
 * @method StringObject first(int $length = 1)
 * @method StringObject last(int $length = 1)
 * @method StringObject intersectLeft(string $string2)
 * @method StringObject intersectRight(string $string2)
 * @method StringObject intersect(string $string2)
 * @method StringObject pad(int $length = 0, string $substring = ' ')
 * @method StringObject padLeft(int $length = 0, string $substring = ' ')
 * @method StringObject padRight(int $length = 0, string $substring = ' ')
 * @method StringObject removeChar(int $offset, int $length = null)
 * @method StringObject removeLeft(string $search)
 * @method StringObject removeRight(string $search)
 * @method StringObject slice(int $start, int $end = null)
 * @method StringObject substring(int $start, int $end = null)
 * @method StringObject surround($substring = ['"', '"'])
 * @method StringObject toggleCase()
 * @method StringObject truncate(int $length, string $suffix = '', bool $wordBreak = true)
 *
 * @since  __DEPLOY_VERSION__
 */
class StringObject implements \Countable, \ArrayAccess, \IteratorAggregate, StringableInterface
{
    use ImmutableHelperTrait;

    /**
     * We only provides 3 default encoding constants of PHP.
     * @see http://php.net/manual/en/xml.encoding.php
     */
    public const ENCODING_DEFAULT_ISO = 'ISO-8859-1';
    public const ENCODING_UTF8 = 'UTF-8';
    public const ENCODING_US_ASCII = 'US-ASCII';

    /**
     * Property string.
     *
     * @var  string
     */
    protected $string = '';

    /**
     * Property encoding.
     *
     * @var  string
     */
    protected $encoding = null;

    /**
     * StringObject constructor.
     *
     * @see  http://php.net/manual/en/mbstring.supported-encodings.php
     *
     * @param string      $string
     * @param null|string $encoding
     */
    public function __construct(string $string = '', ?string $encoding = self::ENCODING_UTF8)
    {
        $this->string   = $string;
        $this->encoding = $encoding === null ? static::ENCODING_UTF8 : $encoding;
    }

    /**
     * __call
     *
     * @param string $name
     * @param array  $args
     *
     * @return  mixed
     * @throws \BadMethodCallException
     */
    public function __call(string $name, array $args)
    {
        $class = StringHelper::class;

        if (is_callable([$class, $name])) {
            return $this->callProxy($class, $name, $args);
        }

        throw new \BadMethodCallException(sprintf('Call to undefined method: %s::%s()', get_called_class(), $name));
    }

    /**
     * callProxy
     *
     * @param string $class
     * @param string $method
     * @param array  $args
     *
     * @return  static
     */
    protected function callProxy(string $class, string $method, array $args)
    {
        $new = $this->cloneInstance();

        $ref = new \ReflectionMethod($class, $method);
        $params = $ref->getParameters();
        array_shift($params);

        /** @var \ReflectionParameter $param */
        foreach (array_values($params) as $k => $param) {
            if (!array_key_exists($k, $args)) {
                if ($param->getName() === 'encoding' && !isset($args[$k])) {
                    $args[$k] = $this->encoding;
                    continue;
                }

                $args[$k] = $param->getDefaultValue();
            }
        }

        $result = $class::$method($new->string, ...$args);

        if (is_string($result)) {
            $new->string = $result;

            return $new;
        }

        return $result;
    }

    /**
     * Retrieve an external iterator
     *
     * @link  http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     *        <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->split());
    }

    /**
     * Whether a offset exists
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     *
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        $offset = $offset >= 0 ? $offset : (int) abs($offset) - 1;

        return $this->length() > $offset;
    }

    /**
     * Offset to retrieve
     *
     * @param int $offset The offset to retrieve.
     *
     * @return string Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->getChar($offset);
    }

    /**
     * Offset to set
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $string <p>
     *                      The value to set.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $string)
    {
        $this->string = Utf8String::substrReplace($this->string, $string, $offset, 1, $this->encoding);
    }

    /**
     * Offset to unset
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        if ($this->length() < abs($offset)) {
            return;
        }

        $this->string = StringHelper::removeChar($this->string, $offset, 1, $this->encoding);
    }

    /**
     * Count elements of an object
     *
     * @link  http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     *        </p>
     *        <p>
     *        The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return $this->length();
    }

    /**
     * Magic method to convert this object to string.
     *
     * @return  string
     */
    public function __toString(): string
    {
        return (string) $this->string;
    }

    /**
     * Method to get property Encoding
     *
     * @return  string
     */
    public function getEncoding(): string
    {
        return $this->encoding;
    }

    /**
     * Method to set property encoding
     *
     * @param   string $encoding
     *
     * @return  static  Return self to support chaining.
     */
    public function withEncoding(string $encoding)
    {
        return $this->cloneInstance(function (StringObject $new) use ($encoding) {
            $new->encoding = $encoding;
        });
    }

    /**
     * Method to get property String
     *
     * @return  string
     */
    public function getString(): string
    {
        return $this->string;
    }

    /**
     * Method to set property string
     *
     * @param   string $string
     *
     * @return  static  Return self to support chaining.
     */
    public function withString(string $string)
    {
        return $this->cloneInstance(function (StringObject $new) use ($string) {
            $new->string = $string;
        });
    }

    /**
     * toLowerCase
     *
     * @return  static
     */
    public function toLowerCase()
    {
        $new = $this->cloneInstance();

        $new->string = Utf8String::strtolower($new->string, $new->encoding);

        return $new;
    }

    /**
     * toUpperCase
     *
     * @return  static
     */
    public function toUpperCase()
    {
        return $this->cloneInstance(function (StringObject $new) {
            $new->string = Utf8String::strtoupper($new->string, $new->encoding);
        });
    }

    /**
     * length
     *
     * @return  int
     */
    public function length(): int
    {
        return Utf8String::strlen($this->string, $this->encoding);
    }

    /**
     * split
     *
     * @param int $length
     *
     * @return  array|bool
     */
    public function split($length = 1)
    {
        return Utf8String::strSplit($this->string, $length, $this->encoding);
    }

    /**
     * replace
     *
     * @param array|string $search
     * @param array|string $replacement
     * @param int|null     $count
     *
     * @return  static
     */
    public function replace($search, $replacement, int &$count = null)
    {
        return $this->cloneInstance(function (StringObject $new) use ($search, $replacement, &$count) {
            $new->string = str_replace($search, $replacement, $new->string, $count);
        });
    }

    /**
     * compare
     *
     * @param string $compare
     * @param bool   $caseSensitive
     *
     * @return  int
     */
    public function compare(string $compare, bool $caseSensitive = true)
    {
        if ($caseSensitive) {
            return Utf8String::strcmp($this->string, $compare);
        }

        return Utf8String::strcasecmp($this->string, $compare, $this->encoding);
    }

    /**
     * reverse
     *
     * @return  static
     */
    public function reverse()
    {
        return $this->cloneInstance(function (StringObject $new) {
            $new->string = Utf8String::strrev($new->string);
        });
    }

    /**
     * substrReplace
     *
     * @param string $replace
     * @param int    $start
     * @param int    $offset
     *
     * @return  static
     */
    public function substrReplace(string $replace, int $start, int $offset = null)
    {
        return $this->cloneInstance(function (StringObject $new) use ($replace, $start, $offset) {
            $new->string = Utf8String::substrReplace($new->string, $replace, $start, $offset, $this->encoding);
        });
    }

    /**
     * ltrim
     *
     * @param string|null $charlist
     *
     * @return  static
     */
    public function trimLeft(string $charlist = null)
    {
        return $this->cloneInstance(function (StringObject $new) use ($charlist) {
            $new->string = Utf8String::ltrim($new->string, $charlist);
        });
    }

    /**
     * rtrim
     *
     * @param string|null $charlist
     *
     * @return  static
     */
    public function trimRight(string $charlist = null)
    {
        return $this->cloneInstance(function (StringObject $new) use ($charlist) {
            $new->string = Utf8String::rtrim($new->string, $charlist);
        });
    }

    /**
     * trim
     *
     * @param string|null $charlist
     *
     * @return  static
     */
    public function trim(string $charlist = null)
    {
        return $this->cloneInstance(function (StringObject $new) use ($charlist) {
            $new->string = Utf8String::trim($new->string, $charlist);
        });
    }

    /**
     * ucfirst
     *
     * @return  static
     */
    public function upperCaseFirst()
    {
        return $this->cloneInstance(function (StringObject $new) {
            $new->string = Utf8String::ucfirst($new->string, $this->encoding);
        });
    }

    /**
     * lcfirst
     *
     * @return  static
     */
    public function lowerCaseFirst()
    {
        return $this->cloneInstance(function (StringObject $new) {
            $new->string = Utf8String::lcfirst($new->string, $this->encoding);
        });
    }

    /**
     * upperCaseWords
     *
     * @return  static
     */
    public function upperCaseWords()
    {
        return $this->cloneInstance(function (StringObject $new) {
            $new->string = Utf8String::ucwords($new->string, $this->encoding);
        });
    }

    /**
     * substrCount
     *
     * @param string $search
     * @param bool   $caseSensitive
     *
     * @return  static
     */
    public function substrCount(string $search, bool $caseSensitive = true)
    {
        return $this->cloneInstance(function (StringObject $new) use ($search, $caseSensitive) {
            $new->string = Utf8String::substrCount($new->string, $search, $caseSensitive, $this->encoding);
        });
    }
}
