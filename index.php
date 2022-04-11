<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API - Prubebas</title>
    <link rel="stylesheet" href="assets/estilo.css" type="text/css">
</head>

<body>

    <div class="container">
        <h1>Api de pruebas</h1>
        <div class="divbody">
            <h3>Auth - login</h3>
            <code>
                POST /api/auth
                <br>
                {
                <br>
                "usuario" :"", -> REQUERIDO
                <br>
                "password": "" -> REQUERIDO
                <br>
                }

            </code>
            <code>
                POST /api/register
                <br>
                {
                <br>
                "usuario" :"", -> REQUERIDO
                <br>
                "password": "" -> REQUERIDO (min-3, max-30)
                <br>
                "passwordConfirm": "" -> REQUERIDO
                <br>
                }

            </code>
        </div>
        <div class="divbody">
            <h3>Pacientes</h3>
            <code>
                GET /api/pacientes?page=$numeroPagina
                <br>
                GET /api/pacientes?id=$idPaciente
            </code>

            <code>
                POST /api/pacientes
                <br>
                {
                <br>
                "nombre" : "", -> REQUERIDO
                <br>
                "dni" : "", -> REQUERIDO
                <br>
                "correo":"", -> REQUERIDO
                <br>
                "codigoPostal" :"",
                <br>
                "genero" : "",
                <br>
                "telefono" : "",
                <br>
                "fechaNacimiento" : "",
                <br>
                "token" : "" -> REQUERIDO
                <br>
                }

            </code>
            <code>
                PUT /api/pacientes
                <br>
                {
                <br>
                "nombre" : "",
                <br>
                "dni" : "",
                <br>
                "correo":"",
                <br>
                "codigoPostal" :"",
                <br>
                "genero" : "",
                <br>
                "telefono" : "",
                <br>
                "fechaNacimiento" : "",
                <br>
                "token" : "" , -> REQUERIDO
                <br>
                "pacienteId" : "" -> REQUERIDO
                <br>
                }

            </code>
            <code>
                DELETE /api/pacientes
                <br>
                {
                <br>
                "token" : "", -> REQUERIDO
                <br>
                "pacienteId" : "" -> REQUERIDO
                <br>
                }

            </code>
        </div>
        <div class="divbody">
            <h3>Citas</h3>
            <code>
                GET /api/Citas?page=$numeroPagina
                <br>
                GET /api/Citas?id=$idCitas
            </code>

            <code>
                POST /api/Citas
                <br>
                {
                <br>
                "pacienteId" : "", -> REQUERIDO
                <br>
                "fecha" : "", -> REQUERIDO
                <br>
                "horaInicio":"", -> REQUERIDO
                <br>
                "motivo" :"", -> REQUERIDO
                <br>
                "estado" : "",
                <br>
                "horaFin" : "",
                <br>
                "token" : "" -> REQUERIDO
                <br>
                }

            </code>
            <code>
                PUT /api/Citas
                <br>
                {
                <br>
                "pacienteId" : "",
                <br>
                "fecha" : "",
                <br>
                "horaInicio":"",
                <br>
                "motivo" :"",
                <br>
                "estado" : "",
                <br>
                "horaFin" : "",
                <br>
                "token" : "" -> REQUERIDO
                <br>
                "citaID" : "" -> REQUERIDO
                <br>
                }
            </code>
            <code>
                DELETE /api/Citas
                <br>
                {
                <br>
                "token" : "", -> REQUERIDO
                <br>
                "citasId" : "" -> REQUERIDO
                <br>
                }

            </code>
        </div>


    </div>

</body>

</html>