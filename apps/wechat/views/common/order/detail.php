{% extends "layouts/main.php" %}
{% block headercss %}
<style>
    .address-list{ margin: 20px 3%; color: #666; padding-bottom: 20px;}
    .address-list li{ border-radius: 5px; border:1px solid #ddd; padding: 10px 0px; margin-bottom: 20px;}
    .address-list .curr{ border: 1px solid #39b867;}
    .address-list li p{ padding: 2px 20px;}
    .address-list hr{ margin: 10px 0;}
    .address-cz{ overflow: hidden; padding:0 20px; color: #bbb;}
    .address-cz a{ color: #bbb;}
    .address-cz label{ float: right; margin: 0;}
    .address-cz a{ float: left; width: 30%; text-align: left; font-size: 1.4rem;}
</style>
{% endblock %}
{% block content %}
<div id='order_info'>
    <header class="header">
        <div class="fix_nav">
            <div class="nav_inner">
                <a class="nav-left back-icon" href="{{url({'for' : 'order-list'})}}">返回</a>
                <div class="tit">填写订单</div>
            </div>
        </div>
    </header>
    <div class="container mb50">
        <div class="row">
            <ul class="list-group">
                <div class="list-group_12">
                  {% for product in info['product_list'] %}
                    <ul class="list-group">
                        <li class="list-group-item hproduct clearfix ">
                            <div class="p-pic">
                                <a href="{{url({'for' : 'product-detail'})}}?product_id={{product['product_id']}}">
                                    <img src="{{product['image']}}" alt="{{product['name']}}" class="img-responsive">
                                </a>
                            </div>
                            <div class="p-info">
                                <a href="{{url({'for' : 'product-detail'})}}?product_id={{product['product_id']}}">
                                    <p class="p-title">{{product['name']}}</p>
                                </a>
                                <p class="p-attr">
                                     <span>
                                        {% for option in product['option'] %}
                                            {% if option | length %}
                                                {{option['name']}}：{{option['value']}}；
                                            {% endif %}
                                        {% endfor %}
                                     </span>
                                </p>
                                <p class="p-origin">
                                    <em class="price">¥{{product['price']}}</em>
                                </p>
                            </div>
                        </li>
                        <li class="list-group-item clearfix">
                            <div class="pull-left mt5">
                                <span class="gary">小计：</span>
                                <em class="red productTotalPrice">￥{{product['total']}}</em>
                            </div>
                            <div class="pull-right mt5">
                                <span class="gary">数量：</span>
                                <em>{{product['quantity']}}</em>
                            </div>
                        </li>
                    </ul>
                    {% endfor %}
                 </div>
             </ul>
            <ul class="address-list" onclick="$('#order_info').hide();$('#address_list').show();" style="margin: 20px 3%; color: #666; padding-bottom: 0px;" id="address_list_checked">
                {% for address in info['address_list'] %}
                    <li class="curr">
                        <p>联系人姓名：{{address['shipping_fullname']}}</p>
                        <p class="order-add1">微信帐号：{{address['shipping_custom_field']['wechat_user']}}</p>
                        <p class="order-add1">身份证号码：{{address['shipping_custom_field']['idcard']}}</p>
                        <p class="order-add1">手机号码：{{address['shipping_telephone']}}</p>
                    </li>
                {% endfor %}
            </ul>
            <div class="list-group">
                <p class="list-group-item text-primary">
                    {% if info['order_status_id'] in [0,1] %}
                        支付：
                    {% else %}
                        状态：
                    {% endif %}
                    <em class="red productTotalPrice" id="payType">
                        {% if info['order_status_id'] in [3,5]%}
                            支付成功
                        {% elseif info['order_status_id'] == 6 %}
                            退款处理中
                        {% elseif info['order_status_id'] == 7 %}
                            退款成功
                        {% elseif info['order_status_id'] == 8 %}
                            退款失败
                        {% else %}
                            <input name="payment" type="radio" checked value="jsWechat">微信支付
                            <input name="payment" type="radio" value="alipayWap">支付宝
                        {% endif %}
                    </em>
                </p>
            </div>
            {% if info['order_status_id'] == 8 %}
            <div class="list-group">
                <p class="list-group-item text-primary">
                    备注：
                    <em class="red productTotalPrice">
                        {{info['comment']}}
                    </em>
                </p>
            </div>
            {% endif %}
            {% if info['order_status_id'] == 4 or info['order_status_id'] == 3 %}
               <div class="list-group">
                   <p class="list-group-item text-primary">
                       <em style="margin-left:85%;" class=" productTotalPrice">
                           <a class="btn btn-info btn-cart" href="javascript:" data-toggle="modal" data-target="#Modal" data-title="申请退款" data-tpl="mp">退款</a>
                       </em>
                   </p>
               </div>
            {% endif %}
        </div>
    </div>
    {% if info['order_status_id'] == 0 or info['order_status_id'] == 1 %}
        <div class="fixed-foot">
            <div class="fixed_inner">
                <div class="pay-point black">
                    实付款：<em class="red f22">￥<span id="totalPrice">{{info['total']}}</span></em>
                </div>
                <div class="buy-btn-fix">
                    <a href="javascript:submitOrder();" class="J_payBtn btn btn-danger btn-buy">提交订单</a>
                </div>
            </div>
        </div>
    {% endif %}
</div>
<div id='address_list' style="display: none;">
    <header class="header">
        <div class="fix_nav">
            <div class="nav_inner">
                <a class="nav-left back-icon" href="javascript:"
                   onclick="$('#order_info').show();$('#address_list').hide();">返回</a>
                <div class="tit">联系方式</div>
            </div>
        </div>
    </header>
    <ul class="address-list" id="address_list_check">
        {% for info in addressList %}
            <li class="curr">
                <p>联系人姓名：{{info['fullname']}} <input type="hidden" name="address_id" value="{{info['address_id']}}"></p>
                <p class="order-add1">微信帐号：{{info['wechat_user']}}</p>
                <p class="order-add1">身份证号码：{{info['idcard']}}</p>
                <p class="order-add1">手机号码：{{info['shipping_telephone']}}</p>
                <hr />
                <div class="address-cz">
                    <label class="am-radio am-warning">
                        <input type="radio" name="address_id" value="{{info['address_id']}}" data-am-ucheck {% if info['default'] %} checked {% endif %}> 选定
                    </label>
                </div>
            </li>
        {% endfor %}
    </ul>
    <div class="fixed-foot">
        <div class="fixed_inner">
            <div class="pay-point">
                <p>&nbsp;</p>
            </div>
            <div class="buy-btn-fix">
                <a href="{{url({'for' : 'user-addAddress'})}}" class="btn btn-danger btn-buy">新增联系方式</a>
                <a href="javascript:" onclick="checkAddress();" class="btn btn-danger btn-buy">确定</a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header member_tc_top">
                <button type="button" class="close member_tc_xx" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">申请退款</h4>
            </div>
            <form class="form" role="form" data-method="formAjax" id="refundForm">
                <div style="overflow:hidden;width: 100%;padding-top: 20px;">
                    <div style="">
                        <div class="member_mp_t_m">
                            <ul>
                                <li>
                                    <input type="hidden" value="{{order_id}}" name="order_id">
                                    <textarea placeholder='退款理由' name="comment" style="width: 95%; height: 200px; resize: none;"></textarea>
                                </li>
                                <li>
                                    <input id='btn_enter' type="submit" class="btn btn-cart" value="确定" />
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </form>
            <div style="height:60px;"></div>
        </div>
    </div>
</div>
{% endblock %}
{% block footer %}
{% endblock %}
{% block footerjs %}
<script charset="utf-8" src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="{{ static_url }}/wechat/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="{{ static_url }}/wechat/js/jquery-form.js" type="text/javascript"></script>
<script type="text/javascript">
{% if info['order_status_id'] == 0 or info['order_status_id'] == 1 %}
    wx.config({
        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: '{{appid}}',// 必填，公众号的唯一标识
        timestamp: '{{timestamp}}', // 必填，生成签名的时间戳
        nonceStr: '{{noncestr}}', // 必填，生成签名的随机串
        signature: '{{signature}}',// 必填，签名，见附录1
        jsApiList: [
            'chooseWXPay',
        ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });

    function submitOrder() {
        var shipping = {{info['shipping']}};
        if ($("#address_list_checked").find("li").length <= 0 && 1 == shipping) {
            $("#order_info").hide();
            $("#address_list").show();
            return false;
        }
        var payment = $('#payType input[name="payment"]:checked ').val();
        if (payment == 'jsWechat') {
            $.ajax({
                url: "{{url({'for' : 'order-checkout'})}}",
                type: 'GET',
                data: {'order_id':{{order_id}}, payment:payment},
                async : true, //默认为true 异步
                dataType : 'json',
                error:function(data){
                },
                success:function(data) {
                    if(data.status == 0) {
                        if (typeof WeixinJSBridge == "undefined"){
                            if( document.addEventListener ){
                                document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                            }else if (document.attachEvent){
                                document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                                document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                            }
                        } else{
                            jsApiCall(data.data.jsParams);
                        }
                    }else{
                        alert(data.info);
                    }
                }
            });
        } else if (payment == 'alipayWap') {
            window.location.href = "{{url({'for' : 'order-checkout'})}}?order_id={{order_id}}&payment=" + payment;
        }
    }

    function jsApiCall(jsApiParameters) {
        jsApiParameters.timeStamp = jsApiParameters.timeStamp.toString();
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest',
            jsApiParameters,
            function(res){
                // 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回    ok，但并不保证它绝对可靠。
                if(res.err_msg == "get_brand_wcpay_request:ok") {
                    //跳转到订单中心
                    window.location.href = '{{url({"for": "order-list"})}}?type=3';
                }else{
                }
            }
        );
        if (typeof WeixinJSBridge == "undefined"){
            if( document.addEventListener ){
                document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
            }else if (document.attachEvent){
                document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
                document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
            }
        }else{
            //jsApiCall(jsApiParameters);
        }
    }
    function checkAddress() {
        var obj = $("#address_list_check").find("li");
        var obj_tmp = obj.clone();
        var obj_parent = null;
        var address_id = null;
        $.each(obj_tmp, function (i, t) {
            var tmp = $(t).find('input[type="radio"]:checked').val();
            if (tmp) {
                $(t).find('.address-cz').remove();
                obj_parent = document.createElement('div');
                $(obj_parent).html(t);
                address_id = tmp;
            }
        });
        if (address_id) {
            $.ajax({
                url: "{{url({'for' : 'order-checkAddress'})}}",
                data: { address_id : address_id, order_id : {{info['order_id']}}},
                type: 'POST',
                async : true, //默认为true 异步
                dataType : 'json',
                error:function (data) {

                },
                success:function(data) {
                    if(data.status == 0) {
                        $("#address_list_checked").html('<div class="list-group_12" style="margin: 10px;">联系方式：</div>' + $(obj_parent).html());
                        $('#order_info').show();
                        $('#address_list').hide();
                    }else{
                        alert(data.info);
                    }
                }
            });
        }
        return false;
    }
{% endif %}

var FormValidate = function () {
    return {
        init: function () {
            var form = $('#refundForm');
            form.validate({
                errorElement: 'em', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    comment:{
                        required: true
                    }
                },
                messages: {
                    comment:{
                        required: "退款理由必填！"
                    }
                },
                submitHandler: function (form) {
                    $(form).ajaxSubmit({
                        type:"post",
                        url:"{{url({'for' : 'order-refund'})}}",
                        success: function(res){
                            if (res.status == 0) {
                                window.location.href = "{{url({'for' : 'order-list'})}}?type=2";
                            }else{
                                alert(res.info);
                            }
                        }
                    });
                }
            });
        }
    }
}();
jQuery(document).ready(function() {
    FormValidate.init();
});
</script>
{% endblock %}