{% extends "layouts/main.php" %}
{% block headercss %}
    <link href="{{ static_url }}/backend/src/bootstrap/css/DT_bootstrap.css" rel="stylesheet" type="text/css"/>
{% endblock %}
{% block content %}
<div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-list"></i>  订单列表</h3>
</div>
<div class="panel-body">
    <div class="well">
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label" for="input-order-id">订单 ID</label>
                    <input type="text" name="filter_order_id" value="" placeholder="订单 ID"
                           id="input-order-id" class="form-control" />
                </div>
                <div class="form-group">
                    <label class="control-label" for="input-customer">会员</label>
                    <input type="text" name="filter_customer" value="" placeholder="会员"
                           id="input-customer" class="form-control" />
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label" for="input-order-status">订单状态</label>
                    <select name="filter_order_status" id="input-order-status" class="form-control">
                        <option value="0">创建订单</option>
                        <option value="1">支付中</option>
                        <option value="2" >取消</option>
                        <option value="3">完成</option>
                        <option value="5">用户已评价</option>
                        <option value="6">退款申请</option>
                        <option value="7">退款成功</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label" for="input-total">总计</label>
                    <input type="text" name="filter_total" value="" placeholder="总计" id="input-total" class="form-control" />
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label" for="input-date-added">创建日期</label>
                    <div class="input-group date">
                        <input type="text" name="filter_date_added" value="" placeholder="创建日期" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label" for="input-date-modified">修改日期</label>
                    <div class="input-group date">
                        <input type="text" name="filter_date_modified" value="" placeholder="修改日期" data-date-format="YYYY-MM-DD" id="input-date-modified" class="form-control" />
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                </div>
                <button type="button" id="button-filter" class="btn btn-primary pull-right" onclick="TableManaged.init();"><i class="fa fa-search"></i> 筛选</button>
            </div>
        </div>
    </div>
    <form method="post" action="" enctype="multipart/form-data" target="_blank" id="form-order">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="sample_1">
               <thead>
                   <tr>
                       <th style="width:8px;" class="text-center">
                           <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
                       </th>
                       <th class="text-left">
                           订单 ID
                       </th>
                       <th class="text-left">
                           会员
                       </th>
                       <th class="text-left">
                           状态
                       </th>
                       <th class="text-left">
                           单品小计
                       </th>
                       <th class="text-left">
                           添加日期
                       </th>
                       <th class="text-left">
                           修改日期
                       </th>
                       <th class="text-left">
                           操作
                       </th>
                   </tr>
               </thead>
               <tbody>
               </tbody>
           </table>
        </div>
    </form>
</div>
{% endblock %}
{% block footer %}
{% endblock %}
{% block footerjs %}
    <script type="text/javascript" src="{{ static_url }}/backend/src/jquery/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="{{ static_url }}/backend/src/bootstrap/js/DT_bootstrap.js"></script>
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
                            { "mDataProp": "order_id", "sClass" : "text-center", "bSortable": false, "fnRender": function(obj) {
                                obj.aData.edit_id = obj.aData.order_id;
                                obj.aData.show_id = obj.aData.order_id;
                                var html = '<input type="checkbox" name="selected[]" value="' + obj.aData.product_id + '" />';
                                return html;
                            }},
                            { "mDataProp": "show_id", "bSortable": false},
                            { "mDataProp": "customer","bSortable": false},
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
                                    default:
                                        status_name = '未知状态';
                                }
                                return status_name;
                            }},
                            { "mDataProp": "total","bSortable": false},
                            { "mDataProp": "date_added","bSortable": false},
                            { "mDataProp": "date_modified","bSortable": false},
                            { "mDataProp": "edit_id", "sClass" : "text-center", "bSortable": false, "fnRender": function(obj) {
                                var html = '';
                                {% for child in currentMenu[currentMenu|length - 1]['children']['table'] %}
                                html = "<a href=\"{{ url({'for' : child['for']}) }}?order_id="
                                    + obj.aData.edit_id + "\" data-toggle=\"tooltip\" title=\"{{child['name']}}\" class=\"{{child['class']}}\">" +
                                    "<i class=\"fa {{child['icon']}}\"></i>" +
                                    "</a>";
                                {% endfor %}
                                return html;
                            }},
                        ],
                        'bAutoWidth': false,
                        "iDisplayLength":12,
                        "bServerSide": true,
                        "bPaginate" : true,
                        "bDestroy":true,
                        "sAjaxSource" : "{{ url({'for': 'backend/order'}) }}",
                        "fnServerParams": function (aoData) {
                            aoData.push({"name": "filter_order_id", "value": $("#input-order-id").val()});
                            aoData.push({"name": "filter_customer", "value": $("#input-customer").val()});
                            aoData.push({"name": "filter_order_status", "value": $("#input-order-status").val()});
                            aoData.push({"name": "filter_total", "value": $("#input-total").val()});
                            aoData.push({"name": "filter_date_added", "value": $("#input-date-added").val()});
                            aoData.push({"name": "filter_date_modified", "value": $("#input-date-modified").val()});
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
        jQuery(document).ready(function() {
            TableManaged.init();
        });

        $('.date').datetimepicker({
            pickTime: false
        });
    </script>
{% endblock %}
