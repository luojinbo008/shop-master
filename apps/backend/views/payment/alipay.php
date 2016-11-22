{% extends "layouts/main.php" %}
{% block headercss %}
<style>
    .pay-test-status {
        font-size: 12px;
        margin-top: 10px;
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
    <h3 class="panel-title"><i class="fa fa-pencil"></i> 支付宝支付</h3>
</div>
<div class="panel-body">
    <form action="{{ url({'for' : 'backend/payment/alipay'}) }}" method="post" enctype="multipart/form-data" id="form-payment-alipay" class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-11 control-label">
                设置自有支付宝支付，买家使用支付宝支付付款购买商品时，
                货款将直接进入您支付宝支付对应的财付通账户，
                由支付宝自动扣除每笔0.6%交易手续费
            </label>
        </div>
        <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-partner">合作身份者ID：</label>
            <div class="col-sm-6">
                <input type="text" name="partner" value="{% if partner is defined %}{{partner}}{% endif %}"
                       placeholder="商户号" id="input-partner" class="form-control">
                <p class="ui-message-warning pay-test-status js-pay-all">
                    合作身份者ID，签约账号，以2088开头由16位纯数字组成的字符串，查看地址：https://b.alipay.com/order/pidAndKey.htm
                </p>
            </div>
        </div>
        <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-seller_id">支付宝账号：</label>
            <div class="col-sm-6">
                <input type="text" name="seller_id" value="{% if seller_id is defined %}{{seller_id}}{% endif %}"
                       placeholder="支付宝账号" id="input-seller_id" class="form-control">
                <p class="ui-message-warning pay-test-status js-pay-all">
                    收款支付宝账号，以2088开头由16位纯数字组成的字符串，一般情况下收款账号就是签约账号
                </p>
            </div>
        </div>
        <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-sign_type">签名方式：</label>
            <div class="col-sm-6">
                <select class="form-control" name="sign_type">
                    <option value="MD5" {% if sign_type is defined and sign_type == 'MD5' %}selected{% endif %}>默认加密</option>
                    <option value="RSA" {% if sign_type is defined and sign_type == 'RSA' %}selected{% endif %}>RSA加密</option>
                    <option value="DSA" {% if sign_type is defined and sign_type == 'DSA' %}selected{% endif %}>DSA加密</option>
                </select>
            </div>
        </div>
        <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-private_key_path">商户的私钥：</label>
            <div class="col-sm-6">
               <textarea name="private_key_path" placeholder="商户的私钥" id="input-private_key_path" rows="6"
                         class="form-control">{% if private_key_path is defined %}{{private_key_path}}{% endif %}</textarea>
                <p class="ui-message-warning pay-test-status js-pay-all ui-message-warning">
                    商户的私钥,此处填写</br>
                    默认私钥：https://doc.open.alipay.com/docs/doc.htm?spm=a219a.7629140.0.0.8kNHCE&treeId=58&articleId=103545&docType=1</br>
                    RSA公私钥生成：https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.nBDxfy&treeId=58&articleId=103242&docType=1</br>
                    DSA公私钥生成：https://doc.open.alipay.com/docs/doc.htm?spm=a219a.7629140.0.0.t12yTC&treeId=58&articleId=103581&docType=1
                </p>
            </div>
        </div>
        <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-ali_public_key_path">支付宝的公钥：</label>
            <div class="col-sm-6">
                <textarea name="ali_public_key_path" placeholder="支付宝的公钥（加密方式选择RSA，DSA才需填写，默认方式无需填写）"
                          id="input-ali_public_key_path" rows="6" class="form-control">{% if ali_public_key_path is defined %}{{ali_public_key_path}}{% endif %}</textarea>
                <p class="ui-message-warning pay-test-status js-pay-all">
                    加密方式选择RSA，DSA才需填写，默认方式无需填写</br>
                    支付宝的公钥，查看地址：https://b.alipay.com/order/pidAndKey.htm
                </p>
            </div>
        </div>
    </form>
</div>
{% endblock %}
{% block footer %}
{% endblock %}
{% block footerjs %}
{% endblock %}