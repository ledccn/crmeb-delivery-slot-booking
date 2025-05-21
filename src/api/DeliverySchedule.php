<?php

namespace Ledc\DeliverySlotBooking\api;

use app\Request;
use Ledc\DeliverySlotBooking\services\DeliveryScheduleService;
use think\Response;

/**
 * 预订配送时间段
 */
class DeliverySchedule
{
    /**
     * 显示资源列表
     * @method GET
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $day = $request->get('day', date('Y-m-d'));
        if (!$day) {
            return response_json()->fail('请输入 YYYY-MM-DD 日期');
        }
        $timestamp = strtotime($day);
        if (date('Y-m-d', $timestamp) !== $day) {
            return response_json()->fail('请输入 YYYY-MM-DD 格式的日期');
        }
        if ($timestamp < strtotime('today')) {
            return response_json()->fail('请输入大于当前日期的日期');
        }

        return response_json()->success('ok', DeliveryScheduleService::get($day));
    }
}
