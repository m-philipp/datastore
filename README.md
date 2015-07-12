Arcwind Datastore
=================

Arcwind is a simple self-hosted timeseries data storage &amp; Visualisation.
 
 * RESTfull JSON API
 * Telnet storage connector
 * Responsive Web-UI
 * Live Data plotting
 * XHR powered visualisation

Everything needed is a Apache/nginx and a MySQL Server. 


![Startpage Screenshot](./screenshot.jpg?raw=true "Startpage Screenshot")


NGINX Routes
============

If you're running this Tool on an nginx Server, you have to place the following line in the according location block.
```
try_files $uri /index.php?__route__=$uri;
```


Make your own TelnetServer Service
==================================

(currently not functional since it doesn't relfect the added user managment)


If you want the Telnet connector to run as a Servive you can use the provided Shell Skript.
Therefore you have to put the:
```
/telnetConnector/telnetConnector.sh
```

in the following folder:
```
/etc/init.d/
```
(Make sure it is executeable)

If you want the Raspberry Pi to start up the Connector automatically you have to one more step:
```
cd /etc/init.d/
sudo update-rc.d telnetConnector.sh defaults
```

Service Skript is taken from:
http://blog.scphillips.com/2013/07/getting-a-python-script-to-run-in-the-background-as-a-service-on-boot/
