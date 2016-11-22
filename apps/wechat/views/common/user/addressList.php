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
    .address-cz label{ float: left; margin: 0;}
    .address-cz a{ float: left; width: 30%; text-align: right; font-size: 1.4rem;}
</style>
{% endblock %}
{% block content %}
<header class="header">
    <div class="fix_nav">
        <div style="max-width:768px;margin:0 auto;background:#000;position: relative;">
            <a class="nav-left back-icon" href="javascript:history.back();">返回</a>
            <div class="tit">管理活动人</div>
        </div>
    </div>
</header>
<ul class="address-list">
    {% for info in list %}
        <li class="curr">
            <p>活动人：{{info['fullname']}}</p>
            <p class="order-add1">微信帐号：{{info['wechat_user']}}</p>
            <p class="order-add1">身份证号码：{{info['idcard']}}</p>
            <p class="order-add1">手机号码：{{info['shipping_telephone']}}</p>
            <hr />
            <div class="address-cz">
                <label class="am-radio am-warning">
                    <input type="radio" name="default_address" value="{{info['address_id']}}" data-am-ucheck {% if info['default'] %} checked {% endif %}> 设为默认
                </label>
                <a href="{{url({'for' : 'user-editAddress'})}}?address_id={{info['address_id']}}">
                    <img src="{{ static_url }}/wechat/ui/images/bj.png" width="18" />&nbsp;编辑</a>
                <a href="javascript:" onclick="deleteAddress({{info['address_id']}})">删除</a>
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
            <a class="btn btn-info btn-cart" onclick="saveDefault();" href="javascript:void(0);">设置默认地址</a>
            <a href="{{url({'for' : 'user-addAddress'})}}" class="btn btn-danger btn-buy">新增活动人</a>
        </div>
    </div>
</div>
{% endblock %}
{% block footerNew %}
{% endblock %}
{% block footerjs %}
<script>
    function saveDefault() {
        var address_id = $("input[name='default_address']:checked").val();
        $.ajax({
            url: '{{url({"for": "user-setAddressDefault"})}}' ,
            data: {address_id:address_id},
            type: 'POST',
            async : true, //默认为true 异步
            dataType : 'json',
            error:function(data) {
            },
            success:function(data) {
                alert(data.info);
            }
        });
    }

    function deleteAddress(address_id) {
        if (confirm("确定删除活动人")) {
            $.ajax({
                url: '{{url({"for": "user-deleteAddress"})}}' ,
                data: {address_id:address_id},
                type: 'POST',
                async : true, //默认为true 异步
                dataType : 'json',
                error:function(data) {

                },
                success:function(data) {
                    if (data.status == 0) {
                        window.location.href = "{{url({'for' : 'user-addressList'})}}";
                    } else {
                        alert(data.info);
                    }
                }
            });
        }
    }
</script>
{% endblock %}
