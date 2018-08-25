<?php
/**
 * Created by PhpStorm.
 * User: drupsys
 * Date: 25/08/18
 * Time: 11:23
 */

namespace OneSpec\Cli;

class Config
{
    private $config;

    /**
     * @var string[]
     */
    private $spec;

    public function __construct()
    {
        $this->config = json_decode(file_get_contents(getcwd() . '/onespec.json'));

        $this->spec[] = getcwd();
        $this->spec = array_merge($this->spec, explode('/', $this->config->spec));
    }

    /**
     * Gets the array with all folders that lead to the spec files
     *
     * @return string[]
     */
    public function getSpecFolders(): array
    {
        return $this->spec;
    }
}