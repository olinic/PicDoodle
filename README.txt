README

--------------------------------
This is the Doodle Password Project. This is a website that uses Doodle and Password authentication. 
The purpose is to compare the two forms of authentication by presenting them to users and having the users complete a survey at the end.

--------------------------------

SETTING UP

To setup the website, clone the git repo to the appropriate directory. 

You will need to setup the MySQL database. The credentials are stored in db.ini. You will need to modify this file to match the credentials of your user and database. 

Note: you can change the following commands to the appropriate database or user (matching the db.ini).

1. CREATE DATABASE DoodleDb;
2. CREATE USER 'doodleUser'@'localhost' IDENTIFIED BY '3doodlEthiS';
3. GRANT SELECT,INSERT,DELETE ON DoodleDb.* TO 'doodleUser'@'localhost';
4. USE DoodleDb;
5. source doodledb.sql;


