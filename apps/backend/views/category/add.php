{% extends "layouts/main.php" %}
{% block headercss %}
{% endblock %}
{% block content %}
<div class="panel-heading">
  <h3 class="panel-title"><i class="fa fa-pencil"></i> 添加分类</h3>
</div>
<div class="panel-body">
  <form action="{{ url({'for' : 'backend/category/add'}) }}" method="post" enctype="multipart/form-data" id="form-category" class="form-horizontal">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#tab-general" data-toggle="tab">常规</a></li>
      <li><a href="#tab-data" data-toggle="tab">数据</a></li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active" id="tab-general">
        <div class="tab-content">
          <div >
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-name">分类名称</label>
              <div class="col-sm-10">
                <input type="text" name="name" value="{% if name is defined %}{{ name }}{% endif %}"
                       placeholder="分类名称" id="input-name" class="form-control" />
                {% if error_name is defined %}
                  <div class="text-danger">{{ error_name }}</div>
                {% endif %}
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-description">描述</label>
              <div class="col-sm-10">
                <textarea name="description" placeholder="描述"
                          id="input-description" class="form-control">{% if description is defined %}{{ description }}{% endif %}</textarea>
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
                          id="input-meta-description" class="form-control">{% if meta_description is defined %}{{ meta_description }}{% endif %}</textarea>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-meta-keyword">Meta Tag 关键词</label>
              <div class="col-sm-10">
                <textarea name="meta_keyword" rows="5" placeholder="Meta Tag 关键词"
                      id="input-meta-keyword" class="form-control">{% if meta_keyword is defined %}{{ meta_keyword }}{% endif %}</textarea>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="tab-pane" id="tab-data">
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-parent">上一级</label>
          <div class="col-sm-10">
            <input type="text" name="parent_name" value="{% if parent_name is defined %}{{ parent_name }}{% endif %}" placeholder="上一级" id="input-parent" class="form-control" />
            <input type="hidden" name="parent_id" value="{% if parent_id is defined %}{{ parent_id }}{% endif %}" />
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-filter"><span data-toggle="tooltip" title="(输入时自动筛选结果)">筛选</span></label>
          <div class="col-sm-10">
            <input type="text" name="filter" value="" placeholder="筛选" id="input-filter" class="form-control" />
            <div id="category-filter" class="well well-sm" style="height: 150px; overflow: auto;">
              {% for category_filter in category_filters %}
                  <div id="category-filter{{ category_filter['filter_id'] }}"><i class="fa fa-minus-circle"></i> {{ category_filter['name'] }}
                      <input type="hidden" name="category_filter[]" value="{{ category_filter['filter_id'] }}" />
                  </div>
              {% endfor %}
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">商店</label>
          <div class="col-sm-10">
            <div class="well well-sm" style="height: 150px; overflow: auto;">
              {% for store in stores%}
                  <div class="checkbox">
                    <label>
                        <?php if(isset($category_store) && in_array($store['store_id'], $category_store)) { ?>
                          <input type="checkbox" name="category_store[]" value="{{ store['store_id'] }}" checked="checked" />
                          {{ store['name'] }}
                        <?php } else { ?>
                            <input type="checkbox" name="category_store[]" value="{{ store['store_id'] }}" />
                            {{ store['name'] }}
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
              <a href="javascript:" id="thumb-image" data-toggle="image" class="img-thumbnail">
                  <img src="{% if thumb is defined %}{{ thumb }}{% endif %}" alt="" title=""
                       data-placeholder="{{ static_url }}/backend/image/no_image-100x100.png">
              </a>
            <input type="hidden" name="image" value="{% if image is defined %}{{ image }}{% endif %}" id="input-image" />
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-top"><span data-toggle="tooltip" title="在页面顶部菜单显示，仅适用顶级目录。">顶部菜单显示</span></label>
          <div class="col-sm-10">
            <div class="checkbox">
              <label>
                <input type="checkbox" name="top" value="1" id="input-top" {% if top is defined %} checked {% endif %}/>
                &nbsp; </label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-column"><span data-toggle="tooltip" title="在页面顶部菜单的子目录显示时排列的行数。 仅适用顶级目录。">列排显示</span></label>
          <div class="col-sm-10">
            <input type="text" name="column" value="{% if column is defined %}{{ column }}{% endif %}" placeholder="列排显示" id="input-column" class="form-control" />
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-sort-order">排序</label>
          <div class="col-sm-10">
            <input type="text" name="sort_order" value="{% if sort_order is defined %}{{ sort_order }}{% endif %}" placeholder="排序" id="input-sort-order" class="form-control" />
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-status">状态</label>
          <div class="col-sm-10">
            <select name="status" id="input-status" class="form-control">
              <option value="1" {% if status is defined and status == 1 %} selected {% endif %}>启动</option>
              <option value="0" {% if status is defined and status == 0 %} selected {% endif %}>停用</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </form>
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
    $('input[name=\'parent_name\']').autocomplete({
        'source': function(request, response) {
            $.ajax({
                url: '{{url({"for" : "backend/category/autoComplete"})}}?filter_name=' +  encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
                    if(0 == json.status){
                        json.data.unshift({
                            category_id: 0,
                            name: '--无--'
                        });
                        response($.map(json.data, function(item) {
                            return {
                                label: item['name'],
                                value: item['category_id']
                            }
                        }));
                    }

                }
            });
        },
        'select': function(item) {
            $('input[name=\'parent_name\']').val(item['label']);
            $('input[name=\'parent_id\']').val(item['value']);
        }
    });
</script>
<script type="text/javascript">
    $('input[name=\'filter\']').autocomplete({
        'source': function(request, response) {
            $.ajax({
                url: '{{url({"for" : "backend/filter/autoComplete"})}}?filter_name=' +  encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
                    if(0 == json.status){
                        response($.map(json.data, function(item) {
                            return {
                                label: item['name'],
                                value: item['filter_id']
                            }
                        }));
                    }
                }
            });
        },
        'select': function(item) {
            $('input[name=\'filter\']').val('');
            $('#category-filter' + item['value']).remove();
            $('#category-filter').append('<div id="category-filter' + item['value'] + '">' +
                '<i class="fa fa-minus-circle"></i> ' + item['label'] +
                '<input type="hidden" name="category_filter[]" value="' + item['value'] + '" /></div>');
        }
    });
    $('#category-filter').delegate('.fa-minus-circle', 'click', function() {
        $(this).parent().remove();
    });
</script>
{% endblock %}
{% block footer %}
{% endblock %}
{% block footerjs %}
{% endblock %}
