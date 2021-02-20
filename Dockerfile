FROM ulsmith/alpine-apache-php7

ADD /public /app/public
RUN chown -R apache:apache /app
