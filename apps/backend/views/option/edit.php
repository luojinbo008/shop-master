{% extends "layouts/main.php" %}
{% block headercss %}
{% endblock %}
{% block content %}
<div class="panel-heading">
  <h3 class="panel-title"><i class="fa fa-pencil"></i> 编辑选项</h3>
</div>
<div class="panel-body">
  <form action="{{ url({'for' : 'backend/option/edit'}) }}" method="post" enctype="multipart/form-data" id="form-option" class="form-horizontal">
    <div class="form-group required">
      <label class="col-sm-2 control-label">选项名称</label>
      <div class="col-sm-10">
        <div class="input-group col-sm-12">
          <input type="hidden" value="{{ option_id }}" name="option_id">
          <input type="text" name="option_name" value="{% if option_name is defined  %}{{option_name}}{% endif %}" placeholder="选项名称" class="form-control" />
        </div>
        {% if error_name is defined %}
          <div class="text-danger">{{ error_name }}</div>
        {% endif %}
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-type">类型</label>
      <div class="col-sm-10">
        <select name="type" id="input-type" class="form-control">
          <optgroup label="选择">
              {% if type == 'select' %}
                  <option value="select" selected="selected">下拉列表</option>
              {% else %}
                  <option value="select">下拉列表</option>
              {% endif %}
              {% if type == 'radio' %}
                  <option value="radio" selected="selected">单选按钮组</option>
              {% else %}
                  <option value="radio">单选按钮组</option>
              {% endif %}
<!--                    {% if type == 'checkbox' %}
                  <option value="checkbox" selected="selected">复选框</option>
              {% else %}
                  <option value="checkbox">复选框</option>
              {% endif %}
              {% if type == 'image' %}
                  <option value="image" selected="selected">图像</option>
              {% else %}
                  <option value="image">图像</option>
              {% endif %}-->
          </optgroup>
<!--                <optgroup label="文字录入">
              {% if type == 'text' %}
                  <option value="text" selected="selected">单行文本</option>
              {% else %}
                  <option value="text">单行文本</option>
              {% endif %}
              {% if type == 'textarea' %}
                  <option value="textarea" selected="selected">多行文本</option>
              {% else %}
                  <option value="textarea">多行文本</option>
              {% endif %}
          </optgroup>
          <optgroup label="文件">
              {% if type == 'file' %}
                  <option value="file" selected="selected">文件</option>
              {% else %}
                  <option value="file">文件</option>
              {% endif %}
          </optgroup>
          <optgroup label="日期">
              {% if type == 'date' %}
                  <option value="date" selected="selected">日期</option>
              {% else %}
                  <option value="date">日期</option>
              {% endif %}
              {% if type == 'time' %}
                  <option value="time" selected="selected">时间</option>
              {% else %}
                  <option value="time">时间</option>
              {% endif %}
              {% if type == 'datetime' %}
                  <option value="datetime" selected="selected">日期&时间</option>
              {% else %}
                  <option value="datetime">日期&时间</option>
              {% endif %}
          </optgroup>-->
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-sort-order">排序</label>
      <div class="col-sm-10">
        <input type="text" name="sort_order" value="{% if sort_order is defined  %}{{sort_order}}{% endif %}" placeholder="排序" id="input-sort-order" class="form-control" />
      </div>
    </div>
    <table id="option-value" class="table table-striped table-bordered table-hover">
      <thead>
        <tr>
          <td class="text-left required">选项值</td>
          <td class="text-left">图像</td>
          <td class="text-right">排序</td>
          <td></td>
        </tr>
      </thead>
      <tbody>
        {% set option_value_row = 0 %}
        {% for option_value in option_values %}
            <tr id="option-value-row{{ option_value_row }}">
              <td class="text-left col-sm-9">
                  <input type="hidden" name="option_value[{{ option_value_row }}][option_value_id]" value="{{ option_value['option_value_id'] }}" />
                <div class="input-group col-sm-12">
                  <input type="text" name="option_value[{{ option_value_row }}][option_value_name]" value="{% if option_value['option_value_name'] is defined %}{{option_value['option_value_name']}}{% endif %}"
                         placeholder="选项值" class="form-control" />
                </div>
                {% if error_option_value[option_value_row] is defined %}
                  <div class="text-danger">{{ error_option_value[option_value_row] }}</div>
                {% endif %}
            </td>
              <td class="text-left">
                <a href="" id="thumb-image{{option_value_row}}" data-toggle="image" class="img-thumbnail"><img src="{{option_value['thumb']}}" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                <input type="hidden" name="option_value[{{option_value_row}}][image]" value="{{option_value['image']}}" id="input-image{{option_value_row}}" /></td>
              <td class="text-right">
                  <input type="text" name="option_value[{{option_value_row}}][sort_order]" value="{{option_value['sort_order']}}" class="form-control" placeholder="排序"  />
              </td>
              <td class="text-left"><button type="button" onclick="$('#option-value-row{{option_value_row}}').remove();" data-toggle="tooltip" title="移除" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
            </tr>
            {% set option_value_row = option_value_row + 1 %}
        {% endfor %}
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3"></td>
          <td class="text-left"><button type="button" onclick="addOptionValue();" data-toggle="tooltip" title="新增" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript">
    $('select[name=\'type\']').on('change', function() {
        if (this.value == 'select' || this.value == 'radio' || this.value == 'checkbox' || this.value == 'image') {
            $('#option-value').show();
        } else {
            $('#option-value').hide();
        }
    });

    $('select[name=\'type\']').trigger('change');

    var option_value_row = {{ option_value_row }};

    function addOptionValue() {
        html  = '<tr id="option-value-row' + option_value_row + '">';
        html += '  <td class="text-left col-sm-9"><input type="hidden" name="option_value[' + option_value_row + '][option_value_id]" value="" />';
        html += '    <div class="input-group col-sm-12">';
        html += '      <input type="text" name="option_value[' + option_value_row + '][option_value_name]" value="" placeholder="选项值" class="form-control" />';
        html += '    </div>';
        html += '  </td>';
        html += '  <td class="text-left"><a href="" id="thumb-image' + option_value_row + '" data-toggle="image" class="img-thumbnail"><img src="{{ placeholder }}" alt="" title="" data-placeholder="{{ placeholder }}" /></a>' +
            '<input type="hidden" name="option_value[' + option_value_row + '][image]" value="" id="input-image' + option_value_row + '" /></td>';
        html += '  <td class="text-right"><input type="text" name="option_value[' + option_value_row + '][sort_order]" value="" placeholder="排序" class="form-control" /></td>';
        html += '  <td class="text-left"><button type="button" onclick="$(\'#option-value-row' + option_value_row + '\').remove();" data-toggle="tooltip" title="移除" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
        html += '</tr>';

        $('#option-value tbody').append(html);
        option_value_row++;
    }
</script>
{% endblock %}
{% block footer %}
{% endblock %}
{% block footerjs %}
{% endblock %}