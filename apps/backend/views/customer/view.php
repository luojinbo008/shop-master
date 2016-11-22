{% extends "layouts/main.php" %}
{% block headercss %}
{% endblock %}
{% block content %}
<div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-pencil"></i> 编辑会员</h3>
</div>
<div class="panel-body">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-general" data-toggle="tab" aria-expanded="true">常规</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab-general">
            <form action="{{ url({'for' : 'backend/customer/edit'}) }}" method="post" enctype="multipart/form-data" id="form-customer" class="form-horizontal">
                <div class="row">
                    <div class="col-sm-2">
                        <ul class="nav nav-pills nav-stacked" id="address">
                            <li class="active">
                                <a href="#tab-customer" data-toggle="tab" aria-expanded="true">常规</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-sm-10">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-customer">
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-customer-group">会员等级</label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="customer_id" value="{{customerInfo['customer_id']}}">
                                        <select name="customer_group_id" id="input-customer-group" class="form-control">
                                            {% for group in groups %}
                                                <option value="{{group['customer_group_id']}}" {% if group['customer_group_id'] == customerInfo['customer_group_id'] %}selected{% endif %}>{{group['name']}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-fullname">姓名</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="fullname" value="{{customerInfo['fullname']}}" placeholder="姓名" id="input-fullname" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-fullname">昵称</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="nickname" value="{{customerInfo['nickname']}}" placeholder="姓名" id="input-fullname" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-fullname">手机号码</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="telephone" value="{{customerInfo['telephone']}}" placeholder="手机号码" id="input-fullname" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-fullname">身份证号码</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="card" value="{{customerInfo['idcard']}}" placeholder="身份证号码" id="input-fullname" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-fullname">状态</label>
                                    <div class="col-sm-10">
                                        <select name="status" id="input-customer-group" class="form-control">
                                            <option value="1" {% if customerInfo['status'] == 1 %}selected{% endif %}>开启</option>
                                            <option value="0" {% if customerInfo['status'] == 0 %}selected{% endif %}>关闭</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{% endblock %}
{% block footer %}
{% endblock %}
{% block footerjs %}
<script type="text/javascript" src="{{ static_url }}/backend/src/jquery/jquery.validate.min.js"></script>
<script type="text/javascript" src="{{ static_url }}/backend/src/jquery/jquery-form.js"></script>
<script type="text/javascript">
    var FormValidate = function () {
        return {
            init: function () {
                var form = $('#editCustomerInfo');
                var error = $('.alert-error', form);
                var success = $('.alert-success', form);
                form.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-inline', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",
                    rules: {
                        customer_group_id: {
                            required: true
                        },
                        status: {
                            required: true
                        }
                    },
                    messages: {
                        customer_group_id: "请选择用户会员等级！",
                        status: "请选择用户状态！"
                    },
                    errorPlacement: function (error, element) {
                        if (element.attr("name") == "file") {
                            error.insertAfter("#addLinkFile");
                        } else {
                            error.insertAfter(element);
                        }
                    },
                    invalidHandler: function (event, validator) {
                        success.hide();
                        error.show();
                    },
                    highlight: function (element) {
                        $(element)
                            .closest('.help-inline').removeClass('ok');
                        $(element)
                            .closest('.control-group').removeClass('success').addClass('error');
                    },
                    unhighlight: function (element) {
                        $(element)
                            .closest('.control-group').removeClass('error');
                    },
                    success: function (label) {
                        if (label.attr("for") == "service" || label.attr("for") == "membership") {
                            label
                                .closest('.control-group').removeClass('error').addClass('success');
                            label.remove();
                        } else {
                            label
                                .addClass('valid').addClass('help-inline ok')
                                .closest('.control-group').removeClass('error').addClass('success');
                        }
                    },
                    submitHandler: function (form) {
                        $(form).ajaxSubmit({
                            type:"post",
                            url:"{{ url({'for': 'backend/customer/edit'}) }}",
                            success: function(res){
                                if(res.status == 0){
                                    window.location.href = "{{ url({'for': 'backend/customer'}) }}";
                                } else {
                                    alert(res.info);
                                }
                            }
                        });
                    }
                });
            }
        }
    }();
    jQuery(document).ready(function() {
        FormValidate.init();
    });
</script>
{% endblock %}