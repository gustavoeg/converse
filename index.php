<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple chat bot</title>
    <style>
        body{
            background-color: black;
            color:aliceblue;
        }
    </style>
</head>
<body>
    <h3>Botman demo</h3>
    <p>Demo de Botman sin el uso de botman studio</p>

    <script>
        var botmanWidget = {
            frameEndpoint: 'chat.html',
            introMessage: 'Hola, soy tu asistente, puedes escribir tu consulta directamente o escribir "hola" para opciones.',
            chatServer : 'chat.php', 
            title: 'Mi asistente virtual', 
            mainColor: '#789ff5',
            bubbleBackground: '#ff76f4',
            aboutText: '',
            bubbleAvatarUrl: '',
        }; 
    </script>
    <script src='js/widget.js'></script>
</body>
</html>