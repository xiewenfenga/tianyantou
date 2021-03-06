@extends('admin.common.layout')
@section('title')提现管理@stop
@section('style')
    {!!HTML::style('admin/css/lists.css')!!}
    {!! HTML::style('admin/css/pop.css') !!}
    {!!HTML::style('admin/css/dialog.css')!!}
    {!!HTML::style('admin/css/form.css')!!}
@stop
@section('content')
    <div class="content-all">
        <div class="content clearfix">
            @include('admin.common.sidebar')
            <div class="content-right">
                <div class="content-right-header clearfix">
                    <div class="content-right-header clearfix">
                        <img src="{!!url('admin/images/u5.png')!!}"/>
                        <a href="{!! url('user') !!}">用户管理&nbsp;&nbsp;>&nbsp;&nbsp;</a>
                        <a href="{!! url('withdraw') !!}">提现管理</a>
                    </div>
                </div>
                <div class="content-right-tit clearfix">
                    <p><a href="javascript:void(0)" class="at">提现列表</a></p>
                </div>
                <div class="comment-search clearfix">
                    <form action="{!! url('withdraw') !!}" id="filterForm" method="GET">
                        <div class="comment-search-inner">
                            <div class="comment-search-goods">
                                <p>账户名<input type="text" name="hold_name" value="{!! Input::get('hold_name') !!}" /></p>
                            </div>
                            <div class="comment-search-person">
                                <p>卡号<input type="text" name="cardno" value="{!! Input::get('cardno') !!}" /></p>
                            </div>
                            <button class="comment-search-btn" id="search">搜索</button>
                            <input type="reset" value="重置" onclick="location='/withdraw'">
                        </div>
                    </form>
                </div>
                    <form action="">
                        <table class="all_shopping" cellspacing="0">
                            <tr>
                                <th width='45'></th>
                                <th width='65'>用户名</th>
                                <th width='65'>账号类型</th>
                                <th width='65'>账户名</th>
                                <th width='100'>开户银行</th>
                                <th width="100">支行名称</th>
                                <th width="150">卡号</th>
                                <th width="80">金额</th>
                                <th width="80">手续费</th>
                                <th width="60">提现时间</th>
                                <th width="60">状态</th>
                                <th>操作</th>
                            </tr>
                            @if(count($lists) > 0)
                            @foreach($lists as $wv)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="withdraw-ids" value="{!! $wv->id or '' !!}" @if($wv->status)disabled  readonly @endif>
                                    </td>
                                    </td>
                                    <td>{!! $wv->user->username or ''!!}</td>
                                    <td>{!! $wv->bank->type==0 ? '银行卡' : '支付宝' !!}</td>
                                    <td>{!! $wv->bank->hold_name or ''  !!}</td>
                                    <td>{!! $wv->bank->bank_name or ''  !!}</td>
                                    <td>{!! $wv->bank->branch_name or '--'  !!}</td>
                                    <td>{!! $wv->bank->cardno or ''  !!}</td>
                                    <td>{!! $wv->price or 0.00 !!}</td>
                                    <td>{!! $wv->commission or 0.00 !!}</td>
                                    <td>{!! date('m/d',strtotime($wv->created_at))  !!}</td>
                                    <td>{!! $wv->status == 0 ? '申请中' :  ($wv->status == 1 ? '已派发':'拒绝派发')  !!}</td>
                                    <td>
                                        <a href="{!! url('withdraw/create',['id'=>$wv->id]) !!}">审核</a>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="12">
                                    <input type="checkbox" class="checkAll" style="float: left;margin-left: 24px;margin-top: 15px;">全选
                                    <button type="button" class="all-del">审核</button>
                                </td>
                            </tr>
                            @else
                                <td colspan="11">
                                    暂无提现信息
                                </td>
                            @endif
                        </table>
                    </form>
            </div>
        </div>
    </div>
    <div id="del_menu_icon" style="display:none;">
        <div id="maskLevel"></div>
        <div class="del_icon_box">
            <div class="del_icon_title">
                <h3>提示</h3>
                <a href="javascript:;" onClick="$('#del_menu_icon').hide();">&times;</a>
            </div>
            <div class="del_icon_content">
                 <h4>请选择下面操作批量进行审核！</h4>
                <form action="{!! url('withdraw/batch') !!}" method="post" class="base_form">
                    <input type="hidden" name="ids" id="withdraw-id" />
                    <input type="radio" name="status" value="1" checked style="margin-left: 15px;">通过
                    <input type="radio" name="status" value="2" style="margin-left: 15px;">驳回
                    <input class="submit" type="submit" value="确定">
                    <input class="button" type="button" onClick="$('#del_menu_icon').hide();" value="取消">
                </form>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(".checkAll").click(function () {
            $("[type=checkbox]:enabled").prop("checked", this.checked);
        });
        $('.all-del').click(function () {
            var ids = [];
            $(".withdraw-ids:checked").each(function(){
                ids.push($(this).val());
            });
            if(ids.length < 1){
                error('未选中任何提现记录!');
            } else{
                $('#del_menu_icon').show();
                $('#del_menu_icon').find('#withdraw-id').val(ids.join(','));
            }
        })
    </script>
@stop
