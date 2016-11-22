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
                    <label class="control-label" for="input-name">会员姓名</label>
                    <input type="text" name="filter_name" value="" placeholder="会员姓名" id="input-name" class="form-control" />
                </div>
                <div class="form-group">
                    <label class="control-label" for="input-telephone">手机号码</label>
                    <input type="text" name="filter_telephone" value="" placeholder="手机号码" id="input-telephone" class="form-control" />
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label" for="input-customer-group">会员等级</label>
                    <select name="filter_customer_group_id" id="input-customer-group" class="form-control">
                        {% for group in groups %}
                            <option value="{{group['customer_group_id']}}">{{group['name']}}</option>
                        {% endfor %}
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label" for="input-status">状态</label>
                    <select name="filter_status" id="input-status" class="form-control">
                        <option value="1">启用</option>
                        <option value="0">停用</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                     <label class="control-label" for="input-date-added">添加日期</label>
                     <div class="input-group date">
                         <input type="text" name="filter_date_added" value="" placeholder="添加日期" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                         <span class="input-group-btn">
                             <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                         </span>
                     </div>
                 </div>
                <div class="form-group">
                    <label class="control-label" for="input-ip">IP</label>
                    <input type="text" name="filter_ip" value="" placeholder="IP" id="input-ip" class="form-control" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <button type="button" id="button-filter" class="btn btn-primary pull-right" onclick="TableManaged.init();"><i class="fa fa-search"></i> 筛选</button>
            </div>
        </div>

    </div>
    <form method="post" action="" enctype="multipart/form-data" target="_blank" id="form-order">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="sample_1">
               <thead>
                   <tr>
                       <th class="text-left">
                           会员姓名
                       </th>
                       <th class="text-left">
                           手机号码
                       </th>
                       <th class="text-left">
                           会员等级
                       </th>
                       <th class="text-left">
                           状态
                       </th>
                       <th class="text-left">
                           IP
                       </th>
                       <th class="text-left">
                           添加日期
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
<script type="text/javascript">
    var TableManaged = function () {
        return {
            init: function () {
                if (!jQuery().dataTable) {
                    return;
                }
                $('#sample_1').dataTable({
                    "aoColumns": [
                        { "mDataProp": "fullname","bSortable": false},
                        { "mDataProp": "telephone","bSortable": false},
                        { "mDataProp": "customer_group","bSortable": false},
                        { "mDataProp": "status","bSortable": false, "fnRender": function(obj) {
                            var status_name = '未知';
                            switch (obj.aData.status) {
                                case '0':
                                    status_name = '停用';
                                    break;
                                case '1':
                                    status_name = '启用';
                                    break;
                            }
                            return status_name;
                        }},
                        { "mDataProp": "ip","bSortable": false},
                        { "mDataProp": "date_added","bSortable": false},
                        { "mDataProp": "customer_id", "sClass" : "text-center", "bSortable": false, "fnRender": function(obj) {
                            var html = '';
                            {% for child in currentMenu[currentMenu|length - 1]['children']['table'] %}
                                html = "<a href=\"{{ url({'for' : child['for']}) }}?customer_id="
                                    + obj.aData.customer_id + "\" data-toggle=\"tooltip\" title=\"{{child['name']}}\" class=\"{{child['class']}}\">" +
                                    "<i class=\"fa {{child['icon']}}\"></i>" +
                                    "</a>";
                            {% endfor %}
                            return html;
                        }},
                    ],
                    "iDisplayLength":12,
                    'bAutoWidth': false,
                    "bServerSide": true,
                    "bPaginate" : true,
                    "bDestroy":true,
                    "sAjaxSource" : "{{ url({'for': 'backend/customer'}) }}",
                    "fnServerParams": function (aoData) {
                        aoData.push({"name": "filter_name", "value": $("#input-name").val()});
                        aoData.push({"name": "filter_telephone", "value": $("#input-telephone").val()});
                        aoData.push({"name": "filter_customer_group_id", "value": $("#input-customer-group").val()});
                        aoData.push({"name": "filter_status", "value": $("#input-status").val()});
                        aoData.push({"name": "filter_date_added", "value": $("#input-date-added").val()});
                        aoData.push({"name": "filter_ip", "value": $("#input-ip").val()});
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
</script>
{% endblock %}