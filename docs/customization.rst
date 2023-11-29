Customization
=============

Every Builder instance give you the possibility to configure the options,
or override the options you already set in your ``sensiolabs_gotenberg.yml``
before generating it, if your PDF need to have his own style.

.. note::

    All of the next example will be realised with a ``TwigBuilder``, but
    every customization methods belows can be done to every builders.

.. tip::

    For more information about the `defaults properties`_.

Additional Assets
-----------------

You may also send additional files, like images, fonts, stylesheets, and so on.

.. warning::
    The only requirement is that their paths in the template file are on the root level.

.. code-block:: html

    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="utf-8" />
            <title>PDF body</title>
        </head>
        <body>
        <img src="ceo.jpeg" />
        <img src="admin.jpeg" />
            <main>
                <h1>Hello world!</h1>
            </main>
        </body>
    </html>

And you can add it with the ``assets`` method.

.. code-block:: php

    use Sensiolabs\GotenbergBundle\Pdf\Gotenberg;

    $twigPdfBuilder = $gotenberg->twig();
    $twigPdfBuilder
        ->content('path/to/template.html.twig')
        ->assets(
            'assets/images/profiles/ceo.jpeg',
            'assets/images/profiles/admin.jpeg',
        )
        ->generate()

.. tip::

    For more information about `assets`_.

Paper size
----------

You can override the default paper size with (width and height, in inches):

.. code-block:: php

    use Sensiolabs\GotenbergBundle\Pdf\Gotenberg;

    $twigPdfBuilder = $gotenberg->twig();
    $twigPdfBuilder
        ->content('path/to/template.html.twig')
        ->paperSize(8.5, 11);

* Letter - 8.5 x 11 (default)
* Legal - 8.5 x 14
* Tabloid - 11 x 17
* Ledger - 17 x 11
* A0 - 33.1 x 46.8
* A1 - 23.4 x 33.1
* A2 - 16.54 x 23.4
* A3 - 11.7 x 16.54
* A4 - 8.27 x 11.7
* A5 - 5.83 x 8.27
* A6 - 4.13 x 5.83

Prefer CSS page size
--------------------

``default: false``

Define whether to prefer page size as defined by CSS.

.. code-block:: php

    $twigPdfBuilder
        ->content('path/to/template.html.twig')
        ->preferCssPageSize();

Print the background graphics
-----------------------------

``default: false``

.. code-block:: php

    $twigPdfBuilder
        ->content('path/to/template.html.twig')
        ->printBackground();


Hide the default white background
---------------------------------

``default: false``

Hide the default white background and allow generating PDFs with transparency.

.. code-block:: php

    $twigPdfBuilder
        ->content('path/to/template.html.twig')
        ->omitBackground();

.. warning::

    The rules regarding the printBackground and omitBackground form fields are the following:

        If printBackground is set to false, no background is printed.
        If printBackground is set to true:
            If the HTML document has a background, that background is used.
            If not:
                If omitBackground is set to true, the default background is transparent.
                If not, the default white background is used.

Landscape orientation
---------------------

``default: false``

The paper orientation to landscape.

.. code-block:: php

    $twigPdfBuilder
        ->content('path/to/template.html.twig')
        ->landscape();

Scale
-----

``default: '1.0'``

The scale of the page rendering.

.. code-block:: php

    $twigPdfBuilder
        ->content('path/to/template.html.twig')
        ->scale(2.0);

Page ranges
-----------

``default: All pages generated``

Page ranges to print (e.g. 1-5, 8, 11-13).

.. code-block:: php

    $twigPdfBuilder
        ->content('path/to/template.html.twig')
        ->nativePageRanges('1-3');

.. warning::

    If the scope does not exist then an error will be thrown.

Header and footer
-----------------

You can add a header and/or a footer to each page of the PDF:

.. code-block:: php

    $twigPdfBuilder
        ->content('path/to/template.html.twig')
        ->header('path/to/header.html.twig')
        ->footer('path/to/footer.html.twig');

.. tip::

    For more information and restrictions about `Header and footer`_.

Wait delay
----------

``default: None``

When the page relies on JavaScript for rendering, and you don't have access to the page's code,
you may want to wait a certain amount of time to make sure Chromium has fully rendered the page
you're trying to generate.

.. code-block:: php

    $twigPdfBuilder
        ->content('path/to/template.html.twig')
        ->waitDelay('5s');

.. tip::

    For more information about `delay`_.

Wait for expression
-------------------

``default: None``

You may also wait until a given JavaScript expression.

.. code-block:: php

    $twigPdfBuilder
        ->content('path/to/template.html.twig')
        ->waitForExpression("window.globalVar === 'ready'");

.. tip::

    For more information about `wait for expression`_.

Emulated Media Type
-------------------

``default: 'print'``

Some websites have dedicated CSS rules for print. Using ``screen`` allows you to force the "standard" CSS rules.

.. code-block:: php

    $twigPdfBuilder
        ->content('path/to/template.html.twig')
        ->emulatedMediaType('screen');

.. tip::

    For more information about `emulated Media Type`_.

User Agent
----------

``default: None``

Override the default User-Agent header.

.. code-block:: php

    $twigPdfBuilder
        ->content('path/to/template.html.twig')
        ->userAgent("Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1");

.. tip::

    For more information about `custom HTTP headers`_.

Extra HTTP headers
------------------

``default: None``

HTTP headers to send by Chromium while loading the HTML document (JSON format).

.. code-block:: php

    $twigPdfBuilder
        ->content('path/to/template.html.twig')
        ->extraHttpHeaders([
            'MyHeader' => 'MyValue'
        ]);

.. tip::

    For more information about `custom HTTP headers`_.

Console Exceptions
------------------

``default: false``

Return a 409 Conflict response if there are exceptions in the Chromium console.

.. code-block:: php

    $twigPdfBuilder
        ->content('path/to/template.html.twig')
        ->failOnConsoleExceptions();

.. tip::

    For more information about `console Exceptions`_.

PDF Format
----------

``default: None``

Convert the resulting PDF into the given PDF/A format.

.. code-block:: php

    $twigPdfBuilder
        ->content('path/to/template.html.twig')
        ->pdfFormat('PDF/A-2b');

.. tip::

    For more information about `pdf formats`_.

PDF Format
----------

``default: false``

Enable PDF for Universal Access for optimal accessibility.

.. code-block:: php

    $twigPdfBuilder
        ->content('path/to/template.html.twig')
        ->pdfUniversalAccess();

.. tip::

    For more information about `pdf formats`_.

.. _assets: https://gotenberg.dev/docs/routes#html-file-into-pdf-route
.. _defaults properties: https://gotenberg.dev/docs/routes#page-properties-chromium
.. _Header and footer: https://gotenberg.dev/docs/routes#header--footer
.. _delay: https://gotenberg.dev/docs/routes#wait-before-rendering
.. _wait for expression: https://gotenberg.dev/docs/routes#wait-before-rendering
.. _emulated Media Type: https://gotenberg.dev/docs/routes#emulated-media-type
.. _custom HTTP headers: https://gotenberg.dev/docs/routes#custom-http-headers
.. _console Exceptions: https://gotenberg.dev/docs/routes#console-exceptions
.. _pdf formats: https://gotenberg.dev/docs/routes#pdfa-chromium
