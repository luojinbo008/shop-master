{% extends "layouts/main.php" %}
{% block headercss %}
{% endblock %}
{% block content %}
<div class="panel-heading">
    <h3 class="panel-title">
        <i class="fa fa-pencil"></i> 添加制造商/品牌/领队
    </h3>
</div>
<div class="panel-body">
    <form action="{{ url({'for' : 'backend/manufacturer/add'}) }}" method="post" enctype="multipart/form-data" id="form-manufacturer" class="form-horizontal">
        <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name">制造商/品牌/领队名称</label>
            <div class="col-sm-10">
                <input type="text" name="name" value="{% if name is defined %}{{ name }}{% endif %}" placeholder="制造商/品牌名称" id="input-name" class="form-control" />
                {% if error_name is defined %}
                    <div class="text-danger">{{ error_name }}</div>
                {% endif %}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">商店</label>
            <div class="col-sm-10">
                <div class="well well-sm" style="height: 150px; overflow: auto;">
                    {% for store in stores %}
                        <div class="checkbox">
                            <label>
                                {% if manufacturer_store is defined %}
                                    {% if store['store_id'] in  manufacturer_store%}
                                        <input type="checkbox" name="manufacturer_store[]" value="{{ store['store_id'] }}" checked="checked" />
                                        {{ store['name'] }}
                                    {% else %}
                                        <input type="checkbox" name="manufacturer_store[]" value="{{ store['store_id'] }}" />
                                    {{ store['name'] }}
                                    {% endif %}
                                {% else %}
                                    <input type="checkbox" name="manufacturer_store[]" value="{{ store['store_id'] }}" />
                                {{ store['name'] }}
                                {% endif %}
                            </label>
                        </div>
                    {% endfor %}
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-image">
                图像
            </label>
            <div class="col-sm-10">
                <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail">
                    <img src="{{ thumb }}" alt="" title="" data-placeholder="{{ placeholder }}" />
                </a>
                <input type="hidden" name="image" value="{{ image }}" id="input-image" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order">
                排序
            </label>
            <div class="col-sm-10">
                <input type="text" name="sort_order" value="{% if sort_order is defined %}{{ sort_order }}{% endif %}" placeholder="排序"
                       id="input-sort-order" class="form-control" />
            </div>
        </div>
    </form>
</div>
{% endblock %}
{% block footer %}
{% endblock %}
{% block footerjs %}
{% endblock %}