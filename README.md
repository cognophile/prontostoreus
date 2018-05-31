# Protostoreus
## Immediate comparison of, access to, and reservation of local self-storage.

## Requirements
- All CakePHP dependencies, listed later in this file. See to it that these are installed first.
- This project requires PHP >=7.0

## Installation and Configuration 
- Download and use composer as detailed by the CakePHP section of this file.
- Restore (or update) the dependancies required by this project: `php composer.phar update`
- You may need to set permissions as such over the `tmp/` and `logs/` directories if using a dedicated web server. 

    ```
    HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
    setfacl -R -m u:${HTTPDUSER}:rwx tmp
    setfacl -R -d -m u:${HTTPDUSER}:rwx tmp
    setfacl -R -m u:${HTTPDUSER}:rwx logs
    setfacl -R -d -m u:${HTTPDUSER}:rwx logs
    ```
- Give your user execution permission over the Cake CLI helper: `chmod +x bin/cake`
    - If this causes issues, try running like so: `php bin/cake.php`
- Copy the `app.default.php` file to a new `app.php` file and modify those parts required: eg. Security Salts, Datasources, Testing, etc.
- Copy the `.env.default.php` file to a new `.env.php` file and modify those parts required.

## Database configuration
Having set up your MySQL server and its root user, edit the following example file to insert a password suitable for your chosen level of security, and execute it as the root MySQL user. 

Run the following commands to create the prontostoreus user and create the database. 

```
cp database/spawn-db.sql.example database/spawn-db.sql
vi spawn-db.sql 
    insert a password into the file
mysql -u root -p < database/spawn-db.sql
```

Enter this newly created information into the `config/app.php` file. Under the `Datasources` key, ensure `MySql` is set as the driver type, and amend the following keys: 
```
'host' => 'localhost',
  ...
'username' => 'prontostoreus',
'password' => 'your-password',
'database' => 'prontostoreus',
```

Install the PHP MySql extension package: `sudo apt-get install php7.0-mysql`

Then run all migrations to create the database schema on a per-component basis. These must be run in order of component: 
1. Location
2. Application
3. Submission
4. Confirmation

- Check status: `bin/cake migrations -p status <name>Component`
- Migrate up: `bin/cake migrations -p migrate <name>Component`
- Rollback: `bin/cake migrations -p rollback <name>Component`

---

## CakePHP Application Skeleton

[![Build Status](https://img.shields.io/travis/cakephp/app/master.svg?style=flat-square)](https://travis-ci.org/cakephp/app)
[![Total Downloads](https://img.shields.io/packagist/dt/cakephp/app.svg?style=flat-square)](https://packagist.org/packages/cakephp/app)

A skeleton for creating applications with [CakePHP](https://cakephp.org) 3.x.

The framework source code can be found here: [cakephp/cakephp](https://github.com/cakephp/cakephp).

### Requirements
Cake requires the following modules (at least) be installed, for whatever version of PHP you're using. 

 - `intl`
 - `mbstring`
 - `xml`

### Installation

1. Download [Composer](https://getcomposer.org/doc/00-intro.md) or update `composer self-update`.
2. Run `php composer.phar create-project --prefer-dist cakephp/app [app_name]`.

If Composer is installed globally, run

```bash
composer create-project --prefer-dist cakephp/app
```

In case you want to use a custom app dir name (e.g. `/myapp/`):

```bash
composer create-project --prefer-dist cakephp/app myapp
```

You can now either use your machine's webserver to view the default home page, or start
up the built-in webserver with:

```bash
bin/cake server -p 8765
```

Then visit `http://localhost:8765` to see the welcome page.

### Update

Since this skeleton is a starting point for your application and various files
would have been modified as per your needs, there isn't a way to provide
automated upgrades, so you have to do any updates manually.

### Configuration

Read and edit `config/app.php` and setup the `'Datasources'` and any other
configuration relevant for your application.

### Layout

The app skeleton uses a subset of [Foundation](http://foundation.zurb.com/) (v5) CSS
framework by default. You can, however, replace it with any other library or
custom styles.
