<?php
/**
 * Created by PhpStorm.
 * User: drupsys
 * Date: 27/08/18
 * Time: 13:17
 */

namespace OneSpec\Result;


class Icon extends Text
{
    public function __construct(string $status)
    {
        switch ($status) {
            case Status::SUCCESS:
                $icon = '✔';
                break;
            case Status::FAILURE:
                $icon = '✘';
                break;
            case Status::EXCEPTION:
                $icon = '✱';
                break;
            case Status::WARNING:
                $icon = '‼';
                break;
            default:
                $icon = '-';
                break;
        }

        parent::__construct($icon, $status);
    }
}