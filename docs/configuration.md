# Configuration

The default configuration for the bundle looks like :

> [!WARNING]  
> If you don't configure anything or configure `null` / `[]`, 
> the defaults values on Gotenberg API will be used.

Assuming you have the following client configured.

```yaml

# app/config/framework.yaml

framework:
    http_client:
        scoped_clients:
            gotenberg.client:
                base_uri: 'http://localhost:3000'
```

Then

```yaml
# app/config/sensiolabs_gotenberg.yaml

# Default configuration for extension with alias: "sensiolabs_gotenberg"
sensiolabs_gotenberg:

    # Base directory will be used for assets, files, markdown
    assets_directory:     '%kernel.project_dir%/assets'

    # HTTP Client reference to use. (Must have a base_uri)
    http_client:          ~ # Required

    # Override the request Gotenberg will make to call one of your routes.
    request_context:

        # Used only when using `->route()`. Overrides the guessed `base_url` from the request. May be useful in CLI.
        base_uri:             ~

    # Enables the listener on kernel.view to stream GotenbergFileResult object.
    controller_listener:  true
    webhook:

        # Prototype
        name:
            name:                 ~
            success:

                # The URL to call.
                url:                  ~

                # Route configuration.
                route:                ~

                  # Examples:
                  # - 'https://webhook.site/#!/view/{some-token}'
                  # - [my_route, { param1: value1, param2: value2 }]

                # HTTP method to use on that endpoint.
                method:               null # One of "POST"; "PUT"; "PATCH"
            error:

                # The URL to call.
                url:                  ~

                # Route configuration.
                route:                ~

                  # Examples:
                  # - 'https://webhook.site/#!/view/{some-token}'
                  # - [my_route, { param1: value1, param2: value2 }]

                # HTTP method to use on that endpoint.
                method:               null # One of "POST"; "PUT"; "PATCH"

            # HTTP headers to send back to both success and error endpoints - default None. https://gotenberg.dev/docs/webhook
            extra_http_headers:

                # Prototype
                name:
                    name:                 ~
                    value:                ~
    default_options:

        # Webhook configuration name.
        webhook:              ~
        pdf:
            html:

                # Add default header to the builder.
                header:

                    # Default header twig template to apply.
                    template:             null

                    # Default context for header twig template.
                    context:              []

                # Add default footer to the builder.
                footer:

                    # Default footer twig template to apply.
                    template:             null

                    # Default context for footer twig template.
                    context:              []

                # Define whether to print the entire content in one single page. - default false. https://gotenberg.dev/docs/routes#page-properties-chromium
                single_page:          null

                # The standard paper size to use, either "letter", "legal", "tabloid", "ledger", "A0", "A1", "A2", "A3", "A4", "A5", "A6" - default None.
                paper_standard_size:  null # One of "letter"; "legal"; "tabloid"; "ledger"; "A0"; "A1"; "A2"; "A3"; "A4"; "A5"; "A6"

                # Paper width, in inches - default 8.5. https://gotenberg.dev/docs/routes#page-properties-chromium
                paper_width:          null

                # Paper height, in inches - default 11. https://gotenberg.dev/docs/routes#page-properties-chromium
                paper_height:         null

                # Top margin, in inches - default 0.39. https://gotenberg.dev/docs/routes#page-properties-chromium
                margin_top:           null

                # Bottom margin, in inches - default 0.39. https://gotenberg.dev/docs/routes#page-properties-chromium
                margin_bottom:        null

                # Left margin, in inches - default 0.39. https://gotenberg.dev/docs/routes#page-properties-chromium
                margin_left:          null

                # Right margin, in inches - default 0.39. https://gotenberg.dev/docs/routes#page-properties-chromium
                margin_right:         null

                # Define whether to prefer page size as defined by CSS - default false. https://gotenberg.dev/docs/routes#page-properties-chromium
                prefer_css_page_size: null

                # Print the background graphics - default false. https://gotenberg.dev/docs/routes#page-properties-chromium
                print_background:     null

                # Hide the default white background and allow generating PDFs with transparency - default false. https://gotenberg.dev/docs/routes#page-properties-chromium
                omit_background:      null

                # The paper orientation to landscape - default false. https://gotenberg.dev/docs/routes#page-properties-chromium
                landscape:            null

                # The scale of the page rendering (e.g., 1.0) - default 1.0. https://gotenberg.dev/docs/routes#page-properties-chromium
                scale:                null

                # Page ranges to print, e.g., "1-5, 8, 11-13" - default All pages. https://gotenberg.dev/docs/routes#page-properties-chromium
                native_page_ranges:   null

                # Duration (e.g, "5s") to wait when loading an HTML document before converting it into PDF - default None. https://gotenberg.dev/docs/routes#wait-before-rendering
                wait_delay:           null

                # The JavaScript expression to wait before converting an HTML document into PDF until it returns true - default None. https://gotenberg.dev/docs/routes#wait-before-rendering
                wait_for_expression:  null

                # The media type to emulate, either "screen" or "print" - default "print". https://gotenberg.dev/docs/routes#emulated-media-type
                emulated_media_type:  null # One of "print"; "screen"

                # Cookies to store in the Chromium cookie jar - default None. https://gotenberg.dev/docs/routes#cookies-chromium
                cookies:

                    # Prototype
                    -
                        name:                 ~
                        value:                ~
                        domain:               ~
                        path:                 null
                        secure:               null
                        httpOnly:             null

                        # Accepted values are "Strict", "Lax" or "None". https://gotenberg.dev/docs/routes#cookies-chromium
                        sameSite:             null # One of "Strict"; "Lax"; "None"

                # Override the default User-Agent HTTP header. - default None. https://gotenberg.dev/docs/routes#custom-http-headers-chromium
                user_agent:           null

                # HTTP headers to send by Chromium while loading the HTML document - default None. https://gotenberg.dev/docs/routes#custom-http-headers
                extra_http_headers:

                    # Prototype
                    name:
                        name:                 ~
                        value:                ~

                # Return a 409 Conflict response if the HTTP status code from the main page is not acceptable. - default [499,599]. https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium
                fail_on_http_status_codes:

                    # Defaults:
                    - 499
                    - 599

                # Return a 409 Conflict response if there are exceptions in the Chromium console - default false. https://gotenberg.dev/docs/routes#console-exceptions
                fail_on_console_exceptions: null

                # Do not wait for Chromium network to be idle. - default false. https://gotenberg.dev/docs/routes#performance-mode-chromium
                skip_network_idle_event: null

                # The metadata to write. Not all metadata are writable. Consider taking a look at https://exiftool.org/TagNames/XMP.html#pdf for an (exhaustive?) list of available metadata.
                metadata:
                    Author:               ~
                    Copyright:            ~
                    CreationDate:         ~
                    Creator:              ~
                    Keywords:             ~
                    Marked:               ~
                    ModDate:              ~
                    PDFVersion:           ~
                    Producer:             ~
                    Subject:              ~
                    Title:                ~
                    Trapped:              ~ # One of "True"; "False"; "Unknown"

                # URLs to download files from (JSON format). - default None. https://gotenberg.dev/docs/routes#download-from
                download_from:

                    # Prototype
                    -
                        url:                  ~
                        extraHttpHeaders:

                            # Prototype
                            name:
                                name:                 ~
                                value:                ~

                # Convert PDF into the given PDF/A format - default None.
                pdf_format:           null # One of "PDF\/A-1b"; "PDF\/A-2b"; "PDF\/A-3b"

                # Enable PDF for Universal Access for optimal accessibility - default false.
                pdf_universal_access: null

                # Either intervals or pages. - default None. https://gotenberg.dev/docs/routes#split-chromium
                split_mode: null

                # Either the intervals or the page ranges to extract, depending on the selected mode. - default None. https://gotenberg.dev/docs/routes#split-chromium
                split_span: null

                # Specify whether to put extracted pages into a single file or as many files as there are page ranges. Only works with pages mode. - default false. https://gotenberg.dev/docs/routes#split-chromium
                split_unify: null

                # Webhook configuration name or definition.
                webhook:

                    # The name of the webhook configuration to use.
                    config_name:          ~
                    success:

                        # The URL to call.
                        url:                  ~

                        # Route configuration.
                        route:                ~

                          # Examples:
                          # - 'https://webhook.site/#!/view/{some-token}'
                          # - [my_route, { param1: value1, param2: value2 }]

                        # HTTP method to use on that endpoint.
                        method:               null # One of "POST"; "PUT"; "PATCH"
                    error:

                        # The URL to call.
                        url:                  ~

                        # Route configuration.
                        route:                ~

                          # Examples:
                          # - 'https://webhook.site/#!/view/{some-token}'
                          # - [my_route, { param1: value1, param2: value2 }]

                        # HTTP method to use on that endpoint.
                        method:               null # One of "POST"; "PUT"; "PATCH"

                    # HTTP headers to send back to both success and error endpoints - default None. https://gotenberg.dev/docs/webhook
                    extra_http_headers:

                        # Example:
                        # - { name: X-Custom-Header, value: custom-header-value }

                        # Prototype
                        name:
                            name:                 ~
                            value:                ~
            url:

                # Add default header to the builder.
                header:

                    # Default header twig template to apply.
                    template:             null

                    # Default context for header twig template.
                    context:              []

                # Add default footer to the builder.
                footer:

                    # Default footer twig template to apply.
                    template:             null

                    # Default context for footer twig template.
                    context:              []

                # Define whether to print the entire content in one single page. - default false. https://gotenberg.dev/docs/routes#page-properties-chromium
                single_page:          null

                # The standard paper size to use, either "letter", "legal", "tabloid", "ledger", "A0", "A1", "A2", "A3", "A4", "A5", "A6" - default None.
                paper_standard_size:  null # One of "letter"; "legal"; "tabloid"; "ledger"; "A0"; "A1"; "A2"; "A3"; "A4"; "A5"; "A6"

                # Paper width, in inches - default 8.5. https://gotenberg.dev/docs/routes#page-properties-chromium
                paper_width:          null

                # Paper height, in inches - default 11. https://gotenberg.dev/docs/routes#page-properties-chromium
                paper_height:         null

                # Top margin, in inches - default 0.39. https://gotenberg.dev/docs/routes#page-properties-chromium
                margin_top:           null

                # Bottom margin, in inches - default 0.39. https://gotenberg.dev/docs/routes#page-properties-chromium
                margin_bottom:        null

                # Left margin, in inches - default 0.39. https://gotenberg.dev/docs/routes#page-properties-chromium
                margin_left:          null

                # Right margin, in inches - default 0.39. https://gotenberg.dev/docs/routes#page-properties-chromium
                margin_right:         null

                # Define whether to prefer page size as defined by CSS - default false. https://gotenberg.dev/docs/routes#page-properties-chromium
                prefer_css_page_size: null

                # Print the background graphics - default false. https://gotenberg.dev/docs/routes#page-properties-chromium
                print_background:     null

                # Hide the default white background and allow generating PDFs with transparency - default false. https://gotenberg.dev/docs/routes#page-properties-chromium
                omit_background:      null

                # The paper orientation to landscape - default false. https://gotenberg.dev/docs/routes#page-properties-chromium
                landscape:            null

                # The scale of the page rendering (e.g., 1.0) - default 1.0. https://gotenberg.dev/docs/routes#page-properties-chromium
                scale:                null

                # Page ranges to print, e.g., "1-5, 8, 11-13" - default All pages. https://gotenberg.dev/docs/routes#page-properties-chromium
                native_page_ranges:   null

                # Duration (e.g, "5s") to wait when loading an HTML document before converting it into PDF - default None. https://gotenberg.dev/docs/routes#wait-before-rendering
                wait_delay:           null

                # The JavaScript expression to wait before converting an HTML document into PDF until it returns true - default None. https://gotenberg.dev/docs/routes#wait-before-rendering
                wait_for_expression:  null

                # The media type to emulate, either "screen" or "print" - default "print". https://gotenberg.dev/docs/routes#emulated-media-type
                emulated_media_type:  null # One of "print"; "screen"

                # Cookies to store in the Chromium cookie jar - default None. https://gotenberg.dev/docs/routes#cookies-chromium
                cookies:

                    # Prototype
                    -
                        name:                 ~
                        value:                ~
                        domain:               ~
                        path:                 null
                        secure:               null
                        httpOnly:             null

                        # Accepted values are "Strict", "Lax" or "None". https://gotenberg.dev/docs/routes#cookies-chromium
                        sameSite:             null # One of "Strict"; "Lax"; "None"

                # Override the default User-Agent HTTP header. - default None. https://gotenberg.dev/docs/routes#custom-http-headers-chromium
                user_agent:           null

                # HTTP headers to send by Chromium while loading the HTML document - default None. https://gotenberg.dev/docs/routes#custom-http-headers
                extra_http_headers: []
                
                    # Example:
                    # 'X-Custom-Header': 'custom-header-value'

                    # Or the syntax below is also possible
                    # - { name: 'X-Custom-Header', value: 'custom-header-value' }

                # Return a 409 Conflict response if the HTTP status code from the main page is not acceptable. - default [499,599]. https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium
                fail_on_http_status_codes:

                    # Defaults:
                    - 499
                    - 599

                # Return a 409 Conflict response if there are exceptions in the Chromium console - default false. https://gotenberg.dev/docs/routes#console-exceptions
                fail_on_console_exceptions: null

                # Do not wait for Chromium network to be idle. - default false. https://gotenberg.dev/docs/routes#performance-mode-chromium
                skip_network_idle_event: null

                # The metadata to write. Not all metadata are writable. Consider taking a look at https://exiftool.org/TagNames/XMP.html#pdf for an (exhaustive?) list of available metadata.
                metadata:
                    Author:               ~
                    Copyright:            ~
                    CreationDate:         ~
                    Creator:              ~
                    Keywords:             ~
                    Marked:               ~
                    ModDate:              ~
                    PDFVersion:           ~
                    Producer:             ~
                    Subject:              ~
                    Title:                ~
                    Trapped:              ~ # One of "True"; "False"; "Unknown"

                # URLs to download files from (JSON format). - default None. https://gotenberg.dev/docs/routes#download-from
                download_from:

                    # Prototype
                    -
                        url:                  ~
                        extraHttpHeaders:

                            # Prototype
                            name:
                                name:                 ~
                                value:                ~

                # Convert PDF into the given PDF/A format - default None.
                pdf_format:           null # One of "PDF\/A-1b"; "PDF\/A-2b"; "PDF\/A-3b"

                # Enable PDF for Universal Access for optimal accessibility - default false.
                pdf_universal_access: null

                # Either intervals or pages. - default None. https://gotenberg.dev/docs/routes#split-chromium
                split_mode: null

                # Either the intervals or the page ranges to extract, depending on the selected mode. - default None. https://gotenberg.dev/docs/routes#split-chromium
                split_span: null

                # Specify whether to put extracted pages into a single file or as many files as there are page ranges. Only works with pages mode. - default false. https://gotenberg.dev/docs/routes#split-chromium
                split_unify: null

                # Webhook configuration name or definition.
                webhook:

                    # The name of the webhook configuration to use.
                    config_name:          ~
                    success:

                        # The URL to call.
                        url:                  ~

                        # Route configuration.
                        route:                ~

                          # Examples:
                          # - 'https://webhook.site/#!/view/{some-token}'
                          # - [my_route, { param1: value1, param2: value2 }]

                        # HTTP method to use on that endpoint.
                        method:               null # One of "POST"; "PUT"; "PATCH"
                    error:

                        # The URL to call.
                        url:                  ~

                        # Route configuration.
                        route:                ~

                          # Examples:
                          # - 'https://webhook.site/#!/view/{some-token}'
                          # - [my_route, { param1: value1, param2: value2 }]

                        # HTTP method to use on that endpoint.
                        method:               null # One of "POST"; "PUT"; "PATCH"

                    # HTTP headers to send back to both success and error endpoints - default None. https://gotenberg.dev/docs/webhook
                    extra_http_headers: []

                        # Example:
                        # 'X-Custom-Header': 'custom-header-value'
    
                        # Or the syntax below is also possible
                        # - { name: 'X-Custom-Header', value: 'custom-header-value' }
            markdown:

                # Add default header to the builder.
                header:

                    # Default header twig template to apply.
                    template:             null

                    # Default context for header twig template.
                    context:              []

                # Add default footer to the builder.
                footer:

                    # Default footer twig template to apply.
                    template:             null

                    # Default context for footer twig template.
                    context:              []

                # Define whether to print the entire content in one single page. - default false. https://gotenberg.dev/docs/routes#page-properties-chromium
                single_page:          null

                # The standard paper size to use, either "letter", "legal", "tabloid", "ledger", "A0", "A1", "A2", "A3", "A4", "A5", "A6" - default None.
                paper_standard_size:  null # One of "letter"; "legal"; "tabloid"; "ledger"; "A0"; "A1"; "A2"; "A3"; "A4"; "A5"; "A6"

                # Paper width, in inches - default 8.5. https://gotenberg.dev/docs/routes#page-properties-chromium
                paper_width:          null

                # Paper height, in inches - default 11. https://gotenberg.dev/docs/routes#page-properties-chromium
                paper_height:         null

                # Top margin, in inches - default 0.39. https://gotenberg.dev/docs/routes#page-properties-chromium
                margin_top:           null

                # Bottom margin, in inches - default 0.39. https://gotenberg.dev/docs/routes#page-properties-chromium
                margin_bottom:        null

                # Left margin, in inches - default 0.39. https://gotenberg.dev/docs/routes#page-properties-chromium
                margin_left:          null

                # Right margin, in inches - default 0.39. https://gotenberg.dev/docs/routes#page-properties-chromium
                margin_right:         null

                # Define whether to prefer page size as defined by CSS - default false. https://gotenberg.dev/docs/routes#page-properties-chromium
                prefer_css_page_size: null

                # Print the background graphics - default false. https://gotenberg.dev/docs/routes#page-properties-chromium
                print_background:     null

                # Hide the default white background and allow generating PDFs with transparency - default false. https://gotenberg.dev/docs/routes#page-properties-chromium
                omit_background:      null

                # The paper orientation to landscape - default false. https://gotenberg.dev/docs/routes#page-properties-chromium
                landscape:            null

                # The scale of the page rendering (e.g., 1.0) - default 1.0. https://gotenberg.dev/docs/routes#page-properties-chromium
                scale:                null

                # Page ranges to print, e.g., "1-5, 8, 11-13" - default All pages. https://gotenberg.dev/docs/routes#page-properties-chromium
                native_page_ranges:   null

                # Duration (e.g, "5s") to wait when loading an HTML document before converting it into PDF - default None. https://gotenberg.dev/docs/routes#wait-before-rendering
                wait_delay:           null

                # The JavaScript expression to wait before converting an HTML document into PDF until it returns true - default None. https://gotenberg.dev/docs/routes#wait-before-rendering
                wait_for_expression:  null

                # The media type to emulate, either "screen" or "print" - default "print". https://gotenberg.dev/docs/routes#emulated-media-type
                emulated_media_type:  null # One of "print"; "screen"

                # Cookies to store in the Chromium cookie jar - default None. https://gotenberg.dev/docs/routes#cookies-chromium
                cookies:

                    # Prototype
                    -
                        name:                 ~
                        value:                ~
                        domain:               ~
                        path:                 null
                        secure:               null
                        httpOnly:             null

                        # Accepted values are "Strict", "Lax" or "None". https://gotenberg.dev/docs/routes#cookies-chromium
                        sameSite:             null # One of "Strict"; "Lax"; "None"

                # Override the default User-Agent HTTP header. - default None. https://gotenberg.dev/docs/routes#custom-http-headers-chromium
                user_agent:           null

                # HTTP headers to send by Chromium while loading the HTML document - default None. https://gotenberg.dev/docs/routes#custom-http-headers
                extra_http_headers: []
                
                    # Example:
                    # 'X-Custom-Header': 'custom-header-value'

                    # Or the syntax below is also possible
                    # - { name: 'X-Custom-Header', value: 'custom-header-value' }

                # Return a 409 Conflict response if the HTTP status code from the main page is not acceptable. - default [499,599]. https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium
                fail_on_http_status_codes:

                    # Defaults:
                    - 499
                    - 599

                # Return a 409 Conflict response if there are exceptions in the Chromium console - default false. https://gotenberg.dev/docs/routes#console-exceptions
                fail_on_console_exceptions: null

                # Do not wait for Chromium network to be idle. - default false. https://gotenberg.dev/docs/routes#performance-mode-chromium
                skip_network_idle_event: null

                # The metadata to write. Not all metadata are writable. Consider taking a look at https://exiftool.org/TagNames/XMP.html#pdf for an (exhaustive?) list of available metadata.
                metadata:
                    Author:               ~
                    Copyright:            ~
                    CreationDate:         ~
                    Creator:              ~
                    Keywords:             ~
                    Marked:               ~
                    ModDate:              ~
                    PDFVersion:           ~
                    Producer:             ~
                    Subject:              ~
                    Title:                ~
                    Trapped:              ~ # One of "True"; "False"; "Unknown"

                # URLs to download files from (JSON format). - default None. https://gotenberg.dev/docs/routes#download-from
                download_from:

                    # Prototype
                    -
                        url:                  ~
                        extraHttpHeaders:

                            # Prototype
                            name:
                                name:                 ~
                                value:                ~

                # Convert PDF into the given PDF/A format - default None.
                pdf_format:           null # One of "PDF\/A-1b"; "PDF\/A-2b"; "PDF\/A-3b"

                # Enable PDF for Universal Access for optimal accessibility - default false.
                pdf_universal_access: null

                # Either intervals or pages. - default None. https://gotenberg.dev/docs/routes#split-chromium
                split_mode: null

                # Either the intervals or the page ranges to extract, depending on the selected mode. - default None. https://gotenberg.dev/docs/routes#split-chromium
                split_span: null

                # Specify whether to put extracted pages into a single file or as many files as there are page ranges. Only works with pages mode. - default false. https://gotenberg.dev/docs/routes#split-chromium
                split_unify: null

                # Webhook configuration name or definition.
                webhook:

                    # The name of the webhook configuration to use.
                    config_name:          ~
                    success:

                        # The URL to call.
                        url:                  ~

                        # Route configuration.
                        route:                ~

                          # Examples:
                          # - 'https://webhook.site/#!/view/{some-token}'
                          # - [my_route, { param1: value1, param2: value2 }]

                        # HTTP method to use on that endpoint.
                        method:               null # One of "POST"; "PUT"; "PATCH"
                    error:

                        # The URL to call.
                        url:                  ~

                        # Route configuration.
                        route:                ~

                          # Examples:
                          # - 'https://webhook.site/#!/view/{some-token}'
                          # - [my_route, { param1: value1, param2: value2 }]

                        # HTTP method to use on that endpoint.
                        method:               null # One of "POST"; "PUT"; "PATCH"

                    # HTTP headers to send back to both success and error endpoints - default None. https://gotenberg.dev/docs/webhook
                    extra_http_headers: []

                        # Example:
                        # 'X-Custom-Header': 'custom-header-value'
    
                        # Or the syntax below is also possible
                        # - { name: 'X-Custom-Header', value: 'custom-header-value' }
            office:

                # Set the password for opening the source file. https://gotenberg.dev/docs/routes#page-properties-libreoffice
                password:             null

                # The paper orientation to landscape - default false. https://gotenberg.dev/docs/routes#page-properties-chromium
                landscape:            null

                # Page ranges to print, e.g., "1-5, 8, 11-13" - default All pages. https://gotenberg.dev/docs/routes#page-properties-chromium
                native_page_ranges:   null

                # Set whether to export the form fields or to use the inputted/selected content of the fields. - default true. https://gotenberg.dev/docs/routes#page-properties-libreoffice
                do_not_export_form_fields: null

                # Set whether to render the entire spreadsheet as a single page. - default false. https://gotenberg.dev/docs/routes#page-properties-libreoffice
                single_page_sheets:   null

                # Merge alphanumerically the resulting PDFs. - default false. https://gotenberg.dev/docs/routes#merge-libreoffice
                merge:                null

                # The metadata to write. Not all metadata are writable. Consider taking a look at https://exiftool.org/TagNames/XMP.html#pdf for an (exhaustive?) list of available metadata.
                metadata:
                    Author:               ~
                    Copyright:            ~
                    CreationDate:         ~
                    Creator:              ~
                    Keywords:             ~
                    Marked:               ~
                    ModDate:              ~
                    PDFVersion:           ~
                    Producer:             ~
                    Subject:              ~
                    Title:                ~
                    Trapped:              ~ # One of "True"; "False"; "Unknown"

                # Specify whether multiple form fields exported are allowed to have the same field name. - default false. https://gotenberg.dev/docs/routes#page-properties-libreoffice
                allow_duplicate_field_names: null

                # Specify if bookmarks are exported to PDF. - default true. https://gotenberg.dev/docs/routes#page-properties-libreoffice
                do_not_export_bookmarks: null

                # Specify that the bookmarks contained in the source LibreOffice file should be exported to the PDF file as Named Destination. - default false. https://gotenberg.dev/docs/routes#page-properties-libreoffice
                export_bookmarks_to_pdf_destination: null

                # Export the placeholders fields visual markings only. The exported placeholder is ineffective. - default false. https://gotenberg.dev/docs/routes#page-properties-libreoffice
                export_placeholders:  null

                # Specify if notes are exported to PDF. - default false. https://gotenberg.dev/docs/routes#page-properties-libreoffice
                export_notes:         null

                # Specify if notes pages are exported to PDF. Notes pages are available in Impress documents only. - default false. https://gotenberg.dev/docs/routes#page-properties-libreoffice
                export_notes_pages:   null

                # Specify, if the form field exportNotesPages is set to true, if only notes pages are exported to PDF. - default false. https://gotenberg.dev/docs/routes#page-properties-libreoffice
                export_only_notes_pages: null

                # Specify if notes in margin are exported to PDF. - default false. https://gotenberg.dev/docs/routes#page-properties-libreoffice
                export_notes_in_margin: null

                # Specify that the target documents with .od[tpgs] extension, will have that extension changed to .pdf when the link is exported to PDF. The source document remains untouched. - default false. https://gotenberg.dev/docs/routes#page-properties-libreoffice
                convert_ooo_target_to_pdf_target: null

                # Specify that the file system related hyperlinks (file:// protocol) present in the document will be exported as relative to the source document location. - default false. https://gotenberg.dev/docs/routes#page-properties-libreoffice
                export_links_relative_fsys: null

                # Export, for LibreOffice Impress, slides that are not included in slide shows. - default false. https://gotenberg.dev/docs/routes#page-properties-libreoffice
                export_hidden_slides: null

                # Specify that automatically inserted empty pages are suppressed. This option is active only if storing Writer documents. - default false. https://gotenberg.dev/docs/routes#page-properties-libreoffice
                skip_empty_pages:     null

                # Specify that a stream is inserted to the PDF file which contains the original document for archiving purposes. - default false. https://gotenberg.dev/docs/routes#page-properties-libreoffice
                add_original_document_as_stream: null

                # Specify if images are exported to PDF using a lossless compression format like PNG or compressed using the JPEG format. - default false. https://gotenberg.dev/docs/routes#images-libreoffice
                lossless_image_compression: null

                # Specify the quality of the JPG export. A higher value produces a higher-quality image and a larger file. Between 1 and 100. - default 90. https://gotenberg.dev/docs/routes#images-libreoffice
                quality:              null

                # Specify if the resolution of each image is reduced to the resolution specified by the form field maxImageResolution. - default false. https://gotenberg.dev/docs/routes#images-libreoffice
                reduce_image_resolution: null

                # If the form field reduceImageResolution is set to true, tell if all images will be reduced to the given value in DPI. Possible values are: 75, 150, 300, 600 and 1200. - default 300. https://gotenberg.dev/docs/routes#images-libreoffice
                max_image_resolution: null # One of 75; 150; 300; 600; 1200

                # URLs to download files from (JSON format). - default None. https://gotenberg.dev/docs/routes#download-from
                download_from:

                    # Prototype
                    -
                        url:                  ~
                        extraHttpHeaders:

                            # Prototype
                            name:
                                name:                 ~
                                value:                ~

                # Convert PDF into the given PDF/A format - default None.
                pdf_format:           null # One of "PDF\/A-1b"; "PDF\/A-2b"; "PDF\/A-3b"

                # Enable PDF for Universal Access for optimal accessibility - default false.
                pdf_universal_access: null
                
                # Either intervals or pages. - default None. https://gotenberg.dev/docs/routes#split-libreoffice
                split_mode: null
                
                # Either the intervals or the page ranges to extract, depending on the selected mode. - default None. https://gotenberg.dev/docs/routes#split-libreoffice
                split_span: null
                
                # Specify whether to put extracted pages into a single file or as many files as there are page ranges. Only works with pages mode. - default false. https://gotenberg.dev/docs/routes#split-libreoffice
                split_unify: null
            merge:

                # Convert PDF into the given PDF/A format - default None.
                pdf_format:           null # One of "PDF\/A-1b"; "PDF\/A-2b"; "PDF\/A-3b"

                # Enable PDF for Universal Access for optimal accessibility - default false.
                pdf_universal_access: null

                # The metadata to write. Not all metadata are writable. Consider taking a look at https://exiftool.org/TagNames/XMP.html#pdf for an (exhaustive?) list of available metadata.
                metadata:
                    Author:               ~
                    Copyright:            ~
                    CreationDate:         ~
                    Creator:              ~
                    Keywords:             ~
                    Marked:               ~
                    ModDate:              ~
                    PDFVersion:           ~
                    Producer:             ~
                    Subject:              ~
                    Title:                ~
                    Trapped:              ~ # One of "True"; "False"; "Unknown"

                # URLs to download files from (JSON format). - default None. https://gotenberg.dev/docs/routes#download-from
                download_from:

                    # Prototype
                    -
                        url:                  ~
                        extraHttpHeaders:

                            # Prototype
                            name:
                                name:                 ~
                                value:                ~
            convert:

                # Convert PDF into the given PDF/A format - default None.
                pdf_format:           null # One of "PDF\/A-1b"; "PDF\/A-2b"; "PDF\/A-3b"

                # Enable PDF for Universal Access for optimal accessibility - default false.
                pdf_universal_access: null

                # URLs to download files from (JSON format). - default None. https://gotenberg.dev/docs/routes#download-from
                download_from:

                    # Prototype
                    -
                        url:                  ~
                        extraHttpHeaders:

                            # Prototype
                            name:
                                name:                 ~
                                value:                ~
            split:
                # Either intervals or pages. - default None. https://gotenberg.dev/docs/routes#split-libreoffice
                split_mode: null

                # Either the intervals or the page ranges to extract, depending on the selected mode. - default None. https://gotenberg.dev/docs/routes#split-libreoffice
                split_span: null

                # Specify whether to put extracted pages into a single file or as many files as there are page ranges. Only works with pages mode. - default false. https://gotenberg.dev/docs/routes#split-libreoffice
                split_unify: null
        screenshot:
            html:

                # The device screen width in pixels. - default 800. https://gotenberg.dev/docs/routes#screenshots-route
                width:                null

                # The device screen height in pixels. - default 600. https://gotenberg.dev/docs/routes#screenshots-route
                height:               null

                # Define whether to clip the screenshot according to the device dimensions - default false. https://gotenberg.dev/docs/routes#screenshots-route
                clip:                 null

                # The image compression format, either "png", "jpeg" or "webp" - default png. https://gotenberg.dev/docs/routes#screenshots-route
                format:               null # One of "png"; "jpeg"; "webp"

                # The compression quality from range 0 to 100 (jpeg only) - default 100. https://gotenberg.dev/docs/routes#screenshots-route
                quality:              null

                # Hide the default white background and allow generating PDFs with transparency - default false. https://gotenberg.dev/docs/routes#page-properties-chromium
                omit_background:      null

                # Define whether to optimize image encoding for speed, not for resulting size. - default false. https://gotenberg.dev/docs/routes#page-properties-chromium
                optimize_for_speed:   null

                # Duration (e.g, "5s") to wait when loading an HTML document before converting it into PDF - default None. https://gotenberg.dev/docs/routes#wait-before-rendering
                wait_delay:           null

                # The JavaScript expression to wait before converting an HTML document into PDF until it returns true - default None. https://gotenberg.dev/docs/routes#wait-before-rendering
                wait_for_expression:  null

                # The media type to emulate, either "screen" or "print" - default "print". https://gotenberg.dev/docs/routes#emulated-media-type
                emulated_media_type:  null # One of "print"; "screen"

                # Cookies to store in the Chromium cookie jar - default None. https://gotenberg.dev/docs/routes#cookies-chromium
                cookies:

                    # Prototype
                    -
                        name:                 ~
                        value:                ~
                        domain:               ~
                        path:                 null
                        secure:               null
                        httpOnly:             null

                        # Accepted values are "Strict", "Lax" or "None". https://gotenberg.dev/docs/routes#cookies-chromium
                        sameSite:             null # One of "Strict"; "Lax"; "None"

                # Override the default User-Agent HTTP header. - default None. https://gotenberg.dev/docs/routes#custom-http-headers-chromium
                user_agent:           null

                # HTTP headers to send by Chromium while loading the HTML document - default None. https://gotenberg.dev/docs/routes#custom-http-headers
                extra_http_headers: []
                
                    # Example:
                    # 'X-Custom-Header': 'custom-header-value'

                    # Or the syntax below is also possible
                    # - { name: 'X-Custom-Header', value: 'custom-header-value' }

                # Return a 409 Conflict response if the HTTP status code from the main page is not acceptable. - default [499,599]. https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium
                fail_on_http_status_codes:

                    # Defaults:
                    - 499
                    - 599

                # Return a 409 Conflict response if there are exceptions in the Chromium console - default false. https://gotenberg.dev/docs/routes#console-exceptions
                fail_on_console_exceptions: null

                # Do not wait for Chromium network to be idle. - default false. https://gotenberg.dev/docs/routes#performance-mode-chromium
                skip_network_idle_event: null

                # URLs to download files from (JSON format). - default None. https://gotenberg.dev/docs/routes#download-from
                download_from:

                    # Prototype
                    -
                        url:                  ~
                        extraHttpHeaders:

                            # Prototype
                            name:
                                name:                 ~
                                value:                ~

                # Webhook configuration name or definition.
                webhook:

                    # The name of the webhook configuration to use.
                    config_name:          ~
                    success:

                        # The URL to call.
                        url:                  ~

                        # Route configuration.
                        route:                ~

                          # Examples:
                          # - 'https://webhook.site/#!/view/{some-token}'
                          # - [my_route, { param1: value1, param2: value2 }]

                        # HTTP method to use on that endpoint.
                        method:               null # One of "POST"; "PUT"; "PATCH"
                    error:

                        # The URL to call.
                        url:                  ~

                        # Route configuration.
                        route:                ~

                          # Examples:
                          # - 'https://webhook.site/#!/view/{some-token}'
                          # - [my_route, { param1: value1, param2: value2 }]

                        # HTTP method to use on that endpoint.
                        method:               null # One of "POST"; "PUT"; "PATCH"

                    # HTTP headers to send back to both success and error endpoints - default None. https://gotenberg.dev/docs/webhook
                    extra_http_headers: []

                        # Example:
                        # 'X-Custom-Header': 'custom-header-value'
    
                        # Or the syntax below is also possible
                        # - { name: 'X-Custom-Header', value: 'custom-header-value' }
            url:

                # The device screen width in pixels. - default 800. https://gotenberg.dev/docs/routes#screenshots-route
                width:                null

                # The device screen height in pixels. - default 600. https://gotenberg.dev/docs/routes#screenshots-route
                height:               null

                # Define whether to clip the screenshot according to the device dimensions - default false. https://gotenberg.dev/docs/routes#screenshots-route
                clip:                 null

                # The image compression format, either "png", "jpeg" or "webp" - default png. https://gotenberg.dev/docs/routes#screenshots-route
                format:               null # One of "png"; "jpeg"; "webp"

                # The compression quality from range 0 to 100 (jpeg only) - default 100. https://gotenberg.dev/docs/routes#screenshots-route
                quality:              null

                # Hide the default white background and allow generating PDFs with transparency - default false. https://gotenberg.dev/docs/routes#page-properties-chromium
                omit_background:      null

                # Define whether to optimize image encoding for speed, not for resulting size. - default false. https://gotenberg.dev/docs/routes#page-properties-chromium
                optimize_for_speed:   null

                # Duration (e.g, "5s") to wait when loading an HTML document before converting it into PDF - default None. https://gotenberg.dev/docs/routes#wait-before-rendering
                wait_delay:           null

                # The JavaScript expression to wait before converting an HTML document into PDF until it returns true - default None. https://gotenberg.dev/docs/routes#wait-before-rendering
                wait_for_expression:  null

                # The media type to emulate, either "screen" or "print" - default "print". https://gotenberg.dev/docs/routes#emulated-media-type
                emulated_media_type:  null # One of "print"; "screen"

                # Cookies to store in the Chromium cookie jar - default None. https://gotenberg.dev/docs/routes#cookies-chromium
                cookies:

                    # Prototype
                    -
                        name:                 ~
                        value:                ~
                        domain:               ~
                        path:                 null
                        secure:               null
                        httpOnly:             null

                        # Accepted values are "Strict", "Lax" or "None". https://gotenberg.dev/docs/routes#cookies-chromium
                        sameSite:             null # One of "Strict"; "Lax"; "None"

                # Override the default User-Agent HTTP header. - default None. https://gotenberg.dev/docs/routes#custom-http-headers-chromium
                user_agent:           null

                # HTTP headers to send by Chromium while loading the HTML document - default None. https://gotenberg.dev/docs/routes#custom-http-headers
                extra_http_headers: []
                
                    # Example:
                    # 'X-Custom-Header': 'custom-header-value'

                    # Or the syntax below is also possible
                    # - { name: 'X-Custom-Header', value: 'custom-header-value' }

                # Return a 409 Conflict response if the HTTP status code from the main page is not acceptable. - default [499,599]. https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium
                fail_on_http_status_codes:

                    # Defaults:
                    - 499
                    - 599

                # Return a 409 Conflict response if there are exceptions in the Chromium console - default false. https://gotenberg.dev/docs/routes#console-exceptions
                fail_on_console_exceptions: null

                # Do not wait for Chromium network to be idle. - default false. https://gotenberg.dev/docs/routes#performance-mode-chromium
                skip_network_idle_event: null

                # URLs to download files from (JSON format). - default None. https://gotenberg.dev/docs/routes#download-from
                download_from:

                    # Prototype
                    -
                        url:                  ~
                        extraHttpHeaders:

                            # Prototype
                            name:
                                name:                 ~
                                value:                ~

                # Webhook configuration name or definition.
                webhook:

                    # The name of the webhook configuration to use.
                    config_name:          ~
                    success:

                        # The URL to call.
                        url:                  ~

                        # Route configuration.
                        route:                ~

                          # Examples:
                          # - 'https://webhook.site/#!/view/{some-token}'
                          # - [my_route, { param1: value1, param2: value2 }]

                        # HTTP method to use on that endpoint.
                        method:               null # One of "POST"; "PUT"; "PATCH"
                    error:

                        # The URL to call.
                        url:                  ~

                        # Route configuration.
                        route:                ~

                          # Examples:
                          # - 'https://webhook.site/#!/view/{some-token}'
                          # - [my_route, { param1: value1, param2: value2 }]

                        # HTTP method to use on that endpoint.
                        method:               null # One of "POST"; "PUT"; "PATCH"

                    # HTTP headers to send back to both success and error endpoints - default None. https://gotenberg.dev/docs/webhook
                    extra_http_headers: []

                        # Example:
                        # 'X-Custom-Header': 'custom-header-value'
    
                        # Or the syntax below is also possible
                        # - { name: 'X-Custom-Header', value: 'custom-header-value' }
            markdown:

                # The device screen width in pixels. - default 800. https://gotenberg.dev/docs/routes#screenshots-route
                width:                null

                # The device screen height in pixels. - default 600. https://gotenberg.dev/docs/routes#screenshots-route
                height:               null

                # Define whether to clip the screenshot according to the device dimensions - default false. https://gotenberg.dev/docs/routes#screenshots-route
                clip:                 null

                # The image compression format, either "png", "jpeg" or "webp" - default png. https://gotenberg.dev/docs/routes#screenshots-route
                format:               null # One of "png"; "jpeg"; "webp"

                # The compression quality from range 0 to 100 (jpeg only) - default 100. https://gotenberg.dev/docs/routes#screenshots-route
                quality:              null

                # Hide the default white background and allow generating PDFs with transparency - default false. https://gotenberg.dev/docs/routes#page-properties-chromium
                omit_background:      null

                # Define whether to optimize image encoding for speed, not for resulting size. - default false. https://gotenberg.dev/docs/routes#page-properties-chromium
                optimize_for_speed:   null

                # Duration (e.g, "5s") to wait when loading an HTML document before converting it into PDF - default None. https://gotenberg.dev/docs/routes#wait-before-rendering
                wait_delay:           null

                # The JavaScript expression to wait before converting an HTML document into PDF until it returns true - default None. https://gotenberg.dev/docs/routes#wait-before-rendering
                wait_for_expression:  null

                # The media type to emulate, either "screen" or "print" - default "print". https://gotenberg.dev/docs/routes#emulated-media-type
                emulated_media_type:  null # One of "print"; "screen"

                # Cookies to store in the Chromium cookie jar - default None. https://gotenberg.dev/docs/routes#cookies-chromium
                cookies:

                    # Prototype
                    -
                        name:                 ~
                        value:                ~
                        domain:               ~
                        path:                 null
                        secure:               null
                        httpOnly:             null

                        # Accepted values are "Strict", "Lax" or "None". https://gotenberg.dev/docs/routes#cookies-chromium
                        sameSite:             null # One of "Strict"; "Lax"; "None"

                # Override the default User-Agent HTTP header. - default None. https://gotenberg.dev/docs/routes#custom-http-headers-chromium
                user_agent:           null

                # HTTP headers to send by Chromium while loading the HTML document - default None. https://gotenberg.dev/docs/routes#custom-http-headers
                extra_http_headers: []

                    # Example:
                    # 'X-Custom-Header': 'custom-header-value'

                    # Or the syntax below is also possible
                    # - { name: 'X-Custom-Header', value: 'custom-header-value' }

                # Return a 409 Conflict response if the HTTP status code from the main page is not acceptable. - default [499,599]. https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium
                fail_on_http_status_codes:

                    # Defaults:
                    - 499
                    - 599

                # Return a 409 Conflict response if there are exceptions in the Chromium console - default false. https://gotenberg.dev/docs/routes#console-exceptions
                fail_on_console_exceptions: null

                # Do not wait for Chromium network to be idle. - default false. https://gotenberg.dev/docs/routes#performance-mode-chromium
                skip_network_idle_event: null

                # URLs to download files from (JSON format). - default None. https://gotenberg.dev/docs/routes#download-from
                download_from:

                    # Prototype
                    -
                        url:                  ~
                        extraHttpHeaders:

                            # Prototype
                            name:
                                name:                 ~
                                value:                ~

                # Webhook configuration name or definition.
                webhook:

                    # The name of the webhook configuration to use.
                    config_name:          ~
                    success:

                        # The URL to call.
                        url:                  ~

                        # Route configuration.
                        route:                ~

                          # Examples:
                          # - 'https://webhook.site/#!/view/{some-token}'
                          # - [my_route, { param1: value1, param2: value2 }]

                        # HTTP method to use on that endpoint.
                        method:               null # One of "POST"; "PUT"; "PATCH"
                    error:

                        # The URL to call.
                        url:                  ~

                        # Route configuration.
                        route:                ~

                          # Examples:
                          # - 'https://webhook.site/#!/view/{some-token}'
                          # - [my_route, { param1: value1, param2: value2 }]

                        # HTTP method to use on that endpoint.
                        method:               null # One of "POST"; "PUT"; "PATCH"

                    # HTTP headers to send back to both success and error endpoints - default None. https://gotenberg.dev/docs/webhook
                    extra_http_headers: []

                        # Example:
                        # 'X-Custom-Header': 'custom-header-value'
                    
                        # Or the syntax below is also possible
                        # - { name: 'X-Custom-Header', value: 'custom-header-value' }
```

> [!TIP]
> For more information about the [PDF properties](https://gotenberg.dev/docs/routes#page-properties-chromium) 
> or [screenshot properties](https://gotenberg.dev/docs/routes#screenshots-route).

## Header and footer defaults templates

You have the option to add a default header and/or footer template to your PDF.
If your template contains variables, simply enter its name and value under `context`
as shown below.

```yaml
sensiolabs_gotenberg:
    http_client: 'gotenberg.client'
    assets_directory: 'assets'
    default_options:
        pdf:
            html:
                header:
                    template: 'header.html.twig'
                    context:
                        title: 'Hello'
                        first_name: 'Jean Michel'
                footer:
                    template: 'footer.html.twig'
                    context:
                        foo: 'bar'
```

## Extra HTTP headers

HTTP headers to send by Chromium while loading the HTML document.

```yaml
sensiolabs_gotenberg:
    default_options:
        pdf:
            html:
                extra_http_headers:
                    'My-Header': 'MyValue'
```

Or the syntax below is also possible

```yaml
sensiolabs_gotenberg:
    default_options:
        pdf:
            html:
                extra_http_headers:
                    - { name: 'My-Header', value: 'MyValue' }
```

Headers to send to your webhook endpoint

```yaml
sensiolabs_gotenberg:
    webhook:
        default:
            extra_http_headers:
                'My-Header': 'MyValue'
```

> [!TIP]
> For more information about [custom HTTP headers](https://gotenberg.dev/docs/routes#custom-http-headers) & [webhook custom HTTP headers](https://gotenberg.dev/docs/configuration#webhook).

## Invalid HTTP Status Codes

To return a 409 Conflict response if the HTTP status code from the main
page is not acceptable.

```yaml
sensiolabs_gotenberg:
    default_options:
        pdf:
            html:
                fail_on_http_status_codes: [401, 403]
```
> [!TIP]
> A X99 entry means every HTTP status codes between X00 and X99 (e.g., 499 means every HTTP status codes between 400 and 499).
> `fail_on_http_status_codes: [499, 599]` would fail on any 4XX or 5XX code. 

> [!TIP]
> For more information about [Invalid HTTP Status Codes](https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium).

## Cookies

Cookies to store in the Chromium cookie jar.

``` yaml
sensiolabs_gotenberg:
    default_options:
        pdf:
            html:
                cookies:
                    - { name: 'yummy_cookie', value: 'choco', domain: 'example.com' }
                    - { name: 'my_cookie', value: 'symfony', domain: 'symfony.com', secure: true, httpOnly: true, sameSite: 'Lax'  }
```

> [!TIP]
> For more information about [cookies](https://gotenberg.dev/docs/routes#cookies-chromium).

## Metadata

Metadata for the generated document.

``` yaml
sensiolabs_gotenberg:
    default_options:
        pdf:
            html:
                metadata:
                    Author: Sensiolabs
                    Subject: Gotenberg
```

> [!TIP]
> For more information about [metadata](https://gotenberg.dev/docs/routes#metadata-chromium).

## Controller Listener

Whenever a controller returns something other than a `Response` object, the [`kernel.view`](https://symfony.com/doc/current/reference/events.html#kernel-view) event is fired.
That listener listen to this event and detects if it is a `GotenbergFileResult` object. If so it automatically calls the `->stream()` method to convert it to a Response object.

Enabled by default but can be disabled via the `sensiolabs_gotenberg.controller_listener` configuration.

## Download from

> [!WARNING]  
> URL of the file. It MUST return a `Content-Disposition` header with a filename parameter.

To download files resource from URLs.

``` yaml
sensiolabs_gotenberg:
    default_options:
        pdf:
            html:
                download_from:
                    - url: 'http://example.com/url/to/file''
                      extraHttpHeaders:
                          - name: 'MyHeader'
                            value: 'MyValue'
                          - name: 'User-Agent'
                            value: 'MyValue'

```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#download-from).
