README

--------------------------------
This is the Doodle Password Project. This is a website that uses Doodle and Password authentication.
The purpose is to compare the two forms of authentication by presenting them to users and having the users complete a survey at the end.

--------------------------------

SETTING UP

Start by installing everything for your server (LAMP, etc).
	sudo apt-get update
	sudo apt-get install lamp-server^

To setup the website, clone the git repo to the appropriate directory (/var/www or /var/www/html, etc).

You will need to setup the MySQL database. The credentials are stored in db.ini. You will need to modify this file to match the credentials of your user and database.

Recommendation: Change the user and password in the following commands (matching the db.ini). It is not a good idea to use the same credentials that are stored publicly on Github :)

Enter the MySQL prompt using the following command:
	mysql -u root -p

Complete the following inside the MySQL prompt (replace <user> and <pass>:
1. CREATE DATABASE DoodleDb;
2. CREATE USER '<user>'@'localhost' IDENTIFIED BY '<pass>';
3. GRANT SELECT,INSERT,DELETE ON DoodleDb.* TO '<user>'@'localhost';
4. USE DoodleDb;
5. source doodledb.sql;

MAIL Functionality
To setup a SMTP server for PHP, you can use the following link: http://askubuntu.com/questions/47609/how-to-have-my-php-send-mail.
Options include postfix, ssmtp, and msmtp.
