# Documentación Técnica: API REST Backend (PHP, MVC & MySQL)

Proyecto backend desarrollado bajo la arquitectura **MVC (Modelo-Vista-Controlador)**, utilizando el patrón **DAO/DTO**, persistencia mediante **MySQLi nativo con consultas preparadas**, autenticación por roles (`JUGADOR` / `ADMIN`), notificaciones automáticas por correo electrónico mediante **PHPMailer** y la lógica de juego del **"Juego de la Mosca"**.

---

## Demostración en Vídeo y Explicación General

### 🎥 Enlace al Videotutorial
👉 **[Ver Demostración en Vídeo del Proyecto](#)** *(https://drive.google.com/drive/folders/1TfPOsPFJ8l0cEpraOeqhtYWg9WZH_uwv?usp=drive_link)*

### Lógica y Funcionamiento General del Juego
El **Juego de la Mosca** es una mecánica interactiva donde el servidor gestiona un tablero unidimensional de posiciones dinámicas (`tamano`):

1. **Creación**: Al invocar `/crear`, el backend inicializa el tablero, esconde aleatoriamente la mosca (`colocarMosca()`) y la asocia a la partida activa del usuario en la base de datos.
2. **Mecánica del Manotazo (`/jugar`)**: El usuario indica una casilla de destino y el servidor evalúa la distancia:
   * **`2` ("¡Cazada!")**: El usuario acierta la posición exacta. La partida finaliza y se desactiva.
   * **`1` ("Casi le das")**: El manotazo ha sido en una casilla adyacente (a distancia de 1). La mosca se asusta y revolotea a otra casilla aleatoria del tablero. Se resta 1 intento.
   * **`0` ("Ni te acercas")**: El manotazo ha caído lejos. La mosca se mueve a una nueva posición aleatoria. Se resta 1 intento.
   * **`-1`**: Intento no válido o sin intentos restantes.
3. **Rendición e Intentos**: El jugador puede consultar sus intentos disponibles (`/intentos`) o abandonar la partida en cualquier momento (`/rendirse`).

### Control de Acceso por Roles (`ADMIN` vs `JUGADOR`)

- **Rol JUGADOR (USER)**:
  - Registro e inicio de sesión (`POST /register`, `POST /login`).
  - Creación y gestión de su propia partida activa (`POST /crear`).
  - Ejecución de jugadas / manotazos (`POST /jugar`).
  - Consulta de intentos restantes (`GET /intentos`).
  - Finalización voluntaria de la partida (`POST /rendirse`).
  - *Restricción*: No tiene acceso a la gestión ni consulta global de otros usuarios.

- **Rol ADMIN (Administrador)**:
  - Todos los permisos de autenticación.
  - Consulta de la lista completa de usuarios registrados (`GET /usuarios`).
  - Consulta detallada de cualquier usuario por su clave primaria (`GET /usuarios/{id}`).
  - Edición y modificación dinámica de datos de cualquier usuario (`PUT /usuarios/{id}`).
  - Eliminación quirúrgica de usuarios (`DELETE /usuarios/{id}`) protegida contra auto-eliminación y con avisos automáticos por email (`PHPMailer`).

---

## 1. Configuración del Entorno y Variables (`.env`)

La conexión a la base de datos MySQL y la configuración del entorno se gestionan mediante variables de entorno aisladas para maximizar la seguridad del proyecto.

| Cargar Variables de Entorno (`.env`) | Función de Carga de Variables en BD | Ejemplo de Archivo `.env` |
| :---: | :---: | :---: |
| ![Cargar Variables](./images/cargarVariablesEntorno.png) | ![Carga Datos BD](./images/cargaDatosENVBD.png) | ![Example ENV](./images/exampleENV.png) |

---

## 2. Arquitectura de Enrutamiento Central (`index.php`)

El archivo `index.php` actúa como el **Front Controller** único del sistema. Intercepta todas las peticiones HTTP, parsea las rutas y deriva el flujo de control hacia los métodos correspondientes en los controladores.

### Flujo de Enrutamiento por Métodos HTTP

| Peticiones GET | Peticiones POST | Peticiones PUT | Peticiones DELETE |
| :---: | :---: | :---: | :---: |
| ![Peticiones GET](./images/PeticionesGET.png) | ![Peticiones POST](./images/PeticionesPOST.png) | ![Peticion PUT](./images/PeticionPUT.png) | ![Peticion DELETE](./images/PeticionDELETE.png) |

---

## 3. Módulo de Usuarios y Autenticación

Secuencia completa de ejecución desde la llamada en Postman, pasando por la validación en el `UsuarioController.php`, hasta la persistencia final en `usuarios_dao.php`.

### A. Registro de Usuarios (`POST /register`)

Permite dar de alta a nuevos usuarios con rol por defecto `JUGADOR` y clave cifrada. Envía una notificación por correo tras completar el registro.

| 1. Petición POSTMAN | 2. Lógica en Controlador | 3. Persistencia en BD (DAO) | 4. Email Confirmación |
| :---: | :---: | :---: | :---: |
| ![Postman Register](./images/POSTMAN-registrarUsuario.png) | ![Register Controller](./images/registerUser.png) | ![Register DAO](./images/registerUserBD.png) | ![Email Registro](./images/FOTO%20MAIL%20REGISTER.png) |

### B. Inicio de Sesión (`POST /login`)

Valida las credenciales del usuario, marca su sesión como activa en la base de datos y envía un correo informando del acceso.

| 1. Petición POSTMAN | 2. Lógica en Controlador | 3. Persistencia en BD (DAO) | 4. Email Login |
| :---: | :---: | :---: | :---: |
| ![Postman Login](./images/POSTMAN-login.png) | ![Login Controller](./images/loginUser.png) | ![Login DAO](./images/LoginUserBD.png) | ![Email Login](./images/FOTO%20MAIL%20LOGIN.png) |

### C. Consultar Usuario Específico por ID (`GET /usuarios/{id}`)

Recupera la información detallada de un usuario en base a su clave primaria.

| 1. Petición POSTMAN | 2. Lógica en Controlador | 3. Consulta en BD (DAO) |
| :---: | :---: | :---: |
| ![Postman Consultar User](./images/POSTMAN-consultarUser.png) | ![Consultar User Controller](./images/consultarUsuario.png) | ![Consultar User DAO](./images/obtenerUserByID-BD.png) |

### D. Consultar Todos los Usuarios (`GET /usuarios`)

Lista completa de usuarios registrados (acceso restringido a administradores).

| 1. Petición POSTMAN | 2. Lógica en Controlador | 3. Consulta en BD (DAO) |
| :---: | :---: | :---: |
| ![Postman Consultar Todos](./images/POSTMAN-ConsultarTodosUsuarios.png) | ![Consultar Todos Controller](./images/consultarTodosUsuarios.png) | ![Consultar Todos DAO](./images/ObtenerTodosUsuariosBD.png) |

### E. Modificar Usuario (`PUT /usuarios/{id}`)

Permite actualizar campos dinámicos de un usuario (nombre, edad, contraseña).

| 1. Petición POSTMAN | 2. Lógica en Controlador | 3. Actualización en BD (DAO) |
| :---: | :---: | :---: |
| ![Postman Modificar](./images/POSTMAN-modificarUser.png) | ![Modificar Controller](./images/modificarUsuario.png) | ![Modificar DAO](./images/editUserBD.png) |

### F. Eliminar Usuario (`DELETE /usuarios/{id}`)

Borrado de un usuario por ID con protección anti-autoborrado de administradores y aviso automático por correo al usuario eliminado.

| 1. Petición POSTMAN | 2. Lógica en Controlador | 3. Borrado en BD (DAO) | 4. Email Eliminación |
| :---: | :---: | :---: | :---: |
| ![Postman Delete](./images/POSTMAN-Deluser.png) | ![Delete Controller](./images/delUsuario.png) | ![Delete DAO](./images/delUserBD.png) | ![Email Borrado](./images/FOTO%20MAIL%20DELETE.png) |

---

## 4. Módulo del Juego de la Mosca

Gestión completa de las partidas activas, colocación aleatoria de la mosca, evaluación de manotazos y control de intentos.

### A. Crear Tablero / Partida (`POST /crear`)

Inicializa el tablero de juego y asigna la posición aleatoria de la mosca asociada al usuario.

| 1. Petición POSTMAN | 2. Lógica en Controlador | 3. Inserción en BD (DAO) |
| :---: | :---: | :---: |
| ![Postman Crear Tablero](./images/POSTMAN-crearTablero.png) | ![Crear Tablero Controller](./images/CrearTablero.png) | ![Crear Tablero DAO](./images/CreaPartidaDB.png) |

### B. Jugar / Dar Manotazo (`POST /jugar`)

Procesa el intento del jugador, actualiza la posición de la mosca si falla o da por finalizada la partida si acierta.

| 1. Petición POSTMAN | 2. Lógica en Controlador | 3. Actualización en BD (DAO) |
| :---: | :---: | :---: |
| ![Postman Jugar](./images/POSTMAN-jugar.png) | ![Jugar Controller](./images/Jugar.png) | ![Actualizar Partida DAO](./images/actualizarPartidaDB.png) |

### C. Consultar Intentos Restantes (`GET /intentos`)

Obtiene el número de intentos disponibles en la partida activa actual.

| 1. Petición POSTMAN | 2. Lógica en Controlador | 3. Consulta en BD (DAO) |
| :---: | :---: | :---: |
| ![Postman Ver Intentos](./images/POSTMAN-VerIntentos.png) | ![Consultar Intentos Controller](./images/consultarIntentos.png) | ![Get Intentos DAO](./images/GetIntentosDB.png) |

### D. Rendirse (`POST /rendirse`)

Finaliza voluntariamente la partida activa del usuario marcándola como inactiva en la base de datos.

| 1. Petición POSTMAN | 2. Lógica en Controlador | 3. Actualización en BD (DAO) |
| :---: | :---: | :---: |
| ![Postman Rendirse](./images/POSTMAN-rendirse.png) | ![Rendirse Controller](./images/rendirse.png) | ![Rendirse DAO](./images/RendirseDB.png) |

### E. Mapeo Interno del Tablero Activo (Soporte DAO)

Métodos auxiliares para la verificación y recuperación del estado del tablero activo en MySQL.

| Obtener Tablero Activo (BD) | Obtener Tablero por ID (BD) |
| :---: | :---: |
| ![Obtener Tablero Activo BD](./images/ObtenerTableroActivoBD.png) | ![Obtener Tablero BD](./images/obtenerTableroBD.png) |

---

## 5. Esquema de Base de Datos y Tablas Relacionales

Estructura física y modelo relacional alojado en MySQL.

| Vista General de la Base de Datos | Tabla `usuarios` | Tabla `partida` |
| :---: | :---: | :---: |
| ![Base de Datos](./images/BaseDeDatos.png) | ![Tabla Usuarios](./images/TablaUsuarios.png) | ![Tabla Partida](./images/TablaPartida.png) |

---

## 6. Sistema de Notificaciones por Correo Electrónico (PHPMailer)

Demostración visual del envío automático de correos en los tres eventos clave del ciclo de vida del usuario.

| Notificación de Registro (`POST /register`) | Notificación de Login (`POST /login`) | Notificación de Eliminación (`DELETE /usuarios/{id}`) |
| :---: | :---: | :---: |
| ![Correo de Registro](./images/FOTOMAILDELETE.png) | ![Correo de Login](./images/FOTOMAILLOGIN.png) | ![Correo de Eliminación](./images/FOTOMAILDELETE.png) |
