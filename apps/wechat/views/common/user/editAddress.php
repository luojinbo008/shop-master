{% extends "layouts/main.php" %}
{% block headercss %}
<style>
    .paybtn{ background:#39b867 ; border-radius: 8px; margin-top: 8px; color: #fff; border: 0; text-align: center; width: 20%; margin: 0 auto; display: block; line-height: 35px; height: 35px;}
    .contact{ padding: 10px;}
    .contact input{ border: 1px solid #ddd; border-radius: 5px; width: 100%; line-height: 35px; padding-left: 10px;}
    .contact li{ margin: 10px 0;}
    .help-inline {color: red;}
</style>
{% endblock %}
{% block content %}
<header class="header">
    <div class="fix_nav">
        <div style="max-width:768px;margin:0 auto;background:#000;position: relative;">
            <a class="nav-left back-icon" href="javascript:history.back();">返回</a>
            <div class="tit">编辑活动人</div>
        </div>
    </div>
</header>
<div style="height: 49px;"></div>
<form id="form" method="post" action="" onsubmit="return false;">
    <ul class="contact">
        <li>
            <input type="hidden" name="address_id" value="{{info['address_id']}}"/>
            <input type="text" name="fullname" placeholder="请输入姓名" value="{{info['fullname']}}"/>
        </li>
        <li>
            <input type="text" name="idcard" placeholder="请输入用户身份证号" value="{{info['custom_field']['idcard']}}"/>
        </li>
        <li>
            <input type="text" name="wechat_user" placeholder="请输入用户微信账号" value="{{info['custom_field']['wechat_user']}}"/>
        </li>
        <li>
            <input type="text" name="shipping_telephone" placeholder="请输入手机号" value="{{info['shipping_telephone']}}"/>
        </li>
    </ul>
    <button class="paybtn" type="submit">确定</button>
</form>
{% endblock %}
{% block footerNew %}
{% endblock %}
{% block footerjs %}
<script src="{{ static_url }}/wechat/js/jquery-1.9.1.min.js" type="text/javascript"></script>
<script src="{{ static_url }}/wechat/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="{{ static_url }}/wechat/js/jquery-form.js" type="text/javascript"></script>
<script type='text/javascript'>
    jQuery.validator.addMethod("isMobile", function(value, element) {
        var length = value.length;
        var mobile = /^(13[0-9]{9})|(18[0-9]{9})|(14[0-9]{9})|(17[0-9]{9})|(15[0-9]{9})$/;
        return this.optional(element) || (length == 11 && mobile.test(value));
    }, "请正确填写您的手机号码！");
    jQuery.validator.addMethod("isIdCard", function(value, element) {
        var card = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
        return this.optional(element) || card.test(value);
    }, "请正确填写您的身份证号码！");
    jQuery.validator.addMethod("isMobile", function(value, element) {
        var length = value.length;
        var mobile = /^(13[0-9]{9})|(18[0-9]{9})|(14[0-9]{9})|(17[0-9]{9})|(15[0-9]{9})$/;
        return this.optional(element) || (length == 11 && mobile.test(value));
    }, "请正确填写您的手机号码！");
    jQuery.validator.addMethod("isIdCard", function(value, element) {
        var card = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
        return this.optional(element) || card.test(value);
    }, "请正确填写您的身份证号码！");

    var FormValidate = function () {
        return {
            init: function () {
                var form = $('#form');
                form.validate({
                    errorElement: 'em', //default input error message container
                    errorClass: 'help-inline', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",
                    rules: {
                        fullname:{
                            required: true
                        },
                        shipping_telephone:{
                            required: true,
                            isMobile : true
                        },
                        idcard:{
                            required: true,
                            isIdCard: true
                        },
                        wechat_user: {
                            required: true,
                        }
                    },
                    messages: {
                        fullname:{
                            required: "请输入姓名"
                        },
                        shipping_telephone:{
                            isMobile: "请输入正确的手机号码",
                            required: "请输入手机号码"
                        },
                        idcard:{
                            isIdCard: "请输入正确的身份证号码",
                            required: "请输入身份证号码"
                        },
                        wechat_user:{
                            required: "请输入微信账号"
                        },
                    },
                    submitHandler: function (form) {
                        $(form).ajaxSubmit({
                            type:"post",
                            url:"{{url({'for' : 'user-editAddress'})}}",
                            success: function(res){
                                if(res.status == 0){
                                    window.location.href = "{{url({'for' : 'user-addressList'})}}";
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
