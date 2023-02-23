# News Parsing Service 
A news parsing service from a news resource, for example hightload.today. The service has a page displaying the list of downloaded news and a CLI command to start parsing.

## Tech Stack 
- Symfony 5.4
- Php 7.4
- Mysql
- Bootstrap 5.1
- Docker (docker-compose)
- RabbitMQ


## Features 
Parsing features:
- From each article, the download should and be saved:
  - title
  - short description
  - picture
  - date added
- When parsing, it is necessary to check the presence of the title in the database, and if the news is already in the database, make a note about the date and time of the last update
- Database queries should be optimized for heavy load
- Parsing should be in several parallel processes (via rabbitMQ)
- Parsing must be run via cron
- Features of the page for viewing news from the database:
- The page for viewing news from the database should be available only after authorization in the system (registration is not required)
- Authorized users can be with one of two roles: admin or moderator (the administrator can delete articles)
- There must be pagination at the end of the list of articles (10 per page)



## Running 
### Prerequisites 
- [Docker][docker] 
- [Docker compose][compose] 
- Copy `.env.example` to `.env` and edit the `.env` file.
- Edit the *app/resources/sources.json* file and add any news sources you wish to scrape data from.
- On Windows, 
  check that every file in the *./shell-scripts/* sub-directory ends with Unix-style (`LF`) line-endings.

- **First run:** Run `docker-compose up -d --build`.
  This command will run `composer install` to install the project's dependencies.
  So, it will take some time before the app is available. 

  You can follow the logs by running `docker-compose logs -f php`.
- **Subsequent runs:** Run `docker-compose up -d`
- Navigate to *http://localhost:<APP_PORT>* where <APP_PORT> is the PORT value set in the *.env* file.


## Working With The Application 

### App Users 
The app currently implements three authentication account details configurable via the *.env* file. 
- **USER**: This is a non-privileged user account. Cannot not view nor delete downloaded news.  
- **ADMIN**: This is an administrator account. It can view and delete downloaded news.
- **MODERATOR**: This is a moderator account. It can view, but cannot delete, downloaded news.

### Adding URL Sources 
There are two ways to add URL resources: 
- Via the *app/resources/sources.json*.  This is the default method of adding a resource.
  The news resources in this file are processed asynchronously via the CLI.
  The CLI command is run as a cron job. 
  You can configure the cron schedule inside the *.env* file. 
  The default schedule is every minute (`* * * * *`).
- Add resources via the web interface. 
  These are processed synchronously via the web interface. 
  This was implemented when I was fleshing out the feature. 
  Anyway, I decided to leave it in place.
  Now I prefer to think of it as just a convenience 
  that allows us to add a resource and process and parse it 
  without waiting for the cron schedule timer to come around.

### Container and Service Administration 
- To log into any container, run: 
  `docker exec -it <APP_NAME>_<SERVICE_NAME> bash` . 
- To view the logs of any service, maybe for troubleshooting purposes, run: 
  `docker-compose logs -f <SERVICE_NAME>`.

Here, 
- *<APP_NAME>* is the name of the containers as specified inside the `.env` file.
- *<SERVICE_NAME>* is the name of the service. The following services are available: 
    - **php**
    - **nginx**
    - **mysql**
    - **rabbitmq**
  
Eg: assuming the <APP_NAME> is **testapp**, 
- to log into the **php** container service, run: `docker exec -it testapp_php bash`.
- to view the logs of the **nginx** service, run: `docker-compose logs -f nginx`.










[docker]: https://www.docker.com/
[compose]: https://docs.docker.com/compose/

Author: Nderi Kamau
Email: nderikamau1212@gmail.com# news-parse
