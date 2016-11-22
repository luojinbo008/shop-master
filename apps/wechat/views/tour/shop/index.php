{% extends "../common/layouts/main.php" %}
{% block headercss %}
{% endblock %}
{% block content %}
<div class="container">
    <div class="row">
        <div id="slide">
            <div class="hd">
                <ul>
                    {% for key,advert in advertList%}
                        <li class="on">{{key + 1}}</li>
                    {% endfor %}
                </ul>
            </div>
            <div class="bd">
                <div class="tempWrap" style="overflow:hidden; position:relative;">
                    <ul style="width: 3840px; position: relative; overflow: hidden; padding: 0px; margin: 0px; transition-duration: 200ms; transform: translateX(-768px);">
                        {% for advert in advertList%}
                            <li style="display: table-cell; vertical-align: top; width: 768px;">
                                <a href="{% if advert['link'] %}{{ advert['link'] }}{% else %} javascript:{% endif %}" target="_blank">
                                    <img src="{{advert['image']}}" alt="{{advert['title']}}" ppsrc="{{advert['image']}}" title="{{ advert['title'] }}">
                                </a>
                            </li>
                        {% endfor %}
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script charset="utf-8" src="{{ static_url }}/wechat/ui/js/TouchSlide.js"></script>
    <script type="text/javascript">
        TouchSlide({
            slideCell:"#slide",
            titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
            mainCell:".bd ul",
            effect:"left",
            autoPlay:true,//自动播放
            autoPage:true, //自动分页
            switchLoad:"_src" //切换加载，真实图片路径为"_src"
        });
    </script>
<!--    <div class="row category">
        <a href="" class="col-xs-4">
            <img class="img-responsive" src="{{ static_url }}/wechat/ui/images/icon_rm.png">
            <h4>热门商品</h4>
        </a>
        <a href="" class="col-xs-4">
            <img class="img-responsive" src="{{ static_url }}/wechat/ui/images/icon_tm.png">
            <h4>积分商城</h4>
        </a>
        <a href="" class="col-xs-4">
            <img class="img-responsive" src="{{ static_url }}/wechat/ui/images/theme.png">
            <h4>路线专题</h4>
        </a>
        <a href="#" class="col-xs-4">
            <img class="img-responsive" src="{{ static_url }}/wechat/ui/images/icon_pp.png">
            <h4>旅游大使</h4>
        </a>
    </div>-->
    <div class="row">
        <!--热门商品-->
        <div class="tb_box">
            <h2 class="tab_tit">
                <a class="more" href="{{url({'for' : 'product-list'})}}">更多</a>
                当前最热
            </h2>
            <div class="tb_type tb_type_even clearfix">
                {% if hotProducts is defined %}
                    {% for product_info in hotProducts %}
                        <a class="th_link" href="{{url({'for' : 'product-detail'})}}?product_id={{product_info['product_id']}}">
                            <img class="tb_pic" src="{{ product_info['image'] }}" style="width:100%;">
                            <p style="margin: 0 0 0px;">{{ product_info['name'] }}</p>
                        </a>
                    {% endfor %}
                {% endif %}
            </div>
        </div>
    </div>
</div>
{% endblock %}
{% block footer %}
{% endblock %}
{% block footerjs %}
<script type="text/javascript">
    $(document).ready(function(){
        $("#slide img").each(function(){
            var img_src=$(this).attr("_src");
            $(this).attr("src",img_src);
        });
    });
    function searchproduct(){
        var filter_name = document.getElementById("filter_name").value;
        if(filter_name == undefined || filter_name==null ||filter_name ==""){
            alert("请输入搜索关键字！");
            return false;
        }
        document.getElementById("searchform").submit();
    }
</script>
{% endblock %}