<header id="header" class="navbar navbar-static-top">
  <div class="navbar-header">
        <a type="button" id="button-menu" class="pull-left"><i class="fa fa-indent fa-lg"></i></a>
<!--        <a href="#" class="navbar-brand"><img src="{{ static_url }}/backend/image/logo.png" alt="分类" title="分类" /></a>-->
  </div>
  <ul class="nav pull-right">
    <li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown"><span class="label label-danger pull-left">0</span>
        <i class="fa fa-bell fa-lg"></i>
      </a>
      <ul class="dropdown-menu dropdown-menu-right alerts-dropdown">
        <li class="dropdown-header">
          订单管理
        </li>
        <li>
          <a href="#" style="display: block; overflow: auto;">
            <span class="label label-warning pull-right">0</span>
            处理中订单
          </a>
        </li>
        <li>
          <a href="#">
            <span class="label label-success pull-right">0</span>
            已完成订单
          </a>
        </li>
        <li>
          <a href="#">
            <span class="label label-danger pull-right">0</span>
            商品退换
          </a>
        </li>
        <li class="divider"></li>
        <li class="dropdown-header">会员</li>
        <li>
          <a href="#">
            <span class="label label-success pull-right">0</span>
            在线会员
          </a>
        </li>
        <li>
          <a href="#">
            <span class="label label-danger pull-right">0</span>
            待审核
          </a>
        </li>
        <li class="divider"></li>
        <li class="dropdown-header">商品管理</li>
        <li>
          <a href="#">
            <span class="label label-danger pull-right">0</span>
            库存不足
          </a>
        </li>
        <li>
          <a href="#">
            <span class="label label-danger pull-right">0</span>
            评论
          </a>
        </li>
    </ul>
    </li>
    <li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-home fa-lg"></i>
      </a>
      <ul class="dropdown-menu dropdown-menu-right">
        <li class="dropdown-header">商店</li>
        <li>
          <a href="#" target="_blank"></a>
        </li>
      </ul>
    </li>
    <li>
      <a href="{{url({'for' : 'backend/common/loginOut'})}}">
        <span class="hidden-xs hidden-sm hidden-md">安全退出</span>
        <i class="fa fa-sign-out fa-lg"></i>
      </a>
    </li>
    </ul>
</header>
