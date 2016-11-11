<body style="text-align: center;">
    <div style="margin: 80px auto 0; width: 580px; background: #FFF; box-shadow: 0 0 10px #333; text-align:left;">
        <div style="margin: 0 40px; color: #999; border-bottom: 1px dotted #DDD; padding: 40px 0 30px; font-size: 13px; text-align: center;">
            <a href="http://acfun.tv" target="_blank"><img src="http://cdn.aixifan.com/acfun-pc/1.7.0/img/logo.png"></a><br>
            最好看的视频网站
        </div>
        <div style="padding: 30px 40px 10px;">
            <?php echo $username; ?>
            您好，您申请了绑定邮箱<br><br>
            请在 1 小时内点击此链接以完成绑定
            <a style="color: #009A61; text-decoration: none;" href="<?php echo $reseturl; ?>" target="_blank">
                <?php echo $reseturl; ?>
            </a><br><br>
            若链接无法点击，请复制粘贴到浏览器进行访问。
            <br>
        </div>
        <div style="text-align: center;">
            <a href="http://www.acfun.tv/app/" style="display: block;height: 210px;" target="_blank">
                <img src="http://cdn.aixifan.com/dotnet/20130418/project/app/style/image/lightbox/erweima.png?v=0.7.4_SD7e" style="width: 182px; height: 182px">
            </a>
        </div>
        <div style="background: #EEE; border-top: 1px solid #DDD; text-align: center; height: 90px; line-height: 90px;">
            <a href="<?php echo $reseturl; ?>" style="padding: 8px 18px; background: #F93A63; color: #FFF; text-decoration: none; border-radius: 3px;" target="_blank">完成绑定 ➔</a>
        </div>
    </div>
</body>