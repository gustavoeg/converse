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
            frameEndpoint: '/converse/chat.html',
            introMessage: 'Hola, soy tu asistente del TSJ. Para volver a acceder a este menú escribe "hola".',
            chatServer : '/converse/chat.php', 
            title: 'Asistente virtual TSJ', 
            mainColor: '#29415c',
            headerTextColor: "#fff",
            bubbleBackground: '#fff',
            aboutText: '',
            bubbleAvatarUrl: '/converse/resources/chat.png',
            widgetOpenedEventData: '.',
            sendWidgetOpenedEvent: true,
            placeholderText: 'Escribe una consulta...',
        }; 
    </script>
    <script src='/converse/js/widget.js'></script>
</body>
</html>