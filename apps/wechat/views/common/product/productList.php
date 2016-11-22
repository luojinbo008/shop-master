{% extends "layouts/main.php" %}
{% block headercss %}
{% endblock %}
{% block content %}
<div class="fanhui_cou">
    <div class="fanhui_1"></div>
    <div class="fanhui_ding">顶部</div>
</div>
<header class="header">
    <div class="fix_nav">
        <div class="nav_inner">
            <a class="nav-left back-icon" href="javascript:history.back();">返回</a>
            <div class="tit">{{titleName}}</div>
        </div>
    </div>
</header>
<div class="container" id="container2">
    <div class="row">
        <ul class="mod-filter clearfix">
            <div class="white-bg_2 bb1">
                <li id="default" class="active">
                    <a title="默认排序"  href="javascript:void(0);">默认</a>
                </li>
                <li id="quantity">
                    <a title="点击库存从高到低排序" href="javascript:void(0);">
                        库存<i class='icon_sort'></i>
                    </a>
                </li>
                <li id="price">
                    <a title="点击按价格从高到低排序" href="javascript:void(0);" >
                        价格<i class='icon_sort'></i>
                    </a>
                </li>
            </div>
        </ul>
        <div class="item-list" id="container" rel="2" status="0">
        </div>
        <div id="ajax_loading" style="display:none;width:300px;margin: 10px  auto 15px;text-align:center;">
            <img src="{{ static_url }}/wechat/ui/images/loading.gif">
        </div>
        <form action="{{url({'for' : 'product-list'})}}" method="post" id="list_form">
            <input type="hidden" id="curCategoryId" name="category_id" value="{{ category_id }}" />
            <input type="hidden" id="curFilterName" name="filter_name" value="{{ filter_name }}" />
            <input type="hidden" id="curOrders" name="order" value="sort_order,asc" />
            <input type="hidden" id="curPageNO" name="page" value="{{page}}" />
            <input type="hidden" id="curTotalPage" name="total_page" value="{{total_page}}" />
        </form>
    </div>
</div>
<div class="clear"></div>
{% endblock %}
{% block footer %}
{% endblock %}
{% block footerjs %}
<script charset="utf-8" src="{{ static_url }}/wechat/ui/js/jquery.form.js?v=01291"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        // 一过来就调用
        sendData();
        $(window).scroll(function() {
            if ($(this).scrollTop() > 300) {
                $(".fanhui_cou").fadeIn(1500);
            } else {
                $(".fanhui_cou").fadeOut(1500);
            }
        });
        $(".fanhui_cou").click(function() {
            $("body,html").animate({scrollTop:0},200);
            return false;
        });
        $(window).scroll(function() {
            if ($(document).height() - $(this).scrollTop() - $(this).height()<50) {
                var curPageNo = $("#curPageNO").val();
                if (isBlank(curPageNo) || curPageNo == 0) {
                    curPageNo = 1;
                }
                curPageNo=parseInt(curPageNo) + 1;
                var totalPage=parseInt($("#curTotalPage").val());
                if (curPageNo<=totalPage) {
                    $("#curPageNO").val(curPageNo);
                    appendData();
                }
            }
        });

        // 绑定 点击事件
        $(".row ul li").bind("click",function() {
            var id = $(this).attr("id");
            var orderDir = "";
            $(".row ul li").each(function(i) {
                if (id != $(this).attr("id")) {
                    $(this).removeClass("active");
                }
            });
            $(this).addClass("active");
            var iElement=$(this).find("i");
            if (id == 'quantity') {
                if ($(iElement).hasClass("icon_sort_up")) {
                    orderDir = "quantity,asc";
                    $(iElement).attr("class","icon_sort_down");

                } else if($(iElement).hasClass("icon_sort_down")){
                    orderDir = "quantity,desc";
                    $(iElement).attr("class","icon_sort_up");

                }else{
                    orderDir = "quantity,desc";
                    $(iElement).attr("class","icon_sort_up");
                }
            } else if (id == 'price') {
                if ($(iElement).hasClass("icon_sort_down")) {
                    orderDir = "price,desc";
                    $(iElement).attr("class","icon_sort_up");

                } else if($(iElement).hasClass("icon_sort_up")){
                    orderDir = "price,asc";
                    $(iElement).attr("class","icon_sort_down");

                }else{
                    orderDir = "price,desc";
                    $(iElement).attr("class","icon_sort_up");
                }
            } else if (id == 'default') {
                orderDir = "sort_order,asc";
            }
            $(this).siblings().find("i").attr("class","icon_sort");

            $("#curOrders").val(orderDir);
            var no_results = $.trim($("#no_results").html());
            if(no_results!="" && no_results!=null && no_results!=undefined){
                return false;
            }
            $("#curPageNO").val(1);
            sendData();
        });
    });

    function sendData() {
        $('#ajax_loading').show();
        $("#list_form").ajaxForm().ajaxSubmit({
            success:function(result) {
                $('#ajax_loading').hide();
                var html = '';
                $.each(result.data.products, function(i, t) {
                    html += '<a href="' + t.url + '">';
                    html += '<div class="hproduct clearfix" style="background:#fff;border-top:0px;">';
                    html += '<div class="p-pic">';
                    html += '<img style="max-height:100px;margin:auto;" class="img-responsive" src="' + t.image + '">';
                    html += '</div>';
                    html += '<div class="p-info">';
                    html += '<p class="p-title">' + t.name + '</p>';
                    if(t.special != 0){
                        html += '<p class="p-origin">特价：<em class="price">¥' + t.special + '</em></p>';
                        html += '<p class="mb0">价格：<del class="old-price">¥' + t.price + '</del></p>';
                    }else{
                        html += '<p class="p-origin">价格：<em class="price">¥' + t.price + '</em></p>';
                    }
                    html += '</div>';
                    html += '</div>';
                    html += '</a>';
                });
                $("#curTotalPage").val(result.data.total_page);
                $("#container").html(html);
            },
            error:function(XMLHttpRequest, textStatus,errorThrown) {
                $("#container").html("");
                $('#ajax_loading').hide();
                floatNotify.simple("查找失败");
                return false;
            }
        })
    }

    function appendData() {
        $('#ajax_loading').show();
        $("#list_form").ajaxForm().ajaxSubmit({
            success:function(result) {
                $('#ajax_loading').hide();
                var html = '';
                $.each(result.data.products, function(i, t){
                    html += '<a href="' + t.url + '">';
                    html += '<div class="hproduct clearfix" style="background:#fff;border-top:0px;">';
                    html += '<div class="p-pic">';
                    html += '<img style="max-height:100px;margin:auto;" class="img-responsive" src="' + t.image + '">';
                    html += '</div>';
                    html += '<div class="p-info">';
                    html += '<p class="p-title">' + t.name + '</p>';
                    if(t.special != 0){
                        html += '<p class="p-origin"><em class="price">¥' + t.special + '</em></p>';
                        html += '<p class="mb0"><del class="old-price">¥' + t.price + '</del></p>';
                    }else{
                        html += '<p class="p-origin"><em class="price">¥' + t.price + '</em></p>';
                    }
                    html += '</div>';
                    html += '</div>';
                    html += '</a>';
                });
                html += html;
                html += html;
                html += html;
                html += html;
                html += html;
                $("#container").append(html);
            },
            error:function(XMLHttpRequest, textStatus,errorThrown) {
                $("#container").html("");
                $('#ajax_loading').hide();
                floatNotify.simple("查找失败");
                return false;
            }
        });
    }

    /**判断是否为空**/
    function isBlank(_value) {
        if(_value==null || _value=="" || _value==undefined){
            return true;
        }
        return false;
    }
</script>
{% endblock %}