<?php
/**
 * Created by PhpStorm.
 * User: drupsys
 * Date: 25/08/18
 * Time: 15:36
 */

namespace OneSpec\Result;

class Result
{
    /** @var string */
    private $status;
    /** @var string */
    private $message;
    /** @var string */
    private $expected;
    /** @var int */
    private $positive;
    /** @var string */
    private $actual;
    /** @var string */
    private $file;
    /** @var int */
    private $line;

    public function __construct(string $status = Status::PASS)
    {
        $this->status = $status;
    }

    public function setFailureDetails(string $message, string $expected, int $positive, string $actual)
    {
        $this->message = $message;
        $this->expected = $expected;
        $this->positive = $positive;
        $this->actual = $actual;
    }

    public function setErrorDetails(string $message, string $file, int $line)
    {
        $this->message = $message;
        $this->file = $file;
        $this->line = $line;
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
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @return int
     */
    public function getLine(): int
    {
        return $this->line;
    }

    /**
     * @return string
     */
    public function getExpected(): string
    {
        return $this->expected;
    }

    /**
     * @return int
     */
    public function getPositive(): int
    {
        return $this->positive;
    }

    /**
     * @return string
     */
    public function getActual(): string
    {
        return $this->actual;
    }
}
