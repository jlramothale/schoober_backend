
/**
 * Author:  jramothale
 * Created: Oct 14, 2018
 */

create schema schoober_db default character set latin1 collate latin1_swedish_ci;

use schoober_db;

/**
  API Authentication Keys: autho a request on an API Level.
  also store a copy of this key into the user system as a .txt file.
  e.g. api_autho_key.txt: 10214154523267284226451221
 */
create table if not exists api_autho_keys (
  id bigint auto_increment primary key not null,
  autho_key varchar(128) unique not null default '',
  device varchar(128) not null default '',
  mac_address varchar(128) not null default '',
  date_generated timestamp not null default current_timestamp,
  deleted tinyint(1) not null default 0
) ENGINE=InnoDB;

-- users
create table if not exists users (
  id bigint auto_increment primary key not null,
  user_id varchar(128) unique not null default '', -- user id
  user_type varchar(64) not null default 'Driver', -- Driver, Parent, Association
  email varchar(256) unique not null default '',
  hash_value varchar(256) not null default '',
  is_online tinyint(1) not null default 0,
  is_suspended tinyint(1) not null default 0,
  is_profile_complete tinyint(1) not null default 0,
  date_added timestamp not null default current_timestamp,
  date_removed timestamp not null default current_timestamp,
  deleted tinyint(1) not null default 0
) ENGINE=InnoDB;

-- profile
create table if not exists user_profile (
  id bigint auto_increment primary key not null,
  user_id varchar(128) references users(user_id),
  first_name varchar(128) not null default '',
  last_name varchar(128) not null default '',
  gender varchar(16) not null default '',
  cell_number varchar(16) not null default '',
  street_address varchar(256) not null default '',
  town varchar(128) not null default '',
  province varchar(24) not null default '',
  code varchar(16) not null default '',
  profile_photo varchar(512) not null default '',
  last_modified timestamp not null default current_timestamp,
  deleted tinyint(1) not null default 0
) ENGINE=InnoDB;

-- password codes: i.e. your one time passwords (OTP)
create table if not exists user_code (
  id bigint unsigned auto_increment primary key not null,
  user_id varchar(128) references users(user_id),
  reason varchar(128) not null default 'Password',
  code varchar(64) not null default '',
  date_sent timestamp not null default current_timestamp,
  expiration_length varchar(16) not null default '60',
  is_expired tinyint(1) not null default 0,
  is_confirmed tinyint(1) not null default 0,
  date_confirmed timestamp not null default current_timestamp,
  deleted tinyint(1) not null default 0
) ENGINE=InnoDB;

-- user operations log
create table if not exists usr_log (
  id bigint unsigned auto_increment primary key not null,
  user_id varchar(128) not null default '',
  date_entry timestamp not null default current_timestamp,
  operation varchar(512) not null default '',
  ip_address varchar(32) not null default '',
  device varchar(512) not null default '',
  location varchar(512) not null default '',
  deleted tinyint(1) not null default 0
) ENGINE=InnoDB;

-- system operation log
create table if not exists sys_log (
  id bigint unsigned auto_increment primary key not null,
  date_entry timestamp not null default current_timestamp,
  operation varchar(512) not null default '',
  ip_address varchar(32) not null default '',
  device varchar(512) not null default '',
  location varchar(512) not null default '',
  deleted tinyint(1) not null default 0
) ENGINE=InnoDB;

/** Driver *
-- driver profile
create table if not exists driver_profile (
  id bigint unsigned auto_increment primary key not null,
  user_id varchar(128) references usr_users(user_id),
  license_number varchar(64) not null default '',
  r_from varchar(256) not null default '', -- Tembisa
  r_to varchar(256) not null default '', -- Norkem Park
  t_slots_count varchar(8) not null default '0', -- a driver can only get 3 slots
  last_modified timestamp not null default current_timestamp,
  deleted tinyint(1) not null default 0
) ENGINE=InnoDB;

-- driver time slots
create table if not exists driver_time_slots (
  id bigint unsigned auto_increment primary key not null,
  user_id varchar(128) references usr_users(user_id),
  m_pick_up_time varchar(128) not null default '', -- morning pick-up time: 6am
  a_pick_up_time varchar(128) not null default '', -- after noon pick-up time: 2pm
  school varchar(128) not null default '', -- Norkem High
  estimated_distance varchar(64) not null default '', -- 5Km
  eta varchar(64) not null default '', -- 23 Minutes
  last_modified timestamp not null default current_timestamp,
  deleted tinyint(1) not null default 0
) ENGINE=InnoDB;

-- driver vehicle
create table if not exists driver_vehicle (
  id bigint unsigned auto_increment primary key not null,
  user_id varchar(128) references usr_users(user_id),
  status varchar(64) not null default 'Review', -- Review, Approved, Decline
  status_update_date timestamp not null default current_timestamp,
  v_type varchar(128) not null default 'Mini Bus', -- Mini Bus, Van.
  capacity varchar(16) not null default '5', -- the capacity of a vehicle, i.e. seats/scholar it can carry
  wheelchair_friendly tinyint(1) not null default 0, -- is the vehicle wheelchair friendly
  v_model varchar(128) not null default '',
  condition varchar(64) not null default 'Good', -- Good, Excellent
  number_plate varchar(32) not null default '',
  engine_number varchar(64) not null default '',
  vin_number varchar(64) not null default '',
  description varchar(2048) not null default '', -- description of the vehicle
  more_info varchar(2048) not null default '', -- any special info about the vehicle
  last_modified timestamp not null default current_timestamp,
  deleted tinyint(1) not null default 0
) ENGINE=InnoDB;

-- vehicle pictures: drivers can upload up to 6 pictures
create table if not exists driver_v_pictures (
  id bigint unsigned auto_increment primary key not null,
  user_id varchar(128) references usr_users(user_id),
  img_1_location varchar(512) not null default '', -- img location of sever
  img_2_location varchar(512) not null default '', -- img location of sever
  img_3_location varchar(512) not null default '', -- img location of sever
  img_4_location varchar(512) not null default '', -- img location of sever
  img_5_location varchar(512) not null default '', -- img location of sever
  img_6_location varchar(512) not null default '', -- img location of sever
  last_modified timestamp not null default current_timestamp,
  deleted tinyint(1) not null default 0
) ENGINE=InnoDB;
