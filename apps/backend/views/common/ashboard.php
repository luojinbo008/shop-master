{% extends "layouts/main.php" %}
{% block headercss %}
    <link type="text/css" href="{{ static_url }}/backend/src/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen" />
{% endblock %}
{% block content %}
<div class="row-fluid">
    <div class="span3 responsive" data-tablet="span6" data-desktop="span3">
        <div class="tile-heading">总订单数量
            <span class="pull-right">
                {{order_percentage}}%
            </span>
        </div>
        <div class="dashboard-stat blue">
            <div class="visual">
                <i class="fa fa-shopping-cart"></i>
            </div>
            <div class="details">
                <div class="number">
                    {{order_total}}
                </div>
            </div>
            <a class="more" href="{{ url({'for' : 'backend/order'}) }}">
                查看更多...... <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
    <div class="span3 responsive" data-tablet="span6" data-desktop="span3">
        <div class="tile-heading" style="background-color: #10a062;">总销售额
            <span class="pull-right">
                {{sale_percentage}}%
            </span>
        </div>
        <div class="dashboard-stat green">
            <div class="visual">
                <i class="fa fa-credit-card"></i>
            </div>
            <div class="details">
                <div class="number">{{sale_total}}</div>
            </div>
            <a class="more" href="{{ url({'for' : 'backend/order'}) }}">
                查看更多...... <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
    <div class="span3 responsive" data-tablet="span6  fix-offset" data-desktop="span3">
        <div class="tile-heading" style="background-color: #6e1881;">总会员数量
            <span class="pull-right">
                {{customer_percentage}}%
            </span>
        </div>
        <div class="dashboard-stat purple">
            <div class="visual">
                <i class="fa fa-user"></i>
            </div>
            <div class="details">
                <div class="number">{{customer_total}}</div>
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
                <div class="number">{{online_total}}</div>
            </div>
            <a class="more" href="{{ url({'for' : 'backend/customer'}) }}">
                查看更多...... <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<div class="row-fluid">
    <div class="span12">
        <div class="panel panel-default">
            <div class="panel-heading">
            <div class="pull-right">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-calendar"></i> <i class="caret"></i>
                </a>
                <ul id="range" class="dropdown-menu dropdown-menu-right">
                    <li>
                        <a href="day">当天</a>
                    </li>
                    <li>
                        <a href="week">本周</a>
                    </li>
                    <li class="active">
                        <a href="month">本月</a>
                    </li>
                    <li>
                        <a href="year">本年</a>
                    </li>
                </ul>
            </div>
            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> 销售分析</h3>
        </div>
        <div class="panel-body">
        <div id="chart-sale" style="width: 100%; height: 260px;"></div>
        </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<div class="row-fluid">
    <div class="span12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> 最新订单</h3>
            </div>
            <div class="panel-body">
                <table class="table">
                    <thead>
                        <tr>
                            <td>订单 ID</td>
                            <td>会员</td>
                            <td>状态</td>
                            <td>添加日期</td>
                            <td >总计</td>
                            <td >操作</td>
                        </tr>
                    </thead>
                    <tbody>
                        {% for order in orders %}
                            <tr>
                                <td>{{ order['order_id'] }}</td>
                                <td>{{ order['customer'] }}</td>
                                <td>{{ order['status_name'] }}</td>
                                <td>{{ order['date_added'] }}</td>
                                <td>￥{{ order['total'] }}</td>
                                <td>
                                    <a href="{{ url({'for' : 'backend/order/view'}) }}?order_id={{ order['order_id'] }}" data-toggle="tooltip" title="" class="btn btn-info" data-original-title="查看详情">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        {% endfor %}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
{% endblock %}
{% block footer %}
{% endblock %}
{% block footerjs %}
<script src="{{ static_url }}/backend/src/jquery/flot/jquery.flot.js" type="text/javascript"></script>
<script type="text/javascript" src="{{ static_url }}/backend/src/jquery/flot/jquery.flot.resize.min.js"></script>
<script type="text/javascript">
    $('#range a').on('click', function(e) {
    	e.preventDefault();
    	$(this).parent().parent().find('li').removeClass('active');
    	$(this).parent().addClass('active');
    	$.ajax({
    		type: 'get',
    		url: '{{url({"for" : "backend/common/chart"})}}?range=' + $(this).attr('href'),
    		dataType: 'json',
    		success: function(json) {
                if (typeof json['data']['order'] == 'undefined') {
                    return false;
                }
    			var option = {
    				shadowSize: 0,
    				colors: ['#9FD5F1', '#1065D2'],
    				bars: {
    					show: true,
    					fill: true,
    					lineWidth: 1
    				},
    				grid: {
    					backgroundColor: '#FFFFFF',
    					hoverable: true
    				},
    				points: {
    					show: false
    				},
    				xaxis: {
    					show: true,
                		ticks: json['data']['xaxis']
    				}
    			}
    			$.plot('#chart-sale', [json['data']['order'], json['data']['customer']], option);
    			$('#chart-sale').bind('plothover', function(event, pos, item) {
    				$('.tooltip').remove();
    				if (item) {
    					$('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">'
                            + item.datapoint[1].toFixed(2)
                            + '</div></div>').prependTo('body');
    					$('#tooltip').css({
    						position: 'absolute',
    						left: item.pageX - ($('#tooltip').outerWidth() / 2),
    						top: item.pageY - $('#tooltip').outerHeight(),
    						pointer: 'cusror'
    					}).fadeIn('slow');
    					$('#chart-sale').css('cursor', 'pointer');
    			  	} else {
    					$('#chart-sale').css('cursor', 'auto');
    				}
    			});
    		},
            error: function(xhr, ajaxOptions, thrownError) {
               alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
    	});
    });
    $('#range .active a').trigger('click');
</script>
{% endblock %}