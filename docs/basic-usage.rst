Basic Usage
===========

Configuration
-------------

Next you'll need to configure ``base_uri`` in the application's configuration's
file to call your pre-installed Gotenberg API.

.. code-block:: yaml

    # app/config/sensiolabs_gotenberg.yml

    sensiolabs_gotenberg:
        base_uri: '%env(GOTENBERG_URL)%'

Thanks to the autowiring, you can access the ``Gotenberg`` simply by type
hinting it in your controller method or in your service: the service
``sensiolabs_gotenberg`` will be automatically injected.

Requirements
------------

.. caution::

    Every twig templates you pass throw Gotenberg need to have the following structure.
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

After injecting ``Gotenberg`` you only need to call the method ``url``,
who will return a``UrlPdfBuilder`` instance.

``UrlPdfBuilder`` let you add the URL of the page you want to convert into PDF
with the method ``content``.

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

``TwigPdfBuilder`` has Twig environment service in his construct and can access
to your configuration ``default_path`` about your templates. It will be more
simple for you to add your templates and the datas you needs.

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

    MarkdownPdfBuilder use Twig environment.
    Because of the implementation way the markdown is added in the HTML template.
    Gotenberg API wait ``e.g {{ toHTML "file.md" }}`` as synthax to add the file in it.
    To don't ends up with an error you can use `verbatim`_ tag from twig.

So your twig template who receive your markdown file need look like this.

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

    You need to have the same filename between the var in the HTML template and
    the file added in ``markdownFile`` method.

``markdownFile`` wait the directory where your file is located.

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

.. tip::

    For more informations go to Gotenberg documentations. (`Markdown file(s) into PDF`_)


Convert an Office document into PDF
-----------------------------------

``officeFile`` wait the directory where your file is located.

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

As you could see in the last examples, you can easily return the response from the
``generate`` method to stream the response to the client.

If you want to save the file locally, you can use the ``saveTo`` method, and the
content of the PDF will be dump into the file in the directory you written.

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
