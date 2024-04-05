<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple chat bot</title>
</head>
<body>
    <h1>Simple Chat bot botman</h1>

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
        <script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>
</body>
</html>