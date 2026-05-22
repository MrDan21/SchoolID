# Sistema de Control de Asistencia Escolar Biométrico

Este proyecto es una solución para el registro de asistencia mediante reconocimiento facial. Conformado por:
1. **Frontend y Backend Administrativo:** Desarrollado en Laravel.
2. **Microservicio de Reconocimiento Facial:** Desarrollado en Python con FastAPI.

---

## Arquitectura del Sistema

- **Laravel** Esel panel de administración web, y expone la API para verificar los rostros de los estudiantes.
- **FastAPI** Es un microservicio para recibir una imagen de la fotografía y devolver un vector numérico (Embedding) que representa las facciones matemáticas del rostro.

---

## 1. Instalación y Configuración del Microservicio (FastAPI)

El microservicio debe estar ejecutándose para que Laravel pueda validar los rostros.

### Requisitos previos
- Python 3.9 o superior.
- `pip` instalado.

### Pasos
1. Abre una terminal y navega a la carpeta del microservicio:
   ```bash
   cd fastapi-microservice
   ```
2. Crea un entorno virtual e inicialízalo:
   ```bash
   python -m venv venv
   # En Windows:
   venv\Scripts\activate
   # En Mac/Linux:
   source venv/bin/activate
   ```
3. Instala las dependencias necesarias:
   ```bash
   pip install -r requirements.txt
   ```
4. Ejecuta el servidor:
   ```bash
   uvicorn main:app --reload
   ```
El servidor se levantará en `http://127.0.0.1:8000`.

*(Nota: Asegúrate de configurar un API Key en tu código de FastAPI para proteger el endpoint contra accesos no autorizados).*

---

## 2. Instalación y Configuración de la Web (Laravel)

### Requisitos previos
- PHP 8.2+
- Composer
- Base de datos MySQL
- Node.js y npm

### Pasos
1. Clona el repositorio e instala las dependencias de PHP y Node:
   ```bash
   composer install
   npm install
   npm run build
   ```
2. Copia el archivo de entorno y genera la llave de la aplicación:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
3. Configura tu `.env` con las credenciales de tu base de datos y la conexión al microservicio:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=tu_base_de_datos
   DB_USERNAME=root
   DB_PASSWORD=

   # --- CONEXIÓN AL MICROSERVICIO PYTHON ---
   FASTAPI_URL=http://127.0.0.1:8000
   FASTAPI_KEY=
   
   # --- CONEXIÓN PARA LA ESP32-CAM ---
   ESP32_API_TOKEN=
   ```
4. Ejecuta las migraciones y vincula la carpeta storage:
   ```bash
   php artisan migrate --seed
   php artisan storage:link
   ```
5. Inicia el servidor:
   ```bash
   php artisan serve
   ```

---

## 3. Uso de la API para Hardware (ESP32-CAM)

Para registrar una asistencia desde la ESP32-CAM.

### Endpoint
`POST /api/attendances`

### Headers requeridos
Para autenticar el dispositivo, debes incluir el token que configuraste en tu `.env` (`ESP32_API_TOKEN`). Y agregar las siguientes cabeceras:
- `Authorization: Bearer {ESP32_API_TOKEN}`
- `Accept: application/json`

### Body (Form-Data)
- `photo`: Archivo de imagen (JPG/PNG).

### Ejemplo de Petición en C++ (ESP32)
```cpp
HTTPClient http;
http.begin("http://tu-dominio.com/api/attendances");
http.addHeader("Authorization", "");
// Enviar request con la imagen en multipart/form-data
```

### Respuestas de la API
**Éxito (200 OK):**
```json
{
    "success": true,
    "message": "Asistencia registrada para: Juan Pérez",
    "student": {
        "id": 1,
        "name": "Juan Pérez",
        "matricula": "A00123"
    },
    "event": "in"
}
```

**Rostro No Reconocido (404 Not Found):**
```json
{
    "error": "Rostro no reconocido en la base de datos."
}
```
