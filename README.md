# Docker - PHP - Laravel Project For Basic Functionality

[Github][1] [Docker][2] is a **Docker and PHP** repository


Setup
------------

* Run Docker Containers
  
  * ``docker compose up -d `` - For a standard build / setup
  * ``bash ./bin/dev-mode.sh -d`` - For a development build / setup
  * ``{XDEBUG_MODE=debug} bash ./bin/dev-mode.sh -d {--build}`` - For a development build / setup with XDEBUG


* Run Composer install to populate vendor folder
  
  ``docker exec app composer install`` 

[//]: # (  * ``composer install --ignore-platform-reqs --working-dir=./app``)

* Install the dependencies inside package.json
  
  ``docker exec app yarn install``


* Compile Assets (css/js changes inside /public/build)

  ``docker exec app yarn encore dev``


* Run Migration

  ``docker exec app symfony console doctrine:migrations:migrate --no-interaction``


* Populate the Database
 
  ``docker exec app symfony console doctrine:fixtures:load --no-interaction``


Useful Info
------------

> [!PHPSTORM] PHP Storm -> Settings -> PHP -> Set "CLI Interpreter"
> 
> Pick "Docker" 
> 
> Image name: "kmtsvetanov/symfony-php-composer:1.0"
> 
>  This will use php 8.2.11


* For installing Docker Desktop on Windows if you wish to use another directory (C: is full) in cmd:
  
  * ``start /w "" "Docker Desktop Installer.exe" install -accept-license --installation-dir="D:\WORK\DOCKER-VIRTUAL\Docker" --wsl-default-data-root="D:\WORK\DOCKER-VIRTUAL\wsl" --windows-containers-default-data-root="D:\WORK\DOCKER-VIRTUAL"``


* Create Images for Prod and store them in DockerHub:
  
  * ``docker login -u <<username>>`` 
  * ``docker build --target app -t <<username>>/symfony-php-composer:1.0 -f ./docker/php/Dockerfile .``
  * ``docker build -t <<username>>/symfony-nginx-php:1.0 -f ./docker/nginx/Dockerfile .`` 
  * ``docker push <<username>>/symfony-php-composer:1.0`` 
  * ``docker push <<username>>/symfony-nginx-php:1.0`` 
  
  * ``docker login -u kmtsvetanov`` 
  * ``docker build --target app -t kmtsvetanov/symfony-php-composer:1.0 -f ./docker/php/Dockerfile .``
  * ``docker build -t kmtsvetanov/symfony-nginx-php:1.0 -f ./docker/nginx/Dockerfile .`` 
  * ``docker push kmtsvetanov/symfony-php-composer:1.0`` 
  * ``docker push kmtsvetanov/symfony-nginx-php:1.0`` 


* We checked if our [environment can run symfony][3] (run inside `setup-app`)
  * ``symfony check:requirements``


* [Install Symfony][4]
  * ``composer create-project symfony/skeleton:"6.3.*" my_project_directory``

<h4>General commands</h4>
  * ``docker exec app bin/console cache:clear`` - Clear the cache in a Symfony application

<h3>Useful Symfony commands </h3>

  * ``symfony console make:controller MoviesController`` - Create a controller
  * ``symfony console debug:router`` - Show all routes
  * ``symfony console doctrine:database:create`` - Create a database
  * ``symfony console make:entity Movie`` - Create/Edit Entity and Repository
    * Check comments 
  
[//]: # (  Movie - Actor --- ManyToMany  - Many 'Movies' have many 'Actors' - Many 'Actors' start in many 'Movies' )
[//]: # (  ``symfony console make:entity Actor`` - Create Entity and Repository)
[//]: # (  ``symfony console make:entity Movie`` -  New property "actors"  One movie - Many Actors | One Actor - Many Movies )
[//]: # ( - actors)
[//]: # ( - ManyToMany)
[//]: # ( - Actor)
[//]: # ( - yes)
[//]: # ( - movies)
[//]: # ([//]: # &#40;  Student  - Project --- ManyToOne  &#41; - Many 'Students' are working on one school 'Project' | One 'Project' has many 'Students' that work on the project)
[//]: # ([//]: # &#40;  Country  - States  --- OneToMany  &#41; - One 'Country' has many 'States' | One 'State' is located in only one 'Country')
[//]: # ([//]: # &#40;  Person   - Heart   --- OneToOne  &#41; - One 'Person' has one 'Heart' | One 'Heart' inside the body of one 'Person')

  * ``symfony console make:migration`` - Create Migration
  * ``symfony console doctrine:migrations:migrate`` - Run Migration
  * ``symfony console doctrine:migrations:migrate prev`` - Roll back the last migration

<h4>Fixtures - Load Dummy Data Fixtures</h4> 
  * ``docker exec app composer require --dev doctrine/doctrine-fixtures-bundle``
  * ``symfony console doctrine:fixtures:load`` - Database will be purged and populated

<h4>Compile Assets in Symfony (node + yarn)</h4> (inside 'app' container)
  * ``composer require webpack`` - Will install symfony/webpack-encore-bundle
  * ``yarn install`` - Will the dependencies inside package.json
  * ``yarn encore dev`` - Compile Assets (css/js changes inside /public/build)

<h4>Symfony Form class</h4>
  * ``composer require symfony/form``
  * ``symfony console make:form MovieFormType Movie`` - created: src/Form/MovieFormType.php + associate the model (Movie) that will use

<h4>Symfony User</h4> 
  * ``symfony console make:user User`` - Create user table (have  Questions) - yes | email | yes
  * ``symfony console make:migration`` - Create Migration
  * ``symfony console doctrine:migrations:migrate`` - Run Migration
  * ``symfony console make:registration-form`` - Create register form (have  Questions) - yes | no | yes | the redirect route
  * ``symfony console make:auth`` - Create LoginForm (have  Questions) - 1 | LoginFormAuthenticator | SecurityController | yes | no

<h4>Symfony Messenger: Sync & Queued Message Handling</h4>
  * ``composer require symfony/messenger``
  * ``symfony console make:message ProcessTaskMessage`` - This will generate a message class, ProcessTaskMessage, in the src/Message | async
  * ``symfony console debug:messenger`` - To see all the configured handlers, run:
  * ``symfony console messenger:consume async -vv`` - Consuming Messages (Running the Worker) - (-vv) to see all the configured handlers

<h4>Symfony microservice</h4>
<img src="images/012-symfony-microservice.png" alt="Sample Image" width="500" height="400">
  * ProductsController->lowestPrice
    * POST request:
      * URL: `http://localhost/products/1/lowest-price?XDEBUG_SESSION_START=PHPSTORM` 
      * Headers
        * Accept: application/json
        * Content-Type: application/json
        * Force-fail: 500
      * Body: {
        "quantity": 5,
        "request_location": "UK",
        "voucher_code": "0U812",
        "request_date": "2022-04-04",
        "product_id": 1
        }

<h4>Entities vs DTOs (Data Transfer Objects)</h4> 
  * Entities represent the real things in your 
  application.
  * DTOs are like data carriers that help you move 
  data between different parts of your application. They keep things 
  organized and make sure data arrives where it's supposed to be. 

<h6>Schema</h4>
<img src="images/012-symfony-microservice_2.png" alt="Sample Image" width="700" height="200">

* ``composer require doctrine/annotations``

<h4>Tests</h4>
* ``vendor/bin/phpunit tests/unit/LowestPriceFilterTest.php``

<h4>Caching</h4>
* ``composer require cache``
* ``symfony console cache:pool:delete cache.app valid-for-product-1`` - Clear an Item out of the cache
* ``composer require predis/predis`` - Library for helping php work with Redis


On merge into main we want to:
------------
* Run the tests
* Build our two images
* Login to the registry (DockerHub)
* Push our built images to the registry


* Irl we would then deploy our code to whatever cloud service we use


[1]: https://github.com/KMTsvetanov/Symfony
[2]: https://hub.docker.com/search?q=kmtsvetanov%2Fsymfony
[3]: https://symfony.com/doc/current/setup.html#technical-requirements
[4]: https://symfony.com/doc/current/setup.html#creating-symfony-applications


