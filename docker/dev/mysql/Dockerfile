FROM mysql:8.0.0

ARG TZ=UTC
ENV TZ ${TZ}
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN chown -R mysql:root /var/lib/mysql/

# COPY dump.sql /docker-entrypoint-initdb.d/dump.sql

CMD ["mysqld"]

EXPOSE 3306
