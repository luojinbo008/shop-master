{% set url = url(), version = '1.0.0', static_url = static_url() %}
<!DOCTYPE html>
<html dir="direction" lang="code">
<head>
    <meta charset="UTF-8" />
    <title></title>
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
</head>
<body>
    <div id="content">
        <div class="container-fluid"><br />
            <br />
            <div class="row">
                <div class="col-sm-offset-4 col-sm-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h1 class="panel-title"><i class="fa fa-lock"></i>  请输入登录信息。</h1>
                        </div>
                        <div class="panel-body">
                            {% if success is defined %}
                                <div class="alert alert-success"><i class="fa fa-check-circle"></i> {{ success }}
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                </div>
                            {% endif %}
                            {% if error_warning is defined %}
                                <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> {{ error_warning }}
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                </div>
                            {% endif %}
                            <form action="{{ url({'for' : 'backend/common/login'}) }}" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="input-username">用户名</label>
                                    <div class="input-group"><span class="input-group-addon"><i class="fa fa-user"></i></span>
                                        <input type="text" name="username" value="" placeholder="用户名" id="input-username" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="input-password">密码</label>
                                    <div class="input-group"><span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                        <input type="password" name="password" value="" placeholder="密码" id="input-password" class="form-control" />
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-key"></i> 登录</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{ partial("layouts/footer") }}
</body>
</html>