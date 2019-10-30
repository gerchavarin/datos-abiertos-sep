# Datos Abiertos SEP
Extraer los datos de los programas de estudios a través de los endpoint de SIRVOES

## Instalación

### Ejecute los siguientes comandos en su máquina host:

*  Clonar el repositorio con git clone
*  `cp .env.example .env`
*  (Editar archivo .env)
*  `composer install`
*  `php artisan key:generate`
*  `php artisan migrate`

### Comandos

Comando para extraer los datos de la página de SIRVOES
`php artisan sirvoes:get`

Comando para exportar la base de datos en un archivo Xlsx
`php artisan excel:generate`