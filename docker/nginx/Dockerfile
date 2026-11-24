# Imagen de nginx con los archivos públicos de la app incluidos.
# En Portainer (stack desde Git) no se puede bind-montar el repo: el código
# debe venir dentro de la imagen. Nginx solo necesita el directorio public/
# (index.php y estáticos); la ejecución de PHP la hace el contenedor `app`.
FROM nginx:alpine

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY public /var/www/public
