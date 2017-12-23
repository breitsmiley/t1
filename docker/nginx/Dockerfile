FROM nginx:1.13-alpine

COPY /docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY /public /srv/blog/public

# Add tool for waiting available of other service
COPY /docker/wait-for /usr/local/bin/wait-for
RUN chmod +x /usr/local/bin/wait-for