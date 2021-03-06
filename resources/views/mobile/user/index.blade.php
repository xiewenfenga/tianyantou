<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
    <link rel="stylesheet" type="text/css" href="//static.tianyantou.com/css/mobile/reset.css"/>
    <link rel="stylesheet" type="text/css" href="//static.tianyantou.com/css/mobile/defult.css"/>
    <title></title>
</head>
<body>
<div class="touzi">
    <div class="first">
        <p class="p1">
            <img src="//static.tianyantou.com/images/mobile/tixian.png"/>
            <span>用户名:</span>
            <span>手机号:13641411254</span>
        </p>
        <p class="p2">
            <img src="//static.tianyantou.com/images/mobile/tixian-2.png"/>
            <img src="//static.tianyantou.com/images/mobile/tixian-1.png"/>

        </p>
    </div>
    <div class="header">
        <p class="p1">天眼投资累计收益（元）</p>
        <p class="p2" style="font-size: 22px;margin-top: 3%;">1,000,000</p>
    </div>
    <ul class="header2">
        <li>

            <p class="p2">可用余额<span>200</span>元</p>
        </li>
        <li>

            <p class="p2">提现</p>
        </li>
    </ul>

    <ul class="content">
        <li class="llot">
            <img src="//static.tianyantou.com/images/mobile/tixian-a.jpg"/>
            <div>
                <p class="p1">投资提交</p>
                <p class="p2">投资记录明细</p>
            </div>
        </li>
        <li class="llot">
            <img src="//static.tianyantou.com/images/mobile/tixian-b.jpg"/>
            <div>
                <p class="p1">投资提交</p>
                <p class="p2">投资记录明细</p>
            </div>
        </li>
        <li class="llot">
            <img src="//static.tianyantou.com/images/mobile/tixian-c.jpg"/>
            <div>
                <p class="p1">投资提交</p>
                <p class="p2">投资记录明细</p>
            </div>
        </li>
        <li class="llot">
            <img src="//static.tianyantou.com/images/mobile/tixian-d.jpg"/>
            <div>
                <p class="p1">投资提交</p>
                <p class="p2">投资记录明细</p>
            </div>
        </li>
    </ul>
    <ul class="foot-cont">
        <li>
            <img src="//static.tianyantou.com/images/mobile/tixian-e.jpg"/>
            <span>优惠卷</span>
            <img src="//static.tianyantou.com/images/mobile/tixian--.png"/>
        </li>
        <li>
            <img src="//static.tianyantou.com/images/mobile/tixian-f.jpg"/>
            <span>大转盘</span>
            <img src="//static.tianyantou.com/images/mobile/tixian--.png"/>
        </li>
        <li>
            <img src="//static.tianyantou.com/images/mobile/tixian-g.jpg"/>
            <span>在线客服</span>
            <img src="//static.tianyantou.com/images/mobile/tixian--.png"/>
        </li>
        <li>
            <img src="//static.tianyantou.com/images/mobile/tixian-h.jpg"/>
            <span>帮助与反馈</span>
            <img src="//static.tianyantou.com/images/mobile/tixian--.png"/>
        </li>
    </ul>
</div>
<div class="jpform-foot">
    <ul>
        <li class="jump_url">
            <a href="{!! config('app.m_url') !!}">
                <img src="//static.tianyantou.com/images/mobile/h1.png"/>
            </a>
            <p>首页</p>
        </li>
        <li class="jump_url">
            <a href="{!! config('app.m_url') !!}/platform">
                <img src="//static.tianyantou.com/images/mobile/h2.png"/>
            </a>
            <p>精选</p>
        </li>
        <li class="jump_url">
            <div class="">
                <a href="{!! config('app.m_url') !!}/signin.html">
                    <img src="//static.tianyantou.com/images/mobile/h3-3.png"/>
                </a>
            </div>
            <p>我的</p>
        </li>
    </ul>
</div>
<script src="//static.tianyantou.com/js/mobile/jquery-2.1.3.min.js" type="text/javascript" charset="utf-8"></script>
<script src="//static.tianyantou.com/js/mobile/swiper.jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    /*可以引用到js文件*/
    var mySwiper = new Swiper(".swiper-container",{
        direction:"horizontal",/*横向滑动*/
        loop:true,/*形成环路（即：可以从最后一张图跳转到第一张图*/
        pagination:".swiper-pagination",/*分页器*/
        prevButton:".swiper-button-prev",/*前进按钮*/
        nextButton:".swiper-button-next",/*后退按钮*/
        autoplay:3000/*每隔3秒自动播放*/
    })
    $(function(){
        $(".data-list").click(function(){
            var url = $(this).find("a").attr("href");
            window.location.href=url;
        })
        $(".submit-gold").click(function(){
            var url = $(this).find("a").attr("href");
            window.location.href=url;
        })
        $(".jump_url").click(function(){
            var url = $(this).find("a").attr("href");
            window.location.href=url;
        })
    })
</script>
</body>
</html>
