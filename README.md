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

## Development
[Alchemy](http://www.alchemy.fr/) and [Labomedia](http://labomedia.org)

## License
This project is licensed under the [GPLv3](http://www.gnu.org/licenses/gpl-3.0.html).
Why GPL ? Because this plugin embeds GPL products (Flowplayer, Flexslider). 
