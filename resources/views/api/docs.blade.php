@extends('layouts.master')
@section('css')
    <link href="{{ asset('/assets/default/css/home.css?v=2.3.4') }}" rel="stylesheet" media="all"/>
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
@endsection
@section('content')
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
@endsection
