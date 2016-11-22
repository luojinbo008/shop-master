{% extends "layouts/main.php" %}
{% block headercss %}
    <link type="text/css" href="{{ static_url }}/backend/src/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen" />
{% endblock %}
{% block content %}
<div class="row-fluid">
    <div class="span3 responsive" data-tablet="span6" data-desktop="span3">
        <div class="tile-heading">总订单数量
            <span class="pull-right">
                11
            </span>
        </div>
        <div class="dashboard-stat blue">
            <div class="visual">
                <i class="fa fa-shopping-cart"></i>
            </div>
            <div class="details">
                <div class="number">
                    111
                </div>
            </div>
            <a class="more" href="{{ url({'for' : 'backend/order'}) }}">
                查看更多...... <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
    <div class="span3 responsive" data-tablet="span6" data-desktop="span3">
        <div class="tile-heading" style="background-color: #10a062;">新消息
            <span class="pull-right">
                11
            </span>
        </div>
        <div class="dashboard-stat green">
            <div class="visual">
                <i class="fa fa-credit-card"></i>
            </div>
            <div class="details">
                <div class="number">
                    22
                </div>
            </div>
            <a class="more" href="{{ url({'for' : 'backend/order'}) }}">
                查看更多...... <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
    <div class="span3 responsive" data-tablet="span6  fix-offset" data-desktop="span3">
        <div class="tile-heading" style="background-color: #6e1881;">总会员数量
            <span class="pull-right">
                33
            </span>
        </div>
        <div class="dashboard-stat purple">
            <div class="visual">
                <i class="fa fa-user"></i>
            </div>
            <div class="details">
                <div class="number">
                    33
                </div>
            </div>
            <a class="more" href="{{ url({'for' : 'backend/customer'}) }}">
                查看更多...... <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
    <div class="span3 responsive" data-tablet="span6" data-desktop="span3">
        <div class="tile-heading" style="background-color: #cb871b;">
            在线人数
        </div>
        <div class="dashboard-stat yellow">
            <div class="visual">
                <i class="fa fa-users"></i>
            </div>
            <div class="details">
                <div class="number">333</div>
            </div>
            <a class="more" href="{{ url({'for' : 'backend/customer'}) }}">
                查看更多...... <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
</div>
{% endblock %}
{% block footer %}
{% endblock %}
{% block footerjs %}
{% endblock %}