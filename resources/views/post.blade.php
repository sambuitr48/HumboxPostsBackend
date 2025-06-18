<!DOCTYPE html>
<html>
<head>
    <title>Notificaciones de Post</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Feed de Publicaciones</h1>
    <div id="notificacion" style="background: #d1e7dd; padding: 10px; display: none;">
        📰 ¡Se ha publicado un nuevo post!
    </div>

    <script src="{{ mix('/js/app.js') }}"></script>
    <script>
        window.Echo.private('posts')
            .listen('.post.created', (e) => {
                console.log("Nuevo post recibido vía WebSocket:", e.post);
                let box = document.getElementById("notificacion");
                box.style.display = "block";
                box.innerHTML = `📰 <strong>${e.post.title}</strong> ha sido publicado`;
                setTimeout(() => box.style.display = "none", 5000);
            });
    </script>
</body>
</html>
