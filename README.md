**Pasos para la descarga y prueba de la aplicación:**

1. clonar el repositorio (menos la persona quien creo el repo)

2. instalar las dependencias que faltan mediante el comando `composer install`

   > puede que, por problemas de compatibilidad, nos pida correr el comando`composer update`

3. crear el fichero `.env` en el directorio principal del proyecto con el contenido pertinente

   > se puede utilizar el fichero `.env.example` para generar el nuevo `.env`

4. finalmente, Laravel puede pedir que se ejecute el comando `php artisan key:generate` para generar una nueva variable de entorno APP_KEY

5. crear la base de datos mediante migraciones o manualmente