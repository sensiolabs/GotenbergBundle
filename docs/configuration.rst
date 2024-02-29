Configuration
=============

The default configuration for the bundle looks like :

.. code-block:: yaml

    # app/config/sensiolabs_gotenberg.yml

    sensiolabs_gotenberg:
        base_uri: 'http://localhost:3000'
        default_options:
            pdf_format: null                    # None
            pdf_universal_access: null          # false
        default_chromium_options:
            paper_width: null                   # 8.5
            paper_height: null                  # 11
            margin_top: null                    # 0.39
            margin_bottom: null                 # 0.39
            margin_left: null                   # 0.39
            margin_right: null                  # 0.39
            prefer_css_page_size: null          # false
            print_background: null              # false
            omit_background: null               # false
            landscape: null                     # false
            scale: null                         # 1.0
            native_page_ranges: null            # All pages
            wait_delay: null                    # None
            wait_for_expression: null           # None
            emulated_media_type: null           # 'print'
            user_agent: null                    # None
            extra_http_headers: null            # None
            fail_on_console_exceptions: null    # false
        default_office_options:
            landscape: null                     # false
            native_page_ranges: null            # All pages
            merge                               # false

.. caution::

    If you don't configure anything, the defaults values on Gotenberg API
    will be used.

.. tip::

    For more information about the `defaults properties`_ for Chromium.

Customization
-------------

Extra HTTP headers
~~~~~~~~~~~~~~~~~~

HTTP headers to send by Chromium while loading the HTML document.

.. code-block:: yaml

    sensiolabs_gotenberg:
        base_uri: 'http://localhost:3000'
        options:
            extra_http_headers:
                - { name: 'My-Header', value: 'MyValue' }
                - { name: 'User-Agent', value: 'MyValue' }

.. tip::

    For more information about `custom HTTP headers`_.

.. _defaults properties: https://gotenberg.dev/docs/routes#page-properties-chromium
.. _custom HTTP headers: https://gotenberg.dev/docs/routes#custom-http-headers
