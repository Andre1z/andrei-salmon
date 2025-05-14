# Andrei | Salmon

Descripción
-----------
Andrei | Salmon es una aplicación web modular basada en PHP que sigue un patrón de diseño MVC (Modelo-Vista-Controlador). Este proyecto simula una red social minimalista, permitiendo funcionalidades como autenticación de usuario, publicación de contenido, interacciones (likes, comentarios), gestión de contactos, envío de mensajes y actualización de perfil.

Características
---------------
- **Autenticación y Registro:**  
  Los usuarios pueden iniciar sesión o registrarse, con formularios dedicados para cada función.
  
- **Publicaciones y Feed:**  
  Los usuarios autenticados pueden crear publicaciones, ver un feed con sus posts y los de sus contactos, dar "me gusta" y comentar en tiempo real.

- **Perfil de Usuario:**  
  Cada usuario dispone de una sección de "Mi Perfil" donde puede actualizar su nombre de usuario y cambiar su contraseña de forma interactiva.

- **Interacción entre Usuarios:**  
  Se permite conectar con otros usuarios, enviar mensajes y gestionar relaciones de amistad o seguimiento.

- **Estilo Minimalista y Moderno:**  
  La interfaz utiliza una paleta de colores basada en azules y púrpuras, con diseño responsive y estructurado mediante CSS Grid, garantizando una experiencia visual atractiva y actual.

Estructura del Proyecto
-----------------------
- **public/index.php:**  
  Front controller que centraliza el enrutamiento basado en el parámetro `action` (por ejemplo, login, signup, profile, logout, etc.).

- **views/:**  
  Contiene las vistas HTML/PHP como header, footer, login, signup, home (feed), profile, etc.

- **src/Models/:**  
  Incluye los modelos de datos (User, Post, Comment, Like, Message) responsables de interactuar con la base de datos.

- **src/Controllers/:**  
  Contiene los controladores (AuthController, PostController, CommentController, ConnectionController, MessageController) que gestionan la lógica de negocio y las peticiones del usuario.

- **config/database.php:**  
  Configuración para la conexión a la base de datos.

- **assets/css/styles.css:**  
  Hoja de estilos que define el aspecto minimalista y moderno del sitio.

Requisitos
----------
- PHP 7.4 o superior.
- Servidor web (por ejemplo, XAMPP, WAMP, LAMP) con soporte para PHP.
- SQLite3 (o MySQL, si se ajusta la configuración) para la base de datos.

Instalación
-----------
1. Clona o descarga el repositorio en el directorio raíz de tu servidor web.
2. Configura la conexión a la base de datos en el archivo `config/database.php` según tu entorno.
3. (Opcional) Importa el script SQL incluido (si lo proporcionas) para crear las tablas necesarias.
4. Accede a la aplicación a través de tu navegador, por ejemplo:  
   `http://localhost/andrei-salmon/public/index.php`

Uso
---
- **Usuarios no autenticados:**  
  Se redirigen a la vista de login (`index.php?action=login`) donde pueden iniciar sesión o acceder al enlace de registro (`index.php?action=signup`).

- **Usuarios autenticados:**  
  Se muestra el feed de publicaciones, permitiendo interactuar con el contenido (crear posts, dar "me gusta", comentar, etc.) y gestionar su perfil y contactos.

Mejoras Futuras
---------------
- Implementación de validación y seguridad adicional en la entrada de datos.
- Optimización del rendimiento y manejo de errores.
- Ampliación de funciones interactivas con AJAX (carga dinámica, notificaciones, etc.).
- Inclusión de imágenes de perfil y la posibilidad de subir archivos multimedia en publicaciones.

Contacto
--------
Para más información, sugerencias o reportar incidencias, ponte en contacto a través del repositorio o mediante el correo del mantenedor.

Este proyecto se ha desarrollado con fines educativos y de demostración, poniendo énfasis en la modularidad y buenas prácticas del desarrollo PHP.

¡Disfruta explorando Andrei | Salmon!