# Nuntio Team Chat

Nuntio is an Open Source Web Chat application.
It's constructed with PHP, Mongo and Redis and has been tested under Apache and Nginx.

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

* Drag&Drop - Files, Text, URIs
* Display Media Inline with Chat Line, Some Preview Method?
* Headless OpenOffice for Conversion?
* PhantomJS for Screenshots
* Automatic Unroller for Short URLs
* Libreoffice File Converter

/usr/lib/libreoffice/program/soffice.bin --headless --nologo --nofirststartwizard --accept=socket,host=127.0.0.1,port=8100;urp
/opt/libreoffice3.4/program/soffice --headless --nologo --nofirststartwizard -convert-to $extension.pdf "$1" -outdir $folder
