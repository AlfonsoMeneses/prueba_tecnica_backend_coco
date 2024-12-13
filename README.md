# Prueba técnica Desarrollador Backend - COCO 
# Documentación de la API

## 1. Estructura y Diseño del Sistema
La API está desarrollada en Laravel, utilizando la arquitectura por capas de esta manera:

```
├── app
│   ├── DTOs
│── │── Enums
│   ├── Exceptions
│   ├── Factories
│   ├── Http
│   │   ├── Controllers
│   │   │   ├─Api
│   ├── Interfaces
│   ├── Models
│   ├── Providers
│   ├── Repositories
│   ├── Services
├── bootstrap
├── config
├── database
│   ├── factories
│   ├── migrations
│   ├── seeders
├── public
├── routes
│   ├── api.php
│   ├── web.php
├── tests
│   ├── Feature
│   ├── Unit
```

### Componentes Principales
- **DTOs**: Objetos que se utilizaran para la transferencia de datos para cada situación, son objetos para separar capas.
- **Factories**: Siguiendo el patrón Factory, permitiendo generar objetos, dependiendo de la necesidad. En este escenario, permite crear un servicio que se encarga de crear una reservación, dependiendo del tipo de recurso, al cual se le está generando la reserva
- **Http/Controllers/Api**: Se manejan los endpoints de la API, en los cuales se obtiene los datos de la petición, y delega a los servicios para realizar dichas solicitudes.
- **Interfaces**: Se encuentras todas las interfaces, para los servicios y los repositorios, y esto permite la creación de componentes reutilizables, sin modificar parte importante en la aplicación. 
- **Models**: Los modelos relacionados con las tablas de la base de datos.
- **Providers**: En este caso, nos permite realizar la relación entre las interfaces y las clases que las implementa, es decir los servicios y repositorios, permitiendo la inyección de dependencias, lo que nos ayuda a lo explicado por las interfaces.
- **Repositories**: Los repositorios, que implementan las interfaces asociadas a los repositorios, se utilizan siguiendo el patrón Repository. Se encarga del manejo de los modelos y el manejo de datos, en este caso, la conexión con la base de datos (PostgreSQL).
- **Services**: Acá se encuentran todos los servicios que fueron implementados por interfaces, y que utiliza el controlador parar delegar las funciones solicitadas en los endpoints. Los servicios se encargan de la lógica de negocio.
- **database/migrations**: Se encuentran las migraciones, con la configuración de las tablas que se van a crear en la base de datos. los cuales se utilizarán al invocar el comando "migrate" de artisan.
- **routes/api.php**: La lista de las rutas de la API.
- **tests/Feature**: Pruebas unitarias para los endpoints de la API, validando los resultados esperados.

### Base de Datos
El diseño de la base de datos se puede revisar en la imagen BD/diagrama.png
Si se quiere crear la base de datos, se encuentra el script en BD/create_db.sql

## 2. Decisiones de Diseño
Se utilizaron los patrones de Repository, Factory, Capa de Servicios y la inyección de dependencias.
Con este diseño podemos centralizar o encapsular la lógica de negocio, en la capa de servicios, el manejo la consulta y funciones con la base de datos en los repositorios. 
El uso del patrón Factory, nos permite, crear varias opciones de crear una reserva, dependiendo del tipo de recurso que se utiliza, esto nos ayuda a realizar ajustes por nuevos tipos de recursos, sin afectar la lógica de negocios.
Esto nos permite reducir el acoplamiento entre clases. (principios SOLID).

## 3. Instrucciones de Configuración

### Requisitos Previos
- PHP >= 8.1
- Composer
- PostgreSQL

### Instalación
1. Crear la base de datos, si no existe alguna, se encuentra el script en BD/create_db.sql para la creación.

2. Configura el archivo `.env` 
    ```
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=reservation_db (o el nombre de la base de datos ya existente)
    DB_USERNAME=usuario
    DB_PASSWORD=contraseña
    ```
3. Migrar la base de datos
    ```
    php artisan migrate
    ```

Con estos pasos, ya se puede ejecutar la API 
```
php artisan serve
```

Además, también se pueden realizar las pruebas unitarias
```
php artisan test
```
Para realizar las pruebas es recomendable verificar los datos que se encuentran en las pruebas, es muy posible que la prueba "CancelReservationEndpointsTest", se necesite hacer ajustes, a diferencia de las demás.
