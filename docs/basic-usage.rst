Basic Usage
===========

Configuration
-------------

Next you'll need to configure ``base_uri`` in the application's configuration's
file to call your previously installed Gotenberg API.

.. code-block:: yaml

    # app/config/sensiolabs_gotenberg.yml

    sensiolabs_gotenberg:
        base_uri: '%env(GOTENBERG_URL)%'

Thanks to the autowiring, you can access the ``Gotenberg`` service simply by
type-hinting it in your controller method or in your service: the service
``sensiolabs_gotenberg`` will be automatically injected.

Requirements
------------

.. caution::

    Every twig templates you pass to Gotenberg need to have the following structure.
    Even Header or Footer parts.

    .. code-block:: html

        <!DOCTYPE html>
        <html lang="en">
          <head>
            <meta charset="utf-8" />
            <title>My PDF</title>
          </head>
          <body>
            <h1>Hello world!</h1>
          </body>
        </html>

.. tip::

    For more informations go to Gotenberg documentations. (`HTML file into PDF`_)

Convert an HTML into PDF
------------------------

After injecting ``Gotenberg`` you simply need to call the method ``url``,
which will return a ``UrlPdfBuilder`` instance.

``UrlPdfBuilder`` lets you pass the URL of the page you want to convert into PDF
to the method ``content``.

.. code-block:: php

    <?php

    namespace App\Controller;

    use Sensiolabs\GotenbergBundle\Pdf\Gotenberg;

    class YourController
    {
        public function yourControllerMethod(Gotenberg $gotenberg): Response
        {
            $urlPdfBuilder = $gotenberg->url();
            return $urlPdfBuilder
                ->content('https://gotenberg.dev/docs/routes')
                ->generate(); // will return directly a stream response
        }
    }

.. tip::

    For more informations go to Gotenberg documentations. (`URL into PDF`_)

Convert a Twig template into PDF
--------------------------------

.. warning::

    To use this builder, you first need to install Twig Environment which is an
    optional service, with the composer command ``composer require symfony/twig-bundle``.

``TwigPdfBuilder`` comes with the Twig Environment service and thus has access
to your your templates ``default_path`` configuration. This makes it easy to
pass the name of the templates to the builder. You can also pass
variables to the templates through the second argument of the methods
``content``, ``header`` and ``footer``.

.. code-block:: php

    <?php

    namespace App\Controller;

    use Sensiolabs\GotenbergBundle\Pdf\Gotenberg;

    class YourController
    {
        public function yourControllerMethod(Gotenberg $gotenberg): Response
        {
            $datas = // Retrieve some datas

            $twigPdfBuilder = $gotenberg->twig();
            return $twigPdfBuilder
                ->content('pdf/body.html.twig', ['invoice' => $invoiceReadModel])
                ->header('pdf/header.html.twig', ['invoice' => $invoiceReadModel])
                ->footer('pdf/footer.html.twig')
                ->generate();  // will return directly a stream response
        }
    }

.. tip::

    For more informations go to Gotenberg documentations. (`HTML file into PDF`_)

Convert Markdown into PDF
-------------------------

.. caution::

    MarkdownPdfBuilder uses Twig.

The ``MarkdownBuilder`` class has a ``content`` method that takes the name of
the Twig template that will receive the Markdown, and ``markdownFile`` method
that expects the local path of the Markdown file to convert.


.. code-block:: php

    <?php

    namespace App\Controller;

    use Sensiolabs\GotenbergBundle\Pdf\Gotenberg;

    class YourController extends AbstractController
    {
        public function yourControllerMethod(Gotenberg $gotenberg): Response
        {
            $markdownBuilder = $gotenberg->markdown();
            return $markdownBuilder
                ->content('pdf/markdown.html.twig', ['invoice' => $invoiceReadModel])
                ->markdownFile('templates/intranet/pdf/file.md')
                ->generate();  // will return directly a stream response
        }
    }

In the template, you can use the ``{{ toHTML "filename.md" }}`` special directive to reference the
Markdown file. The twig template that receives your markdown file will look like this.

    .. code-block:: html

        <!doctype html>
        <html lang="en">
                <head>
                    <meta charset="utf-8">
                    <title>My PDF</title>
                </head>
            <body>
                {% verbatim %}
                    {{ toHTML "file.md" }}
                {% endverbatim %}
            </body>
        </html>

.. warning::
    Gotenberg expects an HTML template containing the directive ``{{ toHTML "file.md" }}``.
    To prevent any conflict, you may want to use the `verbatim`_ tag to
    encapsulate the directive.

    You need to have the same filename between the var in the HTML template and
    the file added in ``markdownFile`` method.

.. tip::

    For more informations go to Gotenberg documentations. (`Markdown file(s) into PDF`_)


Convert an Office document into PDF
-----------------------------------

To convert an Office file to pdf, just pass the file's path to the ``OfficePdfBuilder::officeFile`` method.

.. code-block:: php

    <?php

    namespace App\Controller;

    use Sensiolabs\GotenbergBundle\Pdf\Gotenberg;

    class YourController
    {
        public function yourControllerMethod(Gotenberg $gotenberg): Response
        {
            $office = $gotenberg->office();
            return $office
                ->officeFile('assets/no_name.odt')
                ->generate();  // will return directly a stream response
        }
    }

.. tip::

    For more informations about the extensions supported or more go to Gotenberg
    documentations. (`Convert with LibreOffice`_)

Save the generated PDF
----------------------

As you can see in the examples above, you can easily return the response from the
``generate`` method to stream the response to the client.

If you'd rather save the file locally, you can use the ``saveTo`` method.
It takes the target's file path as argument, and the content of the PDF will be
dumped into this file.

.. code-block:: php

    <?php

    namespace App\Controller;

    use Sensiolabs\GotenbergBundle\Pdf\Gotenberg;

    class YourController
    {
        public function yourControllerMethod(Gotenberg $gotenberg): Response
        {
            $datas = // Retrieve some datas

            $twigPdfBuilder = $gotenberg->twig();
            $twigPdfBuilder
                ->content('pdf/body.html.twig', ['datas' => $datas])
                ->header('pdf/header.html.twig', ['datas' => $datas])
                ->footer('pdf/footer.html.twig')
                ->assets(
                    'assets/images/profiles/ceo.jpeg',
                    'assets/images/profiles/admin.jpeg',
                )
                ->pdfFormat(PdfFormat::Pdf2b->value)
                ->generate()
                ->saveTo('path/to/myAwesome.pdf');

            /**
             * The rest of your code
             */
        }
    }

.. _URL into PDF: https://gotenberg.dev/docs/routes#url-into-pdf-route
.. _HTML file into PDF: https://gotenberg.dev/docs/routes#html-file-into-pdf-route
.. _verbatim: https://twig.symfony.com/doc/3.x/tags/verbatim.html
.. _Markdown file(s) into PDF: https://gotenberg.dev/docs/routes#markdown-files-into-pdf-route
.. _Convert with LibreOffice: https://gotenberg.dev/docs/routes#convert-with-libreoffice
