<!-- HTML for static distribution bundle build -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sanalyer API</title>
    <link rel="stylesheet" type="text/css" href="{{ asset("assets/swagger/swagger-ui.css") }}" >
    <style>
        html
        {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }
        *,
        *:before,
        *:after
        {
            box-sizing: inherit;
        }
        body
        {
            margin:0;
            background: #fafafa;
        }
    </style>
</head>

<body>
<div id="swagger-ui"></div>

<script src="{{ asset("assets/swagger/swagger-ui-bundle.js") }}"> </script>
<script src="{{ asset("assets/swagger/swagger-ui-standalone-preset.js") }}"> </script>
<script>
    window.onload = function() {
        // Build a system
        const ui = SwaggerUIBundle({
            url: '{{ asset("assets/swagger/swagger.json") }}',
            dom_id: '#swagger-ui',
            deepLinking: true,
            validatorUrl: null,
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset.slice(1)
            ],
            presets_config: {
                SwaggerUIStandalonePreset: {
                    TopbarPlugin: false
                }
            },
            plugins: [
                SwaggerUIBundle.plugins.DownloadUrl
            ],
            layout: "StandaloneLayout"
        });
        window.ui = ui
    }
</script>
</body>
</html>