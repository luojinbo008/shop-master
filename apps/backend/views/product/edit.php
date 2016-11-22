{% extends "layouts/main.php" %}
{% block headercss %}
{% endblock %}
{% block content %}
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> 编辑商品</h3>
    </div>
    <div class="panel-body">
        <form action="{{ url({'for' : 'backend/product/edit'}) }}" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab">常规</a></li>
            <li><a href="#tab-data" data-toggle="tab">数据</a></li>
            <li><a href="#tab-links" data-toggle="tab">关联</a></li>
            <li><a href="#tab-option" data-toggle="tab">选项</a></li>
            <li><a href="#tab-discount" data-toggle="tab">批发折扣</a></li>
            <li><a href="#tab-special" data-toggle="tab">特价优惠</a></li>
            <li><a href="#tab-image" data-toggle="tab">图像</a></li>
            <li><a href="#tab-reward" data-toggle="tab">积分奖励</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
                <div class="tab-content">
                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-name">商品名称</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="product_id" value="{{ product_id }}">
                            <input type="text" name="name" value="{% if name is defined %}{{ name }}{% endif %}"
                                   placeholder="商品名称" id="input-name" class="form-control" />
                            {% if error_name is defined %}
                                <div class="text-danger">{{ error_name }}</div>
                            {% endif %}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-description">描述</label>
                        <div class="col-sm-10">
                            <textarea name="description" placeholder="描述"
                                      id="input-description">{% if description is defined %}{{description}}{% endif %}</textarea>
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-meta-title">Meta Tag 标题</label>
                        <div class="col-sm-10">
                            <input type="text" name="meta_title" value="{% if meta_title is defined %}{{ meta_title }}{% endif %}"
                                   placeholder="Meta Tag 标题" id="input-meta-title" class="form-control" />
                            {% if error_meta_title is defined %}
                                <div class="text-danger">{{ error_meta_title }}</div>
                            {% endif %}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-meta-description">Meta Tag 描述</label>
                        <div class="col-sm-10">
                            <textarea name="meta_description" rows="5" placeholder="Meta Tag 描述"
                                      id="input-meta-description" class="form-control">{% if meta_description is defined %}{{meta_description}}{% endif %}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-meta-keyword">Meta Tag 关键词</label>
                        <div class="col-sm-10">
                            <textarea name="meta_keyword" rows="5" placeholder="Meta Tag 关键词"
                                      id="input-meta-keyword" class="form-control">{% if meta_keyword is defined %}{{meta_keyword}}{% endif %}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-tag"><span data-toggle="tooltip" title="英文逗号分割">商品标签</span></label>
                        <div class="col-sm-10">
                            <input type="text" name="tag"
                                   value="{% if tag is defined %}{{tag}}{% endif %}" id="input-tag" placeholder="商品标签"  class="form-control" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab-data">
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-sku"><span data-toggle="tooltip" title="库存单位">库存单位</span></label>
                    <div class="col-sm-10">
                        <input type="text" name="sku" value="{% if sku is defined %}{{sku}}{% endif %}" placeholder="库存单位" id="input-sku" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-price">价格</label>
                    <div class="col-sm-10">
                        <input type="text" name="price" value="{% if price is defined %}{{price}}{% endif %}" placeholder="价格" id="input-price" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-quantity">数量</label>
                    <div class="col-sm-10">
                        <input type="text" name="quantity" value="{% if quantity is defined %}{{quantity}}{% endif %}" placeholder="数量" id="input-quantity" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-minimum"><span data-toggle="tooltip" title="加入订单时所需最小数量">最小购买数量</span></label>
                    <div class="col-sm-10">
                        <input type="text" name="minimum" value="{% if minimum is defined %}{{minimum}}{% endif %}" placeholder="最小购买数量" id="input-minimum" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-subtract">减少库存</label>
                    <div class="col-sm-10">
                        <select name="subtract" id="input-subtract" class="form-control">
                            {% if subtract is defined and subtract == 1 %}
                                <option value="1" selected="selected">开启</option>
                                <option value="0">停用</option>
                            {% elseif subtract is defined and subtract == 0 %}
                                <option value="1">开启</option>
                                <option value="0" selected="selected">停用</option>
                            {% endif %}
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-stock-status"><span data-toggle="tooltip" title="缺少该商品时所显示的数量">缺货时状态</span></label>
                    <div class="col-sm-10">
                        <select name="stock_status_id" id="input-stock-status" class="form-control">
                            {% for stock_status in stock_statuses %}
                                {% if stock_status_id is defined and stock_status_id == stock_status['stock_status_id'] %}
                                    <option value="{{ stock_status['stock_status_id'] }}" selected="selected">{{ stock_status['name'] }}</option>
                                {% else %}
                                    <option value="{{ stock_status['stock_status_id'] }}">{{ stock_status['name'] }}</option>
                                {% endif %}
                            {% endfor %}
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">需要配送</label>
                    <div class="col-sm-10">
                        {% if shipping is defined %}
                        {% if shipping == 1 %}
                        <label class="radio-inline">
                            <input type="radio" name="shipping" value="1" checked="checked">
                            是
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="shipping" value="0">
                            否
                        </label>
                        {% else %}
                        <label class="radio-inline">
                            <input type="radio" name="shipping" value="1">
                            是
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="shipping" value="0" checked="checked">
                            否
                        </label>
                        {% endif %}
                        {% else %}
                        <label class="radio-inline">
                            <input type="radio" name="shipping" value="1" checked="checked">
                            是
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="shipping" value="0">
                            否
                        </label>
                        {% endif %}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-date-available">上架日期</label>
                    <div class="col-sm-3">
                        <div class="input-group date">
                            <input type="text" name="date_available" value="{% if date_available is defined %}{{date_available}}{% endif %}" placeholder="上架日期"
                                   data-date-format="YYYY-MM-DD" id="input-date-available" class="form-control" />
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-status">状态</label>
                    <div class="col-sm-10">
                        <select name="status" id="input-status" class="form-control">
                            {% if status is defined and status == 1 %}
                                <option value="1" selected="selected">启用</option>
                                <option value="0">停用</option>
                            {% else %}
                                <option value="1">启用</option>
                                <option value="0" selected="selected">停用</option>
                            {% endif %}
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-sort-order">排序</label>
                    <div class="col-sm-10">
                        <input type="text" name="sort_order" value="{% if sort_order is defined %}{{sort_order}}{% endif %}" placeholder="排序" id="input-sort-order" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab-links">
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-manufacturer">
                        <span data-toggle="tooltip" title="(输入时自动筛选结果)">
                            制造商/品牌
                        </span>
                    </label>
                    <div class="col-sm-10">
                        <input type="text" name="manufacturer" value="{% if manufacturer is defined %}{{manufacturer}}{% endif %}"
                               placeholder="制造商/品牌" id="input-manufacturer" class="form-control" />
                        <input type="hidden" name="manufacturer_id" value="{% if manufacturer_id is defined %}{{manufacturer_id}}{% endif %}" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-category"><span data-toggle="tooltip" title="(输入时自动筛选结果)">分类</span></label>
                    <div class="col-sm-10">
                        <input type="text" name="category" value="" placeholder="分类" id="input-category" class="form-control" />
                        <div id="product-category" class="well well-sm" style="height: 150px; overflow: auto;">
                            {% for product_category_data in product_category %}
                                <div id="product-category{{ product_category_data['category_id']}}"><i class="fa fa-minus-circle"></i>{{ product_category_data['name'] }}
                                    <input type="hidden" name="product_category[]" value="{{ product_category_data['category_id'] }}" />
                                </div>
                            {% endfor %}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-filter"><span data-toggle="tooltip" title="(输入时自动筛选结果)">筛选</span></label>
                    <div class="col-sm-10">
                        <input type="text" name="filter" value="" placeholder="筛选" id="input-filter" class="form-control" />
                        <div id="product-filter" class="well well-sm" style="height: 150px; overflow: auto;">
                            {% for product_filter_data in product_filter %}
                                <div id="product-filter{{ product_filter_data['filter_id'] }}"><i class="fa fa-minus-circle"></i> {{ product_filter_data['name'] }}
                                    <input type="hidden" name="product_filter[]" value="{{ product_filter_data['filter_id'] }}" />
                                </div>
                            {% endfor %}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">商店</label>
                    <div class="col-sm-10">
                        <div class="well well-sm" style="height: 150px; overflow: auto;">
                            {% for store in stores %}
                                <div class="checkbox">
                                    <label>
                                        {% if store['store_id'] in product_store %}
                                            <input type="checkbox" name="product_store[]" value="{{ store['store_id'] }}" checked="checked" />
                                            {{ store['name'] }}
                                        {% else %}
                                            <input type="checkbox" name="product_store[]" value="{{ store['store_id'] }}" />
                                            {{ store['name'] }}
                                        {% endif %}

                                    </label>
                                </div>
                            {% endfor %}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-related"><span data-toggle="tooltip" title="(输入时自动筛选结果)">相关商品</span></label>
                    <div class="col-sm-10">
                        <input type="text" name="related" value="" placeholder="相关商品" id="input-related" class="form-control" />
                        <div id="product-related" class="well well-sm" style="height: 150px; overflow: auto;">
                             {% for product_related_data in product_related %}
                                <div id="product-related{{ product_related_data['product_id'] }}">
                                    <i class="fa fa-minus-circle"></i>
                                    {{ product_related_data['name'] }}
                                    <input type="hidden" name="product_related[]" value="{{ product_related_data['product_id'] }}" />
                                </div>
                            {% endfor %}
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab-option" style="min-height: 500px;">
              <div class="row">
                <div class="col-sm-2">
                  <ul class="nav nav-pills nav-stacked" id="option">
                    {% set option_row = 0 %}
                    {% for product_option_data in product_option %}
                        <li>
                            <a href="#tab-option{{option_row}}" data-toggle="tab">
                                <i class="fa fa-minus-circle" onclick="$('a[href=\'#tab-option{{option_row}}\']').parent().remove();
                                    $('#tab-option{{option_row}}').remove(); $('#option a:first').tab('show');"></i>
                                {{product_option_data['name']}}
                            </a>
                        </li>
                    {% set option_row = option_row + 1 %}
                    {% endfor %}
                    <li>
                      <input type="text" name="option" value="" placeholder="选项值" id="input-option" class="form-control" />
                    </li>
                  </ul>
                </div>
                <div class="col-sm-10">
                  <div class="tab-content">
                    {% set option_row = 0 %}
                    {% set option_value_row = 0 %}
                    {% for product_option_data in product_option %}
                        <div class="tab-pane" id="tab-option{{option_row}}">
                          <input type="hidden" name="product_option[{{ option_row }}][product_option_id]" value="{{ product_option_data['product_option_id'] }}" />
                          <input type="hidden" name="product_option[{{ option_row }}][name]" value="{{ product_option_data['name'] }}" />
                          <input type="hidden" name="product_option[{{ option_row }}][option_id]" value="{{ product_option_data['option_id'] }}" />
                          <input type="hidden" name="product_option[{{ option_row }}][type]" value="{{ product_option_data['type'] }}" />
                          <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-required{{ option_row }}">必填项</label>
                            <div class="col-sm-10">
                              <select name="product_option[{{ option_row }}][required]" id="input-required{{ option_row }}" class="form-control">
                                  {% if product_option_data['required'] %}
                                    <option value="1" selected="selected">是</option>
                                    <option value="0">否</option>
                                  {% else %}
                                    <option value="1">是</option>
                                    <option value="0" selected="selected">否</option>
                                  {% endif %}
                              </select>
                            </div>
                          </div>
                      {% if product_option_data['type'] == 'text' %}
                          <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-value{{ option_row }}">选项值</label>
                            <div class="col-sm-10">
                              <input type="text" name="product_option[{{ option_row }}][value]" value="{{ product_option_data['value'] }}"
                                     placeholder="选项值" id="input-value{{ option_row }}" class="form-control" />
                            </div>
                          </div>
                      {% endif %}
                      {% if product_option_data['type'] == 'textarea' %}
                          <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-value{{ option_row }}">选项值</label>
                            <div class="col-sm-10">
                              <textarea name="product_option[{{ option_row }}][value]" rows="5" placeholder="选项值" id="input-value{{ option_row }}"
                                        class="form-control">{{ product_option_data['value'] }}</textarea>
                            </div>
                          </div>
                      {% endif %}
                      {% if product_option_data['type'] == 'file' %}
                          <div class="form-group" style="display: none;">
                            <label class="col-sm-2 control-label" for="input-value{{ option_row }}">选项值</label>
                            <div class="col-sm-10">
                              <input type="text" name="product_option[{{ option_row }}][value]" value="{{ product_option_data['value'] }}"
                                     placeholder="选项值" id="input-value{{ option_row }}" class="form-control" />
                            </div>
                          </div>
                      {% endif %}
                      {% if product_option_data['type'] == 'date' %}
                          <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-value{{ option_row }}">选项值</label>
                            <div class="col-sm-3">
                              <div class="input-group date">
                                <input type="text" name="product_option[{{ option_row }}][value]" value="{{ product_option_data['value'] }}"
                                       placeholder="选项值" data-date-format="YYYY-MM-DD" id="input-value{{ option_row }}" class="form-control" />
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                              </div>
                            </div>
                          </div>
                      {% endif %}
                      {% if product_option_data['type'] == 'time' %}
                          <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-value{{ option_row }}">选项值</label>
                            <div class="col-sm-10">
                              <div class="input-group time">
                                <input type="text" name="product_option[{{ option_row }}][value]" value="{{ product_option_data['value'] }}"
                                       placeholder="选项值" data-date-format="HH:mm" id="input-value{{ option_row }}" class="form-control" />
                                <span class="input-group-btn">
                                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                </span>
                              </div>
                            </div>
                          </div>
                      {% endif %}
                      {% if product_option_data['type'] == 'datetime' %}
                          <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-value{{ option_row }}">选项值</label>
                            <div class="col-sm-10">
                              <div class="input-group datetime">
                                <input type="text" name="product_option[{{ option_row }}][value]" value="{{ product_option_data['value'] }}"
                                       placeholder="选项值" data-date-format="YYYY-MM-DD HH:mm" id="input-value{{ option_row }}" class="form-control" />
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                </span>
                              </div>
                            </div>
                          </div>
                        {% endif %}
                        {% if product_option_data['type'] == 'select' or product_option_data['type'] == 'radio'
                            or product_option_data['type'] == 'checkbox' or product_option_data['type'] == 'image' %}
                          <div class="table-responsive">
                            <table id="option-value{{ option_row }}" class="table table-striped table-bordered table-hover">
                              <thead>
                                <tr>
                                  <td class="text-left">选项值</td>
                                  <td class="text-right">数量</td>
                                  <td class="text-left">减少库存</td>
                                  <td class="text-right">价格</td>
                                  <td class="text-right">所需积分</td>
                                  <td></td>
                                </tr>
                              </thead>
                              <tbody>
                                {% if product_option_data['product_option_value'] %}
                                {% for product_option_value in product_option_data['product_option_value'] %}
                                <tr id="option-value-row{{ option_value_row }}">
                                  <td class="text-left">
                                      <select name="product_option[{{ option_row }}][product_option_value][{{ option_value_row }}][option_value_id]" class="form-control">
                                          {% if option_value[product_option_data['option_id']] is defined %}
                                              {% for option_value_data in option_value[product_option_data['option_id']] %}
                                                  {% if option_value_data['option_value_id'] == product_option_value['option_value_id'] %}
                                                    <option value="{{ option_value_data['option_value_id'] }}" selected="selected">{{ option_value_data['name'] }}</option>
                                                  {% else %}
                                                    <option value="{{ option_value_data['option_value_id'] }}">{{ option_value_data['name'] }}</option>
                                                  {% endif %}
                                              {% endfor %}
                                          {% endif %}
                                      </select>
                                      <input type="hidden" name="product_option[{{ option_row }}][product_option_value][{{ option_value_row }}][product_option_value_id]"
                                             value="{{ product_option_value['product_option_value_id'] }}" />
                                  </td>
                                  <td class="text-right">
                                      <input type="text" name="product_option[{{ option_row }}][product_option_value][{{ option_value_row }}][quantity]"
                                            value="{{ product_option_value['quantity'] }}" placeholder="数量" class="form-control" />
                                  </td>
                                  <td class="text-left">
                                      <select name="product_option[{{ option_row }}][product_option_value][{{ option_value_row }}][subtract]" class="form-control">
                                          {%if product_option_value['subtract'] == 1 %}
                                            <option value="1" selected="selected">是</option>
                                            <option value="0">否</option>
                                          {% else %}
                                            <option value="1">是</option>
                                            <option value="0" selected="selected">否</option>
                                          {% endif %}
                                    </select>
                                  </td>
                                  <td class="text-right">
                                      <select name="product_option[{{ option_row }}][product_option_value][{{ option_value_row }}][price_prefix]" class="form-control">
                                          {% if product_option_value['price_prefix'] == '+' %}
                                            <option value="+" selected="selected">+</option>
                                          {% else %}
                                            <option value="+">+</option>
                                          {% endif%}
                                          {% if product_option_value['price_prefix'] == '-' %}
                                            <option value="-" selected="selected">-</option>
                                          {% else %}
                                            <option value="-">-</option>
                                          {% endif %}
                                        </select>
                                    <input type="text" name="product_option[{{ option_row }}][product_option_value][{{ option_value_row }}][price]"
                                           value="{{ product_option_value['price'] }}" placeholder="价格" class="form-control" />
                                  </td>
                                  <td class="text-right">
                                      <select name="product_option[{{ option_row }}][product_option_value][{{ option_value_row }}][points_prefix]" class="form-control">
                                          {% if product_option_value['points_prefix'] == '+' %}
                                            <option value="+" selected="selected">+</option>
                                          {% else %}
                                            <option value="+">+</option>
                                          {% endif %}
                                          {% if product_option_value['points_prefix'] == '-' %}
                                            <option value="-" selected="selected">-</option>
                                          {% else %}
                                            <option value="-">-</option>
                                          {% endif %}
                                      </select>
                                      <input type="text" name="product_option[{{ option_row }}][product_option_value][{{ option_value_row }}][points]"
                                             value="{{ product_option_value['points'] }}" placeholder="所需积分" class="form-control" />
                                  </td>
                                  <td class="text-left">
                                      <button type="button" onclick="$(this).tooltip('destroy');$('#option-value-row{{ option_value_row }}').remove();" data-toggle="tooltip" title="移除" class="btn btn-danger">
                                          <i class="fa fa-minus-circle"></i>
                                      </button>
                                  </td>
                                </tr>
                                {% set option_value_row = option_value_row + 1 %}
                                {% endfor %}
                                {% endif %}
                              </tbody>
                              <tfoot>
                                <tr>
                                  <td colspan="5"></td>
                                  <td class="text-left"><button type="button" onclick="addOptionValue('{{ option_row }}');" data-toggle="tooltip" title="新增" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                                </tr>
                              </tfoot>
                            </table>
                          </div>
                          <select id="option-values{{ option_row }}" style="display: none;">
                            {% if option_value[product_option_data['option_id']] is defined %}
                              {% for option_value_data in option_value[product_option_data['option_id']]%}
                                    <option value="{{ option_value_data['option_value_id'] }}">{{ option_value_data['name'] }}</option>
                              {% endfor %}
                            {% endif %}
                          </select>
                        {% endif %}
                        </div>
                        {% set option_row = option_row + 1 %}
                    {% endfor %}
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-discount">
              <div class="table-responsive">
                <table id="discount" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left">会员等级</td>
                      <td class="text-right">数量</td>
                      <td class="text-right">优先级</td>
                      <td class="text-right">价格</td>
                      <td class="text-left">开始日期</td>
                      <td class="text-left">结束日期</td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    {% set discount_row = 0 %}
                    {% for product_discount_data in product_discount %}
                    <tr id="discount-row{{discount_row}}">
                      <td class="text-left">
                          <select name="product_discount[{{discount_row}}][customer_group_id]" class="form-control">
                              {% for customer_group in customer_groups %}
                                  {% if customer_group['customer_group_id'] == product_discount_data['customer_group_id'] %}
                                    <option value="{{customer_group['customer_group_id']}}" selected="selected">{{ customer_group['name'] }}</option>
                                  {% else %}
                                    <option value="{{customer_group['customer_group_id']}}">{{customer_group['name']}}</option>
                                  {% endif %}
                              {% endfor %}
                        </select>
                      </td>
                      <td class="text-right"><input type="text" name="product_discount[{{discount_row}}][quantity]" value="{{product_discount_data['quantity']}}" placeholder="数量" class="form-control" /></td>
                      <td class="text-right"><input type="text" name="product_discount[{{discount_row}}][priority]" value="{{product_discount_data['priority']}}" placeholder="优先级" class="form-control" /></td>
                      <td class="text-right"><input type="text" name="product_discount[{{discount_row}}][price]" value="{{product_discount_data['price']}}" placeholder="价格" class="form-control" /></td>
                      <td class="text-left" style="width: 20%;">
                          <div class="input-group date">
                              <input type="text" name="product_discount[{{ discount_row }}][date_start]" value="{{ product_discount_data['date_start'] }}" placeholder="开始日期" data-date-format="YYYY-MM-DD" class="form-control" />
                              <span class="input-group-btn">
                                <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                              </span>
                          </div>
                      </td>
                      <td class="text-left" style="width: 20%;">
                          <div class="input-group date">
                          <input type="text" name="product_discount[{{ discount_row }}][date_end]" value="{{ product_discount_data['date_end'] }}" placeholder="结束日期" data-date-format="YYYY-MM-DD" class="form-control" />
                          <span class="input-group-btn">
                          <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                          </span>
                          </div>
                      </td>
                      <td class="text-left"><button type="button" onclick="$('#discount-row{{ discount_row }}').remove();" data-toggle="tooltip" title="移除" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                    </tr>
                        {% set discount_row = discount_row + 1 %}
                    {% endfor %}
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="6"></td>
                      <td class="text-left"><button type="button" onclick="addDiscount();" data-toggle="tooltip" title="新增" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
            <div class="tab-pane" id="tab-special">
              <div class="table-responsive">
                <table id="special" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left">会员等级</td>
                      <td class="text-right">优先级</td>
                      <td class="text-right">价格</td>
                      <td class="text-left">开始日期</td>
                      <td class="text-left">结束日期</td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    {% set special_row = 0 %}
                    {% for product_special_data in product_special %}
                        <tr id="special-row{{special_row}}">
                          <td class="text-left"><select name="product_special[{{ special_row }}][customer_group_id]" class="form-control">
                             {% for customer_group in customer_groups %}
                                  {% if customer_group['customer_group_id'] == product_special_data['customer_group_id'] %}
                                    <option value="{{ customer_group['customer_group_id'] }}" selected="selected">{{ customer_group['name'] }}</option>
                                  {% else %}
                                    <option value="{{ customer_group['customer_group_id'] }}">{{ customer_group['name'] }}</option>
                                  {% endif %}
                              {% endfor %}
                            </select></td>
                          <td class="text-right"><input type="text" name="product_special[{{ special_row }}][priority]" value="{{ product_special_data['priority'] }}" placeholder="优先级" class="form-control" /></td>
                          <td class="text-right"><input type="text" name="product_special[{{ special_row }}][price]" value="{{ product_special_data['price'] }}" placeholder="价格" class="form-control" /></td>
                          <td class="text-left" style="width: 20%;"><div class="input-group date">
                              <input type="text" name="product_special[{{ special_row }}][date_start]" value="{{ product_special_data['date_start'] }}" placeholder="开始时间" data-date-format="YYYY-MM-DD" class="form-control" />
                              <span class="input-group-btn">
                              <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                              </span></div></td>
                          <td class="text-left" style="width: 20%;"><div class="input-group date">
                              <input type="text" name="product_special[{{ special_row }}][date_end]" value="{{ product_special_data['date_end'] }}" placeholder="结束时间" data-date-format="YYYY-MM-DD" class="form-control" />
                              <span class="input-group-btn">
                              <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                              </span></div></td>
                          <td class="text-left"><button type="button" onclick="$('#special-row{{ special_row }}').remove();" data-toggle="tooltip" title="移除" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                        </tr>
                        {% set special_row = special_row + 1 %}
                    {% endfor %}
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="5"></td>
                      <td class="text-left"><button type="button" onclick="addSpecial();" data-toggle="tooltip" title="新增" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
            <div class="tab-pane" id="tab-image">
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left">图像</td>
                    </tr>
                  </thead>

                  <tbody>
                    <tr>
                      <td class="text-left">
                          <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="{{ thumb }}" alt="" title="" data-placeholder="{{ placeholder }}" /></a>
                          <input type="hidden" name="image" value="{{ image }}" id="input-image" />
                      </td>
                  </tr>
                  </tbody>
                </table>
              </div>
              <div class="table-responsive">
                <table id="images" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left">附加图片</td>
                      <td class="text-right">排序</td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    {% set image_row = 0 %}
                        <?php foreach ($product_image as $product_image_data) { ?>
                            <tr id="image-row<?php echo $image_row; ?>">
                              <td class="text-left">
                                  <a href="" id="thumb-image<?php echo $image_row; ?>" data-toggle="image" class="img-thumbnail">
                                      <img src="<?php echo $product_image_data['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" />
                                  </a>
                                  <input type="hidden" name="product_image[<?php echo $image_row; ?>][image]" value="<?php echo $product_image_data['image']; ?>" id="input-image<?php echo $image_row; ?>" />
                              </td>
                              <td class="text-right">
                                  <input type="text" name="product_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $product_image_data['sort_order']; ?>" placeholder="排序" class="form-control" />
                              </td>
                              <td class="text-left">
                                  <button type="button" onclick="$('#image-row<?php echo $image_row; ?>').remove();" data-toggle="tooltip" title="移除" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
                              </td>
                            </tr>
                            <?php $image_row++; ?>
                        <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="2"></td>
                      <td class="text-left"><button type="button" onclick="addImage();" data-toggle="tooltip" title="新增图片" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
            <div class="tab-pane" id="tab-reward">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-points">
                    <span data-toggle="tooltip" title="购买此商品所需奖励积分。如果您不想让客户使用奖励积分来购买此产品，请将值设为0。">所需积分</span>
                </label>
                <div class="col-sm-10">
                  <input type="text" name="points" value="{{ points }}" placeholder="所需积分" id="input-points" class="form-control" />
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left">会员等级</td>
                      <td class="text-right">奖励积分</td>
                    </tr>
                  </thead>
                  <tbody>
                    {% for customer_group in customer_groups %}
                    <tr>
                      <td class="text-left">{{ customer_group['name'] }}</td>
                      <td class="text-right">
                          <input type="text" name="product_reward[{{ customer_group['customer_group_id'] }}][points]"
                                value="<?php echo isset($product_reward[$customer_group['customer_group_id']]) ? $product_reward[$customer_group['customer_group_id']]['points'] : ''; ?>"
                                class="form-control" />
                      </td>
                    </tr>
                    {% endfor %}
                  </tbody>
                </table>
              </div>
            </div>
        </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#input-description').summernote({
          height: 300,
          callbacks:{
              onImageUpload: function(files) {
                  sendFile(files[0]);
              }
          }
        });
        function sendFile(file) {
          data = new FormData();
          data.append("file", file);
          $.ajax({
              data: data,
              type: "POST",
              url: "{{url({'for' : 'backend/common/uploadFileEditor'})}}",
              cache: false,
              contentType: false,
              processData: false,
              success: function(res) {
                  if(res.status == 0){
                    var image = $('<img>').attr('src', res.data.image);
                    $('#input-description').summernote("insertNode", image[0]);
                  }
                  console.log(res);
              },
              error: function(data) {
                  console.log(data);
              }
          });
        }
      });
</script>
<script type="text/javascript">
    // Manufacturer
    $('input[name=\'manufacturer\']').autocomplete({
        'source': function(request, response) {
            $.ajax({
                url: '{{url({"for" : "backend/manufacturer/autoComplete"})}}?filter_name=' +  encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
                    json.data.unshift({
                        manufacturer_id: 0,
                        name: '--无--'
                    });
                    response($.map(json.data, function(item) {
                        return {
                            label: item['name'],
                            value: item['manufacturer_id']
                        }
                    }));
                }
            });
        },
        'select': function(item) {
            $('input[name=\'manufacturer\']').val(item['label']);
            $('input[name=\'manufacturer_id\']').val(item['value']);
        }
    });

    // Category
    $('input[name=\'category\']').autocomplete({
        'source': function(request, response) {
            $.ajax({
                url: '{{url({"for" : "backend/category/autoComplete"})}}?filter_name=' +  encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
                    response($.map(json.data, function(item) {
                        return {
                            label: item['name'],
                            value: item['category_id']
                        }
                    }));
                }
            });
        },
        'select': function(item) {
            $('input[name=\'category\']').val('');

            $('#product-category' + item['value']).remove();

            $('#product-category').append('<div id="product-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_category[]" value="' + item['value'] + '" /></div>');
        }
    });

    $('#product-category').delegate('.fa-minus-circle', 'click', function() {
        $(this).parent().remove();
    });

    // Filter
    $('input[name=\'filter\']').autocomplete({
        'source': function(request, response) {
            $.ajax({
                url: '{{url({"for" : "backend/filter/autoComplete"})}}?filter_name=' +  encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
                    response($.map(json.data, function(item) {
                        return {
                            label: item['name'],
                            value: item['filter_id']
                        }
                    }));
                }
            });
        },
        'select': function(item) {
            $('input[name=\'filter\']').val('');

            $('#product-filter' + item['value']).remove();

            $('#product-filter').append('<div id="product-filter' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_filter[]" value="' + item['value'] + '" /></div>');
        }
    });

    $('#product-filter').delegate('.fa-minus-circle', 'click', function() {
        $(this).parent().remove();
    });

    // Related
    $('input[name=\'related\']').autocomplete({
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
    		$('input[name=\'related\']').val('');

    		$('#product-related' + item['value']).remove();

    		$('#product-related').append('<div id="product-related' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_related[]" value="' + item['value'] + '" /></div>');
    	}
    });

    $('#product-related').delegate('.fa-minus-circle', 'click', function() {
        $(this).parent().remove();
    });
</script>
<script type="text/javascript">
    $('#option a:first').tab('show');
</script>

<script type="text/javascript">
    var option_value_row = {{ option_value_row }};

    function addOptionValue(option_row) {
        html  = '<tr id="option-value-row' + option_value_row + '">';
        html += '  <td class="text-left"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][option_value_id]" class="form-control">';
        html += $('#option-values' + option_row).html();
        html += '  </select><input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][product_option_value_id]" value="" /></td>';
        html += '  <td class="text-right"><input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][quantity]" value="" ' +
            'placeholder="数量" class="form-control" /></td>';
        html += '  <td class="text-left"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][subtract]" class="form-control">';
        html += '    <option value="1">是</option>';
        html += '    <option value="0">否</option>';
        html += '  </select></td>';
        html += '  <td class="text-right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price_prefix]" class="form-control">';
        html += '    <option value="+">+</option>';
        html += '    <option value="-">-</option>';
        html += '  </select>';
        html += '  <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price]" value="" placeholder="价格" class="form-control" /></td>';
        html += '  <td class="text-right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points_prefix]" class="form-control">';
        html += '    <option value="+">+</option>';
        html += '    <option value="-">-</option>';
        html += '  </select>';
        html += '  <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points]" value="" placeholder="所需积分" class="form-control" /></td>';
        html += '  <td class="text-left"><button type="button" onclick="$(this).tooltip(\'destroy\');$(\'#option-value-row' + option_value_row + '\').remove();" data-toggle="tooltip" rel="tooltip" ' +
            'title="移除" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
        html += '</tr>';

        $('#option-value' + option_row + ' tbody').append(html);
              $('[rel=tooltip]').tooltip();

        option_value_row++;
    }
</script>
<script type="text/javascript">
    var option_row = {{ option_row }}

    $('input[name=\'option\']').autocomplete({
        'source': function(request, response) {
        $.ajax({
            url: '{{url({"for" : "backend/option/autoComplete"})}}?filter_name=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                console.log(json);
                response($.map(json.data, function(item) {
                    return {
                        category: item['category'],
                        label: item['name'],
                        value: item['option_id'],
                        type: item['type'],
                        option_value: item['option_value']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        html  = '<div class="tab-pane" id="tab-option' + option_row + '">';
        html += '	<input type="hidden" name="product_option[' + option_row + '][product_option_id]" value="" />';
        html += '	<input type="hidden" name="product_option[' + option_row + '][name]" value="' + item['label'] + '" />';
        html += '	<input type="hidden" name="product_option[' + option_row + '][option_id]" value="' + item['value'] + '" />';
        html += '	<input type="hidden" name="product_option[' + option_row + '][type]" value="' + item['type'] + '" />';

        html += '	<div class="form-group">';
        html += '	  <label class="col-sm-2 control-label" for="input-required' + option_row + '">必填项</label>';
        html += '	  <div class="col-sm-10"><select name="product_option[' + option_row + '][required]" id="input-required' + option_row + '" class="form-control">';
        html += '	      <option value="1">是</option>';
        html += '	      <option value="0">否</option>';
        html += '	  </select></div>';
        html += '	</div>';

        if (item['type'] == 'text') {
            html += '	<div class="form-group">';
            html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '">选项值</label>';
            html += '	  <div class="col-sm-10"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="选项值" id="input-value' + option_row + '" class="form-control" /></div>';
            html += '	</div>';
        }

        if (item['type'] == 'textarea') {
            html += '	<div class="form-group">';
            html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '">选项值</label>';
            html += '	  <div class="col-sm-10"><textarea name="product_option[' + option_row + '][value]" rows="5" placeholder="选项值" id="input-value' + option_row + '" class="form-control"></textarea></div>';
            html += '	</div>';
        }

        if (item['type'] == 'file') {
            html += '	<div class="form-group" style="display: none;">';
            html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '">选项值</label>';
            html += '	  <div class="col-sm-10"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="选项值" id="input-value' + option_row + '" class="form-control" /></div>';
            html += '	</div>';
        }

        if (item['type'] == 'date') {
            html += '	<div class="form-group">';
            html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '">选项值</label>';
            html += '	  <div class="col-sm-3"><div class="input-group date"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="选项值" data-date-format="YYYY-MM-DD" id="input-value' + option_row + '" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
            html += '	</div>';
        }

        if (item['type'] == 'time') {
            html += '	<div class="form-group">';
            html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '">选项值</label>';
            html += '	  <div class="col-sm-10"><div class="input-group time"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="选项值" data-date-format="HH:mm" id="input-value' + option_row + '" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
            html += '	</div>';
        }

        if (item['type'] == 'datetime') {
            html += '	<div class="form-group">';
            html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '">选项值</label>';
            html += '	  <div class="col-sm-10"><div class="input-group datetime"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="选项值" data-date-format="YYYY-MM-DD HH:mm" id="input-value' + option_row + '" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
            html += '	</div>';
        }

        if (item['type'] == 'select' || item['type'] == 'radio' || item['type'] == 'checkbox' || item['type'] == 'image') {
            html += '<div class="table-responsive">';
            html += '  <table id="option-value' + option_row + '" class="table table-striped table-bordered table-hover">';
            html += '  	 <thead>';
            html += '      <tr>';
            html += '        <td class="text-left">选项值</td>';
            html += '        <td class="text-right">数量</td>';
            html += '        <td class="text-left">减少库存</td>';
            html += '        <td class="text-right">价格</td>';
            html += '        <td class="text-right">所需积分</td>';
            html += '        <td></td>';
            html += '      </tr>';
            html += '  	 </thead>';
            html += '  	 <tbody>';

            html += '    </tbody>';
            html += '    <tfoot>';
            html += '      <tr>';
            html += '        <td colspan="5"></td>';
            html += '        <td class="text-left"><button type="button" onclick="addOptionValue(' + option_row + ');" data-toggle="tooltip" title="新增" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>';
            html += '      </tr>';
            html += '    </tfoot>';
            html += '  </table>';
            html += '</div>';

                  html += '  <select id="option-values' + option_row + '" style="display: none;">';

                  for (i = 0; i < item['option_value'].length; i++) {
                html += '  <option value="' + item['option_value'][i]['option_value_id'] + '">' + item['option_value'][i]['name'] + '</option>';
                  }

                  html += '  </select>';
            html += '</div>';
        }

        $('#tab-option .tab-content').append(html);

        $('#option > li:last-child').before('<li><a href="#tab-option' + option_row + '" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$(\'a[href=\\\'#tab-option' + option_row + '\\\']\').parent().remove(); $(\'#tab-option' + option_row + '\').remove(); $(\'#option a:first\').tab(\'show\')"></i> ' + item['label'] + '</li>');

        $('#option a[href=\'#tab-option' + option_row + '\']').tab('show');

        $('[data-toggle=\'tooltip\']').tooltip({
            container: 'body',
            html: true
        });

        $('.date').datetimepicker({
            pickTime: false
        });

        $('.time').datetimepicker({
            pickDate: false
        });

        $('.datetime').datetimepicker({
            pickDate: true,
            pickTime: true
        });

        option_row++;
    }
});
</script>

<script type="text/javascript">
var discount_row = {{ discount_row }};

function addDiscount() {
    html  = '<tr id="discount-row' + discount_row + '">';
    html += '  <td class="text-left"><select name="product_discount[' + discount_row + '][customer_group_id]" class="form-control">';
    {% for customer_group in customer_groups %}
    html += '    <option value="{{ customer_group['customer_group_id'] }}"><?php echo addslashes($customer_group['name']); ?></option>';
    {% endfor %}
    html += '  </select></td>';
    html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][quantity]" value="" placeholder="数量" class="form-control" /></td>';
    html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][priority]" value="" placeholder="优先级" class="form-control" /></td>';
    html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][price]" value="" placeholder="价格" class="form-control" /></td>';
    html += '  <td class="text-left" style="width: 20%;"><div class="input-group date"><input type="text" name="product_discount[' + discount_row + '][date_start]" value="" placeholder="开始日期" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
    html += '  <td class="text-left" style="width: 20%;"><div class="input-group date"><input type="text" name="product_discount[' + discount_row + '][date_end]" value="" placeholder="结束日期" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
    html += '  <td class="text-left"><button type="button" onclick="$(\'#discount-row' + discount_row + '\').remove();" data-toggle="tooltip" title="移除" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
    html += '</tr>';
    $('#discount tbody').append(html);
    $('.date').datetimepicker({
        pickTime: false
    });
    discount_row++;
}
</script>

<script type="text/javascript">
    var special_row = {{ special_row }};
    function addSpecial() {
        html  = '<tr id="special-row' + special_row + '">';
        html += '  <td class="text-left"><select name="product_special[' + special_row + '][customer_group_id]" class="form-control">';
        {% for customer_group in customer_groups %}
            html += '      <option value="{{ customer_group['customer_group_id'] }}"><?php echo addslashes($customer_group['name']); ?></option>';
        {% endfor %}
        html += '  </select></td>';
        html += '  <td class="text-right"><input type="text" name="product_special[' + special_row + '][priority]" value="" placeholder="优先级" class="form-control" /></td>';
        html += '  <td class="text-right"><input type="text" name="product_special[' + special_row + '][price]" value="" placeholder="价格" class="form-control" /></td>';
        html += '  <td class="text-left" style="width: 20%;"><div class="input-group date"><input type="text" name="product_special[' + special_row + '][date_start]" value="" placeholder="开始日期" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
        html += '  <td class="text-left" style="width: 20%;"><div class="input-group date"><input type="text" name="product_special[' + special_row + '][date_end]" value="" placeholder="结束日期" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
        html += '  <td class="text-left"><button type="button" onclick="$(\'#special-row' + special_row + '\').remove();" data-toggle="tooltip" title="移除" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
        html += '</tr>';

    $('#special tbody').append(html);
    $('.date').datetimepicker({
        pickTime: false
    });
    special_row++;
}
</script>

<script type="text/javascript">
  var image_row = {{ image_row }};

  function addImage() {
  	html  = '<tr id="image-row' + image_row + '">';
  	html += '  <td class="text-left"><a href="" id="thumb-image' + image_row + '"data-toggle="image" class="img-thumbnail"><img src="{{ placeholder }}" alt="" title="" data-placeholder="{{ placeholder }}" /></a>' +
        '<input type="hidden" name="product_image[' + image_row + '][image]" value="" id="input-image' + image_row + '" /></td>';
  	html += '  <td class="text-right"><input type="text" name="product_image[' + image_row + '][sort_order]" value="" placeholder="排序" class="form-control" /></td>';
  	html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row  + '\').remove();" data-toggle="tooltip" title="移除" class="btn btn-danger">' +
        '<i class="fa fa-minus-circle"></i></button></td>';
  	html += '</tr>';

  	$('#images tbody').append(html);

  	image_row++;
  }
</script>

<script type="text/javascript">
    $('.date').datetimepicker({
        pickTime: false
    });
    $('.time').datetimepicker({
        pickDate: false
    });
    $('.datetime').datetimepicker({
        pickDate: true,
        pickTime: true
    });
</script>
</div>
{% endblock %}
{% block footer %}
{% endblock %}
{% block footerjs %}
{% endblock %}
