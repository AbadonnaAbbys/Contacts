FROM ubuntu:14.04.4

MAINTAINER okto-digital

# Prepare Debian environment
ENV DEBIAN_FRONTEND noninteractive

# Performance optimization - see https://gist.github.com/jpetazzo/6127116
# this forces dpkg not to call sync() after package extraction and speeds up install
RUN echo "force-unsafe-io" > /etc/dpkg/dpkg.cfg.d/02apt-speedup
# we don't need an apt cache in a container
RUN echo "Acquire::http {No-Cache=True;};" > /etc/apt/apt.conf.d/no-cache

#software-properties-common
RUN apt-get update && \
	apt-get install -y \
		apt-transport-https \lsb-release ca-certificates \
		software-properties-common \
		git curl wget vim mc && \
		add-apt-repository ppa:ondrej/php && \
		sudo apt-key adv --keyserver keyserver.ubuntu.com --recv-keys 14AA40EC0831756756D7F66C4F4EA0AAE5267A6C && \
		# echo "deb http://ppa.launchpad.net/ondrej/php/ubuntu trusty main" | tee /etc/apt/sources.list.d/php.list && \
		# echo "deb-src http://ppa.launchpad.net/ondrej/php/ubuntu trusty main" | tee /etc/apt/sources.list.d/php.list && \
	apt-get update

# install php, nginx...
RUN apt-get update && \
    apt-get install -y \
		php7.2 \
		php7.2-common \
        php7.2-fpm \
        php7.2-mbstring \
        php7.2-curl \
        php7.2-json \
        php7.2-cli \
        php7.2-mysql \
        php7.2-intl \
        php7.2-xsl \
        php7.2-zip \
        php7.2-bcmath \
        php-imagick \
		nginx \
        curl \
		mysql-client \
        imagemagick \
        vim \
        git \
        net-tools \
        telnet \
        mc && \
        apt-get clean

# remove unnecessary files
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /var/lib/apt/lists/*
RUN echo "cgi.fix_pathinfo = 0;" >> /etc/php/7.2/fpm/php.ini
RUN echo "daemon off;" >> /etc/nginx/nginx.conf

# nginx config
ADD nginx.conf /etc/nginx/sites-available/default

# install npm nodejs npm
# ln -s $(which nodejs) /usr/bin/node && \
# https://github.com/nodesource/distributions#installation-instructions
RUN curl -sL https://deb.nodesource.com/setup_6.x | bash - && \
    apt-get install -y nodejs

# install gulp
RUN npm install gulp-cli -g && \
	npm install gulp -D && \
	npm install --save gulp-uglify && \
	npm install --save gulp-css && \
	npm install --save gulp-rename && \
	npm install -g less && \
	npm install apidoc -g

# install composer
ENV composer_hash 544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" &&  \
	php -r "if (hash_file('SHA384', 'composer-setup.php') === '${composer_hash}') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" &&  \
	php composer-setup.php --install-dir=/usr/bin --filename=composer && \
	php -r "unlink('composer-setup.php');"

RUN useradd -G www-data -ms /bin/bash deployer

# installs the composer asset plugin which allows managing bower and npm package dependencies through Composer
# https://github.com/yiisoft/yii2-app-advanced/blob/master/docs/guide/start-installation.md
USER deployer
ADD vimrc /home/deployer/.vimc
RUN composer global require "fxp/composer-asset-plugin:^1.2.0"

# add command vendor/bin of project
#RUN export PATH="$PATH:/var/www/html/bin"
# ENV PATH /project/vendor/bin:${PATH}
ENV PATH /var/www/library:${PATH}

USER root
ADD vimrc /root/.vimc

WORKDIR /var/www/

EXPOSE 80
