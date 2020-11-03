FROM registry.gitlab.cdn77.eu/ci-cd/images/php7.4

ENV PHP_VERSION 7.4

RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        php${PHP_VERSION}-pcov \
        && \
    rm -rf /var/lib/apt/lists/*
