{% extends "../common/layouts/main.php" %}
{% block headercss %}
{% endblock %}
{% block content %}
<header class="header">
    <div class="fix_nav">
        <div class="nav_inner">
            <a class="nav-left back-icon" href="javascript:history.go(-1);">返回</a>
            <div class="tit">分类</div>
        </div>
    </div>
</header>
<div class="container">
    <div class="row" id="row_5">
        <div class="sort-arat" style="margin-top: 10px;">
            <ul>
                {% for category in category_list%}
                    <li>
                        <a href="{{url({'for' : 'product-list'})}}?category_id={{category['category_id']}}">
                            <img alt="图片大小为100*100" style="width:initial;height:100px;" src="{{ category['image'] }}" >
                            <div style="width:90%;white-space: nowrap;text-overflow: ellipsis;overflow:hidden;text-align:center;margin: auto;">{{category['name']}}</div>
                        </a>
                    </li>
                {% endfor %}
            </ul>
        </div>

<!--        <div class="mt10 white-bg">
            <h4 class="sort-tit">领队导游</h4>
            <div class="sort-arat brand-areat">
                <ul>
                    <li>
                        <a href="category_list.html">
                            <img alt="图片大小为200*105" src="img/886a68b6-f0aa-41cd-ad89-757c427a33c9.jpg" style="height: 39px;" />
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>-->
</div>

{% endblock %}
{% block footer %}
{% endblock %}
{% block footerjs %}
{% endblock %}