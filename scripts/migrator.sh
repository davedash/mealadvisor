#!/bin/bash

# This requires a tunnel to be established as such:
# 
#     deveshistan:~ dash$ ssh root@reviewsby.us -L3307:localhost:3306


# download and insert db
echo "Downloading database"

ssh root@reviewsby.us mysqldump -u migrator -pnarnians rbu --skip-opt --create-options --add-drop-table --skip-add-locks  --skip-comments --extended-insert > /tmp/db.rbu

echo "INSERTING DB"
echo "SET storage_engine=InnoDB;
SET foreign_key_checks = 0;
SOURCE /tmp/db.rbu;
DROP TABLE sf_guard_group;
DROP TABLE sf_guard_group_permission;
DROP TABLE sf_guard_module;
DROP TABLE sf_guard_permission;
DROP TABLE sf_guard_remember_key;
DROP TABLE sf_guard_user_permission;
DROP TABLE sf_guard_user_group;

REPLACE INTO auth_user (id, username, password, is_active, last_login, date_joined) 
SELECT id, replace(username, 'http://', ''), CONCAT(algorithm,'$', salt, '$', password), 1, last_login, created_at 
FROM sf_guard_user; 
DROP TABLE sf_guard_user;

UPDATE auth_user SET is_staff = 1 WHERE id=8;
UPDATE auth_user SET is_staff = 1, is_superuser=1 WHERE id=1;

CREATE TABLE restaurant_tag2 (
  id int UNSIGNED NOT NULL AUTO_INCREMENT,
  restaurant_id int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  created_at datetime default NULL,
  tag varchar(100) default NULL,
  normalized_tag varchar(100) NOT NULL default '',
  PRIMARY KEY  (id),
  UNIQUE (restaurant_id, user_id, normalized_tag),
  KEY normalized_tag_index (normalized_tag),
  KEY restaurant_tag_FI_2 (user_id),
  FOREIGN KEY (restaurant_id) REFERENCES restaurant (id),
  FOREIGN KEY (user_id) REFERENCES profile (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO restaurant_tag2 (user_id, restaurant_id, created_at, tag, normalized_tag) 
SELECT user_id, restaurant_id, created_at, tag, normalized_tag FROM restaurant_tag ORDER BY created_at;

DROP TABLE restaurant_tag;
ALTER TABLE restaurant_tag2 RENAME restaurant_tag;

alter table restaurant_search_index ADD id INT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, add primary key (id);
alter table menuitem_tag change id id INT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, add primary key (id);
alter table menuitem_search_index ADD id INT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, add primary key (id);



SET NAMES latin1;
ALTER TABLE restaurant MODIFY COLUMN name TEXT CHARACTER SET latin1;
ALTER TABLE restaurant MODIFY COLUMN name BLOB;
ALTER TABLE restaurant MODIFY COLUMN name TEXT CHARACTER SET utf8;
ALTER TABLE restaurant MODIFY COLUMN name VARCHAR(128);

ALTER TABLE menu_item MODIFY COLUMN name TEXT CHARACTER SET latin1;
ALTER TABLE menu_item MODIFY COLUMN name BLOB;
ALTER TABLE menu_item MODIFY COLUMN name TEXT CHARACTER SET utf8;
ALTER TABLE restaurant_version MODIFY COLUMN description TEXT CHARACTER SET latin1;
ALTER TABLE restaurant_version MODIFY COLUMN description BLOB;
ALTER TABLE restaurant_version MODIFY COLUMN description TEXT CHARACTER SET utf8;
ALTER TABLE restaurant_version MODIFY COLUMN html_description TEXT CHARACTER SET latin1;
ALTER TABLE restaurant_version MODIFY COLUMN html_description BLOB;
ALTER TABLE restaurant_version MODIFY COLUMN html_description TEXT CHARACTER SET utf8;


SET NAMES utf8;

update menu_item set name=replace(name,'Ã»','û') where name LIKE '%Ã»%';
update restaurant set name=replace(name,'Ã»','û') where name LIKE '%Ã»%';
update menu_item set name=replace(name,'Ã©','é') where name LIKE '%Ã©%';
update restaurant set name=replace(name,'Ã©','é') where name LIKE '%Ã©%';
update menu_item set name=replace(name,'Ã¨','è') where name LIKE '%Ã¨%';
update restaurant set name=replace(name,'Ã¨','è') where name LIKE '%Ã¨%';
update menu_item set name=replace(name, 'Ã¶', 'ö') where name LIKE '%Ã¶%';
update restaurant set name=replace(name, 'Ã¶', 'ö') where name LIKE '%Ã¶%';
update menu_item set name=replace(name, 'Ã±', 'ñ') where name LIKE '%Ã±%';
update menu_item set name=replace(name, 'â€™', '\'') where name like '%â€™%';
update restaurant set name=replace(name, 'â€™', '\'') where name like '%â€™%';
update restaurant set name=replace(name, 'Ã¼', 'ü') where name like '%Ã¼%';
update restaurant set name=replace(name, 'TequilerÃ', 'Tequilerí')  where name LIKE '%TequilerÃ%';
update restaurant set name=replace(name, 'TaquerÃ', 'Taquerí')  where name LIKE '%TaquerÃ%';

CREATE TABLE restaurant_tmp (
  id int(11) NOT NULL auto_increment,
  name varchar(128) default NULL,
  stripped_title varchar(128) default NULL,
  approved int(11) default NULL,
  average_rating float(2,1) default NULL,
  num_ratings int(11) default 0,
  version_id int(11) default NULL,
  updated_at datetime default NULL,
  created_at datetime default NULL,
  PRIMARY KEY  (id),
  KEY version_id (version_id),
  UNIQUE name (name),
  UNIQUE slug (stripped_title),
  CONSTRAINT FOREIGN KEY (version_id) REFERENCES restaurant_version (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT IGNORE restaurant_tmp SELECT * FROM restaurant;
DROP TABLE restaurant;
RENAME TABLE restaurant_tmp TO restaurant;

update restaurant_version set description=replace(description,'â€', '\"'), html_description=replace(html_description,'â€œ', '\"') 
  where description like '%â€%';

DELETE l FROM location l LEFT JOIN restaurant r ON l.restaurant_id=r.id WHERE r.id IS NULL;
DELETE FROM location WHERE stripped_title='';

CREATE TABLE location_tmp (
  id int(11) NOT NULL auto_increment,
  restaurant_id int(11) default NULL,
  data_source varchar(32) default NULL,
  data_source_key varchar(255) default NULL,
  name varchar(255) default NULL,
  stripped_title varchar(255) default NULL,
  address varchar(255) default NULL,
  city varchar(128) default NULL,
  state varchar(16) default NULL,
  zip varchar(10) default NULL,
  country_id char(2) default NULL,
  latitude float(10,7) default NULL,
  longitude float(10,7) default NULL,
  phone varchar(16) default NULL,
  approved int(11) default NULL,
  updated_at datetime default NULL,
  created_at datetime default NULL,
  PRIMARY KEY (id),
  UNIQUE KEY  (data_source,data_source_key),
  UNIQUE KEY (restaurant_id,address,city,state,zip,country_id),
  KEY  (restaurant_id),
  KEY  (country_id),
  CONSTRAINT  FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) ON DELETE CASCADE,
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT IGNORE location_tmp SELECT * FROM location;
DROP TABLE location;
RENAME TABLE location_tmp TO location;

DELETE FROM location WHERE id IN (3715, 16480, 16232)

SET foreign_key_checks = 1;
" | mysql -u root rbu     


