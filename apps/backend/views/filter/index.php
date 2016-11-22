{% extends "layouts/main.php" %}
{% block headercss %}
<link href="{{ static_url }}/backend/src/bootstrap/css/DT_bootstrap.css" rel="stylesheet" type="text/css"/>
{% endblock %}
{% block content %}
<div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-list"></i> 筛选列表</h3>
</div>
<div class="panel-body">
    <form action="{{ url({'for' : 'backend/filter/delete'})}}" method="post" enctype="multipart/form-data" id="form-filter">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="sample_1">
                <thead>
                    <tr>
                        <th style="width:8px;" class="text-center">
                            <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
                        </th>
                        <th class="text-left">
                            筛选分组
                        </th>
                        <th class="text-left">
                            排序
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
    // 绑定删除事件
    $("#backend-filter-delete").click(function () {
        confirm('确定删除！') ? del() : false;
    });
    var TableManaged = function () {
        return {
            init: function () {
                if (!jQuery().dataTable) {
                    return;
                }
                $('#sample_1').dataTable({
                    "aoColumns": [
                        { "mDataProp": "filter_group_id", "sClass" : "text-center", "bSortable": false, "fnRender": function(obj) {
                            obj.aData.edit_id = obj.aData.filter_group_id;
                            var html = '<input type="checkbox" name="selected[]" value="' + obj.aData.filter_group_id + '" />';
                            return html;
                        }},
                        { "mDataProp": "name","bSortable": false},
                        { "mDataProp": "sort_order","bSortable": false},
                        { "mDataProp": "edit_id", "sClass" : "text-center", "bSortable": false, "fnRender": function(obj) {
                            var html = '';
                            {% for child in currentMenu[currentMenu|length - 1]['children']['table'] %}
                            html = "<a href=\"{{ url({'for' : child['for']}) }}?filter_group_id="
                                + obj.aData.edit_id + "\" data-toggle=\"tooltip\" title=\"{{child['name']}}\" class=\"{{child['class']}}\">" +
                                "<i class=\"fa {{child['icon']}}\"></i>" +
                                "</a>";
                            {% endfor %}
                            return html;
                        }},
                    ],
                    "iDisplayLength":12,
                    "bServerSide": true,
                    "bPaginate" : true,
                    "bDestroy":true,
                    "sAjaxSource" : "{{ url({'for': 'backend/filter'}) }}",
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
