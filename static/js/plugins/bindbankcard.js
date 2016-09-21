
(function() {
    var $form = $('#bindbankcard');
    var $province = $form.find('select[name=sheng]');
    var $city = $form.find('select[name=city]');
    //showLocation($province, $city);

    $form.submit(function(){
        var $hold_name = $(this).find("select[name='data[hold_name]']");
        var $province = $(this).find("select[name='data[province]']");
        var $city = $(this).find("select[name='data[city]']");
        var bankName = $(this).find("select[name='data[bank_name]']").val();
        if(!$.trim($(this).find("input[name='data[hold_name]']").val())){
            messageBox('请填写开户名称', 2);
            return false;
        }
        if(!$(this).find("select[name='data[bank_name]']").val()) {
            messageBox('请选择开户行', 2);
            return false;
        }
        if($province.val() == '省'){
            messageBox('请选择省份', 2);
            return false;
        }
        if($city.val() == '市'){
            messageBox('请选择城市', 2);
            return false;
        }
        if(!$.trim($(this).find("input[name='data[branch_name]']").val())){
            messageBox('请填写开户支行名称', 2);
            return false;
        }
        var bank_id = $.trim($(this).find("input[name='data[cardno]']").val());
        if(!bank_id){
            messageBox('请输入银行卡号', 2);
            return false;
        }
        if(!/^\d{12,19}$/.test(bank_id)){
            messageBox('银行卡只能包含12到19位数字', 2);
            return false;
        }
        if($.trim($(this).find('input[name=confirm_cardno]').val()) != bank_id){
            messageBox('两次银行卡输入不相同', 2);
            return false;
        }
        //if(!$.trim($(this).find('input[name=verify_code]').val())){
        //    messageBox('请输入手机验证码', 2);
        //    return false;
        //}
        $province.children("option").each(function(i){
            if($(this).val() == $province.val()){
                $('input[name=sheng_str]').val($(this).html());
            }
        });
        $city.children("option").each(function(i){
            if($(this).val() == $city.val()){
                $('input[name=city_str]').val($(this).html());
            }
        });
    });
})();