Installation
============

.. caution::

    To use this bundle, you first need to install and configure `Gotenberg 7.x`_.

Install the bundle using composer :

.. code-block:: bash

    composer require sensiolabs/gotenberg-bundle

If not using Symfony Flex, enable the bundle by adding it to the list of
registered bundles in the ``config/bundles.php`` file of your project:

.. code-block:: php

    // config/bundles.php

    return [
        // ...
        SensioLabs\GotenbergBundle\SensioLabsGotenbergBundle::class => ['all' => true],
    ];


.. _Gotenberg 7.x: https://gotenberg.dev/
