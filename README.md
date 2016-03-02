Pizza-backend
====================

<h2>Short description</h2>
The REST API application is designed to management order in restaurant.

It's based on a few symfony's bundles:

* FOSRestBundle
* SerializerBundle
* ApiDocBundle

And development tools:

* Behat
* Codesniffer
* Vagrant
* Ansible
* Jenkins 

###Links:

Rest API documentation endpoint: `/doc/api`
 
###Getting started

In order to set up this application you need clone this repo:

```git clone https://github.com/tarnawski/Pizza-backend.git```

And you have to install dependencies:

```cd Pizza-backend```

```composer install```

Run Vagrant with prepared environment:

```cd vagrant && vagrant up ```


When the process is complete, get into the machine:
```
vagrant ssh
cd /var/www/Pizza/
```

Creating schema and loading fixtures:
```
php app/console doctrine:schema:create 
php app/console doctrine:fixtures:load
```  


Add to your hosts:
`10.0.0.200 pizza.dev`

That's all! Now you can start to use app!!!