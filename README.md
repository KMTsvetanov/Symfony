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

Useful Info
------------
* For installing Docker Desktop on Windows if you wish to use another directory (C: is full) in cmd:
  
  *
    ``start /w "" "Docker Desktop Installer.exe" install -accept-license --installation-dir="D:\WORK\DOCKER-VIRTUAL\Docker" --wsl-default-data-root="D:\WORK\DOCKER-VIRTUAL\wsl" --windows-containers-default-data-root="D:\WORK\DOCKER-VIRTUAL"``


* Create Images for Prod and store them in DockerHub:
  
  * ``docker login -u <<username>>`` 
  * ``docker build --target app -t <<username>>/php-composer:1.0 -f ./docker/php/Dockerfile .``
  * ``docker build -t <<username>>/nginx-php:1.0 -f ./docker/nginx/Dockerfile .`` 
  * ``docker push <<username>>/php-composer:1.0`` 
  * ``docker push <<username>>/nginx-php:1.0`` 


On merge into main we want to:
------------
* Run the tests
* Build our two images
* Login to the registry (DockerHub)
* Push our built images to the registry


* Irl we would then deploy our code to whatever cloud service we use


[1]: https://github.com/KMTsvetanov/Setup
[2]: https://hub.docker.com/search?q=kmtsvetanov%2F


