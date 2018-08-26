<?php
/**
 * Created by PhpStorm.
 * User: drupsys
 * Date: 26/08/18
 * Time: 14:48
 */

namespace OneSpec\Result;

class Text
{
    /**
     * @var string
     */
    private $value;
    /**
     * @var string
     */
    private $color;
    /**
     * @var string[]
     */
    private $decorations;

    /**
     * Binding constructor.
     * @param string $value
     * @param string $color
     * @param string[] $decorators
     */
    public function __construct(string $value, string $color, array $decorators = [])
    {
        $this->value = $value;
        $this->color = $color;
        $this->decorations = $decorators;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @return string[]
     */
    public function getDecorations(): array
    {
        return $this->decorations;
    }
}