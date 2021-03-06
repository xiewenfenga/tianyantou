@extends('layout.main')
@section('title')银行卡@stop
@section('style')
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <link rel="stylesheet" href="{!! config('app.static_url') !!}/css/account.css" />
    <link rel="stylesheet" href="{!! config('app.static_url') !!}/js/lib/fullcalendar/fullcalendar.min.css" />
    <script src="/vendor/area/area.js"></script>
@stop

@section('content')
    <div class="wrap user-center">
        <div class="container clearfix">
            @include('account.common.menu')
                <div class="main tworow" style="height: 863px;">
                    <div class="main-inner">
                        <h1 class="section-title">{!! $bank->type == 0 ? '绑定银行卡' : '绑定支付宝' !!}</h1>
                        <div class="form-group bindbankcard">
                            <form id="bindbankcard" method="post" data-toggle="ajaxForm">
                                <input type="hidden" name="data[id]" value="{!! $bank->id or '' !!}">
                                <input type="hidden" name="data[user_id]" value="{!! $bank['user_id'] or '' !!}">
                                <input type="hidden" name="data[type]" value="{!! $bank->type or '' !!}">
                                @if($bank->type == 0)
                                <div class="control-group">
                                    <label for="real-name">开户名称</label>
                                    <input type="text" name="data[hold_name]" class="input-style" value="{!! $bank->hold_name or '' !!}">
                                    <em>请添加相同开户名的银行卡</em>
                                </div>
                                <div class="control-group">
                                    <label>开户银行</label>
                                    <select name="data[bank_name]" class="input-style required">
                                        <option value="">请选择开户银行</option>
                                        @if(!empty(brandData()))
                                            @foreach(brandData() as $bv)
                                                <option value="{!! $bv['name'] or '' !!}" @if($bank->bank_name==$bv['name'])selected @endif>{!! $bv['name'] or '' !!}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="control-group">
                                    <label>开户行所在地</label>
                                    <select name="data[province]" class="input-style province" id="province" selectedindex="0">
                                    </select>
                                    <select name="data[city]" class="input-style city" id="city">
                                    </select>
                                </div>
                                <div class="control-group">
                                    <label>开户支行名称</label>
                                    <input type="text" name="data[branch_name]" class="input-style" value="{!! $bank->branch_name or '' !!}">
                                </div>
                                <div class="control-group">
                                    <label>银行卡号</label>
                                    <input type="text" name="data[cardno]" class="input-style" value="{!! $bank->cardno or '' !!}">
                                </div>
                                <div class="control-group">
                                    <label for="">确认卡号</label>
                                    <input type="text" name="confirm_cardno" class="input-style">
                                </div>
                                @else
                                <div class="control-group">
                                    <label>支付宝名称</label>
                                    <input type="text" name="data[hold_name]" class="input-style" value="{!! $bank->hold_name or '' !!}">
                                    <em>请添加相同支付宝名称的账号</em>
                                </div>
                                <div class="control-group">
                                    <label>支付宝账号</label>
                                    <input type="text" name="data[cardno]" class="input-style" value="{!! $bank->cardno or '' !!}">
                                </div>
                                <div class="control-group">
                                    <label for="">确认支付宝账号</label>
                                    <input type="text" name="confirm_cardno" class="input-style" value="{!! $bank->cardno or '' !!}">
                                </div>
                                @endif
                                <input type="submit" class="btn-blue btn-l btn-submit" value="提交">
                            </form>
                        </div>
                        <div class="tip tab-rules">
                            <h3 class="title-indent">温馨提示</h3>
                            <div class="tip-main">
                                <ul class="tab-content">
                                    <li>1. 为保障您的账户资金安全，绑定银行卡时，您选择的银行卡开户名必须与您实名认证姓名一致；</li>
                                    <li><em>2. 请您提供正确的银行卡信息，如果您提供的银行卡信息有误，2元/笔的可提现手续费银行将不予退还；</em></li>
                                    <li>3. 禁止套现、洗钱行为，绑定银行卡仅限储蓄卡，不支持信用卡绑定提现；</li>
                                    <li>4. 填写银行卡信息时，请确认您的“支行名称”(如上海分行虹口支行)，如您无法确定，请致电您的开户银行客服进行咨询。</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
@stop

@section('script')
    <script type="text/javascript" src="{!! config('app.static_url') !!}/js/lib/jquery.form.min.js"></script>
    <script type="text/javascript" src="{!! config('app.static_url') !!}/js/plugins/ucenter.js"></script>
    <script type="text/javascript" src="{!! config('app.static_url') !!}/js/plugins/pagination.js"></script>
    <script type="text/javascript" src="{!! config('app.static_url') !!}/js/plugins/actions.js"></script>
    <script type="text/javascript" src="{!! config('app.static_url') !!}/js/plugins/form.js"></script>
    <script type="text/javascript" src="{!! config('app.static_url') !!}/js/plugins/bindbankcardupdate.js"></script>
    <script>
        _init_area({!! $area !!});
    </script>
@stop