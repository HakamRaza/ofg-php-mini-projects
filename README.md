# PHP Small Projects
This repository contain several seperate solution for a quiz each on its own seperate folder.

## Download


## Running the project
The project was built using docker compose. The services include a mysql service, nginx webserver and a php backend. 

In order to start the app, a docker installation is required (https://docs.docker.com/get-docker/). Run the following commands in the root of the directory. 

1. To clone the entire repo: 
    ``` bash
    git clone https://github.com/HakamRaza/php-ofg.git

2. Go into project folder
    ```
    cd php-ofg/
    ```

3. Please fill in the details in the .env files to run the app.


4. Build the Dockerfile for the backend:
   ``` bash
   docker compose build backend
   ```
   
5. Start the docker compose in detached mode:
   ``` bash
   docker compose up -d
   ```

6. Run composer install to install backend dependencies:
   ``` bash
   docker compose exec backend composer install
   ```

7. Run migrations & seeder (optional):
   ``` bash
   ```

(Technical Diagram)[https://drive.google.com/file/d/10_ZHEzq-1f3q4DZ4I_ybKcj7vbdn92mc/view]
