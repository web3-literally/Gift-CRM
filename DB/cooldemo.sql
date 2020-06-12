/*
SQLyog Professional v12.5.1 (32 bit)
MySQL - 10.4.6-MariaDB : Database - cooldemo
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`cooldemo` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `cooldemo`;

/*Table structure for table `favourite` */

DROP TABLE IF EXISTS `favourite`;

CREATE TABLE `favourite` (
  `favourite_id` int(20) NOT NULL AUTO_INCREMENT,
  `favourite_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `favourite_image` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`favourite_id`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `favourite` */

insert  into `favourite`(`favourite_id`,`favourite_name`,`favourite_image`) values 
(53,'Product E','5.jpg'),
(37,'Product A','1.jpg'),
(52,'Product D','4.jpg'),
(51,'Product C','3.jpg'),
(50,'Product B','2.jpg'),
(49,'Product A','1.jpg'),
(47,'Product E','5.jpg'),
(48,'Product F','6.jpg');

/*Table structure for table `folderlist` */

DROP TABLE IF EXISTS `folderlist`;

CREATE TABLE `folderlist` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT 1 COMMENT 'for the future',
  `pid` int(11) unsigned NOT NULL COMMENT 'the parent id',
  `name` varchar(255) DEFAULT NULL,
  `type` tinyint(1) DEFAULT 1 COMMENT '1:folder 2:list',
  `state` tinyint(1) DEFAULT 1 COMMENT '1:current 0:removed',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

/*Data for the table `folderlist` */

insert  into `folderlist`(`id`,`userid`,`pid`,`name`,`type`,`state`) values 
(1,1,0,'Folder1',1,1),
(2,1,0,'Folder2',1,1),
(3,1,1,'subFolder1',1,1),
(4,1,1,'subFolder2',1,1),
(5,1,3,'ss1',1,1),
(6,1,5,'sss1',1,1),
(7,1,6,'list1',2,1),
(23,1,22,'list2',2,1),
(22,1,21,'sss2',1,1),
(21,1,20,'ss2',1,1),
(20,1,2,'subFolder3',1,1);

/*Table structure for table `product` */

DROP TABLE IF EXISTS `product`;

CREATE TABLE `product` (
  `product_id` int(20) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_image` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `product` */

insert  into `product`(`product_id`,`product_name`,`product_image`) values 
(1,'Product A','1.jpg'),
(2,'Product B','2.jpg'),
(3,'Product C','3.jpg'),
(4,'Product D','4.jpg'),
(5,'Product E','5.jpg'),
(6,'Product F','6.jpg');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
