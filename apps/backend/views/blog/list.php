{% extends "layouts/main.php" %}
{% block headercss %}
    <link href="{{ static_url }}/backend/src/bootstrap/css/DT_bootstrap.css" rel="stylesheet" type="text/css"/>
{% endblock %}
{% block content %}
<div class="panel panel-default">
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
                            <option value="*">全部</option>
                            {% if filter_status is not defined or (filter_status is defined and 1 == filter_status) %}
                                <option value="1" selected="selected">开启</option>
                                <option value="0">关闭</option>
                            {% else %}
                                <option value="1">开启</option>
                                <option value="0" selected="selected">关闭</option>
                            {% endif %}
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <br />
                    <button type="button" id="button-filter" class="btn btn-primary pull-left">
                        <i class="fa fa-search"></i> 筛选
                    </button>
                </div>

            </div>
        </div>
        <form action="" method="post" enctype="multipart/form-data" id="form-blog">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <td style="width: 1px;" class="text-center">
                            <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
                        </td>
                        <td class="text-left">
                            博客标题
                        </td>
                        <td class="text-left">
                            状态
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($blogs) { ?>
                        <?php foreach ($blogs as $blog) { ?>
                            <tr>
                                <td class="text-center"><?php if (in_array($blog['blog_id'], $selected)) { ?>
                                        <input type="checkbox" name="selected[]" value="<?php echo $blog['blog_id']; ?>" checked="checked" />
                                    <?php } else { ?>
                                        <input type="checkbox" name="selected[]" value="<?php echo $blog['blog_id']; ?>" />
                                    <?php } ?></td>
                                <td class="text-left"><?php echo $blog['title']; ?></td>

                                <td class="text-left"><?php echo $blog['status']; ?></td>
                                <td class="text-right"><a href="<?php echo $blog['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </form>
        <div class="row">
            <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
            <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
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
    // 绑定删除事件
    $("#backend-blog-category-delete").click(function () {
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
                        { "mDataProp": "blog_category_id", "sClass" : "text-center", "bSortable": false, "fnRender": function(obj) {
                            obj.aData.edit_id = obj.aData.blog_category_id;
                            var html = '<input type="checkbox" name="selected[]" value="' + obj.aData.blog_category_id + '" />';
                            return html;
                        }},
                        { "mDataProp": "name","bSortable": false},
                        { "mDataProp": "sort_order","bSortable": false},
                        { "mDataProp": "edit_id", "sClass" : "text-center", "bSortable": false, "fnRender": function(obj) {
                            var html = '';
                            {% for child in currentMenu[currentMenu|length - 1]['children']['table'] %}
                                html = "<a href=\"{{ url({'for' : child['for']}) }}?blog_category_id="
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
                    "sAjaxSource" : "{{ url({'for': 'backend/blog/category'}) }}",
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
        $("#form-blog-category").ajaxSubmit({
            type:"post",
            url:"{{ url({'for' : 'backend/blog/category/delete'}) }}",
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