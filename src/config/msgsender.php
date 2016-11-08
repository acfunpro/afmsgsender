<?php

return [
    'template' => [
        '100001' => [
            'type'       => 'register',
            'content'    => '本次注册的验证码是%d, 5分钟内有效',
            'templateId' => 'SMS_25645114', // 对应淘宝开放平台里定义的模板ID
        ],

        '100005' => [
            'type'      => 'resetpassword',
            'content'   => '重置密码的验证码是%d, 不要告诉别人',
            'templateId' => 'SMS_25645114',
        ],

    ],

    // 服务商配置
    'service'    => [
        /**
         * ---------------------------
         * Alidayu 阿里大于
         * http://www.alidayu.com
         * 支持模板短信
         * ---------------------------
         */
        'Alidayu' => [
            // 请求地址 正式环境
            'sendUrl'         => 'https://eco.taobao.com/router/rest',

            // 淘宝开放平台中，对应阿里大鱼短信应用的App Key
            'appKey'          => 'your app key',

            // 淘宝开放平台中，对应阿里大鱼短信应用的App Secret
            'secretKey'       => 'your secret key',

            // 短信签名，传入的短信签名必须是在阿里大鱼“管理中心-短信签名管理”中的可用签名
            'smsFreeSignName' => 'your sms free sign name',
        ],

        /**
         * ---------------------------
         * 微网 乐信
         * http://www.51welink.com/
         * 支持模板短信
         * ---------------------------
         */
        'Welink' => [
            // 请求地址
            'url'     => 'http://cf.51welink.com/submitdata/Service.asmx/g_Submit',

            // 提交账户
            'sname'   => 'your account',

            // 提交账户的密码
            'spwd'    => 'your password',

            // 企业代码（扩展号，不确定请赋值空）
            'scorpid' => '',

            // 产品编号
            'sprdid'  => 'product id',

            // 签名 必须使用中文大括号
            'sign'    => '【your sign】',
        ]
    ]
];