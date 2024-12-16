<!DOCTYPE html>
<html lang="es">
<head> 
    <style>
        @page {
            margin: 0;
        }
        body {
            margin: 0; 
            font-family: Arial, sans-serif;
        }
        h1 {
            text-align: center;
            font-size: 14pt; 
        }
        .barcode-container {
            text-align: center;
        }
        img {
            width: 90%;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <div class="barcode-container">
        <img src="data:image/svg+xml;base64,{{ base64_encode($codigoBarras) }}" alt="Código de barras" />
    </div>
</body>
</html>