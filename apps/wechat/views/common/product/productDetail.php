{% extends "../common/layouts/main.php" %}
{% block headercss %}
    <link rel="stylesheet" href="{{ static_url }}/wechat/ui/css/productDetail.css?v=04.22">
{% endblock %}
{% block content %}
<div class="fanhui_cou">
    <div class="fanhui_1"></div>
    <div class="fanhui_ding">顶部</div>
</div>
<div class="product_detail" id="product_detail">
    <header class="header">
        <div class="fix_nav">
            <div style="max-width:768px;margin:0 auto;background:#000;position: relative;">
                <a class="nav-left back-icon" href="javascript:history.back();">返回</a>
                <div class="tit">商品详细</div>
            </div>
        </div>
    </header>
    <div class="container ">
        <div class="row white-bg">
            <div id="slide">
                <div class="hd">
                    <ul>
                        {% for key,imageInfo in productInfo['images'] %}
                            <li class="on">{{key + 1}}</li>
                        {% endfor %}
                    </ul>
                </div>
                <div class="bd">
                    <div class="tempWrap" style="overflow:hidden; position:relative;">
                        <ul style="width: 3072px; position: relative; overflow: hidden; padding: 0px; margin: 0px; transition-duration: 200ms; transform: translateX(-768px);">
                            {% for imageInfo in productInfo['images']  %}
                                <li style="display: table-cell; vertical-align: middle; max-width: 768px;">
                                    <a href="javascript:void(0);">
                                        <img style="max-width:100vw;max-height:80vw;margin:auto;" src="{{imageInfo['image']}}">
                                    </a>
                                </li>
                            {% endfor %}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <form id="addProduct" method="POST" action="{{url({'for' : 'cart-add'})}}">
            <div class="row gary-bg">
                <div class="white-bg p10 details_con">
                    <input type="hidden" name="product_id" value="{{ productInfo['product_id'] }}">
                    <h1 class="item-name" id="prodName">{{productInfo['name']}}</h1>
                    {% if productInfo['manufacturer_id'] %}
                    <ul>
                        <li>
                            <label>{{manufacturerName}}：<em>{{productInfo['manufacturer_name']}}</em></label>
                        </li>
                    </ul>
                    {% endif %}
                    <ul id="product_option">
                    </ul>
                    <ul>
                        <li>
                            <label>数量：</label>
                            <div class="count_div" style="height: 30px; width: 130px;">
                                <a href="javascript:void(0);" class="minus form-control"></a>
                                    <input name='quantity' type="text" class="count form-control text-center" value="1" id="prodCount" readonly="readonly"/>
                                <a href="javascript:void(0);" class="add form-control"></a>
                            </div>
                        </li>
                        {% if productInfo['subtract'] == 1 %}
                            <li>
                                <label>当前库存：<em id="product_sku">{{productInfo['quantity']}} {{productInfo['sku']}}</em></label>
                            </li>
                        {% endif %}
                    </ul>
                </div>
                <div id="goodsContent" class="goods-content white-bg">
                    <div class="hd hd_fav">
                        <ul>
                            <li class="on">图文详情</li>
                        </ul>
                    </div>
                    <div class="tempWrap" style="overflow:hidden; position:relative;">
                        <div style="width: 2304px; position: relative; overflow: hidden; padding: 0px; margin: 0px; transition-duration: 200ms; transform: translateX(0px);" class="bd">
                            <ul style="display: table-cell; vertical-align: top; max-width: 768px;width: 100%;" class="property">
                                <div class="prop-area" style="min-height:300px;overflow: hidden;">
                                    {{ productInfo['description'] }}
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="clear"></div>
<div class="fixed-foot">
    <div class="fixed_inner">
        <a class="cart-wrap" href="{{url({'for' : 'cart-products'})}}">
            <i class="i-cart"></i>
            <span>购物车</span>
            <span class="add-num" id="totalNum">{{cartCount}}</span>
        </a>
        <div class="buy-btn-fix">
            <a class="btn btn-info btn-cart" onclick="addToCart()" href="javascript:void(0);">加入购物车</a>
            <a class="btn btn-danger btn-buy" onclick="buyNow();" href="javascript:void(0);">{{buyName}}</a>
        </div>
    </div>
</div>
{% endblock %}
{% block footer %}
{% endblock %}
{% block footerjs %}
<script charset="utf-8" src="{{ static_url }}/wechat/ui/js/TouchSlide.js"></script>
<script charset="utf-8" src="{{ static_url }}/wechat/ui/js/jquery.form.js?v=01291"></script>
<script charset="utf-8" src="{{ static_url }}/wechat/js/jquery-ui.min.js?v=01291"></script>
<script type="text/javascript">
var cartUrl = '{{url({"for" : "cart-products"})}}';
var regUrl = '{{url({"for" : "user-register"})}}';
var price = {{ productInfo['price'] }};
var points = {{ productInfo['points'] }};
var productOptions = {{ productInfo['options']|json_encode }};
$(document).ready(function () {
    var html = '';
    var cur_price = price;
    var cur_points = points;
    $.each (productOptions, function(i,t) {
        if(t['product_option_value'][0]){
            if (t['product_option_value'][0]['points_prefix'] == '-') {
                cur_points = parseFloat(cur_points) - parseFloat(t['product_option_value'][0]['points']);
            } else {
                cur_points = parseFloat(cur_points) + parseFloat(t['product_option_value'][0]['points']);
            }
            if (t['product_option_value'][0]['price_prefix'] == '-') {
                cur_price = parseFloat(cur_price) - parseFloat(t['product_option_value'][0]['price']);
            } else {
                cur_price = parseFloat(cur_price) + parseFloat(t['product_option_value'][0]['price']);
            }

            if (t['product_option_value'][0]['quantity'] == 0 || {{productInfo['quantity']}} == 0) {
                $("#product_sku").text("{{productInfo['stock_status']}}");
            } else if(t['product_option_value'][0]['quantity'] < {{productInfo['quantity']}} ) {
                $("#product_sku").text(t['product_option_value'][0]['quantity'] + "{{productInfo['sku']}} ");
            }
            cur_price = cur_price.toFixed(2)
        }
        html += '<li id="choose_' + i +'">';
        html += '<label id="propName" propname="' + t['name'] + '">' + t['name'] + '：</label>';
        if('select' == t['type']){
            html += '<select tid="' + i + '" class="product_option form-control" name="option[' + t['product_option_id'] + ']" style="width: 60%;display: inline" onchange="sumPrice();">';
            $.each(t['product_option_value'], function(index,item){
                html += '<option value="' + item['product_option_value_id'] + '">' + item['name'] + '</option>'
            });
            html += '</select>';
        }else if('radio' == t['type']){
            html += '<dl><input tid="' + i + '"class="product_option form-control" type="hidden" name="option[' + t['product_option_id'] + ']" value="' + t['product_option_value'][0]['product_option_value_id']  +'" />';
            $.each(t['product_option_value'], function(index,item){
                html +=  '<dd class="check" valId="' + t['product_option_value_id'] + '" info="' + item['product_option_value_id'] + '" >' +  item['name'] + '<span></span></dd>';
            });
            html += '</dl>';
        }
        html += '</li>';
    });
    var html_price = '<li>'+
        '<label>价格：</label>' +
        '<span class="price">¥<span class="price" id="prodCash">' + cur_price + '</span></span>' +
        '</li>';
    var html_points = '';
    if (cur_points) {
        html_points = '<li class="cur-points">'+
        '<label>赠送积分：</label>' +
        '<span class="points"><span class="points" id="prodPoints">' + cur_points + '</span></span>' +
        '</li>';
    }

    $("#product_option").html(html_price + html_points +  html);
    // 详情数量减少
    $('.details_con .minus,.cart_count .minus').click(function(){
        var _index=$(this).parent().parent().index()-1;
        var _count=$(this).parent().find('.count');
        var _val=_count.val();
        if(_val > 1){
            _count.val(_val-1);
            $('.details_con .selected span').eq(_index).text(_val-1);
        }
        if(_val<=2){
            $(this).addClass('disabled');
        }
    });
    // 详情数量添加
    $('.details_con .add,.cart_count .add').click(function(){
        var _index=$(this).parent().parent().index()-1;
        var _count=$(this).parent().find('.count');
        var _val=_count.val();
        $(this).parent().find('.minus').removeClass('disabled');
        _count.val(_val-0+1);
        $('.details_con .selected span').eq(_index).text(_val-0+1);
    });

    //详情属性选择
    $('.details_con ul li dd').click(function(e) {
        clickChoose(this);
    });
});

// 选中属性
function clickChoose(object) {
    var product_option_id = $(object).attr("valId");
    var val = $(object).attr("info");
    $("input[name='option[" + product_option_id + "]']").val(val);
    if (!$(object).hasClass('attr_sold_out')) {
        $(object).addClass('check').siblings().removeClass('check');
    }
    sumPrice();
}

function sumPrice() {
    var cur_price = price;
    var cur_points = points;
    $.each($(".product_option"), function() {
        var tid = $(this).attr('tid');
        var product_option_value_id = $(this).val();
        if(productOptions[tid]['product_option_value']){
            $.each(productOptions[tid]['product_option_value'], function(i, t){
                if(t['product_option_value_id'] == product_option_value_id){
                    if (t['price_prefix'] == '-') {
                        cur_price = parseFloat(cur_price) - parseFloat(t['price']);
                    } else{
                        cur_price = parseFloat(cur_price) + parseFloat(t['price']);
                    }
                    if (t['points_prefix'] == '-') {
                        cur_points = parseFloat(cur_points) - parseFloat(t['points']);
                    } else{
                        cur_points = parseFloat(cur_points) + parseFloat(t['points']);
                    }
                    if (t['quantity'] == 0 || {{productInfo['quantity']}} == 0) {
                        $("#product_sku").text("{{productInfo['stock_status']}}");
                    } else if(t['quantity'] < {{productInfo['quantity']}} ) {
                        $("#product_sku").text(t['quantity'] + "{{productInfo['sku']}} ");
                    } else {
                        $("#product_sku").text({{productInfo['quantity']}} + "{{productInfo['sku']}} ");
                    }
                }
            });
        }
        cur_price = cur_price.toFixed(2);
        $("#prodCash").text(cur_price);
        $("#prodCash").parent().parent().parent().find(".cur-points").remove()
        if (cur_points) {
            var html_points = '<li class="cur-points">'+
                '<label>赠送积分：</label>' +
                '<span class="points"><span class="points" id="prodPoints">' + cur_points + '</span></span>' +
                '</li>';
            $("#prodCash").parent().parent().after(html_points);
        }
    });
}

// 加入购物车
function addToCart() {
    $("#addProduct").attr('action', "{{url({'for' : 'cart-add'})}}");
    $("#addProduct").ajaxForm().ajaxSubmit({
        success:function(result) {
            if (result.status == 203) {
                window.location.href = regUrl;
            } else {
                if (result.status == 0) {
                    var cart = $('.cart-wrap');
                    var imgtodrag = $("#slide").find("img").eq(0);

                    if (imgtodrag) {
                        var imgclone = imgtodrag.clone()
                            .offset({
                                top: imgtodrag.offset().top,
                                left: imgtodrag.offset().left
                            }).css({
                                'opacity': '0.5',
                                'position': 'absolute',
                                'height': '150px',
                                'width': '150px',
                                'z-index': '100'
                            }).appendTo($('body')).animate({
                                'top': cart.offset().top + 10,
                                'left': cart.offset().left + 10,
                                'width': 75,
                                'height': 75
                            }, 1000, 'easeInOutExpo');
                        imgclone.animate({
                            'width': 0,
                            'height': 0
                        }, function () {
                            $(this).detach()
                        });
                    }

                    $("#totalNum").text(result.data.count);
                } else {
                    alert(result.info);
                }
            }
        },
        error:function(XMLHttpRequest, textStatus,errorThrown) {
            console.log(XMLHttpRequest);
        }
    });
}

function buyNow() {
    $("#addProduct").attr('action', "{{url({'for' : 'order-createOrderByProduct'})}}");
    $("#addProduct").ajaxForm().ajaxSubmit({
        success:function(result) {
            if (result.status == 203) {
                window.location.href = regUrl;
            } else {
                if (result.status == 0) {
                    window.location.href = "{{url({'for' : 'order-detail'})}}?order_id=" + result.data.order_id;
                } else {
                    alert(result.info);
                }
            }
        },
        error:function(XMLHttpRequest, textStatus,errorThrown) {
            console.log(XMLHttpRequest);
        }
    });
}

</script>

<script type="text/javascript">
    //插件：图片轮播
    TouchSlide({
        slideCell:"#slide",
        titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
        mainCell:".bd ul",
        effect:"left",
        autoPlay:false,//自动播放
        autoPage:true, //自动分页
        switchLoad:"_src" //切换加载，真实图片路径为"_src"
    });
    var scrollTop = 0;
    TouchSlide({
        slideCell:"#goodsContent",
        startFun:function(i,c){
            var prodId = $("#prodId").val();
            if(i==1){//规格参数
                var th = jQuery("#goodsContent .bd ul").eq(i);
                if($(window).scrollTop() > scrollTop){
                    $(window).scrollTop(scrollTop);
                }
            }else if(i==2){//评价
                var th = jQuery("#goodsContent .bd ul").eq(i);
                if($(window).scrollTop() > scrollTop){
                    $(window).scrollTop(scrollTop);
                }
            }else{
                if(scrollTop == 0){
                    $(window).scrollTop(scrollTop);
                    var hd_fav = $('.hd_fav');
                    var position = hd_fav.position();
                    scrollTop = position.top;
                }
            }
        }
    });
</script>
{% endblock %}