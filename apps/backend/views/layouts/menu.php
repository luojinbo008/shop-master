<nav id="column-left">
    <div id="profile">
        <div>
            <i class="fa fa-shopping-cart"></i>
        </div>
    <div>
        <h4>{{username}}</h4>
        <small>{{rolename}}</small>
      </div>
    </div>
    <ul id="menu">
        {% for menu in menus %}
            {% if menu['isTop'] == 1 %}
                <li>
                    <a class="{% if menu['children'] is defined and menu['children']|length %}parent{% endif %}" {% if menu['for'] is defined and menu['for'] !== '' %}href="{{ url({'for' : menu['for']})}}"{% endif %}>
                        {% if menu['icon'] %}
                            <i class="fa {{menu['icon']}} fa-fw"></i>
                        {% endif %}
                        <span>{{menu['name']}}</span>
                    </a>
                    {% if menu['children'] is defined and  menu['children']|length %}
                        <ul>
                            {% for child in menu['children'] %}
                                {% if child['isTop'] == 1 %}
                                    <li>
                                        <a class="{% if child['children'] is defined and child['children']|length %}parent{% endif %}" {% if child['for'] is defined and child['for'] !== '' %}href="{{ url({'for': child['for']}) }}"{% endif %}>{{child['name']}}</a>
                                        {% if child['children'] is defined and child['children']|length %}
                                            <ul>
                                                {% for child_second in child['children'] %}
                                                    {% if child_second['isTop'] == 1 %}
                                                        <li>
                                                            <a {% if child_second['for'] is defined %}href="{{ url({'for': child_second['for']}) }}"{% endif %}>{{child_second['name']}}</a>
                                                        </li>
                                                    {% endif %}
                                                {% endfor %}
                                            </ul>
                                        {% endif %}
                                    </li>
                                {% endif %}
                            {% endfor %}
                        </ul>
                    {% endif %}
            </li>
            {% endif %}
        {% endfor %}

    </ul>
</nav>
