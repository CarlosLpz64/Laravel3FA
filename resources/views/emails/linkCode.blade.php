<!DOCTYPE html>
<html>
<head>
    <title>Código de verificación</title>
</head>
<body>
    <h1>{{ $mailData['title'] }}</h1>
    <p>{{ $mailData['body'] }}</p>
    <a href="{{ $mailData['path'] }}"> Ir a enlace</a>
    <br>
    <p> O copia y pega el siguiente link:</p>
    <p>{{ $mailData['path'] }}</p>
    <p><b>Nota: </b> el enlace recibido caducará en 5 minutos.</p>
    <p>
        Si tú no solicitaste este código de verificación, ignora  este correo. No 
        pases este código a NADIE.
    </p>     
</body>
</html>