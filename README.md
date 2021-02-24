# Phraseanet Wordpress Plugin

This plugin creates the possibility to add media content from a Phraseanet Instance directly into the Gutenberg Block.



## Table of content
[Requirements](#Requirements)

[Browser compatibility](#Browser-compatibility)

[Plugin Installation](#Plugin-Installation) 

[Manual installation (developer)](#Manual-installation-developer)

[Plugin Activation and Configuration](#Plugin-Activation-and-Configuration)

[Run on Docker and Docker development](#Run-on-Docker-and-Docker-development)

- [Prerequisites](#Prerequisites)

 - [Configuration](#Configuration)
    - [env](#env) 	
    - [example configuration](#example-ENV-configuration)
    - [Phraseanet Configuration](#phraseanet-settings)
 
- [Installation](#Installation)

 - [Development mode](#Development-mode)

- [Docker stack](#Docker)

- [Compose files](#Compose-files)

- [Docker Stack Details](#Docker-Stack-Details)


### Requirements

* PHP >= 7.0

* WordPress  >= 5.5 

  

### Browser compatibility

* Chrome/Chromium 20+

* Firefox 15+

* Safari 5+

* Opera 11+

* IE 8+

  

## Plugin Installation

  

- Download the plugin on [Phraseanet.com](https://www.phraseanet.com/download/)

- Upload it to WordPress Admin using the Plugin section

- Enable the plugin in the WordPress Admin using the Plugin section

- Configure the plugin in the new WordPress Settings menu `WP Phraseanet`

  
### Manual installation developer

You need [Composer](http://getcomposer.org/) before continuing.

```bash

cd your-wordpress-root/wp-content/plugins/

git clone https://github.com/alchemy-fr/Phraseanet-Wordpress-Plugin.git

cd Phraseanet-Wordpress-Plugin

composer install --dev

composer update

```

## Plugin Activation and Configuration

Once the plugin is installed, activate it. You must then configure it in the Settings > WP Phraseanet menu.

    
    

## Run on Docker and Docker development

  Make your system meets the prerequisites before dive in. 
  

### Prerequisites

  
* Docker >=19.03.13

* Docker-composer >=1.26.0

  
  ### What is docker? Read this:

[https://www.docker.com/get-started](https://www.docker.com/get-started)

### Host requirement:

|System| Link |
|--|--|
| Linux |  [Docker for linux](https://hub.docker.com/search?q=&type=edition&offering=community&operating_system=linux) |
| Macintosh |  [Docker for mac](https://hub.docker.com/editions/community/docker-ce-desktop-mac)|
| Windows |   [Docker for windows](https://hub.docker.com/editions/community/docker-ce-desktop-windows)|

> Note: All our images are Linux based, so with Macintosh and Windows hosts, the containers run in vm provided by Docker. For optimal performances, prefer a Linux host.

  

### Configuration

  


#### env
Rename env.example to .env file and edit the configuration or ``cp env.example .env``

  
 

#### nginx-conf

  

nginx-conf is locted in the nginx-conf dir

  

#### mysql cnf (optional)

  

Mysql's my.cnf in located in the mysql dir

  

#### ENV configuration

#### compose files

COMPOSE_FILE=docker-compose.yml:docker-compose.db.yml

For dev purpose use COMPOSE_FILE=docker-compose.override.yml:docker-compose.db.yml check [Development mode](##Development-mode) for more details.


##### Mysql settings

> MYSQL_ROOT_PASSWORD=password

>

> MYSQL_DATABASE=phraseanet_wordpress

  
  

##### WordPress settings

  

> SITE_TITLE=Phraseanet for worpdress

>

> WP_ADMIN_USERNAME=admin

>

> WP_ADMIN_PASSWORD=admin123

>

> WP_ADMIN_EMAIL=admin@admin.com

>

> WP_UPDATE=auto #auto -> auto update if available and #never -> don't update keep the current version

  
  

##### Phraseanet Settings

  

> PHRASEANET_PLUGIN_INSTALL=one

>

> *(one) plugin is install only at first launch and (always) plugin is install at each entry point execution.*

  

>

> PHRASEANET_BO_URL=YOUR PHRASEANET URL

>

> PHRASEANET_BO_TOKEN=PHRASEANET TOKEN

>

> PHRASEANET_BO_CLIENT_ID=PHRASEANET CLIENT ID

>

> PHRASEANET_BO_SECRET=PHRASEANET SECRET

  
  

## Installation

After making the necessary changes in the [.env](#env) that are mentioned above you just need to run ```docker-compose up``` or ```docker-compose -d up``` On the first run this will download all the required images from the docker registry and download the WordPress and Phraseanet modules and auto-install and set up them for you, So this process will take a while to complete on the first run.  


## Development mode

In the  [.env](#env) use this COMPOSE_FILE=docker-compose.override.yml:docker-compose.db.yml
On the first run, phraseanet plugin code from root directory will be auto-installed in the WordPress.
You can make live changes in the plugin.


### docker-entrypoint
The plugin installation and plugin settings are located in the docker-entrypoint.sh 

#### Code

  

This will attach the current code to /var/www/html/wp-content/plugins/Phraseanet-Wordpress-Plugin/ with composer install --dev

  
  

#### docker exec

Run ``docker exec -it wordpress bash`` This will create a new Bash session in the WordPress container



## Docker Stack Details

### Compose files

|File| Info |
|--|--|
| docker-compose.yml | for production |
| docker-compose.override.yml | for development |
| docker-compose.db.yml | for database |


### Containers

+ mysql - database

+ gateway - nginx reverse proxy

+ wordpress - main application
  

### Volumes

  
| DRIVER | VOLUME NAME |
|--|--|
| local | DB |
| local | WPDATA |

  
### Images  


| REPOSITORY | TAG |
|--|--|
|mysql| latest |
|php|7.4.4-fpm-alpine3.11|
|nginx|1.17.4-alpine|
  
  

## Developed By

[Alchemy](http://www.alchemy.fr/) and [Labomedia](http://labomedia.org)

  
  

## License

This project is licensed under the [GPLv3](http://www.gnu.org/licenses/gpl-3.0.html).

Why GPL ? Because this plugin embeds GPL products (Flowplayer, Flexslider).
