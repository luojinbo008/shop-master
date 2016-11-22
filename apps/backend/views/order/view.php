{% extends "layouts/main.php" %}
{% block headercss %}
<link href="{{ static_url }}/backend/src/bootstrap/css/DT_bootstrap.css" rel="stylesheet" type="text/css"
      xmlns="http://www.w3.org/1999/html"/>
{% endblock %}
{% block content %}
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <h1>订单管理</h1>
            <ul class="breadcrumb">
                <li>
                    <a href="{{ url({'for' : 'backend/common/ashboard'}) }}">首页</a>
                </li>
                <li>
                    <a href="{{ url({'for' : 'backend/order'}) }}">订单管理</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
       <div class="row">
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-shopping-cart"></i> 订单详情
                        </h3>
                    </div>
                    <table class="table">
                        <tbody>
                            <tr>
                                <td style="width: 1%;">
                                    <button data-toggle="tooltip" title="商店" class="btn btn-info btn-xs">
                                        <i class="fa fa-shopping-cart fa-fw"></i>
                                    </button>
                                </td>
                                <td>
                                    {{ orderInfo['store_info']['name'] }}
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <button data-toggle="tooltip" title="创建日期" class="btn btn-info btn-xs">
                                        <i class="fa fa-calendar fa-fw"></i>
                                    </button>
                                </td>
                                <td>
                                    {{ orderInfo['date_added'] }}
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <button data-toggle="tooltip" title="支付方式" class="btn btn-info btn-xs">
                                        <i class="fa fa-credit-card fa-fw"></i>
                                    </button>
                                </td>
                                <td>{{ orderInfo['payment'] }}</td>
                                <td></td>
                            </tr>
                            {% if orderInfo['order_status_id'] in [3,4,5,6,7,8] %}
                            <tr>
                                <td>
                                    <button data-toggle="tooltip" title="第三方订单号" class="btn btn-info btn-xs">
                                        <i class="fa fa-money fa-fw"></i>
                                    </button>
                                </td>
                                <td>
                                    {{ orderInfo['transaction_id'] }}
                                </td>
                                <td class="text-center">
                                    {% if orderInfo['order_status_id'] == 6 %}
                                    <button id="button-refund" title="退款处理完成" class="btn btn-success btn-xs" onclick="refundBox()">
                                        <i class="fa fa-share"></i>
                                    </button>
                                    {% endif %}
                                </td>
                            </tr>
                            {% endif %}
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-user"></i> 会员详情
                        </h3>
                    </div>
                <table class="table">
                    <tr>
                        <td style="width: 1%;">
                            <button data-toggle="tooltip" title="会员" class="btn btn-info btn-xs">
                                <i class="fa fa-user fa-fw"></i>
                            </button>
                        </td>
                        <td>
                            {{ orderInfo['fullname'] }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <button data-toggle="tooltip" title="会员等级" class="btn btn-info btn-xs">
                                <i class="fa fa-group fa-fw"></i>
                            </button>
                        </td>
                        <td>
                            {{ orderInfo['customer_group_info']['name'] }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <button data-toggle="tooltip" title="电话" class="btn btn-info btn-xs">
                                <i class="fa fa-phone fa-fw"></i>
                            </button>
                        </td>
                        <td>
                            {{ orderInfo['telephone'] }}
                        </td>
                    </tr>
                </table>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-cog"></i> 选项
                        </h3>
                    </div>
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>积分</td>
                                <td class="text-right">{{ orderInfo['reward'] }}</td>
                                <td class="text-center">
                                    <button id="button-reward-add" data-loading-text="加载中..." data-toggle="tooltip" title="添加奖励积分" class="btn btn-success btn-xs">
                                        <i class="fa fa-plus-circle"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-info-circle"></i> 订单基本信息</h3>
            </div>
            <div class="panel-body">
                {% if orderInfo['address_list']|length > 0 %}
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td style="width: 50%;" class="text-left">联系方式（发货地址）</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-left">
                                    {% for address_info in orderInfo['address_list'] %}
                                        姓名：{{ address_info['shipping_fullname'] }}<br>
                                        {% set shipping_custom_field = address_info['shipping_custom_field']|json_decode %}
                                        {% if shipping_custom_field.idcard is defined %}
                                            微信账号：{{ shipping_custom_field.wechat_user }}<br>
                                        {% endif %}
                                        {% if shipping_custom_field.idcard is defined %}
                                            身份证号：{{ shipping_custom_field.idcard }}<br>
                                        {% endif %}
                                        手机号码：{{ address_info['shipping_telephone'] }}<br>
                                    {% endfor %}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                {% endif %}
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td class="text-left">商品</td>
                            <td class="text-right">数量</td>
                            <td class="text-right">单品价格</td>
                            <td class="text-right">单品小计</td>
                        </tr>
                    </thead>
                    <tbody>
                        {% for product in orderInfo['product_list'] %}
                            <tr>
                                <td class="text-left">
                                    {{ product['name'] }}
                                </td>
                                <td class="text-right">{{ product['quantity'] }}</td>
                                <td class="text-right">￥{{ product['price'] }}</td>
                                <td class="text-right">￥{{ product['total'] }}</td>
                            </tr>
                            {% set rows = 0 %}
                            {% for option in product['option'] %}
                            {% if option %}
                                <tr>
                                    {% if 0 == rows %}
                                        <td class="text-right" colspan="2" rowspan="product['option']|length">
                                            商品标签
                                        </td>
                                    {% endif %}
                                    <td class=" text-right">
                                        {{ option['name'] }}
                                    </td>
                                    <td class="text-right">
                                        {{ option['value'] }}
                                    </td>
                                </tr>
                            {% endif %}
                            {% set rows = rows + 1 %}
                            {% endfor %}
                        {% endfor %}
                        <tr>
                            <td colspan="3" class="text-right">总计</td>
                            <td class="text-right">￥{{ orderInfo['total'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-comment-o"></i> 订单历史记录
                </h3>
            </div>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                <div class="tab-pane active" id="tab-history">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="sample_1">
                            <thead>
                                <tr>
                                    <th class="text-left">
                                        添加日期
                                    </th>
                                    <th class="text-left">
                                        备注
                                    </th>
                                    <th class="text-left">
                                        状态
                                    </th>
                                    <th class="text-left">
                                        通知了会员
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" id="refundSubmitForm">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">退款审核</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">审核状态：</label>
                        <div class="col-sm-6">
                            <input type="hidden" name="order_id" value="{{orderInfo['order_id']}}">
                            <select class="col-sm-3 m-wrap form-control" name="status">
                                <option value="7">通过</option>
                                <option value="8">不通过</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">说明：</label>
                        <div class="col-sm-6">
                            <textarea class="col-sm-3 m-wrap form-control" rows="5" name="comment"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary">确定</button>
                </div>
            </form>
        </div>
    </div>
</div>
{% endblock %}
{% block footer %}
{% endblock %}
{% block footerjs %}
    <script type="text/javascript" src="{{ static_url }}/backend/src/jquery/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="{{ static_url }}/backend/src/bootstrap/js/DT_bootstrap.js"></script>
    <script type="text/javascript" src="{{ static_url }}/backend/src/jquery/jquery.validate.min.js"></script>
    <script type="text/javascript" src="{{ static_url }}/backend/src/jquery/jquery-form.js"></script>
    <script type="text/javascript">
        var TableManaged = function () {
            return {
                init: function () {
                    if (!jQuery().dataTable) {
                        return;
                    }
                    $('#sample_1').dataTable({
                        "aoColumns": [
                            { "mDataProp": "date_added", "bSortable": false},
                            { "mDataProp": "comment","bSortable": false},
                            { "mDataProp": "order_status_id","bSortable": false, "fnRender": function(obj) {
                                var status_name = '未知';
                                switch (obj.aData.order_status_id) {
                                    case '0':
                                        status_name = '选择支付中';
                                        break;
                                    case '1':
                                        status_name = '支付中';
                                        break;
                                    case '2':
                                        status_name = '用户主动取消';
                                        break;
                                    case '3':
                                        status_name = '支付完成，第三方回调成功';
                                        break;
                                    case '4':
                                        status_name = '订单支付超时，系统关闭订单';
                                        break;
                                    case '5':
                                        status_name = '用户已评价';
                                        break;
                                    case '6':
                                        status_name = '玩家发起退款申请';
                                        break;
                                    case '7':
                                        status_name = '管理员审核退款，并退款成功';
                                        break;
                                    case '8':
                                        status_name = '管理员审核退款，并退款失败';
                                        break;
                                    default:
                                        status_name = '未知状态';
                                }
                                return status_name;
                            }},
                            { "mDataProp": "notify","bSortable": false}
                        ],
                        'bAutoWidth': false,
                        "iDisplayLength":10,
                        "bServerSide": true,
                        "bPaginate" : true,
                        "bDestroy":true,
                        "sAjaxSource" : "{{ url({'for': 'backend/order/getOrderHistories'}) }}",
                        "fnServerParams": function (aoData) {
                            aoData.push({"name": "order_id", "value": "{{orderInfo['order_id']}}"});
                        },
                        "sDom": "t<'row-fluid'<'span6'i><'span6'p>>",
                        "sPaginationType": "bootstrap",
                        "oLanguage": {
                            'sInfoEmpty' : '从 0 到 0 /共 0 条数据',
                            'sEmptyTable' : '没有数据！',
                            "sLengthMenu": "_MENU_ 每页显示",
                            "oPaginate": {
                                "sPrevious": "上一页",
                                "sNext": "下一页"
                            },
                            "sInfo": "从 _START_ 到 _END_ /共 _TOTAL_ 条数据"
                        }
                    });

                }
            };
        }();

        var FormValidate = function () {
            return {
                init: function () {
                    var form = $('#refundSubmitForm');
                    var error = $('.alert-error', form);
                    var success = $('.alert-success', form);
                    form.validate({
                        errorElement: 'span', //default input error message container
                        errorClass: 'help-inline', // default input error message class
                        focusInvalid: false, // do not focus the last invalid input
                        ignore: "",
                        rules: {
                            comment: {
                                required: true
                            },
                            status: {
                                required: true
                            }
                        },
                        messages: {
                            comment: "请填写审核说明！",
                            status: "请选择审核状态！"
                        },
                        errorPlacement: function (error, element) {
                            error.insertAfter(element);
                        },
                        invalidHandler: function (event, validator) {
                            success.hide();
                            error.show();
                        },
                        highlight: function (element) {
                            $(element)
                                .closest('.help-inline').removeClass('ok');
                            $(element)
                                .closest('.control-group').removeClass('success').addClass('error');
                        },
                        unhighlight: function (element) {
                            $(element)
                                .closest('.control-group').removeClass('error');
                        },
                        success: function (label) {
                            if (label.attr("for") == "service" || label.attr("for") == "membership") {
                                label
                                    .closest('.control-group').removeClass('error').addClass('success');
                                label.remove();
                            } else {
                                label
                                    .addClass('valid').addClass('help-inline ok')
                                    .closest('.control-group').removeClass('error').addClass('success');
                            }
                        },
                        submitHandler: function (form) {
                            if (confirm("审核订单退款成功，并在相关第三方支付平台完成退款流程！")) {
                                $(form).ajaxSubmit({
                                    type: "POST",
                                    url: "{{ url({'for': 'backend/order/refundSubmit'}) }}",
                                    success: function (res) {
                                        if (res.status == 0) {
                                            window.location.href = "{{ url({'for': 'backend/order/view'}) }}?order_id={{orderInfo['order_id']}}";
                                        } else {
                                            alert(res.info);
                                        }
                                    }
                                });
                            }
                        }
                    });
                }
            }
        }();

        jQuery(document).ready(function() {
            TableManaged.init();
            FormValidate.init();
        });
        function refundBox() {
            $('#myModal').modal('show');
        }


    </script>
{% endblock %}