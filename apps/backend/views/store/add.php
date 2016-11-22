{% extends "layouts/main.php" %}
{% block headercss %}
{% endblock %}
{% block content %}
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-store" data-toggle="tooltip" title="保存" class="btn btn-primary">
                    <i class="fa fa-save"></i>
                </button>
                <a href="{{ url({'for' : 'backend/store'}) }}" data-toggle="tooltip" title="取消" class="btn btn-default">
                    <i class="fa fa-reply"></i>
                </a>
            </div>
            <h1>新增商店</h1>
            <ul class="breadcrumb">
                <li>
                    <a href="{{ url({'for' : 'backend/common/ashboard'}) }}">首页</a>
                </li>
                <li>
                    <a href="{{ url({'for' : 'backend/store'}) }}">商店</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        {% if error_warning is defined %}
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> {{error_warning}}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        {% endif %}
        {% if success is defined %}
            <div class="alert alert-success"><i class="fa fa-check-circle"></i> {{success}}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        {% endif %}
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> 新增商店</h3>
            </div>
            <div class="panel-body">
                <form action="{{ url({'for' : 'backend/store/add'}) }}" method="post" enctype="multipart/form-data" id="form-store" class="form-horizontal">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-general" data-toggle="tab">常规</a></li>
                        <li><a href="#tab-store" data-toggle="tab">商店</a></li>
                        <li><a href="#tab-option" data-toggle="tab">选项</a></li>
                        <li><a href="#tab-advert" data-toggle="tab">首页广告</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-general">
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-url">
                                    <span data-toggle="tooltip" data-html="true" title="请输入你网店的完整网址。 注意在最后加上 '/' 。 例： http：//www.azbzo.com/path/<br /><br />请用新的域名或二级域名指定到你的主机。">
                                        网店网址 URL
                                    </span>
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" name="store_url" value="{% if store_url is defined %}{{ store_url }}{% endif %}" placeholder="网店网址 URL" id="input-url" class="form-control" />
                                    {% if error_url is defined %}
                                        <div class="text-danger">{{ error_url }}</div>
                                    {% endif %}
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-meta-title">Meta 标题</label>
                                <div class="col-sm-10">
                                    <input type="text" name="meta_title" value="{% if meta_title is defined %}{{ meta_title }}{% endif %}"
                                           placeholder="Meta 标题" id="input-meta-title" class="form-control" />
                                    {% if error_meta_title is defined %}
                                        <div class="text-danger">{{ error_meta_title }}</div>
                                    {% endif %}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-meta-description">Meta Tag 描述</label>
                                <div class="col-sm-10">
                                    <textarea name="meta_description" rows="5" placeholder="Meta Tag 描述" id="input-meta-description"
                                          class="form-control">{% if meta_description is defined %}{{ meta_description }}{% endif %}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-meta-keyword">Meta Tag 关键词</label>
                                <div class="col-sm-10">
                                    <textarea name="meta_keyword" rows="5" placeholder="Meta Tag 关键词" id="input-meta-keyword"
                                          class="form-control">{% if meta_keyword is defined %}{{ meta_keyword }}{% endif %}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-store">
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-name">网店名称</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" value="{% if name is defined %}{{ name }}{% endif %}"
                                           placeholder="网店名称" id="input-name" class="form-control" />
                                    {% if error_name is defined %}
                                        <div class="text-danger">{{ error_name }}</div>
                                    {% endif %}
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-name">商店类型</label>
                                <div class="col-sm-10">
                                    <select name="store_type" id="input-store-type" class="form-control">
                                        <option value="wechat" selected="selected">微信商城</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-image">图像</label>
                                <div class="col-sm-10">
                                    <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail">
                                        <img src="{{ thumb }}" alt="" title="" data-placeholder="{{ placeholder }}" />
                                    </a>
                                    <input type="hidden" name="image" value="{% if image is defined %}{{ image }}{% endif %}" id="input-image" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-comment">
                                    <span data-toggle="tooltip" title="输入一些特殊的提示性信息，例如不接受支票等。">备注</span>
                                </label>
                                <div class="col-sm-10">
                                    <textarea name="comment" rows="5" placeholder="备注" id="input-comment" class="form-control">{% if comment is defined %}{{ comment }}{% endif %}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-option">
                            <fieldset>
                                <legend>账户</legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-customer-group">
                                        <span data-toggle="tooltip" title="默认会员等级。">会员等级</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <select name="customer_group_id" id="input-customer-group" class="form-control">
                                            {% for customer_group in customer_groups %}
                                                {% if customer_group_id is defined and customer_group['customer_group_id'] == customer_group_id %}
                                                    <option value="{{ customer_group['customer_group_id'] }}" selected="selected">{{ customer_group['name'] }}</option>
                                                {% else %}
                                                    <option value="{{ customer_group['customer_group_id'] }}">{{ customer_group['name'] }}</option>
                                                {% endif %}
                                            {% endfor %}
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>商品库存</legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        <span data-toggle="tooltip" title="在商品页面显示库存数量。">显示库存</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            {% if stock_display is defined and 1 == stock_display %}
                                                <input type="radio" name="stock_display" value="1" checked="checked" />
                                                是
                                            {% else %}
                                                <input type="radio" name="stock_display" value="1" />
                                                是
                                            {% endif %}
                                        </label>
                                        <label class="radio-inline">
                                            {% if stock_display is not defined or 0 == stock_display %}
                                                <input type="radio" name="stock_display" value="0" checked="checked" />
                                                否
                                            {% else %}
                                                <input type="radio" name="stock_display" value="0" />
                                                否
                                            {% endif %}
                                        </label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="tab-pane" id="tab-advert">
                            <fieldset>
                                <legend>首页滚动广告</legend>
                            </fieldset>
                            <div class="form-group">
                                <div class="table-responsive">
                                    <form action="" method="post" enctype="multipart/form-data" id="form-advert" class="form-horizontal">
                                        <table id="images" class="table table-striped table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <td class="text-left">图片（大小768*420）</td>
                                                <td class="text-left">广告词</td>
                                                <td class="text-left">链接</td>
                                                <td class="text-right">排序</td>
                                                <td></td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            {% set image_row = 0 %}
                                            {% if advert_image is defined %}
                                            {% for tmp in advert_image %}
                                            {% set image_row = image_row + 1 %}
                                            <tr id="image-row{{image_row}}">
                                                <td class="text-left">
                                                    <a href="" id="thumb-image{{image_row}}"data-toggle="image" class="img-thumbnail">
                                                        <img src="{{tmp['thumb']}}" alt="" title="" data-placeholder="{{tmp['thumb']}}" />
                                                    </a>
                                                    <input type="hidden" name="advert_image[{{image_row}}][advert_id]" value="{{tmp['advert_id']}}" />
                                                    <input type="hidden" name="advert_image[{{image_row}}][image]" value="{{tmp['image']}}" id="input-image{{image_row}}" />
                                                </td>
                                                <td class="text-right"><input type="text" name="advert_image[{{image_row}}][title]" value="{{tmp['title']}}" placeholder="广告词" class="form-control" /></td>
                                                <td class="text-right">
                                                    <div class="input-group  col-sm-12">
                                                        <input type="text" name="advert_image[{{image_row}}][link]" value="{{tmp['link']}}" placeholder="链接" class="form-control" />
                                                    </div>
                                                    {% if advert_image_link_error[image_row] is defined %}
                                                    <div class="text-danger">{{ advert_image_link_error[image_row] }}</div>
                                                    {% endif %}
                                                </td>
                                                <td class="text-right"><input type="text" name="advert_image[{{image_row}}][sort_order]" value="{{tmp['sort_order']}}" placeholder="排序" class="form-control" /></td>
                                                <td class="text-left">
                                                    <button type="button" onclick="$('#image-row{{image_row}}').remove();" data-toggle="tooltip" title="移除" class="btn btn-danger">
                                                        <i class="fa fa-minus-circle"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            {% endfor %}
                                            {% endif %}
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td colspan="4"></td>
                                                <td class="text-left"><button type="button" onclick="addImage();" data-toggle="tooltip" title="新增图片" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{% endblock %}
{% block footer %}
{% endblock %}
{% block footerjs %}
<script type="text/javascript">
    var image_row = 0;
    function addImage() {
        var html  = '<tr id="image-row' + image_row + '">';
        html += '  <td class="text-left"><a href="" id="thumb-image' + image_row + '"data-toggle="image" class="img-thumbnail"><img src="{{ placeholder }}" alt="" title="" data-placeholder="{{ placeholder }}" /></a>' +
            '<input type="hidden" name="advert_image[' + image_row + '][image]" value="" id="input-image' + image_row + '" /></td>';
        html += '  <td class="text-right"><input type="text" name="advert_image[' + image_row + '][title]" value="" placeholder="广告词" class="form-control" /></td>';
        html += '  <td class="text-right"><input type="text" name="advert_image[' + image_row + '][link]" value="" placeholder="链接" class="form-control" /></td>';
        html += '  <td class="text-right"><input type="text" name="advert_image[' + image_row + '][sort_order]" value="" placeholder="排序" class="form-control" /></td>';
        html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row  + '\').remove();" data-toggle="tooltip" title="移除" class="btn btn-danger">' +
            '<i class="fa fa-minus-circle"></i></button></td>';
        html += '</tr>';
        $('#images tbody').append(html);
        image_row++;
    }
</script>
{% endblock %}