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

sensiolabs_gotenberg:
    assets_directory: '%kernel.project_dir%/assets'
    http_client: 'gotenberg.client' # Required and must have a `base_uri`.
    # Override the request Gotenberg will make to call one of your routes.
    request_context:
        # Used only when using `->route()`. Overrides the guessed `base_url` from the request. May be useful in CLI.
        base_uri: null                              # None
    default_options:
        pdf:
            html:
                header:
                    template: null                  # None
                    context: null                   # None
                footer:
                    template: null                  # None
                    context: null                   # None
                single_page: null                   # false
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
                metadata: null                      # None
            url:
                header:
                    template: null                  # None
                    context: null                   # None
                footer:
                    template: null                  # None
                    context: null                   # None
                single_page: null                   # false
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
                metadata: null                      # None
            markdown:
                header:
                    template: null                  # None
                    context: null                   # None
                footer:
                    template: null                  # None
                    context: null                   # None
                single_page: null                   # false
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
                metadata: null                      # None
            office:
                landscape: null                             # false
                native_page_ranges: null                    # All pages
                export_form_fields: null                    # true
                single_page_sheets: null                    # false
                merge: null                                 # false
                pdf_format: null                            # None
                pdf_universal_access: null                  # false
                metadata: null                              # None
                allow_duplicate_field_names: null           # false
                export_bookmarks: null                      # true
                export_bookmarks_to_pdf_destination: null   # false
                export_placeholders: null                   # false
                export_notes: null                          # false
                export_notes_pages: null                    # false
                export_only_notes_pages: null               # false
                export_notes_in_margin: null                # false
                convert_ooo_target_to_pdf_target: null      # false
                export_links_relative_fsys: null            # false
                export_hidden_slides: null                  # false
                skip_empty_pages: null                      # false
                add_original_document_as_stream: null       # false
                lossless_image_compression: null            # false
                quality: null                               # 90
                reduce_image_resolution: null               # false
                max_image_resolution: null                  # 300
            merge:
                pdf_format: null                    # None
                pdf_universal_access: null          # false
                metadata: null                      # None
            convert:
                pdf_format: null                    # None
                pdf_universal_access: null          # false
        screenshot:
            html:
                width: null                         # 800
                height: null                        # 600
                clip: null                          # false
                format: null                        # png
                quality: null                       # 100
                omit_background: null               # false
                optimize_for_speed: null            # false
                wait_delay: null                    # None
                wait_for_expression: null           # None
                emulated_media_type: null           # 'print'
                cookies: null                       # None
                extra_http_headers: null            # None
                fail_on_http_status_codes: null     # [499-599]
                fail_on_console_exceptions: null    # false
                skip_network_idle_event: null       # false
            url:
                width: null                         # 800
                height: null                        # 600
                clip: null                          # false
                format: null                        # png
                quality: null                       # 100
                omit_background: null               # false
                optimize_for_speed: null            # false
                wait_delay: null                    # None
                wait_for_expression: null           # None
                emulated_media_type: null           # 'print'
                cookies: null                       # None
                extra_http_headers: null            # None
                fail_on_http_status_codes: null     # [499-599]
                fail_on_console_exceptions: null    # false
                skip_network_idle_event: null       # false
            markdown:
                width: null                         # 800
                height: null                        # 600
                clip: null                          # false
                format: null                        # png
                quality: null                       # 100
                omit_background: null               # false
                optimize_for_speed: null            # false
                wait_delay: null                    # None
                wait_for_expression: null           # None
                emulated_media_type: null           # 'print'
                cookies: null                       # None
                extra_http_headers: null            # None
                fail_on_http_status_codes: null     # [499-599]
                fail_on_console_exceptions: null    # false
                skip_network_idle_event: null       # false
```

> [!TIP]
> For more information about the [PDF properties](https://gotenberg.dev/docs/routes#page-properties-chromium) 
> or [screenshot properties](https://gotenberg.dev/docs/routes#screenshots-route).

## Extra HTTP headers

HTTP headers to send by Chromium while loading the HTML document.

```yaml
sensiolabs_gotenberg:
    default_options:
        pdf:
            html:
                extra_http_headers:
                    - { name: 'My-Header', value: 'MyValue' }
```

> [!TIP]
> For more information about [custom HTTP headers](https://gotenberg.dev/docs/routes#custom-http-headers).

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
