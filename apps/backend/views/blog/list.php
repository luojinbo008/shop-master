{% extends "layouts/main.php" %}
{% block headercss %}
    <link href="{{ static_url }}/backend/src/bootstrap/css/DT_bootstrap.css" rel="stylesheet" type="text/css"/>
{% endblock %}
{% block content %}
<div class="panel-heading">
    <h3 class="panel-title">
        <i class="fa fa-list"></i> 博客列表
    </h3>
</div>
<div class="panel-body">
    <div class="well">
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label" for="input-title">
                        博客标题
                    </label>
                    <input type="text" title="filter_title" value="{% if filter_title is defined %}{{filter_title}}{% endif %}" placeholder="博客标题" id="input-title" class="form-control" />
                </div>

            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label" for="input-status">
                        状态
                    </label>
                    <select title="filter_status" id="input-status" class="form-control">
                        <option value="2">全部</option>
                        {% if filter_status is not defined or (filter_status is defined and 1 == filter_status) %}
                            <option value="1" selected="selected">开启</option>
                            <option value="0">停用</option>
                        {% else %}
                            <option value="1">开启</option>
                            <option value="0" selected="selected">停用</option>
                        {% endif %}
                    </select>
                </div>
            </div>
            <div class="col-sm-1">
                <div class="form-group">
                    <label class="control-label" for="input-status">
                        &nbsp;
                    </label>
                    <button type="button" id="button-filter" onclick="TableManaged.init();" class="btn btn-primary pull-left form-control">
                        <i class="fa fa-search"></i> 筛选
                    </button>
                </div>
            </div>

        </div>
    </div>
    <form method="post" enctype="multipart/form-data" id="form-blog">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="sample_1">
                <thead>
                <tr>
                    <th style="width:8px;" class="text-center">
                        <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
                    </th>
                    <th class="text-left">
                        博客标题
                    </th>
                    <th class="text-left">
                        状态
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
    // 绑定删除事件
    $("#backend-blog-delete").click(function () {
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
                        { "mDataProp": "blog_id", "sClass" : "text-center", "bSortable": false, "fnRender": function(obj) {
                            obj.aData.edit_id = obj.aData.blog_id;
                            var html = '<input type="checkbox" name="selected[]" value="' + obj.aData.blog_id + '" />';
                            return html;
                        }},
                        { "mDataProp": "title","bSortable": false},
                        { "mDataProp": "status","bSortable": false, "fnRender" : function (obj) {
                            var name = '未知状态';
                            switch (obj.aData.status) {
                                case '0':
                                    name = '停用';
                                    break;
                                case '1':
                                    name = '开启';
                                    break;
                                default:
                                    name = '未知状态';
                            }
                            return name;
                        }},
                        { "mDataProp": "edit_id", "sClass" : "text-center", "bSortable": false, "fnRender": function(obj) {
                            var html = '';
                            {% for child in currentMenu[currentMenu|length - 1]['children']['table'] %}
                                html = "<a href=\"{{ url({'for' : child['for']}) }}?blog_id="
                                    + obj.aData.edit_id + "\" data-toggle=\"tooltip\" title=\"{{child['name']}}\" class=\"{{child['class']}}\">" +
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
                    "sAjaxSource" : "{{ url({'for': 'backend/blog/list'}) }}",
                    "fnServerParams": function (aoData) {
                        aoData.push({"name": "filter_title", "value": $("#input-title").val()});
                        aoData.push({"name": "filter_status", "value": $("#input-status").val()});
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

    function del(){
        $("#form-blog").ajaxSubmit({
            type:"post",
            url:"{{ url({'for' : 'backend/blog/delete'}) }}",
            success: function(res) {
                if(res.status == 0) {
                    var msg = '<div class="alert alert-success">' +
                        '<i class="fa fa-exclamation-circle"></i>' + res.info +
                        '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                        '</div>';
                    $("#content_body").prepend(msg);
                    TableManaged.init();
                }else{
                    $.each(res.data, function(i, t){
                        var msg = '<div class="alert alert-danger">' +
                            '<i class="fa fa-exclamation-circle"></i>' + t +
                            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                            '</div>';
                        $("#content_body").prepend(msg);
                    });
                }

            }
        });
    }
</script>
{% endblock %}
