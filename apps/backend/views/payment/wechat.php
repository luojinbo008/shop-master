{% extends "layouts/main.php" %}
{% block headercss %}
<style>
    .pay-test-status {
        font-size: 12px;
        margin-top: 10px;
        width: 400px;
    }
    .payment-block .payment-block-body p {
        line-height: 24px;
    }
    .ui-message-warning {
        padding: 7px 15px;
        margin-bottom: 15px;
        color: #333;
        border: 1px solid #e5e5e5;
        line-height: 24px;
    }
    .ui-message-warning {
        color: #333;
        background: #ffc;
        border-color: #fc6;
    }
</style>
{% endblock %}
{% block content %}
<div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-pencil"></i> 微信支付</h3>
</div>
<div class="panel-body">
    <form action="{{ url({'for' : 'backend/payment/wechat'}) }}" method="post" enctype="multipart/form-data" id="form-payment-wechat" class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-12 control-label" style="text-align: center;">
                设置自有微信支付，买家使用微信支付付款购买商品时，
                货款将直接进入您微信支付对应的财付通账户，
                由财付通自动扣除每笔0.6%交易手续费
            </label>
        </div>
        <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-mchid">商户号：</label>
            <div class="col-sm-6">
                <input type="text" name="mchid" value="{% if mchid is defined %}{{mchid}}{% endif %}"
                       placeholder="商户号" id="input-mchid" class="form-control">
            </div>
        </div>
        <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-key">密钥：</label>
            <div class="col-sm-6">
                <input type="text" name="key" value="{% if key is defined %}{{key}}{% endif %}"
                       placeholder="密钥" id="input-secret" class="form-control">
            </div>
        </div>
        <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-status">微信支付状态：</label>
            <div class="col-sm-4">
                <label class="checkbox-inline">
                    <input type="radio" name="status" value="1" {% if status is defined and status == 1 %} checked {% endif %} title="全网支付已发布"/>
                        全网支付已发布
                </label>
                <label class="checkbox-inline">
                    <input type="radio" name="status" value="0" title="测试支付中" {% if status is defined and status == 0 %} checked {% endif %}/>
                        测试支付中
                </label>
                <p class="ui-message-warning pay-test-status js-pay-all">
                    由于微信支付流程限制，该选项需由您进行设置。如您的微信支付已通过微信的审核并开通，
                    请选择“全网支付已发布”状态，以保证粉丝能够在你的店铺正常使用微信支付进行交易。
                    否则，请选择“测试支付中”；
                </p>
            </div>
        </div>
        <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-authorization">微信网页授权：</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="authorization" value="1" {% if authorization is defined and authorization == 1%} checked {% endif %} id="input-authorization">
                        授权回调页面域名已设置为 “interface.azbzo.com”
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-is_https">微信支付配置：</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="is_https" value="1" {% if is_https is defined and is_https == 1%} checked {% endif %}  id="input-is_https">
                        https配置完成
                    </label>
                </div>
            </div>
        </div>
    </form>
</div>
{% endblock %}
{% block footer %}
{% endblock %}
{% block footerjs %}
{% endblock %}