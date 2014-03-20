datastore
=========

Simple self-hosted Sensor Data Storage &amp; Visualisation


If you're running this Tool on an nginx Server, you have to place the following line in the according location block.
```
 try_files $uri /index.php?__route__=$uri;
 ```