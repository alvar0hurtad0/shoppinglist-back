## Prerequisites

Install [Docker](https://www.docker.com/) on your system.

* [Install instructions](https://docs.docker.com/installation/mac/) for Mac OS X
* [Install instructions](https://docs.docker.com/installation/ubuntulinux/) for Ubuntu Linux
* [Install instructions](https://docs.docker.com/installation/) for other platforms

Install [Docker Compose](http://docs.docker.com/compose/) on your system.

To avoid permission problems, the best is ensure your local user has UID 1000 with:
id -u
if is not 1000 ask your teammates to look for a common free UID, use all the same and don't forget change on Dockerfile.

## Setup

Run `docker-compose build`

## Start

Run `docker-compose up` 

## first run
after docker-compose up open a new terminal and exec
docker exec -it [the name of the container () usually soppinglistback_web_1] drush si custom_profile --account-pass=admin
