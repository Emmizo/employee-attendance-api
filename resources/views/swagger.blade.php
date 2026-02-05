<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance API Docs</title>
    <link rel="stylesheet"
          href="https://unpkg.com/swagger-ui-dist@5/swagger-ui.css">
</head>
<body>
<div id="swagger-ui"></div>

<script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-bundle.js"></script>
<script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-standalone-preset.js"></script>
<script>
    window.onload = () => {
        SwaggerUIBundle({
            url: '/openapi.yaml',
            dom_id: '#swagger-ui',
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
            ],
            layout: 'StandaloneLayout',
            deepLinking: true,
            tryItOutEnabled: true,
            requestInterceptor: (request) => {
                return request;
            },
            onComplete: () => {
                console.log('Swagger UI loaded successfully');
            },
            onFailure: (data) => {
                console.error('Swagger UI failed to load:', data);
                document.getElementById('swagger-ui').innerHTML = 
                    '<div style="padding: 20px; text-align: center;">' +
                    '<h2>Failed to load API documentation</h2>' +
                    '<p>Please check the console for errors.</p>' +
                    '</div>';
            }
        });
    };
</script>
</body>
</html>

