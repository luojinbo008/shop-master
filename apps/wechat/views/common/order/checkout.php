{% set url = url(), version = '1.0.0', static_url = static_url() %}
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>支付宝手机网站支付</title>
</head>
<body>
{{html}}
<script src="{{ static_url }}/wechat/ui/js/alipay.js?v=0.8"></script>
<script>
    //该js用于微信上使用支付宝支付
    window.onload = function() {
        var queryParam = '';
        Array.prototype.slice.call(document.querySelectorAll('input[type=hidden]')).forEach(function (ele) {
            queryParam += ele.name + '=' + encodeURIComponent(ele.value) + '&';
        });
        var gotoUrl = document.querySelector('#alipay_form').getAttribute('action') + queryParam;
        _AP.pay(gotoUrl);
    }

</script>
</body>
</html>