# CI3 Starter Kit #
El paquete CI3 Starter Kit contiene todo lo necesario para empezar un desarrollo de un sistema básico con:

- Panel de control
- Usuarios
- Roles
- Permisos
- Perfil

Con una estructura fácil de usar, configurar y extender con pocos conocimientos básicos de programación en lenguaje PHP y el framework PHP Codeingiter v3.x.

# Instalación #
1. Descargue y descomprima el archivo *"ci3-starterkit-free.zip"* en un carpeta temporal en su equipo
2. Copie todo el contenido interno de la carpeta del anterior paso en su servidor mediante un programa FTP (Filezilla, etc.) o el panel de control del servidor (Cpanel, Virtualmin, etc.)
	1. Si es un servidor compartido (bluehost, hostgator, etc.) copiarlo en la carpeta *"./nombre_usuario_del_hosting/public_html/"*
	2. Si es un servidor privado o dedicado (VPS) mayormente se debe copiar el contenido en la carpeta *"/var/www/html/"*
3.  Se debe crear una base de datos a la que se pueda acceder con nombre de usuario y contraseña, y tener estos datos a la mano
5.  Importar en la base de datos el archivo *"./installation/db.sql"* que contiene todos los datos necesarios para utilizar el sistema.
4.  Se debe cambiar los parámetros del archivo *"./application/config/database.php"* con los parámetros del anterior paso como se muestra en el siguiente ejemplo:
5. Se debe editar el archivo ".htaccess" en la configuración de "RewriteBase" a la dirección donde se encuentra el sistema, ej: *"RewriteBase /"* cuando esta la aplicación esta en la raíz o *"RewriteBase /mi_carpeta/"* cuando el sistema esta en la carpeta "mi_carpeta".  
6.  Se debe adicionar permisos de escritura (755 o 777) a la carpeta *"./assets/uploads/*"* y la carpeta  *"./assets/uploads/usuarios/*"* para que el sistema pueda subir archivos a esa carpeta.

# Librerías incluidas #
## Librerías en el servidor ##
El sistema utiliza las siguientes librerías las cuales no deben ser modificadas si es que no se tienen conocimiento de su funcionamiento siendo que se deben incluir de manera obligatoria para que el sistema funcione correctamente:

- Ignited Datatables [MIT](https://www.tldrlegal.com/l/mit)
- Form Generation Class [MIT](https://www.tldrlegal.com/l/mit)
- CodeIgniter Template Class [MIT](https://www.tldrlegal.com/l/mit)
- When [https://github.com/tplaner/When](https://github.com/tplaner/When "When") [MIT](https://www.tldrlegal.com/l/mit)

## Interfaz del usuario ##
El sistema bajo requerimiento de cambios en la presentación como mínimo requiere las siguientes librerías para un correcto funcionamiento si es que no se hayan realizados cambios:

- Bootstrap  [MIT](https://www.tldrlegal.com/l/mit)
- Datatables [MIT](https://www.tldrlegal.com/l/mit)
- BootboxJs  [MIT](https://www.tldrlegal.com/l/mit)
- Bootstrap-datepicker  [Apache](https://www.tldrlegal.com/l/apache2)
- Slicknav  [MIT](https://www.tldrlegal.com/l/mit)
- Chartist  [MIT](https://www.tldrlegal.com/l/mit)
- Jquery-toast-plugin  [MIT](https://www.tldrlegal.com/l/mit)
- Multiselect  [MIT](https://www.tldrlegal.com/l/mit)

Para lo cual se deben poner de forma expresa las licencias de cada librería si es que aquellas lo requieran.

# Licencia #
El presente esta licenciado bajo la licencia MIT, vea el siguiente enlace para ver mas detalles "[MIT](https://www.tldrlegal.com/l/mit)"

Made with caffeine in [Bolivia Software](https://www.boliviasoftware.com/)