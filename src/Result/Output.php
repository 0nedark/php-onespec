<?php
/**
 * Created by PhpStorm.
 * User: drupsys
 * Date: 25/08/18
 * Time: 15:36
 */

namespace Xae3Oow5cahz9shahngu\Result;

use function Functional\filter;
use function Functional\flatten;
use function Functional\map;
use function Functional\zip_all;
use Traversable;

class Output implements \IteratorAggregate, Word
{
    /**
     * @var string
     */
    private $status;
    /**
     * @var string
     */
    private $message;
    /**
     * @var string[]
     */
    private $bindings;

    /**
     * Result constructor.
     *
     * @param string $status
     * @param Text|null $message
     * @param Text[] $bindings
     */
    public function __construct(string $status, ?Text $message, array $bindings = [])
    {
        $this->status = $status;
        $this->message = isset($message) ? $message : new Text('', Color::PRIMARY);
        $this->bindings = $bindings;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return Text
     */
    public function getMessage(): Text
    {
        return $this->message;
    }

    /**
     * @param string $key
     * @return Text
     */
    public function getBinding(string $key): Text
    {
        return $this->bindings[str_replace(':', '', $key)];
    }

    /**
     * Retrieve an external iterator
     * @link https://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        preg_match_all('/:[a-z0-9]+/', $this->message->getValue(), $bindings);
        $splits = preg_split('/:[a-z0-9]+/', $this->message->getValue());
        $words = flatten(map(flatten(zip_all($splits, $bindings[0])), function ($split) {
            return explode(' ', $split);
        }));

        return new \ArrayIterator(filter($words, function ($word) {
            return !empty($word);
        }));
    }
}
