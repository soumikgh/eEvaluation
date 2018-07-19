-- MySQL dump 10.13  Distrib 5.5.31, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: oes
-- ------------------------------------------------------
-- Server version	5.5.31-0ubuntu0.12.04.1-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `answers`
--

DROP TABLE IF EXISTS `answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `answers` (
  `ans_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `q_id` int(11) DEFAULT NULL,
  `answer` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`ans_id`),
  KEY `fk_usr` (`user_id`),
  KEY `fk_q` (`q_id`),
  CONSTRAINT `fk_q` FOREIGN KEY (`q_id`) REFERENCES `questions` (`q_id`),
  CONSTRAINT `fk_usr` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `answers`
--

LOCK TABLES `answers` WRITE;
/*!40000 ALTER TABLE `answers` DISABLE KEYS */;
INSERT INTO `answers` VALUES (1,2,1,3),(2,2,2,3),(3,2,8,NULL),(4,2,9,NULL),(5,2,7,NULL),(6,2,6,NULL),(7,2,3,NULL),(8,2,5,NULL),(9,2,4,NULL),(10,2,10,NULL),(11,3,6,NULL),(12,3,4,NULL),(13,3,2,NULL),(14,3,7,NULL),(15,3,8,NULL),(16,3,3,NULL),(17,3,1,NULL),(18,3,9,NULL),(19,3,10,NULL),(20,3,5,NULL);
/*!40000 ALTER TABLE `answers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exams`
--

DROP TABLE IF EXISTS `exams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exams` (
  `exam_id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `exam_no_of_qs` int(11) NOT NULL,
  `exam_time` int(11) NOT NULL,
  `pos_marks` tinyint(4) NOT NULL,
  `neg_marks` tinyint(4) NOT NULL,
  `exam_allow` tinyint(1) NOT NULL,
  PRIMARY KEY (`exam_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exams`
--

LOCK TABLES `exams` WRITE;
/*!40000 ALTER TABLE `exams` DISABLE KEYS */;
INSERT INTO `exams` VALUES (1,'Sample Exam Title',10,100,2,0,1);
/*!40000 ALTER TABLE `exams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questions` (
  `q_id` int(11) NOT NULL AUTO_INCREMENT,
  `q_body` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `q_ans1` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `q_ans2` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `q_ans3` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `q_ans4` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `q_correct_ans` int(11) NOT NULL,
  `q_allow` tinyint(1) NOT NULL,
  `q_img` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`q_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
INSERT INTO `questions` VALUES (1,'Who is the current President of India?','Phunsukh Wangdu','A. P. J. Abdul Kalam','Pranab Mukherjee','Pratibha Patil',3,1,0),(2,'Who is the current President of the United States of America?','Barack Obama','Mitt Romney','John McCain','Joe Biden',1,1,0),(3,'Who is the current Prime Minister of India?','Sashi Tharoor','Imhotep','Dr. Manmohan Singh','Enrique Iglesias',3,1,0),(4,'What is the capital of India?','Thiruvananthapuram','New Delhi','Ooty','Kolkata',2,1,0),(5,'What is the capital of West Bengal?','Malda','Murshidabad','Bankura','Kolkata',4,1,0),(6,'An example of a fuel-efficient, popular Indian car is Maruti _______ 800.','Skoda','Ferrari','Suzuki','Salman',3,1,0),(7,'Select the appropriate word: \"Why this __________, __________, __________ Di?\"','Godaveri','Kolaveri','Blackberry','Strawberry',2,1,0),(8,'Which recent British exploitation film based on slums in Mumbai  won an Oscar, and needlessly excited a lot of Indians?','SlamCat Billionaire','Bombay Dreams','Bend It Like Beckham','Slumdog Millionaire',4,1,0),(9,'Which function is commonly used to send formatted output to stdout in C?','System.out.print()','out','get_out()','printf()',4,1,0),(10,'The tallest mountain in the world is in this mountain range.','Andes','Himalayas','Appalachian','Rocky',2,1,0);
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_email` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `user_role` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_fname` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_lname` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_pass` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `user_sex` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_addr` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_city` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_state` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_pin` decimal(6,0) DEFAULT NULL,
  `user_pnumber` decimal(10,0) DEFAULT NULL,
  `user_salt` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_status` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_allow` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `q_corr_ans` smallint(6) DEFAULT NULL,
  `q_inc_ans` smallint(6) DEFAULT NULL,
  `q_un_ans` smallint(6) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'soumik@soumikghosh.com','A','Soumik','Ghosh','ùp0fA3HCuou2','F','Test','Kolkata','West Bengal',700061,9433628181,'ù J#¶ùZ¸zÑ~@µ´¼³','A','1',NULL,NULL,NULL,NULL),(2,'sgoes@dispostable.com','U','Sgoes','Surname','ûñTPPLqUmMrck','M','Test','Test','Arunachal Pradesh',555555,5555555555,'ûñFÓð2ø´`‰Vâ‡ž\n³9Ã','A','0',1,1,8,2),(3,'foobar@dispostable.com','U','Foo','Bar','—¸BUyflVAMC6c','M','Test','Test','Haryana',999999,9999999999,'—¸¤«Fò[_û/‡`(üî`','A','0',0,0,10,0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-05-05 15:37:08
