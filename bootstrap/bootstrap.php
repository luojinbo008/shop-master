<?php
/**
 * Description. 
 * 启动器
 * @author zhoutl <mfkgdyve@gmail.com>:
 * @copyright Copyright &copy;  2012-2014 Vim Studio
 * @version ID
 * @package System
 * @Create Time:2015/1/19 15:15:53
 * @Last Modified:2015/1/19 15:15:53
 **/
date_default_timezone_set('Asia/Shanghai');
define('BASE_PATH', __DIR__);
define('CONFIG_PATH', dirname(BASE_PATH) . '/common/config');
define('LIB_PATH', dirname(BASE_PATH) . '/common/library');
define('APPS_PATH', dirname(BASE_PATH) . '/apps');
define('CACHE_PATH', dirname(BASE_PATH) . '/storage/cache/');
define('VIEW_PATH', dirname(BASE_PATH) . '/storage/view/');
define('SESSION_PATH', dirname(BASE_PATH) . '/storage/session/');
define('LOG_PATH', dirname(BASE_PATH) . '/storage/log/');
define('CURRENT_TIME', time());
define('CURRENT_TODAY', strtotime('today'));
define('DIR_IMAGE', dirname(BASE_PATH) .'/public/images/');
define('ORDER_STATUS_START', '0');          // 开始
define('ORDER_STATUS_PAY', '1');            // 支付中
define('ORDER_STATUS_CANCEL', '2');         // 用户主动取消
define('ORDER_STATUS_END', '3');            // 订单结束（支付完成，第三方回调成功）
define('ORDER_STATUS_CLOSE', '4');          // 订单支付超时，系统关闭订单
define('ORDER_STATUS_COMMENT', '5');        // 用户已评价
define('ORDER_STATUS_REFUND_START', '6');   // 玩家发起退款申请
define('ORDER_STATUS_REFUND_END', '7');     // 管理员审核退款，并退款成功
define('ORDER_STATUS_REFUND_FAIL', '8');     // 管理员审核退款失败
define('IMAGE_HTTP', "http://static.azbzo.com/images/");
$loader = include CONFIG_PATH . '/loader.php';
$loader->registerNamespaces([
	'Library' => LIB_PATH,
]);
$loader->register();
$env = \Library\Environment\Environment::Init(['dev' => ['BF*'], 'home' => ['LJB*']])->getCurrentRunEnvironment();
$config = include CONFIG_PATH . DIRECTORY_SEPARATOR . 'config.php';
if (file_exists(CONFIG_PATH . DIRECTORY_SEPARATOR . $env . '/config.php')) {
    $config = array_merge($config, include CONFIG_PATH . DIRECTORY_SEPARATOR . $env . '/config.php');
}
if (file_exists(CONFIG_PATH . DIRECTORY_SEPARATOR . $env . '/services.php')) {
    $di = include CONFIG_PATH . DIRECTORY_SEPARATOR . $env . '/services.php';
} else {
    $di = include CONFIG_PATH . DIRECTORY_SEPARATOR . 'services.php';
}
$module = include  CONFIG_PATH . '/modules.php';
