FROM ubuntu:latest
RUN apt-get update && apt install -y haproxy nginx curl keepalived
RUN rm -rf /var/www/html/index.nginx-debian.html
# RUN echo hello_${NINJA} > /var/www/html/index.html
COPY ./web/index.html /var/www/html/index.html
COPY ./web/default /etc/nginx/sites-available/default
COPY ./ha/haproxy.cfg /etc/haproxy/haproxy.cfg
COPY ${keep} /etc/keepalived/keepalived.conf
ENTRYPOINT service ${service_name} start && service ${service_2} start && /bin/bash
