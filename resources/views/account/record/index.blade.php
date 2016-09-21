@extends('layout.main')
@section('title')网贷记账@stop
@section('style')
    <link rel="stylesheet" href="{!! config('app.static_url') !!}/css/account.css"/>
@stop

@section('content')
    <div class="wrap user-center">
        <div class="container clearfix">
            @include('account.common.menu')
            <div class="main tworow" style="height: 917px;">
                <div class="main-inner">
                    <div class="cont-box-wrap">
                        <span>当前可续购债权: 0个</span>
                        <a class="btn btn-blue-o btn-s" style="float:right" href="{!! config('app.account_url') !!}/record/create" data-toggle="modal">记一笔</a>
                    </div>
                    <div id="autoreserve_records">
                        <table class="table table-blue table-bordered ucenter-table" style="font-size:13px;">
                            <thead>
                            <tr>
                                <th>到期日</th>
                                <th>债权名称</th>
                                <th>待回款本息</th>
                                <th>债权期限</th>
                                <th>当前预约</th>
                                <th>已续次数</th>
                                <th>奖励加息</th>
                                <th>预约</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="norecord">
                                <td colspan="8">
                                    您还没有可续购的债权
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="pagination" data-pagination-ref="autoreserve_records"></div>
                    </div>
                    <div class="tip tab-rules">
                        <h3 class="title-indent">温馨提示</h3>
                        <div class="tip-main">
                            <ul class="tab-content">
                                <li>1、除活期、等额本息债权及全网通外，其他待回款金额均可参与续购，续购的债权是普通的精选P2P债权组合，即不含新手、专享、移动专属和品牌专享。</li>
                                <li>2、预约续购享受加息奖励，奖励规则如下： <br>续购第一次奖励加息0.2%， 第二次0.5%， 第三次1%， 续购三次之后循环。<br>例如：续购债权的年化利率是14%，则续购第一次年化利率=14%+0.2%，第二次年化利率=14%+0.5%，
                                    第三次年化利率=14%+1%，第四次年化利率=14%+0.2%。
                                </li>
                                <li>3、续购奖励的金额会在回款日发放到账户中。</li>
                                <li>4、已转让的债权将自动取消续购且没有续购奖励。</li>
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
    <script type="text/javascript" src="{!! config('app.static_url') !!}/js/plugins/tab.js"></script>
@stop
