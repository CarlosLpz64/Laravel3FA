<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Correo enviado</title>
    <style type="text/css">

.centrar{
    width: 100%;
    height: 95vh;
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    flex-direction: column;
    font: sans-serif;
}
.wrapper { 
  height: 100%;
  width: 100%;
  left:0;
  right: 0;
  top: 0;
  bottom: 0;
  position: absolute;
  z-index: -999;
  background: rgb(4,3,31);
background: -moz-linear-gradient(183deg, rgba(4,3,31,1) 0%, rgba(8,57,66,1) 100%);
background: -webkit-linear-gradient(183deg, rgba(4,3,31,1) 0%, rgba(8,57,66,1) 100%);
background: linear-gradient(183deg, rgba(4,3,31,1) 0%, rgba(8,57,66,1) 100%);
filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#04031f",endColorstr="#083942",GradientType=1);
}
    </style>
</head>
<body>
<div class="wrapper"></div>
    <div class="centrar">
    <h1>Ingresar código</h1>
    <p>Por favor ingresa el código generado desde móvil</p>

    <form method="POST" action="{{ route('submit-code-web') }}">
        @csrf
        <!-- Email Address -->
        <div>
            <input type="text" id="code" name="code"><br><br>
            <input type="submit" value="Submit">
        </div>
    </form>

    <p style="color: red;">{{ $message }}</p>
    </div>
</body>
</html>