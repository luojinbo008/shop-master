{% extends "layouts/main.php" %}
{% block headercss %}
{% endblock %}
{% block content %}
<div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-comments"></i> 公众号授权</h3>
</div>
<div class="panel-body">
    {% if 1 == status %}
        <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
            <form class="form-horizontal">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-customer">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">授权方昵称</label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static">
                                            {{info['authorizer_info']['nick_name']}}
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">授权方头像</label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static">
                                            <img src="{{info['authorizer_info']['head_img']}}" width="100px;">
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">授权方公众号类型</label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static">
                                            {{info['authorizer_info']['service_type_info']['name']}}
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">授权方认证类型</label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static">
                                            {{info['authorizer_info']['verify_type_info']['name']}}
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">授权方公众号所设置的微信号</label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static">
                                            {{info['authorizer_info']['alias']}}
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">授权方公众号功能的开通状况</label>
                                    <div class="col-sm-10">
                                        {% for row in info['authorizer_info']['business_info'] %}
                                            <p class="form-control-static">
                                                {{ row['name'] }}：
                                                {% if row['status'] == 1 %}
                                                    <a href="javascript:void(0)">
                                                        <i class="fa fa-check-square-o"></i> 开启
                                                    </a>
                                                {% else %}
                                                    <a href="javascript:void(0)">
                                                        <i class="fa fa-square-o"></i> 关闭
                                                    </a>
                                                {% endif %}
                                            </p>
                                        {% endfor %}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">授权方公众号的原始ID</label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static">
                                            {{info['authorizer_info']['user_name']}}
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">公众号授权权限集</label>
                                    <div class="col-sm-10">
                                        {% for row in info['authorization_info']['func_info'] %}
                                            <p class="form-control-static">
                                                {{ row['funcscope_category']['name'] }}：
                                                <a href="javascript:void(0)">
                                                    <i class="fa fa-check-square-o"></i> 开启
                                                </a>
                                            </p>
                                        {% endfor %}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        </div>
    {% else %}
        <div class="tab-content">
            <div class="app-init-container">
                <div class="wxsettled">
                    <form class="form-horizontal form-side-line" style="margin-bottom: 0">
                        <h3 class="wxsettled-main-title">绑定微信公众号，把店铺和微信打通</h3>
                        <p class="wxsettled-sub-title">绑定后即可在这里管理您的公众号，平台将提供比微信官方后台更强大的功能！</p>
                        <div class="control-group" style="padding-bottom: 30px;">
                            <a class="btn btn-success fa fa-comments"
                               href="{{url}}" target="_blank">
                                我有微信公众号，立即设置
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    {% endif %}
</div>
{% endblock %}
{% block footer %}
{% endblock %}
{% block footerjs %}
<script type="text/javascript">
    $("#backend-wechat-unAuthorization").click(function () {
        confirm('确定取消授权') ? unAuthorization() : false
    });

    function unAuthorization() {
        $.ajax({
            url: '{{url({"for" : "backend/wechat/unAuthorization"})}}',
            dataType: 'json',
            success: function(json) {
                if(0 == json.status){
                    window.location.href = '{{url({"for" : "backend/wechat/authorization"})}}';
                } else {
                    alert(json.info);
                }
            }
        });
    }
</script>
{% endblock %}