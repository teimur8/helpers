#!/bin/bash

cd /var/www/
mysql -uroot -pflv
show master status \G;
exit;
mysqldump -uroot -p --single-transaction --databases b2b_adkulan_kz | gzip > db.sql.gz
rsync -avz db.sql.gz root@b2bVM:/var/www/
ssh b2bVM
cd /var/www/
pv db.sql.gz | gunzip | mysql -uroot -p123qwe123qwe


mysql -uroot -p123qwe123qwe
STOP SLAVE;
change master to master_host = "10.10.1.6", master_user = "b2bVM", master_password = "WfKFV9gAJ9", master_log_file = "mysql-bin.000001", master_log_pos = 94451699;
start SLAVE;
show slave status \G;



show variables like 'max_connections'
show status like '%onn%'


#mysqldump -uroot -pstrongpassword --single-transaction b2b_adkulan_kz | gzip > db_prod_adkulan.sql.gz
#mysqldump -uroot -pstrongpassword --single-transaction b2b_adkulan_kz_dev | gzip > db_prod_adkulan_dev.sql.gz
#
#mysqldump -uroot -pstrongpassword
#
#gunzip < /path/to/outputfile.sql.gz | mysql -u USER -pPASSWORD DATABASE
