{% extends "layouts/main.php" %}
{% block headercss %}
{% endblock %}
{% block content %}
<div class="panel-heading">
    <h3 class="panel-title">
        <i class="fa fa-pencil"></i> 新增博客
    </h3>
</div>
<div class="panel-body">
    <form action="{{ url({'for' : 'backend/blog/add'}) }}" method="post" enctype="multipart/form-data" id="form-blog" class="form-horizontal">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab-general" data-toggle="tab">常规</a>
            </li>
            <li>
                <a href="#tab-data" data-toggle="tab">数据</a>
            </li>
            <li>
                <a href="#tab-links" data-toggle="tab">关联</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
                <div class="tab-pane">
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-title">博客标题</label>
                            <div class="col-sm-10">
                                <input type="text" name="title" value="{% if title is defined %}{{title}}{% endif %}" placeholder="简述" id="input-title" class="form-control" />
                                {% if error_title is defined %}
                                    <div class="text-danger">{{ error_title }}</div>
                                {% endif %}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-brief">简述</label>
                            <div class="col-sm-10">
                                <textarea name="brief" rows="5" placeholder="简述" id="input-brief"
                                          class="form-control">{% if brief is defined %}{{brief}}{% endif %}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-description">内容</label>
                            <div class="col-sm-10">
                                <textarea name="description" placeholder="内容" class="form-control summernote"
                                          id="input-description">{% if description is defined %}{{description}}{% endif %}</textarea>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-meta-title">Meta Tag 标题</label>
                            <div class="col-sm-10">
                                <input type="text" name="meta_title" value="{% if meta_title is defined %}{{meta_title}}{% endif %}"
                                       placeholder="Meta Tag 标题" id="input-meta-title" class="form-control" />
                                {% if error_meta_title is defined %}
                                    <div class="text-danger">{{ error_meta_title }}</div>
                                {% endif %}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-meta-description">Meta Tag 描述</label>
                            <div class="col-sm-10">
                                <textarea name="meta_description" rows="5" placeholder="Meta Tag 描述" id="input-meta-description"
                                          class="form-control">{% if meta_description is defined %}{{meta_description}}{% endif %}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-meta-keyword">Meta Tag 关键词</label>
                            <div class="col-sm-10">
                                <textarea name="meta_keyword" rows="5" placeholder="Meta Tag 关键词" id="input-meta-keyword"
                                          class="form-control">{% if meta_keyword is defined %}{{meta_keyword}}{% endif %}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-tag">
                                <span data-toggle="tooltip" title="英文逗号分割">标签</span>
                            </label>
                            <div class="col-sm-10">
                                <input type="text" name="tag" value="{% if tag is defined %}{{tag}}{% endif %}"
                                       placeholder="标签" id="input-tag" class="form-control" />
                            </div>
                        </div>
                    </div>
            </div>
            <div class="tab-pane" id="tab-data">
                <div class="form-group">
                    <label class="col-sm-2 control-label">图像</label>
                    <div class="col-sm-10">
                        <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail">
                            <img src="{% if thumb is defined %}{{thumb}}{% endif %}" alt="" title="" data-placeholder="{% if placeholder is defined %}{{placeholder}}{% endif %}" />
                        </a>
                        <input type="hidden" name="image" value="{% if image is defined %}{{image}}{% endif %}" id="input-image" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-status">状态</label>
                    <div class="col-sm-10">
                        <select name="status" id="input-status" class="form-control">
                            <option value="0" {% if status is defined and status == 0 %}selected{% endif %}>关闭</option>
                            <option value="1" {% if status is defined and status == 1 %}selected{% endif %}>开启</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-featured">推荐</label>
                    <div class="col-sm-10">
                        <select name="featured" id="input-featured" class="form-control">
                            <option value="0" {% if featured is defined and featured == 0 %}selected{% endif %}>关闭</option>
                            <option value="1" {% if featured is defined and featured == 1 %}selected{% endif %}>开启</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-hits">浏览次数</label>
                    <div class="col-sm-10">
                        <input type="text" name="hits" value="{% if hits is defined %}{{hits}}{% endif %}" placeholder=""
                               id="input-hits" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-created">发布日期</label>
                    <div class="col-sm-3">
                        <div class="input-group date">
                            <input type="text" name="created" value="{% if created is defined %}{{created}}{% endif %}"
                                   placeholder="发布日期" data-date-format="YYYY-MM-DD" id="input-created" class="form-control" />
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <i class="fa fa-calendar"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-user">发布者</label>
                    <div class="col-sm-10">
                        <select name="user_id" id="input-user" class="form-control">
                            {% for user in users %}
                                <option value="{{user['user_id']}}" {% if user_id is defined and user_id == user['user_id'] %} selected="selected"{% endif %}>{{user['username']}}</option>
                            {% endfor %}
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-video-code">视频代码</label>
                    <div class="col-sm-10">
                        <textarea name="video_code" rows="5" placeholder="视频代码" id="input-video-code"
                                  class="form-control">{% if video_code is defined %}{{video_code}}{% endif %}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-sort-order">推荐</label>
                    <div class="col-sm-10">
                        <input type="text" name="sort_order" value="{% if sort_order is defined %}{{sort_order}}{% endif %}"
                               placeholder="推荐" id="input-sort-order" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab-links">
                <div class="tab-pane" id="tab-links">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-blog-category">所属博客分类</label>
                        <div class="col-sm-10">
                            <div class="well well-sm" style="height: 150px; overflow: auto;">
                                {% for blog_category in blog_categories %}
                                    <div class="checkbox">
                                        <label>
                                            <?php if (isset($blog_blog_category) && in_array($blog_category['blog_category_id'], $blog_blog_category)) { ?>
                                                <input type="checkbox" name="blog_blog_category[]" value="<?php echo $blog_category['blog_category_id']; ?>" checked="checked" />
                                                <?php echo $blog_category['name']; ?>
                                            <?php } else { ?>
                                                <input type="checkbox" name="blog_blog_category[]" value="<?php echo $blog_category['blog_category_id']; ?>" />
                                                <?php echo $blog_category['name']; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                {% endfor %}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-product-related">相关商品</label>
                        <div class="col-sm-10">
                            <input type="text" name="prelated" value="" placeholder="相关商品" id="input-product-related" class="form-control" />
                            <div id="product-related" class="well well-sm" style="height: 150px; overflow: auto;">
                                {% if product_related is defined %}
                                    <?php foreach ($product_relateds as $row) { ?>
                                        <div id="product-related<?php echo $row['product_id']; ?>">
                                            <i class="fa fa-minus-circle"></i> <?php echo $row['name']; ?>
                                            <input type="hidden" name="product_related[]" value="<?php echo $row['product_id']; ?>" />
                                        </div>
                                    <?php } ?>
                                {% endif %}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-blog-related">相关博客文章</label>
                        <div class="col-sm-10">
                            <input type="text" name="brelated" value="" placeholder="相关博客文章" id="input-blog-related" class="form-control" />
                            <div id="blog-related" class="well well-sm" style="height: 150px; overflow: auto;">
                                {% if blog_related is defined %}
                                    <?php foreach ($blog_relateds as $row) { ?>
                                        <div id="blog-related<?php echo $row['blog_id']; ?>">
                                            <i class="fa fa-minus-circle"></i> <?php echo $row['title']; ?>
                                            <input type="hidden" name="blog_related[]" value="<?php echo $row['blog_id']; ?>" />
                                        </div>
                                    <?php } ?>
                                {% endif %}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">商店</label>
                        <div class="col-sm-10">
                            <div class="well well-sm" style="height: 150px; overflow: auto;">
                                <?php foreach ($stores as $store) { ?>
                                    <div class="checkbox">
                                        <label>
                                            <?php if (isset($blog_store) && in_array($store['store_id'], $blog_store)) { ?>
                                                <input type="checkbox" name="blog_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                                                <?php echo $store['name']; ?>
                                            <?php } else { ?>
                                                <input type="checkbox" name="blog_store[]" value="<?php echo $store['store_id']; ?>" />
                                                <?php echo $store['name']; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
{% endblock %}
{% block footer %}
{% endblock %}
{% block footerjs %}
<script type="text/javascript">
    $('input[name=\'prelated\']').autocomplete({
        'source': function(request, response) {
            $.ajax({
                url: '{{url({"for" : "backend/product/autoComplete"})}}?filter_name=' +  encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
                    if (0 == json.status) {
                        response($.map(json.data, function(item) {
                            return {
                                label: item['name'],
                                value: item['product_id']
                            }
                        }));
                    }
                }
            });
        },
        'select': function(item) {
            $('input[name=\'prelated\']').val('');
            $('#product-related' + item['value']).remove();
            $('#product-related').append(
                '<div id="product-related' + item['value'] + '">' +
                '<i class="fa fa-minus-circle"></i> ' +
                item['label'] +
                '<input type="hidden" name="product_related[]" value="' + item['value'] + '" />' +
                '</div>'
            );
        }
    });
    $('#product-related').delegate('.fa-minus-circle', 'click', function() {
        $(this).parent().remove();
    });
</script>
<script type="text/javascript">
    $('input[name=\'brelated\']').autocomplete({
        'source': function(request, response) {
            $.ajax({
                url: '{{url({"for" : "backend/blog/autoComplete"})}}?filter_name=' +  encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
                    if (0 == json.status) {
                        response($.map(json.data, function (item) {
                            return {
                                label: item['name'],
                                value: item['blog_id']
                            }
                        }));
                    }
                }
            });
        },
        'select': function(item) {
            $('input[name=\'brelated\']').val('');
            $('#blog-related' + item['value']).remove();
            $('#blog-related').append(
                '<div id="blog-related' + item['value'] + '">' +
                '<i class="fa fa-minus-circle"></i> ' + item['label'] +
                '<input type="hidden" name="blog_related[]" value="' + item['value'] + '" />' +
                '</div>'
            );
        }
    });
    $('#blog-related').delegate('.fa-minus-circle', 'click', function() {
        $(this).parent().remove();
    });
</script>

{% endblock %}