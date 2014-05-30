
mysqlincbak

mysqlincbak is a PHP script to make incremental backups of mysql databases
on the table level. the advantage is that if you have some huge tables in
your database that hardly ever change, you will only make new backups when
such tables have changed since the last backup.

this saves CPU, hard disk and bandwidth resources. 

the script is especially useful if you backup your database backups to
remote servers, such as Amazon S3. 

installation:
put the 2 phpfiles and the 1 directory into the same directory on your
web server. 

usage:
I typically run this script from cron every so many hours or days, through 
the 'wget -q -T 3600 -O /dev/null http://mydomain.com/mysqlincbak.php' command

dependencies:
- PHP exec() function must be enabled
- script should have write access to the backup folder (mysqlincbak per default)

enjoy,
jeroen

copyright: 
All rights reserved. You may use this script for a small donation, but you 
are not allowed to sell, copy or change it, parts of it, or anything based on it.
feel free to send a donation to paypal@playak.com if this script saves you time 
or money.

todo:
you tell me. i'm happy with it the way it is :)
