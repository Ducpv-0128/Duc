FROM ha/keep
COPY haproxy.cfg /etc/haproxy/haproxy.cfg
COPY keepalived.conf /etc/keepalived/keepalived.conf
CMD [ "echo "net.ipv4.ip_nonlocal_bind=1" >> /etc/sysctl.conf" ]
ENTRYPOINT service haproxy start && service keepalived start && /bin/bash
