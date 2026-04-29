<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Restablecer contraseña</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #0f172a;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            background-color: #0f172a;
            padding: 40px 20px;
            box-sizing: border-box;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            padding: 40px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            font-size: 24px;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.5px;
        }
        .content {
            padding: 40px;
            color: #334155;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #1e293b;
        }
        .message {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 32px;
            color: #64748b;
        }
        .btn-container {
            text-align: center;
            margin: 32px 0;
        }
        .btn {
            display: inline-block;
            background-color: #0f172a;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
        }
        .footer {
            background-color: #f8fafc;
            padding: 24px;
            text-align: center;
            font-size: 13px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>ProReserve</h1>
            </div>
            <div class="content">
                <div class="greeting">¡Hola!</div>
                <div class="message">
                    Has recibido este correo porque se solicitó un restablecimiento de contraseña para tu cuenta de ProReserve. Pulsa el siguiente botón para continuar:
                </div>
                
                <div class="btn-container">
                    <a href="{{ $url }}" class="btn">Restablecer contraseña</a>
                </div>

                <div class="message" style="font-size: 14px; margin-top: 32px; border-top: 1px solid #edf2f7; padding-top: 24px;">
                    Este enlace de restablecimiento de contraseña expirará en 60 minutos.<br><br>
                    Si no has solicitado un restablecimiento de contraseña, puedes omitir este mensaje con total seguridad.
                </div>
            </div>
            <div class="footer">
                &copy; {{ date('Y') }} ProReserve. Todos los derechos reservados.
            </div>
        </div>
    </div>
</body>
</html>
