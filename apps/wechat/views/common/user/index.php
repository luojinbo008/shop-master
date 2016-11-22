{% extends "layouts/main.php" %}
{% block headercss %}
{% endblock %}
{% block content %}
<div class="maincontainer">
    <div class="container" style="max-width:768px;margin:0 auto;">
        <div class="row">
            <div class="member_top member_top_1">
                <div class="member_top_bg">
                    <img src="{{ static_url }}/wechat/ui/images/member_bg.png">
                </div>
                <div class="member_m_pic member_m_pic_1">
                    <img class="img-circle" src="{{ static_url }}/wechat/ui/images/noavatar.png">
                </div>
                <div  class="member_m_z member_m_z_1">
                    <div class="member_m_x">{{nickname}}</div>
                </div>
                <div style="width:33%;position: absolute;left:15%;text-align: right;bottom: 10%;color: #fff;font-size: 10px;">
                    我的积分：{{points}}
                </div>
                <a href="{{url({'for' : 'user-manage'})}}">
                    <div class="member_m_r">
                        账号管理&gt;
                    </div>
                </a>
            </div>
            <div class="list-group mb10">
                <div class="list-group-item p0 clearfix">
                    <div class="col-xs-3 p0">
                       <a class="order_tab_link" href="{{url({'for' : 'order-list'})}}?type=0">
                           <em class="order_img">
                               <img src="{{ static_url }}/wechat/ui/images/order_bg_3.png">
                           </em>待付款
                       </a>
                    </div>
                    <div class="col-xs-3 p0">
                        <a class="order_tab_link" href="{{url({'for' : 'order-list'})}}?type=1">
                            <em class="order_img">
                                <img src="{{ static_url }}/wechat/ui/images/order_bg.png">
                            </em>已付款
                        </a>
                    </div>
                    <div class="col-xs-3 p0">
                        <a class="order_tab_link" href="{{url({'for' : 'order-list'})}}?type=2">
                          <em class="order_img">
                              <img src="{{ static_url }}/wechat/ui/images/order_bg_1.png">
                          </em>退货
                        </a>
                    </div>
                </div>
            </div>
            <div class="list-group mb10">
                <a href="{{url({'for' : 'user-addressList'})}}" class="list-group-item tip">
                    <div class="list_group_img">
                        <img src="{{ static_url }}/wechat/ui/images/order_bg_9.png">
                    </div>
                     常用联系人
                </a>
                <a href="/p/help" class="list-group-item tip">
                    <div class="list_group_img">
                        <img src="{{ static_url }}/wechat/ui/images/order_bg_10.png">
                    </div>
                    常见问题
                </a>
            </div>
        </div>
    </div>
{% endblock %}
{% block footerNew %}
{% endblock %}
{% block footerjs %}
{% endblock %}
