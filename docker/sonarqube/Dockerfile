# Use the official SonarQube image as the base image
FROM sonarqube:latest

# Switch to root user temporarily to install additional packages
USER root
# Create a new user and group with specific permissions
#RUN groupadd -r sonarqube && useradd --no-log-init -r -g sonarqube sonarqube


RUN apt-get update && apt-get install -y ca-certificates curl gnupg unzip
RUN curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg
RUN echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_20.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list
RUN apt-get update && apt-get install nodejs -y

# Optionally, add any custom configurations or plugins:
# COPY plugins/* /opt/sonarqube/extensions/plugins/
# COPY sonar.properties /opt/sonarqube/conf/

# Install SonarQube Scanner
# Set permissions before unzipping
RUN wget https://binaries.sonarsource.com/Distribution/sonar-scanner-cli/sonar-scanner-cli-5.0.1.3006-linux.zip \
    && chmod +x sonar-scanner-cli-5.0.1.3006-linux.zip \
    && unzip sonar-scanner-cli-5.0.1.3006-linux.zip -d /opt/sonar-scanner \
    && rm sonar-scanner-cli-5.0.1.3006-linux.zip

# Set SonarQube Scanner PATH
ENV PATH="/opt/sonar-scanner/sonar-scanner-5.0.1.3006-linux/bin:${PATH}"

# Copy your project's source code into the container
COPY ./app /var/www

# Set permissions to allow Symfony commands to run without permission issues
RUN chown -R www-data:www-data /var/www
RUN chmod -R 775 /var/www/var

# Create necessary directories and set ownership
RUN mkdir -p /opt/sonarqube/.scannerwork && \
    chown -R sonarqube:sonarqube /opt/sonarqube/.scannerwork && \
    mkdir -p /home/sonarqube/.sonar/cache && \
    chown -R sonarqube:sonarqube /home/sonarqube/.sonar

# Switch back to the default sonarqube user
USER sonarqube

# Define a working directory for the user
WORKDIR /opt/sonarqube
