# Phraseanet Wordpress Plugin

This plugin creates the possibility to add media content from a Phraseanet Instance directly into the Wywiwyg editor (tinyMCE).

### Requirements
* PHP 7.0.33
* Wordpress 5.5 and higher

### Browser compatibility
* Chrome/Chromium 20+
* Firefox 15+
* Safari 5+
* Opera 11+
* IE 8+

## Installation

 - Download the plugin on [Phraseanet.com](https://www.phraseanet.com/download/)
 - Upload it to Wordpress Admin using the Plugin section
 - Enable the plugin in the Wordpress Admin using the Plugin section
 - Configure the plugin in the new Wordpress Settings menu `WP Phraseanet`

### Manual installation (developer)

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






## Docker


### Requirements

* Docker
* Docker-composer

### Configuration

#### env

Rename.env.example to .env file and edit the configuration


#### nginx-conf

nginx-conf is locted in the nginx-conf dir

#### mysql cnf (optional)

Mysql's my.cnf in located in the mysql dir

#### ENV configuration

##### Mysql settings

> MYSQL_ROOT_PASSWORD=password
> 
> MYSQL_DATABASE=drupal


##### Wordpress settings


> SITE_TITLE=Phraseanet for worpdress
> WP_ADMIN_USERNAME=admin
> WP_ADMIN_PASSWORD=admin123
> WP_ADMIN_EMAIL=admin@admin.com


##### phraseanet settings

> PHRASEANET_BO_URL=YOUR PHRASEANET URL
> PHRASEANET_BO_TOKEN=PHRASEANET TOKEN
> PHRASEANET_BO_CLIENT_ID=PHRASEANET CLIENT ID
> PHRASEANET_BO_SECRET=PHRASEANET SECRET


#### Installation

RUN `docker-compose` up or `docker-compose -d up`

This will auto install the Wordpress and phraseanet plugin



##### Containers

* mysql - database
* gateway - nginx reverse proxy
* wordpress - main application

##### Volumes

* DB
* WPDATA

##### Images

* Mysql 
* Php-alpine 
* Nginx




### Development mode

Run `composer install`

Run `docker-compose -f docker-composer-override.yml up `







## Development
[Alchemy](http://www.alchemy.fr/) and [Labomedia](http://labomedia.org)


## License
This project is licensed under the [GPLv3](http://www.gnu.org/licenses/gpl-3.0.html).
Why GPL ? Because this plugin embeds GPL products (Flowplayer, Flexslider). 
