# Protostoreus
## Immediate comparison of, access to, and reservation of local self-storage.
This project was produced as an accompanying artefact to a dissertation project. This project focused on attempting to answer the question: How do we architect software to be both extensible and maintainable to prevent stagnation, remain agile and proactive to business change, and to explore the architectural patterns and methods which provide these qualities (and more) and how they might oppose, relate, or cooperate with one another.

 As a brief overview, this repository is the backing API for the Prontostoreus client (see sibling repository on profile). It has been architected to separate the platform from the business components which provide functionality. As such, the API platform uses an architecture similar to the Mediator design pattern and Micro-kernal architecture where it is only responsible for establishing the platform, handling the request-response cycle, hosting, and routing to components.  

 For the components (component-based software), these are included using the plugin architecture model. CakePHP was chosen as the framework tool as it provides easy component creation and inclusion tooling, configuration, and resolution. These components are self-contained - defining their own routes for the API to serve, including all database migrations for any tables it depends on which are designed to be idempotent. 
 
 his means, that components are entirely optional - though all are included in this repository, the maintainers will have the ability to add, extend, maintain, and remove components from the platform at will, with no affect on other compoents as all are treated and designed as independantly developable and deployable entities. The inner architecture of the components uses the MVC layering pattern. 

 The components are included in the repository (but are capable of being held within their own repositories).

## Requirements
- All CakePHP dependencies, listed later in this file. See to it that these are installed first.
- This project requires PHP >=7.0.
- Download (or globally install) the Composer package manager. 
- Cake requires the following modules (at least) be installed, for whatever version of PHP you're using. 
    - `intl`
    - `mbstring`
    - `xml`

If you're on macOS, the following medium post is [a great how-to guide](https://medium.com/@jjdanek/installing-php-extensions-on-mac-after-homebrew-acfddd6be602) ([by John Danek](https://medium.com/@jjdanek)) to installing a version of PHP with all these extra modules by default, via [Homebrew](https://brew.sh/).

For those on a Linux environment, all version of PHP and its extension modules are available through `apt-get`.

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

Install the PHP MySql extension package: `sudo apt-get install php7.0-mysql`, if on Linux. For macOS and Windows, you can download the community version of MySQL from their website, [here](https://dev.mysql.com/downloads/mysql/). 

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
