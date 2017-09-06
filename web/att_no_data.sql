-- MySQL dump 10.13  Distrib 5.5.55, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: att
-- ------------------------------------------------------
-- Server version	5.5.55-0+deb8u1

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
-- Table structure for table `AtAbsence`
--

DROP TABLE IF EXISTS `AtAbsence`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtAbsence` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attendance` int(11) NOT NULL,
  `absenceType` int(11) DEFAULT NULL,
  `certification` int(11) DEFAULT NULL,
  `stateJustif` tinyint(1) DEFAULT '0' COMMENT 'Estado de la Justificacion\n',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_AtInasistencia_idAsistencia` (`attendance`),
  KEY `IXFK_Absence_Attendance` (`attendance`),
  KEY `IXFK_Absence_Certification` (`certification`),
  KEY `IXFK_Absence_AbsenceType` (`absenceType`),
  CONSTRAINT `FK_attendance` FOREIGN KEY (`attendance`) REFERENCES `AtAttendance` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_type` FOREIGN KEY (`absenceType`) REFERENCES `AtAbsenceType` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1178 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtAbsenceNotification`
--

DROP TABLE IF EXISTS `AtAbsenceNotification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtAbsenceNotification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `employee` int(11) NOT NULL,
  `absenceType` int(11) NOT NULL,
  `days` int(11) NOT NULL,
  `fromDate` date NOT NULL,
  `toDate` date NOT NULL,
  `code` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IXFK_Notification_Employee` (`employee`),
  KEY `IXFK_Notification_AbsenceType` (`absenceType`),
  CONSTRAINT `FK_AbsType` FOREIGN KEY (`absenceType`) REFERENCES `AtAbsenceType` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_Employee` FOREIGN KEY (`employee`) REFERENCES `AtEmployee` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtAbsenceType`
--

DROP TABLE IF EXISTS `AtAbsenceType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtAbsenceType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtAditionalHoursDetail`
--

DROP TABLE IF EXISTS `AtAditionalHoursDetail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtAditionalHoursDetail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attendance` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IXFK_AtDetalleHorasExtras_AtAsistencia` (`attendance`),
  KEY `FK_type_idx` (`type`),
  CONSTRAINT `FK_typeaditionalhour` FOREIGN KEY (`type`) REFERENCES `AtAditionalHoursType` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtAditionalHoursType`
--

DROP TABLE IF EXISTS `AtAditionalHoursType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtAditionalHoursType` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `description` varchar(45) NOT NULL,
  `labourAgreement` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_agreement_idx` (`labourAgreement`),
  CONSTRAINT `FK_labourag` FOREIGN KEY (`labourAgreement`) REFERENCES `AtAgreement` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtAgreement`
--

DROP TABLE IF EXISTS `AtAgreement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtAgreement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `union` int(11) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_Union_idx` (`union`),
  CONSTRAINT `FG_union` FOREIGN KEY (`union`) REFERENCES `AtUnion` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtAttendance`
--

DROP TABLE IF EXISTS `AtAttendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtAttendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plan` int(11) NOT NULL,
  `stateAttendance` int(11) DEFAULT NULL,
  `inAtt` datetime DEFAULT NULL,
  `outAtt` datetime DEFAULT NULL,
  `logIn` datetime DEFAULT NULL,
  `logOut` datetime DEFAULT NULL,
  `hsWorked` time DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `plan_UNIQUE` (`plan`),
  KEY `IXFK_AtAsistencia_AtEstadoEmpleado` (`stateAttendance`),
  KEY `plan_idx` (`plan`),
  CONSTRAINT `plan` FOREIGN KEY (`plan`) REFERENCES `AtPlan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `stateAtt` FOREIGN KEY (`stateAttendance`) REFERENCES `AtStateAtt` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=248 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtBusiness`
--

DROP TABLE IF EXISTS `AtBusiness`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtBusiness` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(45) NOT NULL,
  `company` int(11) NOT NULL,
  `state` varchar(45) NOT NULL,
  `country` varchar(45) NOT NULL,
  `obs` text,
  PRIMARY KEY (`id`),
  KEY `business_company_idx` (`company`),
  CONSTRAINT `business_company` FOREIGN KEY (`company`) REFERENCES `AtCompany` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtCertificate`
--

DROP TABLE IF EXISTS `AtCertificate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtCertificate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee` int(11) NOT NULL,
  `date` date NOT NULL,
  `dateFrom` date DEFAULT NULL,
  `dateTo` date DEFAULT NULL,
  `aprobationState` tinyint(1) DEFAULT NULL,
  `details` text,
  `scan` varchar(100) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT '1',
  `attachDoc` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `un_certificate` (`employee`,`dateFrom`,`dateTo`),
  KEY `IXFK_AtCertificate_AtEmployee` (`employee`),
  KEY `FK_AtCertificate_Type_idx` (`type`),
  CONSTRAINT `FF_Type` FOREIGN KEY (`type`) REFERENCES `AtCertificateType` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_AtCertificate_AtEmployee` FOREIGN KEY (`employee`) REFERENCES `AtEmployee` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtCertificateMedical`
--

DROP TABLE IF EXISTS `AtCertificateMedical`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtCertificateMedical` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `matricula` bigint(20) NOT NULL,
  `diagnostico` text,
  `certificate` int(11) NOT NULL,
  `indicaReposo` tinyint(1) DEFAULT NULL,
  `profesional` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IXFK_AtCertificateMedical_AtCertificate` (`certificate`),
  CONSTRAINT `FK_AtCertificateMedical_AtCertificate` FOREIGN KEY (`certificate`) REFERENCES `AtCertificate` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtCertificateStude`
--

DROP TABLE IF EXISTS `AtCertificateStude`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtCertificateStude` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `institute` varchar(45) NOT NULL,
  `dateExam` date NOT NULL,
  `subject` varchar(45) NOT NULL,
  `certificate` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_Certificate_Stude_idx` (`certificate`),
  CONSTRAINT `FK_Certificate_Stude` FOREIGN KEY (`certificate`) REFERENCES `AtCertificate` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtCertificateType`
--

DROP TABLE IF EXISTS `AtCertificateType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtCertificateType` (
  `id` int(11) NOT NULL,
  `description` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtCompany`
--

DROP TABLE IF EXISTS `AtCompany`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtCompany` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(45) NOT NULL,
  `obs` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtContract`
--

DROP TABLE IF EXISTS `AtContract`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtContract` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee` int(11) NOT NULL,
  `business` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `sch` int(11) DEFAULT NULL,
  `fileNumber` int(11) DEFAULT NULL,
  `agreement` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `in_time` time DEFAULT NULL,
  `out_time` time DEFAULT NULL,
  PRIMARY KEY (`employee`,`business`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `IDX_6316C3561CD9BF62` (`employee`),
  KEY `IDX_6316C3563847FA69` (`business`),
  KEY `FK_25609506B88E4152` (`sch`),
  KEY `FK_4A6603572E655A24` (`agreement`),
  KEY `AtContract_ibfk_3` (`status`),
  CONSTRAINT `AtContract_ibfk_1` FOREIGN KEY (`employee`) REFERENCES `AtEmployee` (`id`),
  CONSTRAINT `AtContract_ibfk_2` FOREIGN KEY (`business`) REFERENCES `AtBusiness` (`id`),
  CONSTRAINT `AtContract_ibfk_3` FOREIGN KEY (`status`) REFERENCES `AtEmployeeStatus` (`id`),
  CONSTRAINT `FK_25609506B88E4152` FOREIGN KEY (`sch`) REFERENCES `Atschema` (`id`),
  CONSTRAINT `FK_4A6603572E655A24` FOREIGN KEY (`agreement`) REFERENCES `AtAgreement` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19104 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtContractRestDay`
--

DROP TABLE IF EXISTS `AtContractRestDay`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtContractRestDay` (
  `employee_id` int(11) NOT NULL,
  `business_id` int(11) NOT NULL,
  `restday_id` int(11) NOT NULL,
  PRIMARY KEY (`employee_id`,`business_id`,`restday_id`),
  KEY `IDX_8BA13D9C8C03F15CA89DB457` (`employee_id`,`business_id`),
  KEY `IDX_8BA13D9C853A7BF6` (`restday_id`),
  CONSTRAINT `FK_8BA13D9C8C03F15CA89DB457` FOREIGN KEY (`employee_id`, `business_id`) REFERENCES `AtContract` (`employee`, `business`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtEmployee`
--

DROP TABLE IF EXISTS `AtEmployee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtEmployee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `DNI` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `sex` varchar(1) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` varchar(35) DEFAULT NULL,
  `celPhone` varchar(35) DEFAULT NULL,
  `address` varchar(45) DEFAULT NULL,
  `addresslat` varchar(45) DEFAULT NULL,
  `addresslng` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `state` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_AtEmpleado_DNI` (`DNI`)
) ENGINE=InnoDB AUTO_INCREMENT=16328 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtEmployeeStatus`
--

DROP TABLE IF EXISTS `AtEmployeeStatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtEmployeeStatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtEvent`
--

DROP TABLE IF EXISTS `AtEvent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtEvent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eventType` int(11) DEFAULT NULL,
  `attendance` int(11) DEFAULT NULL,
  `schedule` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_schedule_idx` (`schedule`),
  KEY `FK_att_idx` (`attendance`),
  KEY `FK_event_idx` (`eventType`),
  CONSTRAINT `FK_att` FOREIGN KEY (`attendance`) REFERENCES `AtAttendance` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_event` FOREIGN KEY (`eventType`) REFERENCES `AtEventType` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_schedule` FOREIGN KEY (`schedule`) REFERENCES `AtSchedule` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtEventType`
--

DROP TABLE IF EXISTS `AtEventType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtEventType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `externalSystem` int(11) DEFAULT NULL,
  `description` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_ExternalSystem_idx` (`externalSystem`),
  KEY `FK_ExSys_idx` (`externalSystem`),
  CONSTRAINT `FK_ExSys` FOREIGN KEY (`externalSystem`) REFERENCES `AtExternSystem` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtExternSystem`
--

DROP TABLE IF EXISTS `AtExternSystem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtExternSystem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `type` int(11) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `port` int(11) NOT NULL,
  `module` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_Type_idx` (`type`),
  CONSTRAINT `FK_SystemType` FOREIGN KEY (`type`) REFERENCES `AtExternSystemType` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtExternSystemType`
--

DROP TABLE IF EXISTS `AtExternSystemType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtExternSystemType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtLicense`
--

DROP TABLE IF EXISTS `AtLicense`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtLicense` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `absenceType` int(11) NOT NULL,
  `maxDay` int(11) NOT NULL COMMENT 'Dias Autorizados Anuales',
  `maxDayCons` int(11) DEFAULT NULL,
  `certification` binary(1) NOT NULL DEFAULT '1',
  `payment` binary(1) NOT NULL DEFAULT '0',
  `labouragreement` int(11) NOT NULL,
  `description` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `absenceType` (`absenceType`),
  KEY `FK_lagr_idx` (`labouragreement`),
  CONSTRAINT `FK_absenceType` FOREIGN KEY (`absenceType`) REFERENCES `AtAbsenceType` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_lagr` FOREIGN KEY (`labouragreement`) REFERENCES `AtAgreement` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtMedicalOrder`
--

DROP TABLE IF EXISTS `AtMedicalOrder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtMedicalOrder` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `service` int(11) NOT NULL,
  `prediagnostic` text NOT NULL,
  `employee` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_serMed_idx` (`service`),
  KEY `FK_empl_idx` (`employee`),
  CONSTRAINT `FK_empl` FOREIGN KEY (`employee`) REFERENCES `AtEmployee` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_serMed` FOREIGN KEY (`service`) REFERENCES `AtMedicalService` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtMedicalService`
--

DROP TABLE IF EXISTS `AtMedicalService`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtMedicalService` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `externalSystem` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_ExternalSystem_idx` (`externalSystem`),
  CONSTRAINT `FK_ExternalSys` FOREIGN KEY (`externalSystem`) REFERENCES `AtExternSystem` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtMedicalVisit`
--

DROP TABLE IF EXISTS `AtMedicalVisit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtMedicalVisit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visitDate` datetime DEFAULT NULL,
  `matricula` varchar(50) DEFAULT NULL,
  `diagnostic` text,
  `medicalRest` binary(1) DEFAULT '0',
  `restDateFrom` datetime DEFAULT NULL,
  `restDateTo` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtPlan`
--

DROP TABLE IF EXISTS `AtPlan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtPlan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee` int(11) NOT NULL,
  `date` date NOT NULL,
  `inPlan` datetime NOT NULL,
  `outPlan` datetime NOT NULL,
  `hsWorkPlan` int(11) NOT NULL,
  `statePlan` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_index` (`employee`,`date`),
  KEY `IXFK_Plan_Empleado` (`employee`),
  KEY `IXFK_Plan_StatePlan` (`statePlan`),
  CONSTRAINT `employee` FOREIGN KEY (`employee`) REFERENCES `AtEmployee` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `statePlan` FOREIGN KEY (`statePlan`) REFERENCES `AtStatePlan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=549 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtPlanInconsistency`
--

DROP TABLE IF EXISTS `AtPlanInconsistency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtPlanInconsistency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plan` int(11) NOT NULL,
  `att` int(11) NOT NULL,
  `obs` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `plan_idx` (`plan`),
  KEY `att_idx` (`att`),
  CONSTRAINT `incons_att` FOREIGN KEY (`att`) REFERENCES `AtAttendance` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `incons_plan` FOREIGN KEY (`plan`) REFERENCES `AtPlan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtRestDay`
--

DROP TABLE IF EXISTS `AtRestDay`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtRestDay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtSchedule`
--

DROP TABLE IF EXISTS `AtSchedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtSchedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `executeDay` date DEFAULT NULL,
  `executeHour` time DEFAULT NULL,
  `command` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtStateAtt`
--

DROP TABLE IF EXISTS `AtStateAtt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtStateAtt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtStatePlan`
--

DROP TABLE IF EXISTS `AtStatePlan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtStatePlan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtUnion`
--

DROP TABLE IF EXISTS `AtUnion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtUnion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtWorkFlow`
--

DROP TABLE IF EXISTS `AtWorkFlow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtWorkFlow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `stateKey` varchar(45) DEFAULT NULL,
  `entityID` int(11) DEFAULT NULL,
  `workflow` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_entity_wkflow` (`entityID`,`workflow`),
  KEY `IXFK_wftype` (`workflow`),
  CONSTRAINT `FK_wktype` FOREIGN KEY (`workflow`) REFERENCES `AtWorkflowType` (`serviceID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtWorkflowMsg`
--

DROP TABLE IF EXISTS `AtWorkflowMsg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtWorkflowMsg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `workflow` int(11) NOT NULL,
  `create_at` datetime NOT NULL,
  `wfstate` varchar(45) NOT NULL,
  `details` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_workflow_idx` (`workflow`),
  CONSTRAINT `FK_workflow` FOREIGN KEY (`workflow`) REFERENCES `AtWorkFlow` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AtWorkflowType`
--

DROP TABLE IF EXISTS `AtWorkflowType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AtWorkflowType` (
  `serviceID` varchar(50) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `entityWorkFlow` varchar(50) NOT NULL,
  PRIMARY KEY (`serviceID`),
  KEY `name` (`name`),
  KEY `IXFK_AtWorkflowState_AtWorkflow` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Atschema`
--

DROP TABLE IF EXISTS `Atschema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Atschema` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `description` varchar(45) NOT NULL,
  `hours` decimal(3,1) NOT NULL,
  `days` int(11) NOT NULL,
  `agreement` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_44345435fd3433` (`agreement`),
  CONSTRAINT `FK_44345435fd3433` FOREIGN KEY (`agreement`) REFERENCES `AtAgreement` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fos_group`
--

DROP TABLE IF EXISTS `fos_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fos_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_4B019DDB5E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fos_user`
--

DROP TABLE IF EXISTS `fos_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fos_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_957A647992FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_957A6479A0D96FBF` (`email_canonical`),
  UNIQUE KEY `UNIQ_957A6479C05FB297` (`confirmation_token`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fos_user_user_group`
--

DROP TABLE IF EXISTS `fos_user_user_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fos_user_user_group` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`),
  KEY `IDX_B3C77447A76ED395` (`user_id`),
  KEY `IDX_B3C77447FE54D947` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-06-09 12:30:38
