-- MySQL dump 10.13  Distrib 8.1.0, for macos11.7 (x86_64)
--
-- Host: localhost    Database: desafio_php_clientes
-- ------------------------------------------------------
-- Server version	8.1.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (1,'Alice Souza','111-1111','alice@email.com','Rua A, 1'),(2,'Bruno Martins','222-2222','bruno@email.com','Rua B, 2'),(3,'Carla Silva','333-3333','carla@email.com','Rua C, 3'),(4,'Diego Oliveira','444-4444','diego@email.com','Rua D, 4'),(5,'Elena Castro','555-5555','elena@email.com','Rua E, 5'),(6,'Felipe Costa','666-6666','felipe@email.com','Rua F, 6'),(7,'Gisele Dias','777-7777','gisele@email.com','Rua G, 7'),(8,'Henrique Santos','888-8888','henrique@email.com','Rua H, 8'),(9,'Igor Lima','999-9999','igor@email.com','Rua I, 9'),(10,'Juliana Moraes','000-0000','juliana@email.com','Rua J, 10');
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedidos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int DEFAULT NULL,
  `valor_total` float DEFAULT NULL,
  `descricao` text,
  `data` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidos`
--

LOCK TABLES `pedidos` WRITE;
/*!40000 ALTER TABLE `pedidos` DISABLE KEYS */;
INSERT INTO `pedidos` VALUES (1,1,150.99,'Camiseta de algodão, jeans azul','2023-10-22 10:30:00'),(2,2,249.5,'Sapatos de couro marrom','2023-10-23 11:15:00'),(3,3,75,'Óculos de sol com proteção UV','2023-10-24 14:45:00'),(4,4,99.99,'Relógio analógico, pulseira de couro','2023-10-25 16:20:00'),(5,5,349.99,'Notebook Dell, Intel i7, 16GB RAM','2023-10-26 09:55:00'),(6,6,45,'Livro best-seller: \"A Arte da Guerra\"','2023-10-27 20:10:00'),(7,7,199.99,'Smartphone Samsung Galaxy S21','2023-10-28 13:30:00'),(8,8,79.95,'Fones de ouvido Bluetooth','2023-10-29 18:45:00'),(9,9,129,'Tênis esportivo, tamanho 42','2023-10-30 10:05:00'),(10,10,299.99,'Televisão LED 50 polegadas','2023-10-31 15:25:00');
/*!40000 ALTER TABLE `pedidos` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-10-22  5:50:22
