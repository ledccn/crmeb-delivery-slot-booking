<?php

use app\adminapi\middleware\AdminAuthTokenMiddleware;
use app\adminapi\middleware\AdminCheckRoleMiddleware;
use app\adminapi\middleware\AdminLogMiddleware;
use app\http\middleware\AllowOriginMiddleware;
use app\Request;
use Ledc\DeliverySlotBooking\adminapi\DeliveryScheduleExceptions;
use Ledc\DeliverySlotBooking\adminapi\DeliveryScheduleTemplates;
use Ledc\DeliverySlotBooking\adminapi\DeliveryTimeSlots;
use Ledc\DeliverySlotBooking\services\DeliveryScheduleService;
use think\facade\Route;

/**
 * 预订配送时间段管理 相关路由
 */
Route::group('delivery-slot-booking', function () {
    // 预订配送时间段【结合预订配送时间段模板和预订配送时间段异常】
    Route::get('schedule', function (Request $request) {
        $day = $request->get('day');
        if (!$day) {
            return response_json()->fail('请输入 YYYY-MM-DD 日期');
        }
        $timestamp = strtotime($day);
        if (date('Y-m-d', $timestamp) !== $day) {
            return response_json()->fail('请输入 YYYY-MM-DD 格式的日期');
        }

        return response_json()->success('ok', DeliveryScheduleService::get($day, false));
    });

    // 预订配送时间段
    Route::group('time_slots', function () {
        Route::get('index', implode('@', [DeliveryTimeSlots::class, 'index']));
        Route::post('save', implode('@', [DeliveryTimeSlots::class, 'save']));
        Route::get('read', implode('@', [DeliveryTimeSlots::class, 'read']));
        Route::delete(':id', implode('@', [DeliveryTimeSlots::class, 'delete']));
    });

    // 预订配送时间段模板
    Route::group('schedule_templates', function () {
        Route::get('index', implode('@', [DeliveryScheduleTemplates::class, 'index']));
        Route::post('save', implode('@', [DeliveryScheduleTemplates::class, 'save']));
        Route::get('read', implode('@', [DeliveryScheduleTemplates::class, 'read']));
        Route::delete(':id', implode('@', [DeliveryScheduleTemplates::class, 'delete']));
    });

    // 预订配送时间段异常
    Route::group('schedule_exceptions', function () {
        Route::get('index', implode('@', [DeliveryScheduleExceptions::class, 'index']));
        Route::post('save', implode('@', [DeliveryScheduleExceptions::class, 'save']));
        Route::get('read', implode('@', [DeliveryScheduleExceptions::class, 'read']));
        Route::delete(':id', implode('@', [DeliveryScheduleExceptions::class, 'delete']));
    });
})->middleware([
    AllowOriginMiddleware::class,
    AdminAuthTokenMiddleware::class,
    AdminCheckRoleMiddleware::class,
    AdminLogMiddleware::class
])->option(['mark' => 'delivery-slot-booking', 'mark_name' => '预订配送时间段管理']);