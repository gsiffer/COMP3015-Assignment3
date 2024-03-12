create database redis_test;
use redis_test;

create table actor (
   actor_id int auto_increment not null primary key,
   first_name varchar(255),
   last_name varchar(255),
   last_update datetime
);