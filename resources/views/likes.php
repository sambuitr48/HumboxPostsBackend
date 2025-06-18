<!DOCTYPE html>
<html>
<head>
    <title>Notificaciones de Likes</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Actividad de Likes</h1>
    <div id="notificacion-like" style="background: #fff3cd; padding: 10px; display: none;">
        👍 ¡A alguien le gustó una publicación!
    </div>

    <script src="{{ mix('/js/app.js') }}"></script>
    <script>
        window.Echo.private('post-likes')
            .listen('.post.liked', (e) => {
                console.log("Nuevo like vía WebSocket:", e.like);
                let box = document.getElementById("notificacion-like");
                box.style.display = "block";
                box.innerHTML = `👍 Publicación <strong>#${e.like.post_id}</strong> recibió un like`;
                setTimeout(() => box.style.display = "none", 5000);
            });
    </script>
</body>
</html>
