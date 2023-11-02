# Saka Calendar Converter (Indian National Calendar)

## Configuration

Requirement:
- [PHP](https://www.php.net/) >= 5.x
- [Elasticsearch](https://www.elastic.co/elasticsearch/) 5.6
- [MySQL](https://www.mysql.com/) 5.7
- [Git](https://git-scm.com/)

### Development environment

Packages:

- Linux e.g. [Ubuntu](https://ubuntu.com/) 16.04 LTS or [macOS](https://www.apple.com/macos/)
- [Apache](https://httpd.apache.org/) 2.4
- [PHP](https://www.php.net/) 7.0
- [Elasticsearch](https://www.elastic.co/elasticsearch/) 5.6
- [MySQL](https://www.mysql.com/) 5.7
- [Yarn](https://yarnpkg.com/)
- [Ruby](https://www.ruby-lang.org/en/) 2.6
- [Git](https://git-scm.com/)

Language packages:

- [Composer](https://getcomposer.org/) (PHP) 1.x
- [Capistrano](https://capistranorb.com/) (Ruby) 3.x

#

### System packages

#### Ubuntu

Ubuntu 16.4 has PHP 7.0 in it default repository so install the main packages, including the required php extensions:

```
sudo apt install apache2 mysql-server ruby git php7.0 php7.0-cli php7.0-common php7.0-curl php7.0-gd php7.0-intl php7.0-json php7.0-mbstring php7.0-mcrypt php7.0-mysql php7.0-opcache php7.0-readline php7.0-xml php7.0-zip php-imagick
```

Elasticsearch 5.6 can be found here:

https://www.elastic.co/guide/en/elasticsearch/reference/5.6/deb.html

#### macOS

Install Xcode

```
xcode-select --install
```

Install homebrew, instructions are here: https://brew.sh/

```
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

Install openssl apache, mysql, git, ruby, ImageMagick

```
brew install openssl httpd mysql@5.7 git ruby imagemagick
```

Add tap for php 7.0

```
brew tap shivammathur/php
brew install shivammathur/php/php@7.0
```

install additional php extensions with pecl

```
pecl install apcu imagick yaml
```

install elasticsearch

```
brew install elasticsearch@5.6
```

### Language packages

Install composer globally, following the instruction here:

https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos

Capistrano is a Ruby gem install with the following

```
sudo gem install capistrano
sudo gem install capistrano-symfony
```

#

## Prerequisites

You will need settings/variables for the following

### MySQL

- Database
- Database user
- Database password

### Elasticsearch

- host
- port
- username and password, if configured

### Sign in with Google

You will need to create credentials here:

https://developers.google.com/identity/sign-in/web/sign-in

You will need to add them to parameters.yml in the next step

- google_app_id:
- google_app_secret:

#

## Application stack

### Symfony: /src

[Symfony](https://symfony.com/) 3.4 LTS

### Frontend JS & CSS: /assets

Assets are managed with [Symfony Encore](https://symfony.com/doc/3.4/frontend/encore/installation.html) which sets up a [Webpack](https://webpack.js.org/) environment

Core libraries, see package.json

- [Bootstrap](https://getbootstrap.com/)
- [D3](https://d3js.org/)
- [Leaflet](https://leafletjs.com/)
- [Vis](https://visjs.org/)

## Local installation

Checkout source code

```
git clone git@github.com:hridigital/jaina-prosopography.git
```

Install php packages with Composer, including Symfony

```
composer install
```

Update parameters.yml with MySQL, Elasticsearch and secrets

```
#app/config/parameters.yml

# This file is a "template" of what your parameters.yml file should look like
# Set parameters here that may be different on each deployment target of the app, e.g. development, staging, production.
# http://symfony.com/doc/current/best_practices/configuration.html#infrastructure-related-configuration
parameters:
    #MySql database settings
    database_host: ~
    database_port: ~
    database_name: ~
    database_user: ~
    database_password: ~
    # You should uncomment this if you want to use pdo_sqlite
    #database_path: "%kernel.root_dir%/data.db3"

    #not required for Jaina as it does not send email
    mailer_transport: smtp
    mailer_host: 127.0.0.1
    mailer_user: ~
    mailer_password: ~

    # A secret key that's used to generate certain security-related tokens
    # random string to e.g. as generated with `pwgen -s 40`
    secret: ~

    # hashed htauth password
    admin_password: ~

    #sign in with google settings
    google_app_id: 1
    google_app_secret: a

    #elasticsearch settings
    elasticsearch_host: localhost
    elasticsearch_port: 9200
    elasticsearch_username: ~
    elasticsearch_password: ~

    #required for dumping routes,
    #especially for js routing bundle as the generated from command line,
    #as Symfony cannot determine these automatically from the http server
    #this is also required for production as routes are build on the
    #production server durning deployment.
    router.request_context.host: localhost:8000
    router.request_context.scheme: http
    router.request_context.base_url: /
```

Drop and have Symfony create the initial database

```
php bin/console doctrine:database:drop  --force
php bin/console doctrine:database:create
```

Create tables with one of the following options:

- Restore database from a dump
- Create schema
- Run migrations

#### Restore a dump

Use mysqldump or alternate tool.

#### Create schema directly without migrations

```
php bin/console doctrine:schema:create
```

#### Create schema with migrations

```
php bin/console doctrine:migrations:migrate
```

#### Populate elasticsearch index with

```
php bin/console fos:elastica:populate
php bin/console jaina:reset:relationship
php bin/console jaina:index:relationship
```

### Assets including JS and CSS

#### Install node module

```
yarn install
```

#### Build frontend assets including JS and CSS

```
yarn encore dev
```

#

## Local Development

### Run Symfony

Either

```
php bin/console server:run
```

By default this will run on http://localhost:8000/

Alternatively configure Apache to server as per production, for development set web/app_dev.php as the index.

## Building assets

Install packages with:

```
yarn install
```

One time build assets for development environment:

```
yarn encore dev
```

Continuous build of assets with hot reload:

```
yarn encore dev-server
```

One time build assets for production environment:

```
yarn encore prod
```

### Tests

Run tests with php unit

```
./vendor/bin/simple-phpunit src/AppBundle/Tests
```

Or speed up and run with paratest to run tests is parallel

```
./vendor/bin/paratest src/AppBundle/Tests
```

#

## Deployment to production

### Example Apache configuration

Application deployed to /var/www/html/jaina-prosopography/current/web

```
Alias /jaina /var/www/html/jaina-prosopography/current/web
<Directory "/var/www/html/jaina-prosopography/current/web">
        AllowOverride None
        Order Allow,Deny
        Allow from All
        #http basic auth - remove to make site public
    	AuthType Basic
    	AuthName "Restricted Content"
    	AuthUserFile /etc/apache2/htpasswd/jaina
   	    Require valid-user
        #

        <IfModule mod_rewrite.c>
        Options -MultiViews

        RewriteEngine On
        <IfModule mod_vhost_alias.c>
            RewriteBase /jaina
        </IfModule>
                RewriteCond %{REQUEST_FILENAME} !-f
                RewriteRule ^(.*)$ app.php [QSA,L]
        </IfModule>
</Directory>
```

### Deployment overview

Deployment is performed with Capistrano which deploys from the master branch of GitHub Repository. Each deploy will create an new release directory base on the current stat of master. Capistrano will symlink persistent files such as parameter.yml from the shared directory it creates during the initial deploy.

The script builds assets on the local machine and copies them with SCP to production server, this therefore requires that Yarn in installed on the local deployment machine. This is defined in tasks at the end of config/deploy.rb

### Capistrano configuration

Update the core settings required for the deployment, the git repository, deployment directory and and production server domain name.

#### config/deploy.rb

```
set :repo_url, "git@github.com:hridigital/jaina-prosopography.git"
set :deploy_to, '/var/www/html/jaina-prosopography'
```

#### config/deploy/prod.rb

```
server 'hric.shef.ac.uk', roles: %w{app db web}
```

### First deploy

Run

```
cap production deploy
```

This will have created the skeleton directory structure on the server.

### Database parameter.yml

Create the production database and credentials

Create a parameter.yml in shared/app/config on the production server

Populate with the database and other required settings

Redeploy to complete first deploy

```
cap production deploy
```

Setup production database as in same way for development, from a MySql dump or have Symfony create it for you.

Create the elasticsearch index,

```
php bin/console fos:elastica:populate --env=prod
php bin/console jaina:reset:relationship --env=prod
php bin/console jaina:index:relationship --env=prod
```

### Subsequent deployments

```
cap production deploy
```

#

## Cron

The elasticsearch index will periodically need to be rebuilt to reflect the changes made to the MySQl database.

Use Cron to update elasticsearch index with a shell script.

e.g. create a script in /etc/cron.daily like this:

```

#!/bin/sh

# cd to the directory where jaina has been deployed on the server

cd /var/www/html/jaina-prosopography/current

# Run the ES index populate command for Jaina.

php bin/console fos:elastica:populate --env=prod

# Run the commands to build ES network index

php bin/console jaina:reset:relationship --env=prod
php bin/console jaina:index:relationship --env=prod

```

#

## Admin users

User account are handled by OAuth via a Google account.
After a user signs up they will have an entry in the fos_user table.
Their id is the google generated id, as displayed on the top right of the nav bar

To grant admin access use the FOSUserBundle command to promote an existing user with following roles

```
ROLE_ADMIN
ROLE_SONATA_ADMIN
```

Run

```
php bin/console fos:user:promote
```

Enter the user id and the required role when promoted
