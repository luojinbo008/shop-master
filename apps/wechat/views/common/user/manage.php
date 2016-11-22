{% set url = url(), version = '1.0.0', static_url = static_url() %}
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content="telephone=no" name="format-detection" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="viewport" content="width=320, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon.png"/>
	<title>徒游户外</title>
	<meta name="keywords" content="徒游 户外" />
	<meta name="description" content="徒游 户外" />
	<link href="{{ static_url }}/wechat/register/css/reset.css" rel="stylesheet" type="text/css" />
	<link href="{{ static_url }}/wechat/register/css/head.css" rel="stylesheet" type="text/css" />
	<link href="{{ static_url }}/wechat/register/css/foot.css" rel="stylesheet" type="text/css" />
	<link rel='stylesheet' type='text/css' href='{{ static_url }}/wechat/register/css/signup.css' />
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
    var FormValidate = function () {
       return {
           init: function () {
               var form = $('#form_edit');
               form.validate({
                   errorElement: 'em', //default input error message container
                   errorClass: 'help-inline', // default input error message class
                   focusInvalid: false, // do not focus the last invalid input
                   ignore: "",
                   rules: {
                       fullname:{
                           required: true
                       },
                       telephone:{
                           required: true,
                           isMobile : true
                       },
                       idcard:{
                           required: true,
                           isIdCard: true
                       }
                   },
                   messages: {
                       fullname:{
                           required: "姓名必填！"
                       },
                       idcard:{
                           required: "身份证号码必填！"
                       },
                       telephone:{
                           required: "手机号码必填！"
                       }
                   },
                   submitHandler: function (form) {
                       $(form).ajaxSubmit({
                           type:"post",
                           url:"{{url({'for' : 'user-update'})}}",
                           success: function(res){
                               if(res.status == 0){
                                   window.location.href = "{{url({'for' : 'user-index'})}}";
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
</head>
<body>
	<div class="header" id="header">
        <a class="back" href="javascript:history.back();"></a>
		<span class="headline">用户管理</span>
	</div>
	<section class="signup">
	<div class="form">
        {% if type == 'show' %}
            <form id="form" method="post">
                <ul>
                    <li>
                        <input class='tipInput' placeholder='昵称' type="text" placeholder="" value="{{nickname}}" disabled name="nickname">
                    </li>
                    <li>
                        <input type="hidden" value="edit" name="type">
                        <input class='tipInput' placeholder='真实姓名' type="text" placeholder="" value="{{fullname}}" disabled name="fullname">
                    </li>
                    <li>
                        <input class='tipInput' placeholder='手机号' type="text" placeholder="" value="{{telephone}}" disabled name="telephone">
                    </li>
                    <li>
                        <input class='tipInput' placeholder='身份证号码' type="text" placeholder="" value="{{idcard}}" disabled name="idcard">
                    </li>
                    <li>
                        <input id='btn_enter' type="submit" class="btn" value="编辑用户" />
                    </li>
                </ul>
            </form>
        {% else %}
            <form id="form_edit" method="post">
                <ul>
                    <li>
                        <input class='tipInput' placeholder='昵称' type="text" placeholder="" value="{{nickname}}" name="nickname">
                    </li>
                    <li>
                        <input class='tipInput' placeholder='真实姓名' type="text" placeholder="" value="{{fullname}}" name="fullname">
                    </li>
                    <li>
                        <input class='tipInput' placeholder='手机号' type="text" placeholder="" value="{{telephone}}" name="telephone">
                    </li>
                    <li>
                        <input class='tipInput' placeholder='身份证号码' type="text" placeholder="" value="{{idcard}}" name="idcard">
                    </li>
                    <li>
                        <input id='btn_enter' type="submit" class="btn" value="保存" />
                    </li>
                </ul>
            </form>
        {% endif %}
	</div>
</section>
</body>
</html>

