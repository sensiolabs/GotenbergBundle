Configuration
=============

The default configuration for the bundle looks like :

.. code-block:: yaml

    # app/config/sensiolabs_gotenberg.yml

    sensiolabs_gotenberg:
        base_uri: 'http://localhost:3000'
        base_directory: '%kernel.project_dir%'
        http_client: null   # Defaults to 'http_client'
        default_options:
            html:
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
                cookies: null                       # None
                extra_http_headers: null            # None
                fail_on_http_status_codes: null     # [499-599]
                fail_on_console_exceptions: null    # false
                skip_network_idle_event: null       # false
                pdf_format: null                    # None
                pdf_universal_access: null          # false
            url:
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
                cookies: null                       # None
                extra_http_headers: null            # None
                fail_on_http_status_codes: null     # [499-599]
                fail_on_console_exceptions: null    # false
                skip_network_idle_event: null       # false
                pdf_format: null                    # None
                pdf_universal_access: null          # false
            markdown:
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
                cookies: null                       # None
                extra_http_headers: null            # None
                fail_on_http_status_codes: null     # [499-599]
                fail_on_console_exceptions: null    # false
                skip_network_idle_event: null       # false
                pdf_format: null                    # None
                pdf_universal_access: null          # false
            office:
                landscape: null                     # false
                native_page_ranges: null            # All pages
                merge: null                         # false
                pdf_format: null                    # None
                pdf_universal_access: null          # false

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
        default_options:
            html:
                extra_http_headers:
                    - { name: 'My-Header', value: 'MyValue' }

.. tip::

    For more information about `custom HTTP headers`_.

Invalid HTTP Status Codes
~~~~~~~~~~~~~~~~~~~~~~~~~

To return a 409 Conflict response if the HTTP status code from the main page is not acceptable.

.. code-block:: yaml

    sensiolabs_gotenberg:
        base_uri: 'http://localhost:3000'
        default_options:
            html:
                fail_on_http_status_codes: [401, 403]

.. tip::

    For more information about `Invalid HTTP Status Codes`_.

Cookies
~~~~~~~

Cookies to store in the Chromium cookie jar.

.. code-block:: yaml

    sensiolabs_gotenberg:
        base_uri: 'http://localhost:3000'
        default_options:
            html:
                cookies:
                    - { name: 'yummy_cookie', value: 'choco', domain: 'example.com' }
                    - { name: 'my_cookie', value: 'symfony', domain: 'symfony.com', secure: true, httpOnly: true, sameSite: 'Lax'  }

.. tip::

    For more information about `custom HTTP headers`_.

.. _defaults properties: https://gotenberg.dev/docs/routes#page-properties-chromium
.. _custom HTTP headers: https://gotenberg.dev/docs/routes#custom-http-headers
.. _Invalid HTTP Status Codes: https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium
