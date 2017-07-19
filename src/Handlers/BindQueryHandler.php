<?php
/**
 * The file is part of Notadd
 *
 * @author: AllenGu<674397601@qq.com>
 * @copyright (c) 2017, notadd.com
 * @datetime: 17-7-19 下午7:37
 */

namespace Notadd\WechatLogin\Handlers;

use Notadd\Foundation\Routing\Abstracts\Handler;
use Notadd\WechatLogin\Models\WechatUser;

class BindQueryHandler extends Handler
{
    public function execute()
    {
        $this->validate($this->request, [
            'token' => 'required',
            'user_id' => 'user_id'
        ], [
            'token.required' => 'token为必传参数',
            'user_id.required' => 'user_id为必传参数'
        ]);

        /**
         * verify the token's validity(5 min = 300s)
         */

        $token = $this->request->input('token');

        $timestamp = substr($token, 22);

        if (time() - $timestamp > 300) {
            $this->withCode(402)->withMessage('token失效，请刷新二维码页面重试');
        }
        $uid = $this->request->input('user_id');

        $user = WechatUser::where('user_id', $uid)->first();

        if ($user instanceof WechatUser)
        {
            $this->withCode(200)->withMessage('绑定成功');
        } else {
            $this->withCode(402)->withMessage('绑定失败');
        }
    }
}