# Online examination system 

(created as a final year project for B. Tech)

### With Docker

#### Requirements
* Docker Compose ([get it here](https://docs.docker.com/compose/install/))

#### To run
* Clone the repository to your computer
* Make sure port 80 is free (`sudo lsof -i :80`)
* Run `docker-compose up -d`
* Open http://localhost/ on your browser.

### Manual install
 
#### Requirements
* PHP 5.1+, older versions needs the PDO PECL extension
* MySQL 5.x
* A web server capable of serving PHP scripts

#### To run
1. Import the SQL dump at extras/oes.sql into a database
2. Change the database connection string in includes/db_connect.php

#### Administrator login

User: soumik@soumikghosh.com  
Password: soumik

An online demo of the system is available at [http://stuff.soumikghosh.com/eevaluation](http://stuff.soumikghosh.com/eevaluation/)
