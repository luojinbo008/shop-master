{% extends "layouts/main.php" %}
{% block headercss %}
    <link rel="stylesheet" href="{{ static_url }}/wechat/ui/css/orderList.css?v=01291">
{% endblock %}
{% block content %}
<header class="header">
    <div class="fix_nav">
		<div class="nav_inner">
			<a class="nav-left back-icon" href="#">返回</a>
			<div class="tit">我的订单</div>
		</div>
    </div>
</header>
	<div id="user-b">
	    <div class="myorder-tab">
	    	<div class="myorder-nav">
		    	<ul id="tab_btn" class="coupon-list">
		    		<li {% if type == 0 %} class="pick" {% endif %}>
                        <a href="{{url({'for' : 'order-list'})}}?type=0"><span class="bar"></span>待付款</a>
                    </li>
		    		<li {% if type == 1 %} class="pick" {% endif %}>
                        <a href="{{url({'for' : 'order-list'})}}?type=1"><span class="bar"></span>已付款</a>
                    </li>
                    <li {% if type == 2 %} class="pick" {% endif %}>
                        <a href="{{url({'for' : 'order-list'})}}?type=2"><span class="bar"></span>退款</a>
                    </li>
		    	</ul>
		    	<div class="cl"></div>
		    </div>
		    <div class="myorder-content">
		    	<ul>
		    		<li class="mc-all tab_content show">
		    			<ul>
                            {% for info in orderList %}
                                <li>
                                    {% set quantity = 0 %}
                                    {% for product in info['products'] %}
                                    {% set quantity = quantity + product['quantity'] %}
                                    <a href='{{url({"for" : "product-detail"})}}?product_id={{product["product_id"]}}'>
                                         <div class="mc-sum-box">
                                             <div class="myorder-sum fl">
                                                 <img src="{{product['image']}}">
                                             </div>
                                             <div class="myorder-text">
                                                <h1>{{product['name']}}</h1>
                                                <h2>
                                                    {% for option in product['option'] %}
                                                       {% if option|length %}
                                                           {{option['name']}}：{{ option['value'] }}；
                                                       {% endif %}
                                                    {% endfor %}
                                                </h2>
                                                 <div class="myorder-cost">
                                                     <span>数量:{{product['quantity']}}</span>
                                                     <span class="mc-t">￥{{product['price']}}/件</span>
                                                 </div>
                                             </div>
                                         </div>
                                    </a>
                                    {% endfor %}
                                    <div class="mc-sum-Am">
                                        共{{quantity}}件商品，免运费<span>实付：<span class="mc-t">￥{{info['total']}}</span></span>
                                    </div>
                                    <div class="mc-sum-Am">
                                        <h3>
                                            {{ info['status_name'] }}
                                            {% if type == 0 %}
                                                <a style="margin-top: -8px;" class="btn btn-danger btn-buy pull-right" href='{{url({"for" : "order-detail"})}}?order_id={{info["order_id"]}}'>支付</a>
                                            {% else %}
                                                <a style="margin-top: -8px;" class="btn btn-cart btn-info pull-right" href='{{url({"for" : "order-detail"})}}?order_id={{info["order_id"]}}'>查看订单</a>
                                            {% endif %}
                                        </h3>
                                    </div>
                                </li>
                            {% endfor %}
		    			</ul>
		    		</li>
		    	</ul>
		    </div>
	    </div>
{% endblock %}
{% block footer %}
{% endblock %}
{% block footerjs %}
{% endblock %}