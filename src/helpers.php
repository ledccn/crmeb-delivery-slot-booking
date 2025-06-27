<?php

use app\Request;
use crmeb\utils\Json;
use think\facade\Log;

if (!function_exists('request')) {
    /**
     * 获取当前Request对象实例
     * @return Request|\think\Request
     */
    function request(): \think\Request
    {
        return app('request');
    }
}

if (!function_exists('response_json')) {
    /**
     * JSON响应
     * @return Json
     */
    function response_json(): Json
    {
        return app('json');
    }
}

if (!function_exists('filter_where')) {
    /**
     * 过滤where内的：null，空字符串
     * @param array $where
     * @return array
     */
    function filter_where(array $where): array
    {
        return array_filter($where, function ($v) {
            return null !== $v && '' !== $v;
        });
    }
}

if (!function_exists('log_develop')) {
    /**
     * 记录日志
     * @param mixed $msg
     * @return void
     */
    function log_develop($msg)
    {
        Log::write($msg, 'develop');
    }
}
