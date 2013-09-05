Getting started with SnowcapAdminBundle
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
