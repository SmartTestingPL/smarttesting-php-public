FROM php:7.4-zts-alpine AS build

RUN apk update && \
    apk add --no-cache $PHPIZE_DEPS git

RUN git clone https://github.com/krakjoe/parallel

WORKDIR /parallel

RUN phpize

RUN ./configure

RUN make install

RUN EXTENSION_DIR=`php-config --extension-dir 2>/dev/null` && \
	cp "$EXTENSION_DIR/parallel.so" /parallel.so

RUN sha256sum /parallel.so

FROM php:7.4-zts-alpine

COPY --from=build /parallel.so /parallel.so

RUN EXTENSION_DIR=`php-config --extension-dir 2>/dev/null` && \
	mv /parallel.so "$EXTENSION_DIR/parallel.so" && \
	docker-php-ext-enable parallel
