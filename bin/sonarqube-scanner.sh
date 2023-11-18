#!/bin/bash

# Your existing content for the sonarqube-scanner.sh script
# bash ./bin/sonarqube-scanner.sh

# Add the SonarQube scanner command
winpty docker exec -u root -it sonarqube sh -c "cd /var/www && \
  sonar-scanner \
  -Dsonar.projectKey=003-symfony6 \
  -Dsonar.sources=. \
  -Dsonar.host.url=http://localhost:9000 \
  -Dsonar.tests=tests \
  -Dsonar.php.coverage.reportPaths=tests/coverage.xml \
  -Dsonar.test.inclusions=**/*Test.php \
  -Dsonar.token=sqp_28856557424d07b64cca9d92c04c21582e01582e"