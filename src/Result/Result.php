<?php
/**
 * Created by PhpStorm.
 * User: drupsys
 * Date: 25/08/18
 * Time: 15:36
 */

namespace OneSpec\Result;

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
     * @param string $message
     * @param string[] $bindings
     */
    public function __construct(string $status, string $message = '', array $bindings = [])
    {

        $this->status = $status;
        $this->message = $message;
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
     * @return string
     */
    public function getMessage(): string
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
