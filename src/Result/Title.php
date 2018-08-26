<?php
/**
 * Created by PhpStorm.
 * User: drupsys
 * Date: 26/08/18
 * Time: 16:33
 */

namespace OneSpec\Result;

class Title
{
    /**
     * @var Text
     */
    private $id;
    /**
     * @var Text
     */
    private $name;
    /**
     * @var string
     */
    private $status;

    public function __construct(string $key, string $status)
    {
        [$id, $name] = explode(':', $key);
        $this->id = new Text($id, $status);
        $this->name = new Text($name, Color::PRIMARY);
        $this->status = $status;
    }

    /**
     * @return Text
     */
    public function getId(): Text
    {
        return $this->id;
    }

    /**
     * @return Text
     */
    public function getShortId(): Text
    {
        $shortId = substr($this->id->getValue(), 0, 4);
        return new Text($shortId, $this->id->getColor());
    }

    /**
     * @return Text
     */
    public function getName(): Text
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
}