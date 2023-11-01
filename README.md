# Docker - PHP

[Github][1] [Docker][2] is a **Docker and PHP** repository


Setup
------------

* For a standard build / setup
  
  ``docker compose up -d ``

* For a development build / setup

  ``bash ./bin/dev-mode.sh -d``

* For a development build / setup with XDEBUG
  ``XDEBUG_MODE=debug bash ./bin/dev-mode.sh -d``

* Run Composer install to populate vendor folder
  * ``docker exec app composer install`` 

[//]: # (  * ``composer install --ignore-platform-reqs --working-dir=./app``)

* Run Migration
  * ``docker exec app symfony console doctrine:migrations:migrate --no-interaction``

* Populate the Database
  * ``docker exec app symfony console doctrine:fixtures:load --no-interaction``



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


* ``npm install file-loader --save-dev`` - Compile Assets (css/js changes inside /public/build)

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


