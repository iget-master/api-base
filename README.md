# API Base

Description goes here.

## Installation Guide

* Require `iget-master/api-base` on composer
* Add the `Iget\ApiBase\ApiBaseServiceProvider::class` services provider on `app.php` configuration file.

### Optional steps

#### Enable token based authentication

* Set `auth-token` as Guard Driver on `auth.php` configuration file.
* Use only `Iget\ApiBase\Http\Middleware\Authenticate::class` middleware on your API routes.

#### Configure User Provider to use our User model

* Set `Iget\ApiBase\Models\User` as model on your user provider