###################
Snowcap i18n Bundle
###################

Introduction
============

SnowcapI18nBundle provides several utilities to deal with multi-language website and applications.


Installation and configuration
==============================

Step 1: Install SnowcapAdminBundle using composer
-------------------------------------------------

Using the command line:

.. code-block:: bash

    composer require snowcap/i18n-bundle


Step 2: Enable the bundle
-------------------------

Enable the bundle in the kernel:

.. code-block:: php

    <?php
    // app/AppKernel.php

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Snowcap\I18nBundle\SnowcapI18nBundle(),
        );
    }


Step 3: Configure the bundle
----------------------------

At the very least, you need to declare which locales are available in your project:

.. code-block:: yaml

    # app/config/config.yml

    snowcap_i18n:
        locales: [en, fr]


Contents
========

.. toctree::
    :maxdepth: 2

    interfaces_traits