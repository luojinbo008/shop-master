{% extends "layouts/main.php" %}
{% block headercss %}
<style>
    .cart_list ul li{color: #999;font-size: 13px;padding: 10px 0px;}
    .cart_list label{margin-top:5px;margin-right: 5px;margin-left:5px;}
    .cart_list ul li .price{font-size: 20px;color: #ce0000;}
    .cart_list ul li dl{display: inline-block;width: 82%;vertical-align: top;margin-top: -3px;}
    .cart_list ul li dl dd,.cart_list .minus,.cart_list .count,.cart_list .add{display: inline-block;color: #333;padding: 4px 10px;border: 1.5px solid #aaa;margin:  0 10px 5px 0;position: relative;cursor: pointer;}
    .cart_list ul li dl .check span{display: inline-block;width: 14px;height: 14px;background:  url({{ static_url }}/wechat/ui/images/checkIcont.png) no-repeat;position: absolute;bottom: -1px;right: -1px;}
    .cart_list .count { color: #333; font-size: 13px; height: 25px; vertical-align: middle; width: 40px;-webkit-appearance:none;}
    .cart_list .minus,.cart_list .add{font-size: 18px;color: #333;font-weight: bold;width: 10px;text-align: center;height: 25px;vertical-align: middle;background: url({{ static_url }}/wechat/ui/images/addIcon.png) no-repeat center 90%;background-size: auto 600%;}
    .cart_list .minus{background-position: center 50%;}

</style>
{% endblock %}
{% block content %}
<div class="fanhui_cou">
	<div class="fanhui_1"></div>
	<div class="fanhui_ding">顶部</div>
</div>
<div id="product_detail">
    <header class="header header_1">
        <div class="fix_nav">
            <div class="nav_inner">
                <a class="nav-left back-icon" href="javascript:history.back();">返回</a>
                <div class="tit">购物车</div>
            </div>
        </div>
    </header>
    <div class="container cart_list">
        <div class="row rowcar">
            {% for product in product_list %}
                <ul class="list-group detail_ul_{{product['cart_id']}}" cart_id="{{product['cart_id']}}">
                    <li class="list-group-item text-primary">
                        <div class="icheck pull-left mr5">
                            <input type="checkbox" checked="checked" class="ids" itemkey=""/>
                            <label class="checkLabel pull-left">
                                <span style="top:-10px;"></span>
                            </label>
                        </div>
                        {{store_name}}
                    </li>
                    <li class="list-group-item hproduct clearfix detail_li">
                        <div class="p-pic ml5">
                            <a href="{{url({'for' : 'product-detail'})}}?product_id={{product['product_id']}}">
                                <img class="img-responsive" src="{{product['image']}}"/>
                            </a>
                        </div>
                        <div class="p-info">
                            <a href="{{url({'for' : 'product-detail'})}}?product_id={{product['product_id']}}">
                                <p class="p-title">{{product['name']}}</p>
                            </a>
                            <p class="p-attr">
                                <span>
                                    {% for option in product['option'] %}
                                        {{option['name']}}：{{option['value']}}；
                                    {% endfor %}
                                </span>
                            </p>
                            <p class="p-origin">
                                <a class="close p-close mr10" onclick="deleteShopCart('{{product['name']}}', {{product['cart_id']}}, this)" href="javascript:void(0);">×</a>
                                <em class="price">¥<?php echo sprintf("%01.2f", $product['price']); ?></em>
                            </p>
                        </div>
                    </li>
                    <li class="list-group-item clearfix">
                        <div class="pull-left mt5">
                            <span class="gary">小计：</span>
                            <em class="red productTotalPrice">¥<?php echo sprintf("%01.2f", $product['total']); ?></em>
                        </div>
                        <div class="btn-group btn-group-sm pull-right">
                            <span class="mt5" style="float: left;">数量：</span>
                            <a href="javascript:void(0);" onclick="disDe(this)"  class="minus form-control"></a>
                            <input type="text" class="count form-control text-center" readonly="readonly" value="{{product['quantity']}}" cart_id="{{product['cart_id']}}">
                            <a href="javascript:void(0);" onclick="increase(this)" class="add form-control"></a>
                        </div>
                    </li>
                </ul>
            {% endfor %}
           </div>
    </div>
    <div class="fixed-foot">
        <div class="fixed_inner">
            <div class="pay-point">
                 <p>合计：<em class="red f22">¥<span id="totalPrice"><?php echo sprintf("%01.2f", $total); ?></span></em></p>
            </div>
            <div class="buy-btn-fix">
                <a href="javascript:" onclick="checkCart();" class="btn btn-danger btn-buy">去结算</a>
            </div>
        </div>
    </div>
</div>
<div class="clear"></div>
{% endblock %}
{% block footer %}
{% endblock %}
{% block footerjs %}
<script type="text/javascript">
    $(document).ready(function() {
        // 勾选
        $(".checkLabel").click(function () {
            var flag = $(this).prev().prop('checked');
            if (flag) {
                $(this).prev().prop("checked", false);
            } else {
                $(this).prev().prop("checked", true);
            }
            //计算总价
            calculateTotal();
        });
    });

    //计算总价
    function calculateTotal()
    {
        var allCash = 0;
        var list = $(".container").find(".list-group").get();
        for(var i=0;i<list.length;i++){
            var selected = $(list[i]).find("div[class='icheck pull-left mr5']>:checkbox").prop("checked");
            if (selected) {
                var cash = $(list[i]).find("em[class='price']").html().substring(1);//取单价
                var count = $(list[i]).find("input[class='count form-control text-center']").val();//取数量
                allCash += Number(cash) * Number(count);
            }
        }
        allCash = Math.round(Number(allCash)*100)/100;
        var pos_decimal = allCash.toString().indexOf('.');
        if (pos_decimal < 0){
            allCash += '.00';
        }
        $("#totalPrice").html(allCash);
    }

    // 加
    function increase(obj) {
        var _this = $(obj);
        var _count_obj=_this.prev();
        var count =Number($(_count_obj).val());
        var cart_id =Number($(_count_obj).attr('cart_id'));
        var _num = parseInt(count) + 1;
        var re = /^[1-9]+[0-9]*]*$/;
        if ( isNaN(_num) || ! re.test(_num)) {
            return ;
        } else if (_num==9999) {
            return;
        }
        if (changeShopCartNumber(obj, cart_id, _num)) {
            $(_count_obj).val(count + 1);
            var cash = $(_this).parent().parent().prev().find("em[class='price']").html().substring(1);//单价
            var e_cash = Math.round((Number(cash) * Number(count + 1))*100) / 100;
            var pos_decimal = e_cash.toString().indexOf('.');
            if (pos_decimal < 0) {
                e_cash += '.00';
            }
            $(_this).parent().prev().find("em").html("¥" + e_cash);
            // 计算总价
            calculateTotal();
        }
    }

    // 减
    function disDe(obj) {
        var _this = $(obj);
        var _count_obj=_this.next();
        var count =Number($(_count_obj).val());
        var cart_id =Number($(_count_obj).attr('cart_id'));
        var _num = parseInt(count)-1;
        var re = /^[1-9]+[0-9]*]*$/;
        if (isNaN(_num) || ! re.test(_num)) {
            return ;
        } else if (_num==0) {
            return ;
        }
        if (changeShopCartNumber(obj, cart_id, _num)) {
            $(_count_obj).val(count - 1);
            var cash = $(_this).parent().parent().prev().find("em[class='price']").html().substring(1); // 单价
            var e_cash = Math.round((Number(cash) * Number(count - 1))*100)/100;
            var pos_decimal = e_cash.toString().indexOf('.');
            if (pos_decimal < 0) {
              e_cash += '.00';
            }
            $(_this).parent().prev().find("em").html("¥"+e_cash);

            //计算总价
            calculateTotal();
        }

    }

    // 更新购物车商品数量
    var change_status = true;
    function changeShopCartNumber(obj, cart_id, quantity) {
        if (!change_status) {
            return false;
        }
    	var config = false;
    	$.ajax({
    		url: "{{url({'for' : 'cart-changeQuantity'})}}",
    		data: {"quantity" : quantity,"cart_id" : cart_id},
    		type: 'post',
    		async: false, //默认为true 异步
    		dataType: 'json',
    		error: function(data) {

    		},
    		success: function(result) {
    			if (result.status == 0) {
                    var _this = $(obj);
                    var pos_decimal = result.data.price.toString().indexOf('.');
                    if (pos_decimal < 0) {
                        result.data.price += '.00';
                    }
                    $(_this).parent().parent().prev().find("em[class='price']").html("¥" + result.data.price);
    				config = true;
    			} else {
                    alert(result.info);
                }
    		}
    	});
    	return config;
    }

    function checkCart() {
        var cart_ids = new Array;
        var list = $(".container").find(".list-group").get();
        for (var i=0;i<list.length;i++) {
            var selected = $(list[i]).find("div[class='icheck pull-left mr5']>:checkbox").prop("checked");
            if (selected) {
                cart_ids.push($(list[i]).attr('cart_id'));
            }
        }
        if (cart_ids.length == 0) {
            alert("没有选择购买物品！");
            return false;
        }
        $.ajax({
            url: "{{url({'for' : 'cart-checkout'})}}",
            data:{cart_ids : cart_ids},
            type:'post',
            async : true, //默认为true 异步
            dataType : 'json',
            error:function(data){

            },
            success:function(res) {
                if (res.status == 0) {
                    window.location.href = "{{url({'for' : 'order-detail'})}}?order_id=" + res.data.order_id;
                }else{
                    alert(res.info);
                }
            }
        });
    }

    function deleteShopCart(name, cart_id, obj) {
        if(confirm("删除后不可恢复, 确定要删除'" + name + "'吗？")){
            $.ajax({
                url: "{{url({'for' : 'cart-delete'})}}",
                data: {"cart_id":cart_id},
                type:'POST',
                async : true, //默认为true 异步
                dataType : 'json',
                error:function(data){
                },
                success:function(data){
                    if(data.status == 0){
                        $(obj).parent().parent().parent().parent().remove();
                        calculateTotal();
                        return ;
                    }else{
                        alert("删除失败");
                        return false;
                    }

                }
            });
        }
    }
</script>
{% endblock %}
