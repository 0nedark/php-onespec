<?php
/**
 * Created by PhpStorm.
 * User: drupsys
 * Date: 25/08/18
 * Time: 15:36
 */

namespace OneSpec\Result;

use Symfony\Component\Console\Terminal;
use Traversable;

class Result implements \IteratorAggregate, Binding
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
     * Retrieve an external iterator
     * @link https://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->bindings);
    }
}
