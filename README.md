Yii 2 Advanced Application Template With RBAC
===================================

Yii 2 Advanced Application Template With RBAC user management tool is a skeleton Yii 2 application best for
developing complex Web applications with multiple tiers.

The template includes three tiers: front end, back end, and console, each of which
is a separate Yii application.

The template is designed to work in a team development environment. It supports
deploying the application in different environments.

Also it includes nice user management system in backend, and custom layout system.


DIRECTORY STRUCTURE
-------------------

```
common
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
backend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains backend configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    runtime/             contains files generated during runtime
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
frontend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
tests                    contains various tests for the advanced application
    codeception/         contains tests developed with Codeception PHP Testing Framework
```


REQUIREMENTS
------------

The minimum requirement by this application template that your Web server supports PHP 5.4.0.


INSTALLATION
------------

### Install via Composer

If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

You can then install the application using the following command:

~~~
php composer.phar global require "fxp/composer-asset-plugin:1.0.0"
php composer.phar create-project --prefer-dist dezmont765/yii2-rbac-advanced-template
~~~


GETTING STARTED
---------------

After you install the application, you have to conduct the following steps to initialize
the installed application. You only need to do these once for all.

1. Run command `php init` to initialize the application with a specific environment.
2. Create a new database and adjust the `components['db']` configuration in `common/config/common-local.php` accordingly.
3. Apply migrations with console commands: 
   a) `php yii migrate`. This will create tables needed for the application to work;
   b) `php yii migrate --migrationPath=@yii/rbac/migrations`. This will create RBAC tables.
4. Run command `php yii rbac/init` to install necessary RBAC rules in DB
4. Set document roots of your Web server:

- for frontend `/path/to/yii-application/frontend/web/` and using the URL `http://frontend/`
- for backend `/path/to/yii-application/backend/web/` and using the URL `http://backend/`

To login into the application, you need to first sign up, with any of your email address, username and password.
Then, you can login into the application with same email address and password at any time.

There are 3 types of users in system : `super_admin`, `admin` and `user`.
This types could be simply changed by editing `role` field in `user` table in db, or via admin tool in backend.

When you register your first user in system, he will be a `user`.
But `user` have no access to backend, so you have to change his role to `admin` or `super_admin`.
Once, you log in backend, you will see a simple but powerful user-managment page where you can CRUD users, as you wish.
