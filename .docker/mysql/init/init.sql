CREATE DATABASE IF NOT EXISTS `mycommerce`;

CREATE USER 'root'@'localhost' IDENTIFIED BY `local`;
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%';