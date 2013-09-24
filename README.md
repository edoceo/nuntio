# Nuntio Team Chat

Nuntio is an Open Source Web Chat application.
It's constructed with PHP, Mongo and Redis and has been tested under Apache and Nginx.

* Multiple Chat Rooms
* Webhook for git & svn
* Drag & Drop File Uploads

## Installation

	mkdir -p /opt/edoceo/app/nuntio
	cd /opt/edoceo/app/nuntio
	git clone https://github.com/edoceo/nuntio.git ./
	curl -sS https://getcomposer.org/installer | php
	./composer.phar update

## Configuration

Use one of the provided Apache or Nginx configuration files to get the service configured.

### Apache

### Nginx

## Hosted Service

Nuntio is also available as a hosted service, please see http://nunt.io/

## Todo

* Display Media Inline with Chat Line, Some Preview Method?
* Headless OpenOffice for Conversion?
* PhantomJS for Screenshots
* Automatic Unroller for Short URLs
* Libreoffice File Converter

