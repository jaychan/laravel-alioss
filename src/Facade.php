<?php

namespace JayChan\Aliyun\OSS;

use Illuminate\Support\Facades\Facade as LaravelFacade;

/**
 * 支付外观.
 *
 * @author JayChan <voidea@foxmail.com>
 *
 * @version 1.0
 */
class Facade extends LaravelFacade
{
    public static function getFacadeAccessor()
    {
        return 'filesystem.alioss';
    }
}
