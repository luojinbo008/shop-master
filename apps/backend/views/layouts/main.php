{% set url = url(), version = '1.0.0', static_url = static_url() %}
<!DOCTYPE html>
<html dir="direction" lang="code">
<head>
<meta charset="UTF-8" />
<title>{{ appname }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<script type="text/javascript" src="{{ static_url }}/backend/src/jquery/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="{{ static_url }}//backend/src/bootstrap/js/bootstrap.min.js"></script>
<link href="{{ static_url }}/backend/src/bootstrap/css/bootstrap.css" type="text/css" rel="stylesheet" />
<link href="{{ static_url }}/backend/src/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
<link href="{{ static_url }}/backend/src/summernote/summernote.css" rel="stylesheet" />
<script type="text/javascript" src="{{ static_url }}/backend/src/summernote/summernote.js"></script>
<script src="{{ static_url }}/backend/src/jquery/datetimepicker/moment.js" type="text/javascript"></script>
<script src="{{ static_url }}/backend/src/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="{{ static_url }}/backend/src/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
<link type="text/css" href="{{ static_url }}/backend/css/stylesheet.css" rel="stylesheet" media="screen" />
<script src="{{ static_url }}/backend/js/common.js" type="text/javascript"></script>
{% block headercss %}
{% endblock %}
</head>
    <body>
        <div id="container">
            {{ partial("layouts/header") }}
            {{ partial("layouts/menu") }}
            <div id="content">
                <div class="page-header">
                    <div class="container-fluid">
                        <h1>{% if currentMenu[0] is defined %}{{currentMenu[0]['name']}}{% endif %}</h1>
                        <ul class="breadcrumb">
                            {% for tmp in currentMenu %}
                                <li>{{tmp['name']}}</li>
                            {% endfor %}
                        </ul>
                        {% if currentMenu[currentMenu|length - 1]['children'] is defined and currentMenu[currentMenu|length - 1]['children']|length > 0 %}
                            <div class="pull-right">
                                {% for child in currentMenu[currentMenu|length - 1]['children']['top'] %}
                                    {% if child['type'] == 'a' %}
                                        <a href="{{url({'for' : child['for']})}}" data-toggle="tooltip" title="{{child['name']}}" class="{{child['class']}}">
                                            <i class="fa {{child['icon']}}"></i>
                                        </a>
                                    {% elseif child['type'] == 'button' %}
                                        <button type="button" data-toggle="tooltip" title="{{child['name']}}" class="{{child['class']}}" id="{{child['id']}}">
                                            <i class="fa {{child['icon']}}"></i>
                                        </button>
                                    {% elseif child['type'] == 'submit' %}
                                        <button type="submit" form="{{child['form']}}" data-toggle="tooltip" title="{{child['name']}}" class="{{child['class']}}">
                                            <i class="fa {{child['icon']}}"></i>
                                        </button>
                                    {% endif %}
                                {% endfor %}
                            </div>
                        {% endif %}
                    </div>
                </div>
                <div class="container-fluid" id="content_body">
                    <?php if ($this->flashSession->has('error')) {?>
                        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $this->flashSession->getMessages('error', true)[0]; ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php } elseif ($this->flashSession->has('success')) {?>
                        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $this->flashSession->getMessages('success', true)[0]; ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php } ?>
                    {% block content %}

                    {% endblock %}
                </div>
            </div>
            {{ partial("layouts/footer") }}
        </div>
    </body>
{% block footerjs %}
{% endblock %}
</html>

