{% extends "layouts/main.php" %}
{% block headercss %}
<link href="{{ static_url }}/backend/src/bootstrap/css/DT_bootstrap.css" rel="stylesheet" type="text/css"/>
{% endblock %}
{% block content %}
<div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-list"></i> 商品列表</h3>
</div>
<div class="panel-body">
    <div class="well">
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label" for="input-name">商品名称</label>
                    <input type="text" name="filter_name" value="" placeholder="商品名称" id="input-name" class="form-control" />
                </div>
                <div class="form-group">
                    <label class="control-label" for="input-id">商品 ID</label>
                    <input type="text" name="filter_product_id" value="" placeholder="商品 ID" id="input-id" class="form-control" />
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label" for="input-price">价格</label>
                    <input type="text" name="filter_price" value="" placeholder="价格" id="input-price" class="form-control" />
                </div>
                <div class="form-group">
                    <label class="control-label" for="input-quantity">数量</label>
                    <input type="text" name="filter_quantity" value="" placeholder="数量" id="input-quantity" class="form-control" />
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label" for="input-status">状态</label>
                    <select name="filter_status" id="input-status" class="form-control">
                        <option value="1">开启</option>
                        <option value="0">停用</option>
                    </select>
                </div>
                <div class="form-group">

                </div>
            </div>
        </div>
        <div class="row">
            <button type="button" id="button-filter" class="btn btn-primary pull-right" onclick="search();">
                <i class="fa fa-search"></i> 筛选
            </button>
        </div>
    </div>
    <form method="post" enctype="multipart/form-data" id="form-product">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="sample_1" >
                <thead>
                    <tr>
                        <th style="width:8px;" class="text-center">
                            <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
                        </th>
                        <th class="text-left">
                            图像
                        </th>
                        <th class="text-left">
                            商品ID
                        </th>
                        <th class="text-left">
                            商品名称
                        </th>
                        <th class="text-left">
                            价格
                        </th>
                        <th class="text-left">
                            数量
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
    $("#backend-product-delete").click(function () {
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
                        { "mDataProp": "product_id", "sClass" : "text-center", "bSortable": false, "fnRender": function(obj) {
                            obj.aData.edit_id = obj.aData.product_id;
                            obj.aData.show_id = obj.aData.product_id;
                            var html = '<input type="checkbox" name="selected[]" value="' + obj.aData.product_id + '" />';
                            return html;
                        }},
                        { "mDataProp": "image", "sClass" : "text-center", "bSortable": false, "fnRender": function(obj) {
                            var html = ' <span class="img-thumbnail list"><i class="fa fa-camera fa-2x"></i></span>';
                            if(obj.aData.image) {
                                html = '<img src="' + obj.aData.image + '" alt="' + obj.aData.image + '" class="img-thumbnail" />';
                            }
                            return html;
                        }},
                        { "mDataProp": "show_id","bSortable": false},
                        { "mDataProp": "name","bSortable": false},
                        { "mDataProp": "special","bSortable": false, "fnRender": function(obj) {
                            if(obj.aData.special) {
                                return '<span style="text-decoration: line-through;">' + obj.aData.price + '</span><br/>' +
                                    '<div class="text-danger">' + obj.aData.special + '</div>'
                            }
                            return obj.aData.price;
                        }},
                        { "mDataProp": "quantity","bSortable": false, "fnRender": function(obj) {
                            if(obj.aData.quantity <= 0) {
                                return '<span class="label label-warning">' + obj.aData.quantity + '</span>';
                            }else if(obj.aData.quantity  <= 5) {
                                return '<span class="label label-danger">' + obj.aData.quantity + '</span>';
                            }else{
                                return '<span class="label label-success">' + obj.aData.quantity + '</span>';
                            }
                        }},
                        { "mDataProp": "status","bSortable": false},
                        { "mDataProp": "edit_id", "sClass" : "text-center", "bSortable": false, "fnRender": function(obj) {
                            var html = '';
                            {% for child in currentMenu[currentMenu|length - 1]['children']['table'] %}
                                html = "<a href=\"{{ url({'for' : child['for']}) }}?product_id="
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
                    "sAjaxSource" : "{{ url({'for': 'backend/product'}) }}",
                    "fnServerParams": function (aoData) {
                        aoData.push({"name": "filter_name", "value": $("#input-name").val()});
                        aoData.push({"name": "filter_model", "value": $("#input-model").val()});
                        aoData.push({"name": "filter_price", "value": $("#input-price").val()});
                        aoData.push({"name": "filter_quantity", "value": $("#input-quantity").val()});
                        aoData.push({"name": "filter_product_id", "value": $("#input-id").val()});
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

    function search(){
        TableManaged.init();
    }

    function del(){
          $("#form-product").ajaxSubmit({
              type:"post",
              url:"{{ url({'for' : 'backend/product/delete'}) }}",
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

<script type="text/javascript">
    $('input[name=\'filter_name\']').autocomplete({
        'source': function(request, response) {
            $.ajax({
                url: '{{url({"for" : "backend/product/autoComplete"})}}?filter_name=' +  encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
                    response($.map(json.data, function(item) {
                        return {
                            label: item['name'],
                            value: item['product_id']
                        }
                    }));
                }
            });
        },
        'select': function(item) {
            $('input[name=\'filter_name\']').val(item['label']);
        }
    });

    $('input[name=\'filter_model\']').autocomplete({
        'source': function(request, response) {
            $.ajax({
                url: '{{url({"for" : "backend/product/autoComplete"})}}?filter_model=' +  encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
                    response($.map(json.data, function(item) {
                        return {
                            label: item['model'],
                            value: item['product_id']
                        }
                    }));
                }
            });
        },
        'select': function(item) {
            $('input[name=\'filter_model\']').val(item['label']);
        }
    });
</script>
{% endblock %}