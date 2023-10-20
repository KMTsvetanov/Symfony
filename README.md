# Docker - PHP

[Docker PHP][1] is a **Docker and PHP** repository


Setup
------------

* For a standard build / setup, simply run
``docker compose up -d ``
  

* For a development build / setup, simply run
``bash ./bin/dev-mode.sh``
  
``XDEBUG_MODE=debug bash ./bin/dev-mode.sh -d``
  
``docker compose -f docker-compose.dev.yml up``
  

`` TODO docker compose -f docker-compose.dev.yml --env-file .env.local up --build -d``





Useful Info
------------
* For installing Docker Desktop on Windows if you wish to use another directory (C: is full)
  ``start /w "" "Docker Desktop Installer.exe" install -accept-license --installation-dir="D:\WORK\DOCKER-VIRTUAL\Docker" --wsl-default-data-root="D:\WORK\DOCKER-VIRTUAL\wsl" --windows-containers-default-data-root="D:\WORK\DOCKER-VIRTUAL"``


* Create Images for Prod:
  
  ``docker login -u <<username>>`` 

  ``docker build --target app -t <<username>>/php-composer:1.0 -f ./docker/php/Dockerfile .``
  
  ``docker build --target app -t <<username>>/nginx-php:1.0 -f ./docker/nginx/Dockerfile .``

  ``docker compose -f --target app -t <<username>>/php-composer:1.0 -f ./docker/php/Dockerfile .``
  
  ``docker compose -f --target app -t <<username>>/nginx-php:1.0 -f ./docker/nginx/Dockerfile .``

  ``docker push <<username>>/php-composer:1.0``
  
  ``docker push <<username>>/nginx-php:1.0``



On merge into main we want to:
------------
* Run the tests
* Build our two images
* Login to the registry (DockerHub)
* Push our built images to the registry


* Irl we would then deploy our code to whatever cloud service we use


[1]: https://github.com/KMTsvetanov/Setup


