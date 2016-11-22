{% extends "layouts/main.php" %}
{% block headercss %}
<link href="{{ static_url }}/backend/src/bootstrap/css/DT_bootstrap.css" rel="stylesheet" type="text/css"/>
{% endblock %}
{% block content %}
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> 商店列表</h3>
    </div>
    <div class="panel-body">
        <form action="{{ url({'for' : 'backend/store/delete'}) }}" method="post" enctype="multipart/form-data" id="form-store">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="sample_1">
                    <thead>
                    <tr>
                        <th style="width:8px;" class="text-center">
                            <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
                        </th>
                        <th class="text-left">
                            商店名称
                        </th>
                        <th class="text-left">
                            商店域名
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
                        { "mDataProp": "store_id", "sClass" : "text-center", "bSortable": false, "fnRender": function(obj) {
                            obj.aData.edit_id = obj.aData.store_id;
                            var html = '<input type="checkbox" name="selected[]" value="' + obj.aData.store_id + '" />';
                            return html;
                        }},
                        { "mDataProp": "name","bSortable": false},
                        { "mDataProp": "store_url","bSortable": false},
                        { "mDataProp": "edit_id", "sClass" : "text-center", "bSortable": false, "fnRender": function(obj) {
                            var html = '';
                            {% for child in currentMenu[currentMenu|length - 1]['children']['table'] %}
                                html = "<a href=\"{{ url({'for' : child['for']}) }}?store_id="
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
                    "sAjaxSource" : "{{ url({'for': 'backend/store'}) }}",
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
        $("#form-store").ajaxSubmit({
            type:"post",
            url:"{{ url({'for' : 'backend/store/delete'}) }}",
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