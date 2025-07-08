<?php

namespace Ledc\DeliverySlotBooking;

use app\dao\user\UserBrokerageDao;
use app\model\order\StoreOrder;
use app\model\user\User;
use app\services\order\StoreOrderTakeServices;
use app\services\user\UserBrokerageServices;
use app\services\user\UserServices;
use app\services\wechat\WechatUserServices;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 分销助手
 */
class ShareProfitHelper
{
    /**
     * 直推奖配置KEY
     */
    public const FIRST_ORDER_ONE_BROKERAGE_AMOUNT = 'first_order_one_brokerage_amount';
    /**
     * 间推奖配置KEY
     */
    public const FIRST_ORDER_TWO_BROKERAGE_AMOUNT = 'first_order_two_brokerage_amount';

    /**
     * 直推奖
     * - 每位用户首次成单时给直推人发放的拉新佣金，终生仅发放一次。
     * @return string
     */
    public static function firstOrderOneBrokerageAmount(): string
    {
        return sys_config(self::FIRST_ORDER_ONE_BROKERAGE_AMOUNT, '0');
    }

    /**
     * 间推奖
     * - 每位用户首次成单时给间推人发放的拉新佣金，终生仅发放一次。
     * @return string
     */
    public static function firstOrderTwoBrokerageAmount(): string
    {
        return sys_config(self::FIRST_ORDER_TWO_BROKERAGE_AMOUNT, '0');
    }

    /**
     * 管理奖
     * - 每笔订单给直推人奖励的佣金
     * @return string
     */
    public static function orderOneBrokerageAmount(): string
    {
        return sys_config('order_one_brokerage_amount', '0');
    }

    /**
     * 添加首笔订单佣金
     * @param User $user 当前订单的所属用户
     * @param int $spread 推广人id（上级或上上级）
     * @param string $brokerage_price 佣金
     * @param StoreOrder $storeOrder 订单
     * @return bool
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public static function addFirstOrderBrokerageAmount(User $user, int $spread, string $brokerage_price, StoreOrder $storeOrder): bool
    {
        //商城分销功能是否开启 0关闭1开启
        if (!sys_config('brokerage_func_status')) {
            return true;
        }
        if (!sys_config('brokerage_user_status')) {
            return true;
        }
        if (!$spread || $spread == -1) {
            return true;
        }
        if (!$brokerage_price) {
            return true;
        }
        //根据手机号码查询此用户注销过，不反推广佣金
        if ($user->phone != '' && User::where(['phone' => $user->phone, 'is_del' => 1])->count()) {
            return false;
        }
        //根据openid查询此用户注销过，不反推广佣金
        $wechatUserServices = app()->make(WechatUserServices::class);
        $openidArray = $wechatUserServices->getColumn(['uid' => $user->uid], 'openid', 'id');
        if ($wechatUserServices->getCount([['openid', 'in', $openidArray], ['is_del', '=', 1]])) {
            return false;
        }

        // 仅返佣金一次
        if (1 !== StoreOrder::where(['uid' => $user->uid, 'paid' => 1])->count()) {
            return true;
        }
        $spread_user = User::where(['uid' => $spread, 'status' => 1])->find();
        if (!$spread_user) {
            return false;
        }
        /** @var UserServices $userServices */
        $userServices = app()->make(UserServices::class);
        if (!$userServices->checkUserPromoter($spread, $spread_user)) {
            return false;
        }
        /** @var UserBrokerageServices $userBrokerageServices */
        $userBrokerageServices = app()->make(UserBrokerageServices::class);
        /** @var UserBrokerageDao $userBrokerageDao */
        $userBrokerageDao = app()->make(UserBrokerageDao::class);

        $spreadPrice = $spread_user['brokerage_price'];
        // 上级推广员返佣之后的金额
        $balance = bcadd($spreadPrice, $brokerage_price, 2);
        // 添加返佣记录
        $res1 = $userBrokerageServices->income('get_user_brokerage', $spread, [
            'nickname' => $user->nickname,
            'number' => floatval($brokerage_price)
        ], $balance, $user->uid);
        // 添加用户余额
        $res2 = $userBrokerageDao->bcInc($spread, 'brokerage_price', $brokerage_price, 'uid');
        //给上级发送获得佣金的模板消息
        /** @var StoreOrderTakeServices $storeOrderTakeServices */
        $storeOrderTakeServices = app()->make(StoreOrderTakeServices::class);
        $storeOrderTakeServices->sendBackOrderBrokerage([], $spread, $brokerage_price, 'user');
        return $res1 && $res2;
    }
}
