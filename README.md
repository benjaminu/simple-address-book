simple-address-book
===================

My simple web based symfony2 address book

Installation
===================
1) Clone the git Repository
---------------

    git clone git@github.com:benjaminu/simple-address-book.git


Note that you **must** have git installed and be able to execute the `git` commands to execute this script.

2) Installation
---------------

### a) Check your System Configuration

Before you begin, make sure that your local system is properly configured
for Symfony. To do this, execute the following:

    php app/check.php

If you get any warnings or recommendations, fix these now before moving on.

For example. fixing warnings about permissions for app/cache and app/logs directories:

    rm -rf app/cache app/logs
    mkdir app/cache app/logs
    sudo chmod -R 777 app/cache app/logs

### b) Setup database config

Open app/config and copy parameters.example.yml to parameters.yml (parameters.yml is in the .gitignore file, this is for local use only so do not commit it!)

Edit the parameters.yml file and add the following details.

    [parameters]
        database_driver:   pdo_mysql
        database_host:     127.0.0.1
        database_port:     ~
        database_name:     your_database_name (see MYSQL Notes)
        database_user:     your_username (see MYSQL Notes)
        database_password: your_password (see MYSQL Notes)

        mailer_transport: smtp
        mailer_host:      localhost
        mailer_user:      ~
        mailer_password:  ~

        locale:    en
        secret:    ThisTokenIsNotSoSecretChangeIt     (please change this)
        intention: ThisTokenIsAlsoNotSoSecretChangeIt (please change this)

### c) Install the Vendor Libraries

Run the following:

    composer -n install

Note that you **must** have composer installed and be able to execute the `composer` command to execute this script.

### d) Create database/update schema.
    
    php app/console doctrine:database:drop --force
    php app/console doctrine:database:create
    php app/console doctrine:schema:update --force
    php app/console assets:install --symlink web