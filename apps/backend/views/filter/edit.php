{% extends "layouts/main.php" %}
{% block headercss %}
{% endblock %}
{% block content %}
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-pencil"></i> 编辑筛选</h3>
        </div>
        <div class="panel-body">
            <form action="{{url({'for' : 'backend/filter/edit'})}}" method="post" enctype="multipart/form-data" id="form-filter" class="form-horizontal">
                <div class="form-group required">
                    <label class="col-sm-2 control-label">筛选分组名称</label>
                    <div class="col-sm-10">
                        <div class="input-group col-sm-12">
                            <input type="hidden" name="filter_group_id" value="{{ filter_group_id }}" />
                            <input type="text" name="filter_group_name" value="{% if filter_group_name is defined %}{{filter_group_name}}{% endif %}" placeholder="筛选分组名称" class="form-control" />
                        </div>
                        {% if error_group is defined %}
                            <div class="text-danger">{{ error_group }}</div>
                        {% endif %}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-sort-order">排序</label>
                    <div class="col-sm-10">
                        <input type="text" name="sort_order" value="{% if sort_order is defined %}{{ sort_order }}{% endif %}"
                               placeholder="排序" id="input-sort-order" class="form-control" />
                    </div>
                </div>
                <table id="filter" class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <td class="text-left required">筛选名称</td>
                        <td class="text-right">排序</td>
                        <td></td>
                    </tr>
                    </thead>
                    <tbody>
                    {% set filter_row = 0 %}
                    {% for filter in filters %}
                        <tr id="filter-row{{ filter_row }}">
                            <td class="text-left" style="width: 70%;">
                                <input type="hidden" name="filters[{{ filter_row }}][filter_id]" value="{{ filter['filter_id'] }}" />
                                <div class="input-group  col-sm-12">
                                    <input type="text" name="filters[{{ filter_row }}][filter_name]" value="{{ filter['name'] }}"" placeholder="筛选名称" class="form-control" />
                                </div>
                                {% if error_filter[filter_row] is defined %}
                                    <div class="text-danger">{{ error_filter[filter_row] }}</div>
                                {% endif %}
                            <td class="text-right"><input type="text" name="filters[{{ filter_row }}][sort_order]" value="{{filter['sort_order']}}" placeholder="排序" id="input-sort-order" class="form-control" /></td>
                            <td class="text-left"><button type="button" onclick="$('#filter-row{{ filter_row }}').remove();" data-toggle="tooltip" title="移除页面" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                        </tr>
                        {% set filter_row += 1 %}
                    {% endfor %}
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="2"></td>
                        <td class="text-left"><a onclick="addFilterRow();" data-toggle="tooltip" title="添加筛选" class="btn btn-primary"><i class="fa fa-plus-circle"></i></a></td>
                    </tr>
                    </tfoot>
                </table>
            </form>
        </div>
    </div>

    <script type="text/javascript">
    var filter_row = {% if filter_row is defined %}{{ filter_row }}{% else %} 0 {% endif %};
    function addFilterRow() {
        html  = '<tr id="filter-row' + filter_row + '">';
        html += '  <td class="text-left" style="width: 70%;"><input type="hidden" name="filters[' + filter_row + '][filter_id]" value="" />';
        html += '  <div class="input-group　col-sm-10">';
        html += '    <input type="text" name="filters[' + filter_row + '][filter_name]" value="" placeholder="筛选名称" class="form-control" />';
        html += '  </div>';
        html += '  </td>';
        html += '  <td class="text-right"><input type="text" name="filters[' + filter_row + '][sort_order]" value="" placeholder="排序" id="input-sort-order" class="form-control" /></td>';
        html += '  <td class="text-left"><button type="button" onclick="$(\'#filter-row' + filter_row + '\').remove();" data-toggle="tooltip" title="移除" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
        html += '</tr>';
        $('#filter tbody').append(html);
        filter_row++;
    }
    </script>
{% endblock %}
{% block footer %}
{% endblock %}
{% block footerjs %}
{% endblock %}
