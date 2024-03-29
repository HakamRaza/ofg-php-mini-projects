# PHP Mini Projects

This repository contain several seperate solution for a quiz each on its own seperate folder.
There are 3 different folder but only the first 2 need webservers to use it.

- `Qs01-RepeatingCharacters` - serve in docker
- `Qs02-RewardPoints` - serve in docker
- `Qs03-MySQLQuery` - contain SQL file that can be imported directly to MyPhpAdmin / Laragon

## Running the project

The project serve in docker are built using docker compose. The services include a mysql service, nginx webserver and a php backend.

In order to start the app, a docker installation is required (https://docs.docker.com/get-docker/). Run the following commands in the root of the directory.

1. To clone the entire repo:

   ```bash
   git clone https://github.com/HakamRaza/ofg-php-mini-projects

   ```

2. Go into project folder

   ```
   cd ofg-php-mini-projects/
   ```

3. A sample .env is given and set up to comply with docker mysql service.

4. Build the Dockerfile for the projects:
   ``` bash
   docker compose build project_one
   ```

   ``` bash
   docker compose build project_two
   ```

5. Start the docker compose in detached mode if you want to use the same terminal:

   ```bash
   docker compose up -d
   ```

6. Only project two require composer and this project does not require any dependencies:

   ```bash
   docker compose exec project_two composer install
   ```

7. The `nginx` config uses `localhost` as the server name so that no modification to the hosts file is required.
   Port exposed is `:80` for project one and port `:81` for project two.
   
   ```
   Project one endpoint : http://localhost:80
   ```
   
   ```
   Project two endpoint : http://localhost:81
   ```

## Technical Diagram

Technical diagram ERD & UML for project two can be refer here:

[Technical Diagram Project Two](https://drive.google.com/file/d/10_ZHEzq-1f3q4DZ4I_ybKcj7vbdn92mc/view)
