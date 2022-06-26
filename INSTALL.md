# Installation Manual

## Minimum System Requirements

* Core 2 Duo Processor or higher
* 4 GB DDR2 RAM or more
* Ubuntu 14.04 or later version

## Tools & Dependencies

* Apache2
* PHP
* MySQL
* R & Shiny Server
* Bedtools

### PHP Modules

* php-common
* php-mysql

### Perl Modules

+ Statistics::Basic
+ File::Basename

### R packages

UpSetR

corrplot

dplyr

SuperExactTest

shinythemes

shinyjs

# Installation

Start by installing any HTTP server(Apache2) having support for server-side languages like PHP and configure accordingly. Install Bedtools, PHP,  MySQL and R+ Shiny Server followed mentioned modedules/packages.

Clone (`git clone https://github.com/sraghav-lab/BedSect.git`)or download the [Bedsect Github repositoy](https://github.com/sraghav-lab/BedSect/archive/master.zip
)

Put all the contents from **application** folder to the Apache2 server root directory .

Create a database for Bedsect and import `bedsect_schema.sql `present in **database_schema** directory to the newly created database.

`mysql -u <username> -p dbname<bedsect_schema.sql `

To import the GTRD database schema use the `gtrd_schema.sql` file.

Update the database name, host, username and password in `config.php` file located in the root directory. The config file also contains default installation path and path to Shiny server, update the paths accordingly.

By default the email sending feature is disabled. To enable email sending first update **SMTP** served details in `mail.php` and remove comment form `jobControl.php` line no 25 and `runJob.php` line no 79.

Copy the contents of **shiny** folder to the Shiny server root directory. Default: `/srv/shiny-server`. 

Change the user and group of the `/var/www/html `folder to `www-data:www-data` and set permission of the `server/files` folder to 755.

Navigate to `localhost` or `server-ip-address `and do a test run using the demo data provided in **test_data** folder.
