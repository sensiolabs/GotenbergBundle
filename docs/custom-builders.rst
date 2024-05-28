Custom builders
===============

Sometimes, depending on your needs, you might require a custom builder.
Here, we will implement an example of a custom builder that will output content in
HTML instead of Twig.
First, you just need to implement the BuilderInterface.

- The method ``getEndpoint``, relative to the desired `API endpoint`.
- The method ``getMultipartFormData``, related to the data to be transformed
into multipart/form-data before sending the request to the API.

.. code-block:: php

    namespace App\Services;

    use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;

    class HtmlPdfBuilder implements BuilderInterface
    {
        private array $multipartFormData = [];

        public function getMultipartFormData(): array
        {
            return $this->multipartFormData;
        }

        public function getEndpoint(): string
        {
            return '/forms/chromium/convert/html';
        }
    }

Subsequently, in this class, we can add as many methods as we wish.

We are adding a method, content(), which adds our HTML file to
the multipartFormData array. In this method, we use the `Mime component`,
which creates a FormDataPart for our future multipart/form-data.

As a reminder, the file we provide to it must follow the `naming rules of Gotenberg`.
Alternatively, you can use the BodyPart enum to rename your file on the fly.

.. code-block:: php

    use Sensiolabs\GotenbergBundle\Enum\Part;
    use Symfony\Component\Mime\Part\DataPart;
    use Symfony\Component\Mime\Part\File;

    class HtmlPdfBuilder implements BuilderInterface
    {
        /**
        * The rest of your code
        */

        public function content(string $path): self
        {
            $dataPart = DataPart::fromPath($path, Part::BodyPart->value);

            $this->multipartFormData[] = [
                'files' => $dataPart,
            ];

            return $this;
        }
    }

We can then use our builder wherever we want. To generate a PDF from it,
simply pass it as an argument to the ``generate`` method of Gotenberg.

.. code-block:: php

    namespace App\Controller;

    use Sensiolabs\GotenbergBundle\Pdf\Gotenberg;

    class YourController
    {
        public function yourControllerMethod(Gotenberg $gotenberg): Response
        {
            $myBuilder = new HtmlPdfBuilder();
            $myBuilder->content('path/to/my-awesome-template.html');

            return $gotenberg->generate($myBuilder);
        }
    }

Potentially, you may want to use methods like ``margins``, ``assets``, ``landscape``, etc.
You just need to add BuilderTrait to your Builder, and all these methods will be available.

By adding that, you can remove the getMultipartFormData() method, which is now part of the Trait
and use everything you want.

.. code-block:: php

    use Sensiolabs\GotenbergBundle\Enum\Part;
    use Symfony\Component\Mime\Part\DataPart;
    use Symfony\Component\Mime\Part\File;

    class HtmlPdfBuilder implements BuilderInterface
    {
        use BuilderTrait;

        public function getEndpoint(): string
        {
            return '/forms/chromium/convert/html';
        }

        public function content(string $path): self
        {
            $dataPart = DataPart::fromPath($path, Part::BodyPart->value);

            $this->multipartFormData[] = [
                'files' => $dataPart,
            ];

            return $this;
        }
    }

.. _API endpoint: https://gotenberg.dev/docs/routes
.. _Mime component: https://symfony.com/doc/current/components/mime.html
.. _naming rules of Gotenberg: https://gotenberg.dev/docs/routes#html-file-into-pdf-route
