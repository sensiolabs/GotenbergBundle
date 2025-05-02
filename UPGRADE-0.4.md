# UPGRADE FROM 0.3.1 to 0.4.0

## DEPRECATED

- Deprecate `gotenberg_font`, use `gotenberg_font_face` or
`gotenberg_font_style_tag` instead.

*Before*
```html
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>PDF with Custom Font</title>
        <style>
            {{ gotenberg_font('fonts/custom-font.ttf', 'my_font') }}
        </style>
    </head>
    <!-- rest of your code -->
</html>
```

*After*
```html
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>PDF with Custom Font</title>
        <style>
            {{ gotenberg_font_face('fonts/custom-font.ttf', 'my_font') }}
        </style>
    </head>
    <!-- rest of your code -->
</html>
```
