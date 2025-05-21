<?php

use app\api\middleware\AuthTokenMiddleware;
use app\api\middleware\StationOpenMiddleware;
use app\http\middleware\AllowOriginMiddleware;
use Ledc\DeliverySlotBooking\api\DeliverySchedule;
use Ledc\DeliverySlotBooking\api\DeliveryScheduleExceptions;
use Ledc\DeliverySlotBooking\api\DeliveryScheduleTemplates;
use think\facade\Route;

/**
 * 预订配送时间段 相关路由
 */
Route::group('delivery_slot_booking', function () {
    // 预订配送时间段【结合预订配送时间段模板和预订配送时间段异常】
    Route::get('schedule', implode('@', [DeliverySchedule::class, 'index']));

    // 预订配送时间段模板
    Route::group('templates', function () {
        Route::get('index', implode('@', [DeliveryScheduleTemplates::class, 'index']));
        Route::get('read', implode('@', [DeliveryScheduleTemplates::class, 'read']));
    });

    // 预订配送时间段异常
    Route::group('exceptions', function () {
        Route::get('index', implode('@', [DeliveryScheduleExceptions::class, 'index']));
        Route::get('read', implode('@', [DeliveryScheduleExceptions::class, 'read']));
    });
})->middleware(AllowOriginMiddleware::class)
    ->middleware(StationOpenMiddleware::class)
    ->middleware(AuthTokenMiddleware::class, false)
    ->option(['mark' => 'delivery_slot_booking', 'mark_name' => '预订配送时间段']);