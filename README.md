test
====

A quick & dirty symfony project for a OAT testing.

**How to deploy ?**

As this is a symfony 3.4 project, please install [composer](https://getcomposer.org/download/).

Clone the current repository in your server, use the oat.conf for your nginx/fpm configuration or make your [own installation](https://symfony.com/doc/current/setup/web_server_configuration.html).

**How to load ?**

Once your symfony project is correctly installed, you'll have to have a database.
First of all you need to install the database, please run the following commands :

`bin/console doctrine:database:create`
`bin/console doctrine:schema:update --dump-sql --force`

Then, add the testtakers.json file in web/files/. You can now start the import using this command : 

`bin/console oat:load-takers testtakers.json`

It will load the takers in your database. The unicity of all takers is made with their login.


**How to use ?**

You can now access your api of takers by calling the two available urls :
http://www.oat.test/app_dev.php/api/takers
http://www.oat.test/app_dev.php/api/taker/111

The first url gives you a list of takers, limited to 10 takers by load, you an ask for the next load by giving the the number of the next load.
For the second load you have this url : 
http://www.oat.test/app_dev.php/api/takers/10
