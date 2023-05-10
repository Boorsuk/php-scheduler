FROM php:8.1-cli

RUN apt-get update && apt-get install -y \
    git \
    curl \
    vim \
    tree \
    zsh

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install oh-my-zsh
RUN sh -c "$(curl -fsSL https://raw.github.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"


WORKDIR /scheduler