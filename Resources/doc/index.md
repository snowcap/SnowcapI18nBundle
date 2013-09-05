Getting started with SnowcapI18nBundle
=======================================

SnowcapI18nBundle provides a few useful features for multilingual websites.

## Prerequisites

This version of the bundle requires Symfony 2.3+. If you are using Symfony
2.2.x, please use the 2.2.x branch of the bundle.

## Installation

Installation is a 3 step process:

1. Download SnowcapI18nBundle using composer
2. Enable the Bundle and its dependencies
3. Configure your locales

### Step 1: Download SnowcapAdminBundle using composer

Add SnowcapI18nBundle in your composer.json:

```js
{
    "require": {
        "snowcap/admin-bundle": "dev-master"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update snowcap/i18n-bundle
```

Composer will install the bundle to your project's `vendor/snowcap` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Snowcap\I18nBundle\SnowcapI18nBundle(),
    );
}
```

### Step 3: Configure your locales

```yml
# app/config/config.yml

snowcap_i18n:
    locales: ["fr", "en"]

```

## Route annotations

SnowcapI18nBundle provides a simple annotation class, similar to the one provided by [Sensio FrameworkExtraBundle](http://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/routing.html).

### Basic usage

Importing annotations-based i18n routes is straightforward:

```yml
# app/config/routing.yml

hello_world:
    resource: "@HelloWorldBundle/Controller/"
    type: annotation_i18n

```

In your controllers, use the annotation as you would use the vanilla @Route one:

``` php
<?php
// src/Hello/WorldBundle/Controller/DefaultController.php

use Snowcap\I18nBundle\Annotation\I18nRoute;

/**
 * @I18nRoute("hello", name="say_hello")
 */
public function helloAction)
{
    // ...
}
```

When parsing your controllers, for each @I18nRoute annotation, SnowcapI18nBundle will create one route per locale (as specified in your `config.yml` file).

Assuming we have to locales ("fr" and "en"), the above example will result in the following routes being created:

* say_hello.fr (/fr/say_hello)
* say_hello.en (/en/say_hello)

Please note that internally, **SnowcapI18nBundle replace the default router with its own**. While this will be fine in most cases, 
you will run into trouble if you are already using a custom router class.

### Using a non-i18n route among i18n routes

In the above example, every route in @HelloWorldBundle/Controller/ is imported as an i18n route (see the `routing.yml` file). While it is quite practical, you might need to keep a few locale-neutral routes 
in one or more controllers. To do that, simply use the @I18nRoute as follows:

``` php
<?php
// src/Hello/WorldBundle/Controller/DefaultController.php

// ...

/**
 * @I18nRoute("", name="landing_page", i18n=false)
 */
public function landingAction)
{
    // ...
}
```

The above route will be imported as a regular, locale-agnostic route.

## Twig extension

SnowcapI18nBundle comes with a Twig extension that offers a few functions / filters.

**get_active_locales**

The `get_active_locales` twig function returns an associative array of locale iso codes and locale names. It takes an optional single *locale* parameter, that is used to build locale names. By default, the request locale will be used.

A simple example, showing how you could build a language switcher with that function:

```jinja
<ul class="locales">
    {% for locale_code, locale_name in get_active_locales() %}
        <li>
            <a href="{{ path(route_name, route_params|merge({'_locale': locale_code})) }}">
                {{ locale_name }}
            </a>
        </li>
    {% endfor %}
</ul>
```
