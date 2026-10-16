# 🐝 Documentación Técnica: API REST Backend (PHP, MVC & MySQL)

Proyecto backend desarrollado bajo la arquitectura **MVC (Modelo-Vista-Controlador)**, utilizando el patrón **DAO/DTO**, persistencia mediante **MySQLi nativo con consultas preparadas**, autenticación por roles (`JUGADOR` / `ADMIN`), notificaciones automáticas por correo electrónico mediante **PHPMailer** y la lógica de juego del **"Juego de la Mosca"**.

---

## 📁 1. Configuración del Entorno y Variables (`.env`)

La conexión a la base de datos MySQL y la configuración del entorno se gestionan mediante variables de entorno aisladas para maximizar la seguridad del proyecto.

| Cargar Variables de Entorno (`.env`) | Función de Carga de Variables en BD | Ejemplo de Archivo `.env` |
| :---: | :---: | :---: |
| ![Cargar Variables](./images/cargarVariablesEntorno.png) | ![Carga Datos BD](./images/cargaDatosENVBD.tiff) | ![Example ENV](./images/exampleENV.tiff) |

---

## 🏗️ 2. Arquitectura de Enrutamiento Central (`index.php`)

El archivo `index.php` actúa como el **Front Controller** único del sistema. Intercepta todas las peticiones HTTP, parsea las rutas y deriva el flujo de control hacia los métodos correspondientes en los controladores.

### Flujo de Enrutamiento por Métodos HTTP

| Peticiones GET | Peticiones POST | Peticiones PUT | Peticiones DELETE |
| :---: | :---: | :---: | :---: |
| ![Peticiones GET](./images/PeticionesGET.tiff) | ![Peticiones POST](./images/PeticionesPOST.tiff) | ![Peticion PUT](./images/PeticionPUT.tiff) | ![Peticion DELETE](./images/PeticionDELETE.tiff) |

---

## 👥 3. Módulo de Usuarios y Autenticación

Secuencia completa de ejecución desde la llamada en Postman, pasando por la validación en el `UsuarioController.php`, hasta la persistencia final en `usuarios_dao.php`.

### A. Registro de Usuarios (`POST /register`)

Permite dar de alta a nuevos usuarios con rol por defecto `JUGADOR` y clave cifrada.

| 1. Petición POSTMAN | 2. Lógica en Controlador | 3. Persistencia en BD (DAO) |
| :---: | :---: | :---: |
| ![Postman Register](./images/POSTMAN-registrarUsuario.tiff) | ![Register Controller](./images/registerUser.tiff) | ![Register DAO](./images/registerUserBD.tiff) |

### B. Inicio de Sesión (`POST /login`)

Valida las credenciales del usuario y marca su sesión como activa en la base de datos.

| 1. Petición POSTMAN | 2. Lógica en Controlador | 3. Persistencia en BD (DAO) |
| :---: | :---: | :---: |
| ![Postman Login](./images/POSTMAN-login.tiff) | ![Login Controller](./images/loginUser.tiff) | ![Login DAO](./images/LoginUserBD.tiff) |

### C. Consultar Usuario Específico por ID (`GET /usuarios/{id}`)

Recupera la información detallada de un usuario en base a su clave primaria.

| 1. Petición POSTMAN | 2. Lógica en Controlador | 3. Consulta en BD (DAO) |
| :---: | :---: | :---: |
| ![Postman Consultar User](./images/POSTMAN-consultarUser.tiff) | ![Consultar User Controller](./images/consultarUsuario.tiff) | ![Consultar User DAO](./images/obtenerUserByID-BD.tiff) |

### D. Consultar Todos los Usuarios (`GET /usuarios`)

Lista completa de usuarios registrados (acceso restringido a administradores).

| 1. Petición POSTMAN | 2. Lógica en Controlador | 3. Consulta en BD (DAO) |
| :---: | :---: | :---: |
| ![Postman Consultar Todos](./images/POSTMAN-ConsultarTodosUsuarios.tiff) | ![Consultar Todos Controller](./images/consultarTodosUsuarios.tiff) | ![Consultar Todos DAO](./images/ObtenerTodosUsuariosBD.tiff) |

### E. Modificar Usuario (`PUT /usuarios/{id}`)

Permite actualizar campos dinámicos de un usuario (nombre, edad, contraseña).

| 1. Petición POSTMAN | 2. Lógica en Controlador | 3. Actualización en BD (DAO) |
| :---: | :---: | :---: |
| ![Postman Modificar](./images/POSTMAN-modificarUser.tiff) | ![Modificar Controller](./images/modificarUsuario.tiff) | ![Modificar DAO](./images/editUserBD.tiff) |

### F. Eliminar Usuario (`DELETE /usuarios/{id}`)

Borrado de un usuario por ID con protección anti-autoborrado de administradores y aviso por correo.

| 1. Petición POSTMAN | 2. Lógica en Controlador | 3. Borrado en BD (DAO) |
| :---: | :---: | :---: |
| ![Postman Delete](./images/POSTMAN-Deluser.tiff) | ![Delete Controller](./images/delUsuario.tiff) | ![Delete DAO](./images/delUserBD.tiff) |

---

## 🎲 4. Módulo del Juego de la Mosca

Gestión completa de las partidas activas, colocación aleatoria de la mosca, evaluación de manotazos y control de intentos.

### A. Crear Tablero / Partida (`POST /crear`)

Inicializa el tablero de juego y asigna la posición aleatoria de la mosca asociada al usuario.

| 1. Petición POSTMAN | 2. Lógica en Controlador | 3. Inserción en BD (DAO) |
| :---: | :---: | :---: |
| ![Postman Crear Tablero](./images/POSTMAN-crearTablero.tiff) | ![Crear Tablero Controller](./images/CrearTablero.tiff) | ![Crear Tablero DAO](./images/CreaPartidaDB.tiff) |

### B. Jugar / Dar Manotazo (`POST /jugar`)

Procesa el intento del jugador, actualiza la posición de la mosca si falla o da por finalizada la partida si acierta.

| 1. Petición POSTMAN | 2. Lógica en Controlador | 3. Actualización en BD (DAO) |
| :---: | :---: | :---: |
| ![Postman Jugar](./images/POSTMAN-jugar.tiff) | ![Jugar Controller](./images/Jugar.tiff) | ![Actualizar Partida DAO](./images/actualizarPartidaDB.tiff) |

### C. Consultar Intentos Restantes (`GET /intentos`)

Obtiene el número de intentos disponibles en la partida activa actual.

| 1. Petición POSTMAN | 2. Lógica en Controlador | 3. Consulta en BD (DAO) |
| :---: | :---: | :---: |
| ![Postman Ver Intentos](./images/POSTMAN-VerIntentos.tiff) | ![Consultar Intentos Controller](./images/consultarIntentos.tiff) | ![Get Intentos DAO](./images/GetIntentosDB.tiff) |

### D. Rendirse (`POST /rendirse`)

Finaliza voluntariamente la partida activa del usuario marcándola como inactiva en la base de datos.

| 1. Petición POSTMAN | 2. Lógica en Controlador | 3. Actualización en BD (DAO) |
| :---: | :---: | :---: |
| ![Postman Rendirse](./images/POSTMAN-rendirse.tiff) | ![Rendirse Controller](./images/rendirse.tiff) | ![Rendirse DAO](./images/RendirseDB.tiff) |

### E. Mapeo Interno del Tablero Activo (Soporte DAO)

Métodos auxiliares para la verificación y recuperación del estado del tablero activo en MySQL.

| Obtener Tablero Activo (BD) | Obtener Tablero por ID (BD) |
| :---: | :---: |
| ![Obtener Tablero Activo BD](./images/ObtenerTableroActivoBD.tiff) | ![Obtener Tablero BD](./images/obtenerTableroBD.tiff) |

---

## 🗄️ 5. Esquema de Base de Datos y Tablas Relacionales

Estructura física y modelo relacional alojado en MySQL.

| Vista General de la Base de Datos | Tabla `usuarios` | Tabla `partida` |
| :---: | :---: | :---: |
| ![Base de Datos](./images/BaseDeDatos.tiff) | ![Tabla Usuarios](./images/TablaUsuarios.tiff) | ![Tabla Partida](./images/TablaPartida.tiff) |