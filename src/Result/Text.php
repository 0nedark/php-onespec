<?php
/**
 * Created by PhpStorm.
 * User: drupsys
 * Date: 26/08/18
 * Time: 14:48
 */

namespace Xae3Oow5cahz9shahngu\Result;

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
    private $decorators;

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
        $this->decorators = $decorators;
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
    public function getDecoratedValue(): string
    {
        $value = $this->value;
        foreach ($this->decorators as $decorator) {
            if ($decorator === 'KEY') {
                $value = substr($this->value, 0, 4);
            }
        }

        return $value;
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
    public function getDecorators(): array
    {
        return $this->decorators;
    }
}