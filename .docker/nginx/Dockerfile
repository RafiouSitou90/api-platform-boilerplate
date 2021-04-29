ARG NGINX_VERSION=1.19

FROM nginx:${NGINX_VERSION}-alpine AS symfony_nginx

ARG WORKDIR=/var/www/app

RUN rm -rf /etc/nginx/conf.d/default.conf

COPY ./conf.d/default.conf /etc/nginx/conf.d/

WORKDIR ${WORKDIR}

EXPOSE 80
