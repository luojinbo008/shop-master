{% extends "layouts/main.php" %}
{% block headercss %}
{% endblock %}
{% block content %}
<div class="panel-heading">
    <h3 class="panel-title">
        <i class="fa fa-pencil"></i> 添加博客分类
    </h3>
</div>
<div class="panel-body">
    <form action="{{ url({'for' : 'backend/blog/category/add'}) }}" method="post" enctype="multipart/form-data" id="form-blog-category" class="form-horizontal">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab-general" data-toggle="tab">常规</a>
            </li>
            <li>
                <a href="#tab-data" data-toggle="tab">数据</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active in" id="tab-general">
                <div class="tab-content">
                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-name">博客分类名称</label>
                        <div class="col-sm-10">
                            <input type="text" name="blog_category_name" value="{% if blog_category_name is defined %}{{blog_category_name}}{% endif %}"
                                   placeholder="博客分类名称" id="input-name" class="form-control" />
                            {% if error_name is defined %}
                                <div class="text-danger">{{error_name}}</div>
                            {% endif %}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-description">描述</label>
                        <div class="col-sm-10">
                            <textarea name="blog_category_description" placeholder="描述" id="input-description"
                                      class="form-control">{% if blog_category_description is defined %}{{blog_category_description}}{% endif %}</textarea>
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-meta-title">Meta Tag 标题</label>
                        <div class="col-sm-10">
                            <input type="text" name="blog_category_meta_title"
                                   value="{% if blog_category_meta_title is defined %}{{blog_category_meta_title}}{% endif %}" placeholder="Meta Tag 标题" id="input-meta-title" class="form-control" />
                            {% if error_meta_title is defined %}
                                <div class="text-danger">{{ error_meta_title }}</div>
                            {% endif %}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-meta-description">Meta Tag 描述</label>
                        <div class="col-sm-10">
                            <textarea name="blog_category_meta_description" rows="5" placeholder="Meta Tag 描述" id="input-meta-description"
                                      class="form-control">{% if blog_category_meta_description is defined %}{{blog_category_meta_description}}{% endif %}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-meta-keyword">Meta Tag 关键词</label>
                        <div class="col-sm-10">
                            <textarea name="blog_category_meta_keyword" rows="5" placeholder="Meta Tag 关键词" id="input-meta-keyword"
                                      class="form-control">{% if blog_category_meta_keyword is defined %}{{blog_category_meta_keyword}}{% endif %}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab-data">
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-parent">上一级</label>
                    <div class="col-sm-10">
                        <input type="text" name="path_name" value="{% if path_name is defined %}{{path_name}}{% endif %}" placeholder="上一级" id="input-parent" class="form-control" />
                        <input type="hidden" name="parent_id" value="{% if parent_id is defined %}{{parent_id}}{% endif %}" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">商店</label>
                    <div class="col-sm-10">
                        <div class="well well-sm" style="height: 150px; overflow: auto;">
                            {% for store in stores %}
                                <div class="checkbox">
                                    <label>
                                        <?php if (isset($blog_category_store) && in_array($store['store_id'], $blog_category_store)) { ?>
                                            <input type="checkbox" name="blog_category_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                                            <?php echo $store['name']; ?>
                                        <?php } else { ?>
                                            <input type="checkbox" name="blog_category_store[]" value="<?php echo $store['store_id']; ?>" />
                                            <?php echo $store['name']; ?>
                                        <?php } ?>
                                    </label>
                                </div>
                            {% endfor %}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">图像</label>
                    <div class="col-sm-10">
                        <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail">
                            <img src="{{thumb}}" alt="" title="" data-placeholder="{{placeholder}}" />
                        </a>
                        <input type="hidden" name="image" value="{% if image is defined %}{{image}}{% endif %}" id="input-image" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-sort-order">排序</label>
                    <div class="col-sm-10">
                        <input type="text" name="sort_order" value="{% if sort_order is defined %}{{sort_order}}{% endif %}" placeholder="排序" id="input-sort-order" class="form-control" />
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
            </div>
        </div>
    </form>
</div>
{% endblock %}
{% block footer %}
{% endblock %}
{% block footerjs %}
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
    $('input[name=\'path_name\']').autocomplete({
        'source': function(request, response) {
            $.ajax({
                url: '{{url({"for" : "backend/blog/category/autoComplete"})}}?filter_name=' +  encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
                    if (0 == json.status) {
                        json.data.unshift({
                            category_blog_id: 0,
                            name: '--无--'
                        });
                        response($.map(json.data, function(item) {
                            return {
                                label: item['name'],
                                value: item['blog_category_id']
                            }
                        }));
                    }

                }
            });
        },
        'select': function(item) {
            $('input[name=\'path_name\']').val(item['label']);
            $('input[name=\'parent_id\']').val(item['value']);
        }
    });
</script>
{% endblock %}
