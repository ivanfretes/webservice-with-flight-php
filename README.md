
# webservice with fligth php 
A form to create a simple and no complicate webservice in minutes 

### Requeriments?: ###
Flight requires PHP 5.3 or greater.

### Features ###

* foldername : It's optional
* webservice : To you like name, this is a example, the name can be any
* table : It's a name of table of the database, I suggest add a prefix/postfix or encapsulate it and validate in the code
* field_name : (Optional) Generaly this is a identify(id) or code of the database
* field_value : It's required if you initialize the field_name

### How to Implement?: ###

---

    http://example.com/foldername/table/$field_name/$field_value

---



## Do you want a create token?

Modify the code in the index.php file

	<?

	define("DBMANAGER", "yourdbmanager"); // eg mysql, postgre, cubrid, etc
	define("URL_WEBSERVICE", "yoururlbase");
	define("HOSTNAME","localhost");
	define("DBNAME","yourdbname");
	define("HOSTUSER","yourdbuser");
	define("HOSTPASS", "yourdbpassword");

	<?

Fligth Documentation - http://flightphp.com/learn