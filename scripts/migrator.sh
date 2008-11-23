#!/bin/bash

# This requires a tunnel to be established as such:
# 
#     deveshistan:~ dash$ ssh root@reviewsby.us -L3307:localhost:3306


# download and insert db
echo "Downloading database"

# ssh root@reviewsby.us mysqldump -u migrator -pnarnians rbu --skip-opt --add-drop-table --skip-add-locks  --skip-comments --extended-insert > /tmp/db.rbu

echo "INSERTING DB"
echo "SET storage_engine=InnoDB;
SET foreign_key_checks = 0;
SET foreign_key_checks = 0;
SOURCE /tmp/db.rbu;
DROP TABLE sf_guard_group;
DROP TABLE sf_guard_group_permission;
DROP TABLE sf_guard_module;
DROP TABLE sf_guard_permission;
DROP TABLE sf_guard_remember_key;
DROP TABLE sf_guard_user_permission;
DROP TABLE sf_guard_user_group;
REPLACE INTO auth_user (id, username, password, is_active, last_login, date_joined) SELECT id, username, CONCAT(algorithm,'$', salt, '$', password), 1, last_login, created_at FROM sf_guard_user; DROP TABLE sf_guard_user
UPDATE auth_user SET is_staff = 1 WHERE id=8;
UPDATE auth_user SET is_staff = 1, is_superuser=1 WHERE id=1;
SET foreign_key_checks = 1;
" | mysql -u root rbu     


