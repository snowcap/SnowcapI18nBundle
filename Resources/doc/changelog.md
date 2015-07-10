SnowcapI18nBundle - Changelog
=============================

## 2015-07-10

* When using SnowcapAdminBundle & embedding the i18n_form.html.twig file, you must now also include the i18n_form.js
in your ``base.html.twig`` file in your AdminBundle:

```jinja
{# src/Acme/AdminBundle/Resources/views/base.html.twig #}

{% extends 'SnowcapAdminBundle::admin_base.html.twig' %}

{# ... #}

{% block javascripts %}
    {{ parent() }}
    {% javascripts
        'bundles/snowcapi18n/js/admin/i18n_form.js'
        output='cache/assetic/js/acmeadmin.js'
    %}
        <script type="text/javascript" src="{{ asset_url }}"></script>
    {% endjavascripts %}
{% endblock javascripts %}
```
