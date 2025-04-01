# MKL Financiero

## Requisitos Previos
- PHP 8.0 o superior
- Composer
- Node.js y NPM

## Pasos de Instalación

### 1. Instalación de Composer
- Descarga Composer desde la [página web oficial](https://getcomposer.org/download/)
- Sigue las instrucciones de instalación según tu sistema operativo
- Verifica la instalación ejecutando:
  ```bash
  composer --version
  ```

### 2. Instalación de Dependencias PHP
```bash
composer install
```

### 3. Configuración del Entorno
- Copia el archivo `.env.example` a `.env`
- Genera la clave de la aplicación:
  ```bash
  php artisan key:generate
  ```

### 4. Instalación de Dependencias de Node.js
```bash
npm install
```

### 5. Compilación de Assets
```bash
npm run build
```

### 6. Iniciar el Servidor de Desarrollo
```bash
php artisan serve
```

## Notas Importantes
- Asegúrate de tener instalado PHP 8.0 o una versión superior
- Verifica que todas las extensiones requeridas de PHP estén habilitadas
- El proyecto debe tener permisos de escritura en las carpetas de almacenamiento y caché

## Problemas Comunes
Si encuentras algún problema durante la instalación, verifica:
1. Que todas las dependencias estén correctamente instaladas
2. Los permisos de los directorios
3. La configuración de tu servidor web
