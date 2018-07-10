<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\QiNiuController;
use App\Http\Wechat\ChatController;
use App\Http\Wechat\CommentController;
use App\Http\Wechat\CompareFaceController;
use App\Http\Wechat\FollowController;
use App\Http\Wechat\FormIdController;
use App\Http\Wechat\InboxController;
use App\Http\Wechat\IndexController;
use App\Http\Wechat\MatchLoveController;
use App\Http\Wechat\PartTimeJobController;
use App\Http\Wechat\PostController;
use App\Http\Wechat\PraiseController;
use App\Http\Wechat\SaleFriendController;
use App\Http\Wechat\TopicController;
use App\Http\Wechat\UserController;
use Illuminate\Support\Facades\Log;

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

    $api->group(['prefix' => 'wechat','middleware' => 'api.throttle', 'limit' => 100, 'expires' => 1], function ($api) {

        $api->get('/config',IndexController::class . '@config');

        $api->group(['prefix' => 'auth', 'middleware' => 'before'], function ($api) {

            /** 登录 */
            $api->post('/login', LoginController::class . '@apiLogin');

        });

        $api->group(['middleware' => ['wechat']], function ($api) {

            /** 获取用户信息 */
            $api->get('/user/{id}', UserController::class . '@user');

            /** 获取个人信息 */
            $api->get('/personal_info', UserController::class . '@personal');

            /** 选择所有学校 */
            $api->put('/clear_school',UserController::class . '@clearSchool');

            /** 获取个人学校 */
            $api->get('/school', UserController::class . '@school');

            /** 获取随机学校 */
            $api->get('/recommend_school', UserController::class . '@recommendSchool');

            /** 设置学校 */
            $api->put('/set/{id}/college', UserController::class . '@setCollege');

            /** 搜索学校 */
            $api->get('/search_college', UserController::class . '@searchCollege');

            /** 获取七牛上传token */
            $api->get('/upload_token', QiNiuController::class . '@getUploadToken');

            /** 发表贴子 */
            $api->post('post', PostController::class . '@store');

            /** 贴子列表 */
            $api->get('/post', PostController::class . '@postList');

            /** 贴子详情 */
            $api->get('/post/{id}', PostController::class . '@detail');

            /** 评论 */
            $api->post('/comment', CommentController::class . '@store');

            /** 获取最新的贴子 */
            $api->get('/most_new_post', PostController::class . '@getMostNewPost');

            /** 点赞 */
            $api->post('/praise', PraiseController::class . '@store');

            /** 删除表白墙 */
            $api->delete('/delete/{id}/post', PostController::class . '@delete');

            /** 删除评论 */
            $api->delete('/delete/{id}/comment', CommentController::class . '@delete');

            /** 新增卖舍友 */
            $api->post('/sale_friend', SaleFriendController::class . '@save');

            /** 获取卖舍友列表 */
            $api->get('/sale_friends', SaleFriendController::class . '@saleFriends');

            /** 卖舍友详情 */
            $api->get('/sale_friend/{id}', SaleFriendController::class . '@detail');

            /** 获取最新卖舍友 */
            $api->get('/most_new_sale_friend', SaleFriendController::class . '@mostNewSaleFriends');

            /** 删除卖舍友 */
            $api->delete('/delete/{id}/sale_friend', SaleFriendController::class . '@delete');

            /** 新建匹配 */
            $api->post('/match_love', MatchLoveController::class . '@save');

            /** 匹配列表 */
            $api->get('/match_loves', MatchLoveController::class . '@matchLoves');

            /** 匹配详情 */
            $api->get('/match_love/{id}', MatchLoveController::class . '@detail');

            /** 删除匹配 */
            $api->delete('/delete/{id}/match_love', MatchLoveController::class . '@delete');

            /** 获取最新的匹配 */
            $api->get('/most_new_match_loves', MatchLoveController::class . '@newList');

            /** 获取匹配成功的信息 */
            $api->get('/match/{id}/result', MatchLoveController::class . '@matchSuccess');

            /** 检测是否有新的消息 */
            $api->get('/new/{type}/inbox', InboxController::class . '@getNewInbox');

            /** 根据对象类型获取新的消息列表 */
            $api->get('/user/{type}/inbox/{messageType}', InboxController::class . '@userInbox');

            /** 发送私信 */
            $api->post('/send/{id}/message', ChatController::class . '@sendMessage');

            /** 私信列表 */
            $api->get('/message/{id}/list', ChatController::class . '@chatList');

            /** 获取最新私信 */
            $api->get('/new/{id}/messages', ChatController::class . '@getNewMessage');

            /** 获取发给我的私信 */
            $api->get('/new_messages', ChatController::class . '@newLetter');

            /** 撤回消息 */
            $api->delete('/delete/{id}/chat_message', ChatController::class . '@delete');

            /** 私信好友列表 */
            $api->get('/friends', ChatController::class . '@friends');

            /** 关注 */
            $api->post('/follow', FollowController::class . '@contact');

            /** 取消关注 */
            $api->put('/cancel/{id}/follow/{type}', FollowController::class . '@cancelFollow');

            /** 搜索 */
            $api->get('/search',IndexController::class . '@search');

            /** 获取小程序的客服 */
            $api->get('/service',IndexController::class . '@service');

            /** 情侣脸比对 */
            $api->post('/compare_face',CompareFaceController::class . '@store');

            /** 获取话题详情 */
            $api->get('/topic/{id}',TopicController::class . '@topicDetail');

            /** 获取上架话题 */
            $api->get('/topic',TopicController::class . '@topic');

            /** 点赞话题 */
            $api->post('/praise/{id}/topic',TopicController::class . '@praiseTopic');

            /** 话题的评论列表 */
            $api->get('/topic/{id}/comments',TopicController::class . '@topicComments');

            /** 获取最新的话题评论 */
            $api->get('/topic/{id}/new_comments',TopicController::class . '@getMostNewTopComments');

            /** 修改用户信息 */
            $api->patch('/update/{id}/user',UserController::class . '@updateUser');

            /** 获取手机验证码 */
            $api->get('/get_message_code',IndexController::class . '@getMessageCode');

            /** 提交用户资料 */
            $api->post('/profile',UserController::class . '@createProfile');

            /** 获取个人资料 */
            $api->get('/profile',UserController::class . '@profile');

            /** 发布悬赏 */
            $api->post('/post_help',PartTimeJobController::class . '@store');

            /** 悬赏列表 */
            $api->get('/helps',PartTimeJobController::class . '@partTimJobs');

            /** 获取最新的悬赏 */
            $api->get('/new_helps',PartTimeJobController::class . '@getMostNew');

            /** 接单 */
            $api->post('/receipt_order',PartTimeJobController::class . '@receiptOrder');

            /** 悬赏令详情 */
            $api->get('/job_detail/{id}',PartTimeJobController::class . '@detail');

            /** 确认悬赏令完成 */
            $api->post('/finish/{id}/job',PartTimeJobController::class . '@finishJob');

            /** 评价悬赏任务 */
            $api->post('/comment/{id}/job',PartTimeJobController::class . '@commentPartTimeJob');

            /** 赏金猎人的悬赏记录 */
            $api->get('/employee/{id}/record',PartTimeJobController::class .'@employeeJobComments');

            /** 赏金猎人的悬赏记录 */
            $api->get('/job/{id}/mission_record',PartTimeJobController::class .'@missionRecord');

            /** 放弃任务 */
            $api->put('/stop/{id}/job',PartTimeJobController::class . '@stopMission');

            /** 不信任猎人，重新发布悬赏 */
            $api->put('/restart/{id}/job',PartTimeJobController::class . '@restartJob');

            /** 删除悬赏令 */
            $api->delete('/delete/{id}/job',PartTimeJobController::class . '@delete');

            /** 收集form id */
            $api->post('/save_form_id',FormIdController::class . '@save');

            /** 获取小程序的二维码 */
            $api->get('/qr_code',UserController::class . '@qrCode');
        });

    });

});


