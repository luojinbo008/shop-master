{% set url = url(), version = '1.0.0', static_url = static_url() %}
<!DOCTYPE html>
<html>
<head>
    <script charset="utf-8" src="{{ static_url }}/wechat/ui/js/jquery.min.js?v=01291"></script>
    <script charset="utf-8" src="{{ static_url }}/wechat/ui/js/bootstrap.min.js?v=01292"></script>
    <script charset="utf-8" src="{{ static_url }}/wechat/ui/js/global.js?v=01291"></script>
    <script charset="utf-8" src="{{ static_url }}/wechat/ui/js/template.js?v=01291"></script>

    <link rel="stylesheet" href="{{ static_url }}/wechat/ui/css/bootstrap.css?v=01291">
    <link rel="stylesheet" href="{{ static_url }}/wechat/ui/css/style.css?v=1?v=01291">
    <link rel="stylesheet" href="{{ static_url }}/wechat/ui/css/member.css?v=01291">
    <link rel="stylesheet" href="{{ static_url }}/wechat/ui/css/order3.css?v=01291">
    <link rel="stylesheet" href="{{ static_url }}/wechat/ui/font-awesome/css/font-awesome.min.css?v=01291">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="telephone=no" name="format-detection">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1;user-scalable=no;">
    <title>{{store_name}}</title>
    {% block headercss %}
    {% endblock %}
</head>
<body>
{% block content %}
{% endblock %}
{{ partial("../common/layouts/footer") }}
{% block footerjs %}
{% endblock %}
</body>
</html>

