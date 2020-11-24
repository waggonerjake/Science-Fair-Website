-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 17, 2020 at 05:10 PM
-- Server version: 5.5.65-MariaDB
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jwaggon_db`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`jwaggon`@`localhost` PROCEDURE `getAverageRank`(IN `proj` INT)
    NO SQL
BEGIN
DECLARE numOfUnrankedProjects int;


SELECT count(*) into numOfUnrankedProjects FROM `ASSIGNMENT` where rank = -1;

IF numOfUnrankedProjects = 0 THEN
SELECT CAST(avg(rank) as INT) as average FROM `ASSIGNMENT` WHERE projectID = proj and rank != -1;
ELSE
SELECT -1 as average FROM DUAL;
END IF;
END$$

CREATE DEFINER=`jwaggon`@`localhost` PROCEDURE `updateProjectRank`(IN `avgRank` INT, IN `proj` INT)
    NO SQL
update PROJECT set rank = avgRank where projectID = proj$$

CREATE DEFINER=`jwaggon`@`localhost` PROCEDURE `update_ranks`(IN `myJudge` INT)
    NO SQL
BEGIN

DECLARE counter int;
DECLARE judgeScore int;
DECLARE done INT DEFAULT FALSE;

DECLARE cur1 CURSOR FOR
SELECT DISTINCT(score) 
FROM `ASSIGNMENT` 
where judgeID = myJudge
AND score != -1
ORDER BY judgeID, score DESC;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;


OPEN cur1;

set @counter = 1;

loop_action: LOOP
  FETCH cur1 into judgeScore;
  IF done THEN
    LEAVE loop_action;
  END IF;
  
  update ASSIGNMENT 
  set rank = @counter 
  where judgeID = myJudge 
  AND score = judgeScore;
  
  set @counter = @counter + 1;
  
END LOOP;

CLOSE cur1;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `ADMINISTRATOR`
--

CREATE TABLE IF NOT EXISTS `ADMINISTRATOR` (
  `adminID` int(11) NOT NULL,
  `firstName` varchar(50) CHARACTER SET utf8 NOT NULL,
  `lastName` varchar(50) CHARACTER SET utf8 NOT NULL,
  `middleName` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(70) CHARACTER SET utf8 NOT NULL,
  `password` varchar(150) CHARACTER SET utf8 NOT NULL,
  `levelID` int(11) NOT NULL,
  `active` varchar(1) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ADMINISTRATOR`
--

INSERT INTO `ADMINISTRATOR` (`adminID`, `firstName`, `lastName`, `middleName`, `email`, `password`, `levelID`, `active`) VALUES
(1, 'JAKE', 'WAGGONER', 'JAKERS', 'JWAGGON@IU.EDU', 'password', 1, 'Y'),
(4, 'JACOB', 'HEEBNER', '', 'JACOBHEEBNER@GMAIL.COM', 'password', 1, 'Y'),
(19, 'JAKE', 'WAGGONER', 'JANE', 'test@test.com', 'password', 2, 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `ADMIN_LEVEL`
--

CREATE TABLE IF NOT EXISTS `ADMIN_LEVEL` (
  `levelID` int(11) NOT NULL,
  `level` varchar(25) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ADMIN_LEVEL`
--

INSERT INTO `ADMIN_LEVEL` (`levelID`, `level`) VALUES
(1, 'Admin'),
(2, 'Grade Level Chair');

-- --------------------------------------------------------

--
-- Table structure for table `ASSIGNMENT`
--

CREATE TABLE IF NOT EXISTS `ASSIGNMENT` (
  `assignmentID` int(11) NOT NULL,
  `sessionID` int(11) NOT NULL,
  `judgeID` int(11) NOT NULL,
  `projectID` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `rank` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=670 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ASSIGNMENT`
--

INSERT INTO `ASSIGNMENT` (`assignmentID`, `sessionID`, `judgeID`, `projectID`, `score`, `rank`) VALUES
(593, 1, 45, 34, 12, 9),
(594, 1, 43, 36, 83, 2),
(595, 1, 42, 38, 25, 10),
(596, 1, 44, 39, 90, 2),
(597, 1, 46, 40, 34, 7),
(598, 1, 47, 41, 100, 1),
(599, 1, 45, 42, 33, 7),
(600, 2, 41, 43, 69, 5),
(601, 2, 43, 44, 12, 9),
(602, 2, 42, 45, 33, 9),
(603, 2, 46, 46, 23, 8),
(604, 2, 44, 47, 55, 5),
(605, 2, 47, 48, 88, 3),
(606, 2, 48, 49, 33, 6),
(607, 3, 41, 50, 15, 10),
(608, 3, 42, 51, 51, 6),
(609, 3, 43, 52, 44, 5),
(610, 3, 44, 53, 81, 3),
(611, 3, 46, 54, 82, 3),
(612, 3, 47, 55, 91, 2),
(613, 3, 48, 34, 72, 3),
(614, 4, 41, 36, 73, 3),
(615, 4, 43, 38, 33, 6),
(616, 4, 42, 39, 97, 1),
(617, 4, 44, 40, 11, 7),
(618, 4, 48, 41, 44, 5),
(619, 4, 46, 42, 44, 6),
(620, 4, 47, 43, 38, 6),
(621, 5, 41, 44, 99, 1),
(622, 5, 43, 45, 69, 3),
(623, 5, 45, 46, 40, 6),
(624, 5, 42, 47, 64, 4),
(625, 5, 44, 48, 8, 8),
(626, 5, 47, 49, 55, 5),
(627, 5, 48, 50, 85, 2),
(628, 6, 43, 51, 44, 5),
(629, 6, 42, 52, 61, 5),
(630, 6, 41, 53, 56, 7),
(631, 6, 45, 54, 69, 4),
(632, 6, 44, 55, 1, 10),
(633, 6, 47, 34, 29, 8),
(634, 6, 46, 36, 4, 9),
(635, 7, 41, 38, 29, 8),
(636, 7, 43, 39, 18, 8),
(637, 7, 42, 40, 20, 11),
(638, 7, 44, 41, 81, 3),
(639, 7, 45, 42, 90, 1),
(640, 7, 46, 43, 54, 5),
(641, 7, 45, 44, 80, 2),
(642, 8, 44, 45, 2, 9),
(643, 8, 41, 46, 86, 2),
(644, 8, 43, 47, 22, 7),
(645, 8, 42, 48, 43, 7),
(646, 8, 45, 49, 77, 3),
(647, 8, 47, 50, 75, 4),
(648, 8, 48, 51, 95, 1),
(649, 9, 44, 52, 29, 6),
(650, 9, 42, 53, 36, 8),
(651, 9, 41, 54, 63, 6),
(652, 9, 43, 55, 53, 4),
(653, 9, 45, 34, 22, 8),
(654, 9, 45, 36, 55, 5),
(655, 9, 46, 38, 90, 2),
(656, 10, 41, 39, 72, 4),
(657, 10, 43, 40, 88, 1),
(658, 10, 42, 41, 92, 2),
(659, 10, 44, 42, 77, 4),
(660, 10, 48, 43, 56, 4),
(661, 10, 46, 44, 91, 1),
(662, 10, 45, 45, 90, 1),
(663, 13, 43, 46, 11, 10),
(664, 13, 41, 47, 20, 9),
(665, 13, 46, 48, 78, 4),
(666, 13, 42, 49, 71, 3),
(667, 13, 44, 50, 99, 1),
(668, 13, 47, 51, 34, 7),
(669, 13, 48, 52, 21, 7);

-- --------------------------------------------------------

--
-- Table structure for table `BOOTH_NUMBER`
--

CREATE TABLE IF NOT EXISTS `BOOTH_NUMBER` (
  `boothID` int(11) NOT NULL,
  `boothNumber` int(11) NOT NULL,
  `active` varchar(1) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `BOOTH_NUMBER`
--

INSERT INTO `BOOTH_NUMBER` (`boothID`, `boothNumber`, `active`) VALUES
(1, 44, 'Y'),
(2, 455, 'Y'),
(52, 52, 'Y'),
(56, 66, 'Y'),
(60, 78, 'Y'),
(66, 132, 'Y'),
(80, 47, 'Y'),
(81, 81, 'Y'),
(82, 97, 'Y'),
(83, 10, 'Y'),
(86, 111, 'Y'),
(87, 12, 'Y'),
(88, 764, '1'),
(89, 13, 'Y'),
(90, 14, 'Y'),
(91, 15, 'Y'),
(92, 16, 'Y'),
(93, 17, 'Y'),
(94, 18, 'Y'),
(95, 43, 'Y'),
(96, 38, 'Y'),
(98, 999, 'Y'),
(99, 105, 'Y'),
(100, 144, 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `CATEGORY`
--

CREATE TABLE IF NOT EXISTS `CATEGORY` (
  `categoryID` int(11) NOT NULL,
  `categoryName` varchar(50) CHARACTER SET utf8 NOT NULL,
  `active` varchar(1) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `CATEGORY`
--

INSERT INTO `CATEGORY` (`categoryID`, `categoryName`, `active`) VALUES
(1, 'Computer Science', 'Y'),
(2, 'Physics', 'Y'),
(6, 'Biology', 'Y'),
(7, 'Accounting', 'Y'),
(24, 'ASTRONOMY', 'Y'),
(25, 'MATH', 'Y'),
(28, 'ELEMENTARY LINEAR ALGEBRA', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `CITY`
--

CREATE TABLE IF NOT EXISTS `CITY` (
  `cityID` int(11) NOT NULL,
  `cityName` varchar(50) CHARACTER SET utf8 NOT NULL,
  `countyID` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=761 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `CITY`
--

INSERT INTO `CITY` (`cityID`, `cityName`, `countyID`) VALUES
(4, 'ALBANY', 18),
(5, 'ALBION', 58),
(6, 'ALEXANDRIA', 78),
(7, 'AMBIA', 77),
(8, 'AMBOY', 26),
(9, 'AMO', 14),
(10, 'ANDERSON', 78),
(11, 'ANDREWS', 82),
(12, 'ANGOLA', 79),
(13, 'ARCADIA', 54),
(14, 'ARCOLA', 61),
(15, 'ARGOS', 38),
(16, 'ARLINGTON', 24),
(17, 'ASHLEY', 56),
(18, 'ATHENS', 2),
(19, 'ATLANTA', 54),
(20, 'ATTICA', 76),
(21, 'ATWOOD', 40),
(22, 'AUBURN', 56),
(23, 'AURORA', 42),
(24, 'AUSTIN', 44),
(25, 'AVILLA', 58),
(26, 'AVOCA', 84),
(27, 'AVON', 14),
(28, 'BAINBRIDGE', 73),
(29, 'BARGERSVILLE', 41),
(30, 'BATESVILLE', 48),
(31, 'BATH', 32),
(32, 'BATTLE GROUND', 86),
(33, 'BEDFORD', 84),
(34, 'BEECH GROVE', 87),
(35, 'BELLMORE', 28),
(36, 'BENNINGTON', 70),
(37, 'BENTONVILLE', 69),
(38, 'BERNE', 31),
(39, 'BETHLEHEM', 19),
(40, 'BEVERLY SHORES', 33),
(41, 'BICKNELL', 60),
(42, 'BIPPUS', 82),
(43, 'BIRDSEYE', 23),
(44, 'BLANFORD', 39),
(45, 'BLOOMFIELD', 91),
(46, 'BLOOMINGDALE', 28),
(47, 'BLOOMINGTON', 29),
(48, 'BLUFFTON', 88),
(49, 'BOGGSTOWN', 43),
(50, 'BOONE GROVE', 33),
(51, 'BOONVILLE', 74),
(52, 'BORDEN', 19),
(53, 'BOSTON', 8),
(54, 'BOSWELL', 77),
(55, 'BOURBON', 38),
(56, 'BOWLING GREEN', 66),
(57, 'BRADFORD', 34),
(58, 'BRANCHVILLE', 53),
(59, 'BRAZIL', 66),
(60, 'BREMEN', 38),
(61, 'BRIDGETON', 28),
(62, 'BRINGHURST', 25),
(63, 'BRISTOL', 21),
(64, 'BRISTOW', 53),
(65, 'BROOK', 90),
(66, 'BROOKLYN', 47),
(67, 'BROOKSTON', 80),
(68, 'BROOKVILLE', 32),
(69, 'BROWNSBURG', 14),
(70, 'BROWNSTOWN', 10),
(71, 'BROWNSVILLE', 55),
(72, 'BRUCEVILLE', 60),
(73, 'BRYANT', 13),
(74, 'BUCK CREEK', 86),
(75, 'BUCKSKIN', 37),
(76, 'BUFFALO', 80),
(77, 'BUNKER HILL', 26),
(78, 'BURKET', 40),
(79, 'BURLINGTON', 25),
(80, 'BURNETTSVILLE', 80),
(81, 'BURROWS', 25),
(82, 'BUTLER', 56),
(83, 'BUTLERVILLE', 89),
(84, 'CAMBRIDGE CITY', 8),
(85, 'CAMBY', 47),
(86, 'CAMDEN', 25),
(87, 'CAMPBELLSBURG', 85),
(88, 'CANAAN', 20),
(89, 'CANNELBURG', 64),
(90, 'CANNELTON', 53),
(91, 'CARBON', 66),
(92, 'CARLISLE', 15),
(93, 'CARMEL', 54),
(94, 'CARTHAGE', 24),
(95, 'CAYUGA', 39),
(96, 'CEDAR GROVE', 32),
(97, 'CEDAR LAKE', 4),
(98, 'CELESTINE', 23),
(99, 'CENTERPOINT', 66),
(100, 'CENTERVILLE', 8),
(101, 'CENTRAL', 34),
(102, 'CHALMERS', 80),
(103, 'CHANDLER', 74),
(104, 'CHARLESTOWN', 19),
(105, 'CHARLOTTESVILLE', 9),
(106, 'CHESTERTON', 33),
(107, 'CHRISNEY', 35),
(108, 'CHURUBUSCO', 30),
(109, 'CICERO', 54),
(110, 'CLARKS HILL', 86),
(111, 'CLARKSBURG', 36),
(112, 'CLARKSVILLE', 19),
(113, 'CLAY CITY', 66),
(114, 'CLAYPOOL', 40),
(115, 'CLAYTON', 14),
(116, 'CLEAR CREEK', 29),
(117, 'CLIFFORD', 6),
(118, 'CLINTON', 39),
(119, 'CLOVERDALE', 73),
(120, 'COAL CITY', 92),
(121, 'COALMONT', 66),
(122, 'COATESVILLE', 73),
(123, 'COLFAX', 7),
(124, 'COLUMBIA CITY', 30),
(125, 'COLUMBUS', 6),
(126, 'COMMISKEY', 89),
(127, 'CONNERSVILLE', 69),
(128, 'CONVERSE', 26),
(129, 'CORTLAND', 10),
(130, 'CORUNNA', 56),
(131, 'CORY', 66),
(132, 'CORYDON', 34),
(133, 'COVINGTON', 76),
(134, 'CRAIGVILLE', 88),
(135, 'CRANDALL', 34),
(136, 'CRANE', 16),
(137, 'CRAWFORDSVILLE', 49),
(138, 'CROMWELL', 58),
(139, 'CROSS PLAINS', 48),
(140, 'CROTHERSVILLE', 10),
(141, 'CROWN POINT', 4),
(142, 'CULVER', 38),
(143, 'CUTLER', 25),
(144, 'CYNTHIANA', 75),
(145, 'DALE', 35),
(146, 'DALEVILLE', 18),
(147, 'DANA', 39),
(148, 'DANVILLE', 14),
(149, 'DARLINGTON', 49),
(150, 'DAYTON', 86),
(151, 'DECATUR', 31),
(152, 'DECKER', 60),
(153, 'DEEDSVILLE', 26),
(154, 'DELONG', 2),
(155, 'DELPHI', 25),
(156, 'DEMOTTE', 72),
(157, 'DENVER', 26),
(158, 'DEPAUW', 34),
(159, 'DEPUTY', 20),
(160, 'DERBY', 53),
(161, 'DILLSBORO', 42),
(162, 'DONALDSON', 38),
(163, 'DUBLIN', 8),
(164, 'DUBOIS', 23),
(165, 'DUGGER', 15),
(166, 'DUNKIRK', 13),
(167, 'DUNREITH', 3),
(168, 'DUPONT', 20),
(169, 'DYER', 4),
(170, 'EARL PARK', 77),
(171, 'EAST CHICAGO', 4),
(172, 'EAST ENTERPRISE', 70),
(173, 'EATON', 18),
(174, 'ECKERTY', 52),
(175, 'ECONOMY', 8),
(176, 'EDINBURGH', 41),
(177, 'EDWARDSPORT', 60),
(178, 'ELBERFELD', 74),
(179, 'ELIZABETH', 34),
(180, 'ELIZABETHTOWN', 6),
(181, 'ELKHART', 21),
(182, 'ELLETTSVILLE', 29),
(183, 'ELNORA', 64),
(184, 'ELWOOD', 78),
(185, 'EMINENCE', 47),
(186, 'ENGLISH', 52),
(187, 'ETNA GREEN', 40),
(188, 'EVANSTON', 35),
(189, 'EVANSVILLE', 12),
(190, 'FAIR OAKS', 72),
(191, 'FAIRBANKS', 15),
(192, 'FAIRLAND', 43),
(193, 'FAIRMOUNT', 68),
(194, 'FALMOUTH', 24),
(195, 'FARMERSBURG', 15),
(196, 'FARMLAND', 62),
(197, 'FERDINAND', 23),
(198, 'FILLMORE', 73),
(199, 'FINLY', 9),
(200, 'FISHERS', 54),
(201, 'FLAT ROCK', 43),
(202, 'FLORA', 25),
(203, 'FLORENCE', 70),
(204, 'FLOYDS KNOBS', 5),
(205, 'FOLSOMVILLE', 74),
(206, 'FONTANET', 50),
(207, 'FOREST', 7),
(208, 'FORT BRANCH', 37),
(209, 'FORT RITNER', 84),
(210, 'FORT WAYNE', 61),
(211, 'FORTVILLE', 9),
(212, 'FOUNTAIN CITY', 8),
(213, 'FOUNTAINTOWN', 43),
(214, 'FOWLER', 77),
(215, 'FOWLERTON', 68),
(216, 'FRANCESVILLE', 11),
(217, 'FRANCISCO', 37),
(218, 'FRANKFORT', 7),
(219, 'FRANKLIN', 41),
(220, 'FRANKTON', 78),
(221, 'FREDERICKSBURG', 85),
(222, 'FREEDOM', 92),
(223, 'FREELANDVILLE', 60),
(224, 'FREETOWN', 10),
(225, 'FREMONT', 79),
(226, 'FRENCH LICK', 63),
(227, 'FRIENDSHIP', 48),
(228, 'FULDA', 35),
(229, 'FULTON', 2),
(230, 'GALVESTON', 67),
(231, 'GARRETT', 56),
(232, 'GARY', 4),
(233, 'GAS CITY', 68),
(234, 'GASTON', 18),
(235, 'GENEVA', 31),
(236, 'GENTRYVILLE', 35),
(237, 'GEORGETOWN', 5),
(238, 'GLENWOOD', 69),
(239, 'GOLDSMITH', 83),
(240, 'GOODLAND', 90),
(241, 'GOSHEN', 21),
(242, 'GOSPORT', 92),
(243, 'GRABILL', 61),
(244, 'GRAMMER', 6),
(245, 'GRANDVIEW', 35),
(246, 'GRANGER', 51),
(247, 'GRANTSBURG', 52),
(248, 'GRASS CREEK', 2),
(249, 'GRAYSVILLE', 15),
(250, 'GREENCASTLE', 73),
(251, 'GREENFIELD', 9),
(252, 'GREENS FORK', 8),
(253, 'GREENSBORO', 3),
(254, 'GREENSBURG', 36),
(255, 'GREENTOWN', 81),
(256, 'GREENVILLE', 5),
(257, 'GREENWOOD', 41),
(258, 'GRIFFIN', 75),
(259, 'GRIFFITH', 4),
(260, 'GRISSOM ARB', 26),
(261, 'GROVERTOWN', 59),
(262, 'GUILFORD', 42),
(263, 'GWYNNEVILLE', 43),
(264, 'HAGERSTOWN', 8),
(265, 'HAMILTON', 79),
(266, 'HAMLET', 59),
(267, 'HAMMOND', 4),
(268, 'HANNA', 71),
(269, 'HANOVER', 20),
(270, 'HARDINSBURG', 85),
(271, 'HARLAN', 61),
(272, 'HARMONY', 66),
(273, 'HARRODSBURG', 29),
(274, 'HARTFORD CITY', 22),
(275, 'HARTSVILLE', 6),
(276, 'HATFIELD', 35),
(277, 'HAUBSTADT', 37),
(278, 'HAYDEN', 89),
(279, 'HAZLETON', 37),
(280, 'HEBRON', 33),
(281, 'HELMSBURG', 17),
(282, 'HELTONVILLE', 84),
(283, 'HEMLOCK', 81),
(284, 'HENRYVILLE', 19),
(285, 'HIGHLAND', 4),
(286, 'HILLSBORO', 76),
(287, 'HILLSDALE', 39),
(288, 'HOAGLAND', 61),
(289, 'HOBART', 4),
(290, 'HOBBS', 83),
(291, 'HOLLAND', 23),
(292, 'HOLTON', 48),
(293, 'HOMER', 24),
(294, 'HOPE', 6),
(295, 'HOWE', 45),
(296, 'HUDSON', 79),
(297, 'HUNTERTOWN', 61),
(298, 'HUNTINGBURG', 23),
(299, 'HUNTINGTON', 82),
(300, 'HURON', 84),
(301, 'HYMERA', 15),
(302, 'IDAVILLE', 80),
(304, 'INDIANAPOLIS', 87),
(305, 'INGALLS', 78),
(306, 'INGLEFIELD', 12),
(307, 'IRELAND', 23),
(308, 'JAMESTOWN', 65),
(309, 'JASONVILLE', 91),
(310, 'JASPER', 23),
(311, 'JEFFERSONVILLE', 19),
(312, 'JONESBORO', 68),
(313, 'JONESVILLE', 6),
(314, 'JUDSON', 28),
(315, 'KEMPTON', 83),
(316, 'KENDALLVILLE', 58),
(317, 'KENNARD', 3),
(318, 'KENTLAND', 90),
(319, 'KEWANNA', 2),
(320, 'KEYSTONE', 88),
(321, 'KIMMELL', 58),
(322, 'KINGMAN', 76),
(323, 'KINGSBURY', 71),
(324, 'KINGSFORD HEIGHTS', 71),
(325, 'KIRKLIN', 7),
(326, 'KNIGHTSTOWN', 3),
(327, 'KNIGHTSVILLE', 66),
(328, 'KNOX', 59),
(329, 'KOKOMO', 81),
(330, 'KOLEEN', 91),
(331, 'KOUTS', 33),
(332, 'KURTZ', 10),
(333, 'LA CROSSE', 71),
(334, 'LA FONTAINE', 46),
(335, 'LA PORTE', 71),
(336, 'LACONIA', 34),
(337, 'LADOGA', 49),
(338, 'LAFAYETTE', 86),
(339, 'LAGRANGE', 45),
(340, 'LAGRO', 46),
(341, 'LAKE CICOTT', 67),
(342, 'LAKE STATION', 4),
(343, 'LAKE VILLAGE', 90),
(344, 'LAKETON', 46),
(345, 'LAKEVILLE', 51),
(346, 'LAMAR', 35),
(347, 'LANESVILLE', 34),
(348, 'LAOTTO', 58),
(349, 'LAPAZ', 38),
(350, 'LAPEL', 78),
(351, 'LARWILL', 30),
(352, 'LAUREL', 32),
(353, 'LAWRENCEBURG', 42),
(354, 'LEAVENWORTH', 52),
(355, 'LEBANON', 65),
(356, 'LEESBURG', 40),
(357, 'LEITERS FORD', 2),
(358, 'LEO', 61),
(359, 'LEOPOLD', 53),
(360, 'LEROY', 4),
(361, 'LEWIS', 50),
(362, 'LEWISVILLE', 3),
(363, 'LEXINGTON', 44),
(364, 'LIBERTY', 55),
(365, 'LIBERTY CENTER', 88),
(366, 'LIBERTY MILLS', 46),
(367, 'LIGONIER', 58),
(368, 'LINCOLN CITY', 35),
(369, 'LINDEN', 49),
(370, 'LINTON', 91),
(371, 'LITTLE YORK', 85),
(372, 'LIZTON', 14),
(373, 'LOGANSPORT', 67),
(374, 'LOOGOOTEE', 16),
(375, 'LOSANTVILLE', 62),
(376, 'LOWELL', 4),
(377, 'LUCERNE', 67),
(378, 'LYNN', 62),
(379, 'LYNNVILLE', 74),
(380, 'LYONS', 91),
(381, 'MACKEY', 37),
(382, 'MACY', 26),
(383, 'MADISON', 20),
(384, 'MANILLA', 24),
(385, 'MARENGO', 52),
(386, 'MARIAH HILL', 35),
(387, 'MARION', 68),
(388, 'MARKLE', 88),
(389, 'MARKLEVILLE', 78),
(390, 'MARSHALL', 28),
(391, 'MARTINSVILLE', 47),
(392, 'MARYSVILLE', 19),
(393, 'MATTHEWS', 68),
(394, 'MAUCKPORT', 34),
(395, 'MAXWELL', 9),
(396, 'MAYS', 24),
(397, 'MC CORDSVILLE', 9),
(398, 'MECCA', 28),
(399, 'MEDARYVILLE', 11),
(400, 'MEDORA', 10),
(401, 'MELLOTT', 76),
(402, 'MEMPHIS', 19),
(403, 'MENTONE', 40),
(404, 'MEROM', 15),
(405, 'MERRILLVILLE', 4),
(406, 'METAMORA', 32),
(407, 'MEXICO', 26),
(408, 'MIAMI', 26),
(409, 'MICHIGAN CITY', 71),
(410, 'MICHIGANTOWN', 7),
(411, 'MIDDLEBURY', 21),
(412, 'MIDDLETOWN', 3),
(413, 'MIDLAND', 91),
(414, 'MILAN', 48),
(415, 'MILFORD', 40),
(416, 'MILL CREEK', 71),
(417, 'MILLERSBURG', 21),
(418, 'MILLHOUSEN', 36),
(419, 'MILLTOWN', 52),
(420, 'MILROY', 24),
(421, 'MILTON', 8),
(422, 'MISHAWAKA', 51),
(423, 'MITCHELL', 84),
(424, 'MODOC', 62),
(425, 'MONGO', 45),
(426, 'MONON', 80),
(427, 'MONROE', 31),
(428, 'MONROE CITY', 60),
(429, 'MONROEVILLE', 61),
(430, 'MONROVIA', 47),
(431, 'MONTEREY', 11),
(432, 'MONTEZUMA', 28),
(433, 'MONTGOMERY', 64),
(434, 'MONTICELLO', 80),
(435, 'MONTMORENCI', 86),
(436, 'MONTPELIER', 22),
(437, 'MOORELAND', 3),
(438, 'MOORES HILL', 42),
(439, 'MOORESVILLE', 47),
(440, 'MORGANTOWN', 47),
(441, 'MOROCCO', 90),
(442, 'MORRIS', 48),
(443, 'MORRISTOWN', 43),
(444, 'MOUNT AYR', 90),
(445, 'MOUNT SAINT FRANCIS', 5),
(446, 'MOUNT SUMMIT', 3),
(447, 'MOUNT VERNON', 75),
(448, 'MULBERRY', 7),
(449, 'MUNCIE', 18),
(450, 'MUNSTER', 4),
(451, 'NABB', 19),
(452, 'NAPOLEON', 48),
(453, 'NAPPANEE', 21),
(454, 'NASHVILLE', 17),
(455, 'NEEDHAM', 41),
(456, 'NEW ALBANY', 5),
(457, 'NEW CARLISLE', 51),
(458, 'NEW CASTLE', 3),
(459, 'NEW GOSHEN', 50),
(460, 'NEW HARMONY', 75),
(461, 'NEW HAVEN', 61),
(462, 'NEW LEBANON', 15),
(463, 'NEW LISBON', 3),
(464, 'NEW MARKET', 49),
(465, 'NEW MIDDLETOWN', 34),
(466, 'NEW PALESTINE', 9),
(467, 'NEW PARIS', 21),
(468, 'NEW POINT', 36),
(469, 'NEW RICHMOND', 49),
(470, 'NEW ROSS', 49),
(471, 'NEW SALISBURY', 34),
(472, 'NEW TRENTON', 32),
(473, 'NEW WASHINGTON', 19),
(474, 'NEW WAVERLY', 67),
(475, 'NEWBERRY', 91),
(476, 'NEWBURGH', 74),
(477, 'NEWPORT', 39),
(478, 'NEWTOWN', 76),
(479, 'NINEVEH', 41),
(480, 'NOBLESVILLE', 54),
(481, 'NORMAN', 10),
(482, 'NORTH JUDSON', 59),
(483, 'NORTH LIBERTY', 51),
(484, 'NORTH MANCHESTER', 46),
(485, 'NORTH SALEM', 14),
(486, 'NORTH VERNON', 89),
(487, 'NORTH WEBSTER', 40),
(488, 'NOTRE DAME', 51),
(489, 'OAKFORD', 81),
(490, 'OAKLAND CITY', 37),
(491, 'OAKTOWN', 60),
(492, 'OAKVILLE', 18),
(493, 'ODON', 64),
(494, 'OLDENBURG', 32),
(495, 'ONWARD', 67),
(496, 'OOLITIC', 84),
(497, 'ORA', 59),
(498, 'ORESTES', 78),
(499, 'ORLAND', 79),
(500, 'ORLEANS', 63),
(501, 'OSCEOLA', 51),
(502, 'OSGOOD', 48),
(503, 'OSSIAN', 88),
(504, 'OTISCO', 19),
(505, 'OTTERBEIN', 77),
(506, 'OTWELL', 57),
(507, 'OWENSBURG', 91),
(508, 'OWENSVILLE', 37),
(509, 'OXFORD', 77),
(510, 'PALMYRA', 34),
(511, 'PAOLI', 63),
(512, 'PARAGON', 47),
(513, 'PARIS CROSSING', 89),
(514, 'PARKER CITY', 62),
(515, 'PATOKA', 37),
(516, 'PATRICKSBURG', 92),
(517, 'PATRIOT', 70),
(518, 'PAXTON', 15),
(519, 'PEKIN', 85),
(520, 'PENDLETON', 78),
(521, 'PENNVILLE', 13),
(522, 'PERRYSVILLE', 39),
(523, 'PERSHING', 8),
(524, 'PERU', 26),
(525, 'PETERSBURG', 57),
(526, 'PETROLEUM', 88),
(527, 'PIERCETON', 40),
(528, 'PIERCEVILLE', 48),
(529, 'PIMENTO', 50),
(530, 'PINE VILLAGE', 1),
(531, 'PITTSBORO', 14),
(532, 'PLAINFIELD', 14),
(533, 'PLAINVILLE', 64),
(534, 'PLEASANT LAKE', 79),
(535, 'PLEASANT MILLS', 31),
(536, 'PLYMOUTH', 38),
(537, 'POLAND', 92),
(538, 'PONETO', 88),
(539, 'PORTAGE', 33),
(540, 'PORTLAND', 13),
(541, 'POSEYVILLE', 75),
(542, 'PRAIRIE CREEK', 50),
(543, 'PRAIRIETON', 50),
(544, 'PREBLE', 31),
(545, 'PRINCETON', 37),
(546, 'PUTNAMVILLE', 73),
(547, 'QUINCY', 92),
(548, 'RAGSDALE', 60),
(549, 'RAMSEY', 34),
(550, 'REDKEY', 13),
(551, 'REELSVILLE', 73),
(552, 'REMINGTON', 72),
(553, 'RENSSELAER', 72),
(554, 'REYNOLDS', 80),
(555, 'RICHLAND', 35),
(556, 'RICHMOND', 8),
(557, 'RIDGEVILLE', 62),
(558, 'RILEY', 50),
(559, 'RISING SUN', 27),
(560, 'ROACHDALE', 73),
(561, 'ROANN', 46),
(562, 'ROANOKE', 82),
(563, 'ROCHESTER', 2),
(564, 'ROCKFIELD', 25),
(565, 'ROCKPORT', 35),
(566, 'ROCKVILLE', 28),
(567, 'ROLLING PRAIRIE', 71),
(568, 'ROME', 53),
(569, 'ROME CITY', 58),
(570, 'ROMNEY', 86),
(571, 'ROSEDALE', 28),
(572, 'ROSELAWN', 90),
(573, 'ROSSVILLE', 7),
(574, 'ROYAL CENTER', 67),
(575, 'RUSHVILLE', 24),
(576, 'RUSSELLVILLE', 73),
(577, 'RUSSIAVILLE', 81),
(578, 'SAINT ANTHONY', 23),
(579, 'SAINT BERNICE', 39),
(580, 'SAINT CROIX', 53),
(581, 'SAINT JOE', 56),
(582, 'SAINT JOHN', 4),
(583, 'SAINT MARY OF THE WOODS', 50),
(584, 'SAINT MEINRAD', 35),
(585, 'SAINT PAUL', 36),
(586, 'SALAMONIA', 13),
(587, 'SALEM', 85),
(588, 'SAN PIERRE', 59),
(589, 'SANDBORN', 60),
(590, 'SANTA CLAUS', 35),
(591, 'SARATOGA', 62),
(592, 'SCHERERVILLE', 4),
(593, 'SCHNEIDER', 4),
(594, 'SCHNELLVILLE', 23),
(595, 'SCIPIO', 89),
(596, 'SCOTLAND', 91),
(597, 'SCOTTSBURG', 44),
(598, 'SEDALIA', 7),
(599, 'SEELYVILLE', 50),
(600, 'SELLERSBURG', 19),
(601, 'SELMA', 18),
(602, 'SERVIA', 46),
(603, 'SEYMOUR', 10),
(604, 'SHARPSVILLE', 83),
(605, 'SHELBURN', 15),
(606, 'SHELBY', 4),
(607, 'SHELBYVILLE', 43),
(608, 'SHEPARDSVILLE', 50),
(609, 'SHERIDAN', 54),
(610, 'SHIPSHEWANA', 45),
(611, 'SHIRLEY', 3),
(612, 'SHOALS', 16),
(613, 'SILVER LAKE', 40),
(614, 'SMITHVILLE', 29),
(615, 'SOLSBERRY', 91),
(616, 'SOMERSET', 46),
(617, 'SOMERVILLE', 37),
(618, 'SOUTH BEND', 51),
(619, 'SOUTH MILFORD', 45),
(620, 'SOUTH WHITLEY', 30),
(621, 'SPEEDWAY', 87),
(622, 'SPENCER', 92),
(623, 'SPENCERVILLE', 61),
(624, 'SPICELAND', 3),
(625, 'SPRINGPORT', 3),
(626, 'SPRINGVILLE', 84),
(627, 'SPURGEON', 57),
(628, 'STANFORD', 29),
(629, 'STAR CITY', 11),
(630, 'STATE LINE', 1),
(631, 'STAUNTON', 66),
(632, 'STENDAL', 57),
(633, 'STILESVILLE', 14),
(634, 'STINESVILLE', 29),
(635, 'STOCKWELL', 86),
(636, 'STRAUGHN', 3),
(637, 'STROH', 45),
(638, 'SULLIVAN', 15),
(639, 'SULPHUR', 52),
(640, 'SULPHUR SPRINGS', 3),
(641, 'SUMAVA RESORTS', 90),
(642, 'SUMMITVILLE', 78),
(643, 'SUNMAN', 48),
(644, 'SWAYZEE', 68),
(645, 'SWEETSER', 68),
(646, 'SWITZ CITY', 91),
(647, 'SYRACUSE', 40),
(648, 'TALBOT', 77),
(649, 'TASWELL', 52),
(650, 'TAYLORSVILLE', 6),
(651, 'TEFFT', 72),
(652, 'TELL CITY', 53),
(653, 'TEMPLETON', 77),
(654, 'TENNYSON', 74),
(656, 'TERRE HAUTE', 50),
(657, 'THAYER', 90),
(658, 'THORNTOWN', 65),
(659, 'TIPPECANOE', 38),
(660, 'TIPTON', 83),
(661, 'TOPEKA', 45),
(662, 'TRAFALGAR', 41),
(663, 'TROY', 35),
(664, 'TUNNELTON', 84),
(665, 'TWELVE MILE', 67),
(666, 'TYNER', 38),
(667, 'UNDERWOOD', 44),
(668, 'UNION CITY', 62),
(669, 'UNION MILLS', 71),
(670, 'UNIONDALE', 88),
(671, 'UNIONVILLE', 29),
(672, 'UNIVERSAL', 39),
(673, 'UPLAND', 68),
(674, 'URBANA', 46),
(675, 'VALLONIA', 10),
(676, 'VALPARAISO', 33),
(677, 'VAN BUREN', 68),
(678, 'VEEDERSBURG', 76),
(679, 'VELPEN', 57),
(680, 'VERNON', 89),
(681, 'VERSAILLES', 48),
(682, 'VEVAY', 70),
(683, 'VINCENNES', 60),
(684, 'WABASH', 46),
(685, 'WADESVILLE', 75),
(686, 'WAKARUSA', 21),
(687, 'WALDRON', 43),
(688, 'WALKERTON', 51),
(689, 'WALLACE', 76),
(690, 'WALTON', 67),
(691, 'WANATAH', 71),
(692, 'WARREN', 82),
(693, 'WARSAW', 40),
(694, 'WASHINGTON', 64),
(695, 'WATERLOO', 56),
(696, 'WAVELAND', 49),
(697, 'WAWAKA', 58),
(698, 'WAYNETOWN', 49),
(699, 'WEBSTER', 8),
(700, 'WEST BADEN SPRINGS', 63),
(701, 'WEST COLLEGE CORNER', 55),
(702, 'WEST HARRISON', 42),
(703, 'WEST LAFAYETTE', 86),
(704, 'WEST LEBANON', 1),
(705, 'WEST MIDDLETON', 81),
(706, 'WEST NEWTON', 87),
(707, 'WEST TERRE HAUTE', 50),
(708, 'WESTFIELD', 54),
(709, 'WESTPHALIA', 60),
(710, 'WESTPOINT', 86),
(711, 'WESTPORT', 36),
(712, 'WESTVILLE', 71),
(713, 'WHEATFIELD', 72),
(714, 'WHEATLAND', 60),
(715, 'WHEELER', 33),
(716, 'WHITELAND', 41),
(717, 'WHITESTOWN', 65),
(718, 'WHITING', 4),
(719, 'WILKINSON', 9),
(720, 'WILLIAMS', 84),
(721, 'WILLIAMSBURG', 8),
(722, 'WILLIAMSPORT', 1),
(723, 'WINAMAC', 11),
(724, 'WINCHESTER', 62),
(725, 'WINDFALL', 83),
(726, 'WINGATE', 49),
(727, 'WINONA LAKE', 40),
(728, 'WINSLOW', 57),
(729, 'WOLCOTT', 80),
(730, 'WOLCOTTVILLE', 45),
(731, 'WOLFLAKE', 58),
(732, 'WOODBURN', 61),
(733, 'WORTHINGTON', 91),
(734, 'WYATT', 51),
(735, 'YEOMAN', 25),
(736, 'YODER', 61),
(737, 'YORKTOWN', 18),
(738, 'YOUNG AMERICA', 67),
(739, 'ZANESVILLE', 61),
(740, 'ZIONSVILLE', 65),
(756, 'TEST_CITY', 120),
(758, 'ZOOINSVILLE', 122),
(759, 'LOUIE-VILLE', 123);

-- --------------------------------------------------------

--
-- Table structure for table `COUNTY`
--

CREATE TABLE IF NOT EXISTS `COUNTY` (
  `countyID` int(11) NOT NULL,
  `countyName` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `COUNTY`
--

INSERT INTO `COUNTY` (`countyID`, `countyName`) VALUES
(121, 'AAA'),
(31, 'ADAMS'),
(61, 'ALLEN'),
(6, 'BARTHOLOMEW'),
(77, 'BENTON'),
(22, 'BLACKFORD'),
(65, 'BOONE'),
(17, 'BROWN'),
(25, 'CARROLL'),
(67, 'CASS'),
(19, 'CLARK'),
(66, 'CLAY'),
(7, 'CLINTON'),
(52, 'CRAWFORD'),
(64, 'DAVIESS'),
(56, 'DE KALB'),
(42, 'DEARBORN'),
(36, 'DECATUR'),
(18, 'DELAWARE'),
(23, 'DUBOIS'),
(21, 'ELKHART'),
(69, 'FAYETTE'),
(5, 'FLOYD'),
(76, 'FOUNTAIN'),
(32, 'FRANKLIN'),
(2, 'FULTON'),
(37, 'GIBSON'),
(68, 'GRANT'),
(91, 'GREENE'),
(54, 'HAMILTON'),
(9, 'HANCOCK'),
(34, 'HARRISON'),
(14, 'HENDRICKS'),
(3, 'HENRY'),
(122, 'HOTDOG'),
(81, 'HOWARD'),
(82, 'HUNTINGTON'),
(10, 'JACKSON'),
(72, 'JASPER'),
(13, 'JAY'),
(20, 'JEFFERSON'),
(89, 'JENNINGS'),
(41, 'JOHNSON'),
(60, 'KNOX'),
(40, 'KOSCIUSKO'),
(71, 'LA PORTE'),
(45, 'LAGRANGE'),
(4, 'LAKE'),
(84, 'LAWRENCE'),
(78, 'MADISON'),
(87, 'MARION'),
(38, 'MARSHALL'),
(16, 'MARTIN'),
(26, 'MIAMI'),
(29, 'MONROE'),
(49, 'MONTGOMERY'),
(47, 'MORGAN'),
(90, 'NEWTON'),
(58, 'NOBLE'),
(27, 'OHIO'),
(63, 'ORANGE'),
(92, 'OWEN'),
(28, 'PARKE'),
(53, 'PERRY'),
(123, 'PICKLE'),
(57, 'PIKE'),
(33, 'PORTER'),
(75, 'POSEY'),
(11, 'PULASKI'),
(73, 'PUTNAM'),
(62, 'RANDOLPH'),
(48, 'RIPLEY'),
(24, 'RUSH'),
(44, 'SCOTT'),
(43, 'SHELBY'),
(35, 'SPENCER'),
(51, 'ST JOSEPH'),
(59, 'STARKE'),
(79, 'STEUBEN'),
(15, 'SULLIVAN'),
(70, 'SWITZERLAND'),
(120, 'TEST_COUNTY'),
(86, 'TIPPECANOE'),
(83, 'TIPTON'),
(55, 'UNION'),
(12, 'VANDERBURGH'),
(39, 'VERMILLION'),
(50, 'VIGO'),
(46, 'WABASH'),
(1, 'WARREN'),
(74, 'WARRICK'),
(85, 'WASHINGTON'),
(8, 'WAYNE'),
(88, 'WELLS'),
(80, 'WHITE'),
(30, 'WHITLEY');

-- --------------------------------------------------------

--
-- Table structure for table `GENDER`
--

CREATE TABLE IF NOT EXISTS `GENDER` (
  `genderID` int(11) NOT NULL,
  `genderName` varchar(20) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `GENDER`
--

INSERT INTO `GENDER` (`genderID`, `genderName`) VALUES
(2, 'FEMALE'),
(1, 'MALE'),
(3, 'OTHER'),
(4, 'PREFER NOT TO ANSWER');

-- --------------------------------------------------------

--
-- Table structure for table `GRADE`
--

CREATE TABLE IF NOT EXISTS `GRADE` (
  `gradeID` int(11) NOT NULL,
  `grade` int(11) NOT NULL,
  `active` varchar(1) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `GRADE`
--

INSERT INTO `GRADE` (`gradeID`, `grade`, `active`) VALUES
(2, 2, 'Y'),
(3, 3, 'Y'),
(4, 4, 'Y'),
(5, 5, 'Y'),
(6, 6, 'Y'),
(7, 7, 'Y'),
(10, 8, 'Y'),
(11, 9, 'Y'),
(26, 13, 'Y'),
(38, 10, 'Y'),
(49, 11, 'Y'),
(50, 12, 'Y'),
(51, 1, 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `JUDGE`
--

CREATE TABLE IF NOT EXISTS `JUDGE` (
  `judgeID` int(11) NOT NULL,
  `firstName` varchar(50) CHARACTER SET utf8 NOT NULL,
  `lastName` varchar(50) CHARACTER SET utf8 NOT NULL,
  `middleName` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `title` varchar(70) CHARACTER SET utf8 NOT NULL,
  `highestDegree` varchar(30) CHARACTER SET utf8 NOT NULL,
  `employer` varchar(70) CHARACTER SET utf8 NOT NULL,
  `email` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `password` varchar(150) CHARACTER SET utf8 DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `checkin` varchar(1) CHARACTER SET utf8 DEFAULT 'N'
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `JUDGE`
--

INSERT INTO `JUDGE` (`judgeID`, `firstName`, `lastName`, `middleName`, `title`, `highestDegree`, `employer`, `email`, `password`, `year`, `checkin`) VALUES
(41, 'JACOB', 'HEEBNER', '', 'SOFTWARE DEVELOPER', 'MASTER', 'GOOGLE', 'JACOBHEEBNER@GMAIL.COM', 'password', 2020, 'Y'),
(42, 'JAKE', 'WAGGONER', '', 'JANITOR', 'BACHELOR', 'GOOGLE', 'JAKEWAGGON@GMAIL.COM', 'password', 2020, 'Y'),
(43, 'NOAH', 'PEERSON', '', 'COOK', 'GED', 'GOOGLE', 'NOAHPEARSON@GMAIL.COM', 'password', 2020, 'Y'),
(44, 'JASON', 'HAMPSHIRE', '', 'COACH', 'GED', 'GOOGLE', 'JHEEBNER@IU.EDU', 'password', 2020, 'N'),
(45, 'SPECIAL', 'JUDGE', 'AWARDS', 'SPECIAL AWARDS JUDGE', 'BACHELOR', 'GOOGLE', 'SPECIAL AWARDS JUDGE', 'password', 2020, 'N'),
(46, 'JOHN', 'SMITH', 'JAMES', 'FOOTBALL COACH', 'BACHELOR', 'MICROSOFT', 'noahpeep@gmail.com', 'password', 2020, 'N'),
(47, 'JACOB', 'WAGGONER', 'JAKERS', 'RAPPER', 'BACHELOR', 'MICROSOFT', 'FAKE@FAKE.COM', 'A3MI553NNKGY793', 2020, 'N'),
(48, 'JOHN', 'DOE', 'JAMES', 'SOFTWARE DEVELOPER', 'GED', 'MICROSOFT', 'JCHAMPSH@IU.EDU', 'L1WOV8K6YLRL264', 2020, 'Y');

--
-- Triggers `JUDGE`
--
DELIMITER $$
CREATE TRIGGER `Set_Default_Values` BEFORE INSERT ON `JUDGE`
 FOR EACH ROW set new.year = (select extract(YEAR from CURRENT_TIMESTAMP))
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `JUDGE_CATEGORY_PREFERENCE`
--

CREATE TABLE IF NOT EXISTS `JUDGE_CATEGORY_PREFERENCE` (
  `judgeID` int(11) NOT NULL,
  `categoryID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `JUDGE_CATEGORY_PREFERENCE`
--

INSERT INTO `JUDGE_CATEGORY_PREFERENCE` (`judgeID`, `categoryID`) VALUES
(41, 1),
(41, 6),
(41, 7),
(42, 1),
(42, 2),
(42, 25),
(43, 1),
(43, 2),
(43, 25),
(44, 2),
(44, 24),
(44, 25),
(45, 6),
(45, 7),
(46, 1),
(46, 6),
(46, 7),
(47, 6),
(47, 24),
(47, 28),
(48, 2),
(48, 25);

-- --------------------------------------------------------

--
-- Table structure for table `JUDGE_GRADE_PREFERENCE`
--

CREATE TABLE IF NOT EXISTS `JUDGE_GRADE_PREFERENCE` (
  `judgeID` int(11) NOT NULL,
  `projectGradeID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `JUDGE_GRADE_PREFERENCE`
--

INSERT INTO `JUDGE_GRADE_PREFERENCE` (`judgeID`, `projectGradeID`) VALUES
(41, 5),
(41, 21),
(41, 22),
(42, 1),
(42, 4),
(42, 21),
(43, 4),
(43, 5),
(43, 22),
(44, 1),
(44, 4),
(44, 21),
(45, 4),
(46, 4),
(46, 5),
(46, 22),
(47, 21),
(48, 1),
(48, 4),
(48, 5);

-- --------------------------------------------------------

--
-- Table structure for table `PROJECT`
--

CREATE TABLE IF NOT EXISTS `PROJECT` (
  `projectID` int(11) NOT NULL,
  `projectNumber` int(11) NOT NULL,
  `title` varchar(150) CHARACTER SET utf8 NOT NULL,
  `abstract` varchar(1000) CHARACTER SET utf8 NOT NULL,
  `projectGradeID` int(11) NOT NULL,
  `categoryID` int(11) NOT NULL,
  `boothID` int(11) NOT NULL,
  `cnID` varchar(70) CHARACTER SET utf8 NOT NULL,
  `rank` int(11) DEFAULT '-1'
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `PROJECT`
--

INSERT INTO `PROJECT` (`projectID`, `projectNumber`, `title`, `abstract`, `projectGradeID`, `categoryID`, `boothID`, `cnID`, `rank`) VALUES
(34, 1, 'HOW DOES DOG FOOD TASTE?', 'WE WANT TO FIND OUT HOW GOOD DOG FOOD TASTES', 21, 2, 66, 'N100', 7),
(36, 2, 'HOW DOES CAT FOOD TASTE?', 'WE WANT TO FIND OUT HOW GOOD CAT FOOD TASTES', 5, 7, 2, 'N101', 5),
(38, 3, 'HOW DOES FISH FOOD TASTE?', 'WE WANT TO FIND OUT HOW GOOD FISH FOOD TASTES', 4, 7, 1, 'N102', 6),
(39, 4, 'WATER IN A CUP', 'WE WANT TO FIND OUT HOW MUCH WATER IS IN 1 CUP OF WATER.', 1, 1, 80, 'N104', 4),
(40, 5, 'BIG VOLCANO', 'HOW BIG CAN OUR VOLCANO BE?', 22, 25, 52, 'N105', 6),
(41, 6, 'FLAT EARTH', 'PROVING OUR EARTH IS FLAT.', 21, 2, 56, 'N106', 3),
(42, 7, 'IS THE MOON MADE OF CHEESE?', 'NOPE', 22, 24, 60, 'N107', 4),
(43, 8, 'LOW EFFORT PROJECT', 'TRUE', 21, 6, 81, 'N108', 5),
(44, 9, 'HOW MANY BEERS TO GET DRUNK?', 'MANY', 22, 7, 82, 'N109', 3),
(45, 10, 'RUNNING OUT OF IDEAS', 'GIVE ME MORE PROJECT IDEAS.', 1, 25, 83, 'N110', 6),
(46, 11, 'HOW MANY LICKS?', 'TO GET TO THE CENTER OF THE EARTH.', 5, 6, 86, 'N111', 6),
(47, 12, 'FOUR MORE TO GO!', '4 MORE PROJECTS!', 4, 1, 87, 'N112', 6),
(48, 13, 'EXPLODING SAMSUNG BATTERY', 'HOW HOT DOES YOUR PHONE HAVE TO GET TO EXPLODE?', 22, 2, 89, 'N113', 6),
(49, 14, 'PROJECT 14', 'OOF', 21, 24, 90, 'N114', 4),
(50, 15, 'PROJECT 15', 'OOF', 21, 25, 91, 'N115', 4),
(51, 16, 'THE LAST MELON', 'ICE AGE', 1, 2, 92, 'N116', 5),
(52, 17, 'RACECARS OFF A CLIFF', 'HOW FAST CAN THEY REACH THE BOTTOM?', 23, 2, 95, '4206969', 6),
(53, 33, 'EFFECTS OF ACNE ON TURTLES', 'I LIKE TOURTLES', 1, 6, 96, '1155665', 6),
(54, 18, 'HOW LONG CAN FISH STAY AWAKE', '"FISH CAN''T STAY AWAKE FOREVER', 23, 6, 99, '3456789', 4),
(55, 19, 'IS SPACE INFINITE', 'IS THERE REALLY AN END TO SPACE?', 23, 2, 100, '1234567', 5);

-- --------------------------------------------------------

--
-- Table structure for table `PROJECT_GRADE_LEVEL`
--

CREATE TABLE IF NOT EXISTS `PROJECT_GRADE_LEVEL` (
  `projectGradeID` int(11) NOT NULL,
  `levelName` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT 'This holds values like "Elementary", "Junior High", "Freshman" etc.',
  `active` varchar(1) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `PROJECT_GRADE_LEVEL`
--

INSERT INTO `PROJECT_GRADE_LEVEL` (`projectGradeID`, `levelName`, `active`) VALUES
(1, 'Sophomore', 'Y'),
(4, 'Senior', 'Y'),
(5, 'Junior', 'Y'),
(21, 'Freshman', 'Y'),
(22, 'Junior High', 'Y'),
(23, 'Elementary', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `SCHOOL`
--

CREATE TABLE IF NOT EXISTS `SCHOOL` (
  `schoolID` int(11) NOT NULL,
  `countyID` int(11) NOT NULL,
  `schoolName` varchar(70) CHARACTER SET utf8 NOT NULL,
  `cityID` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `SCHOOL`
--

INSERT INTO `SCHOOL` (`schoolID`, `countyID`, `schoolName`, `cityID`) VALUES
(1, 14, 'BROWNSBURG HIGH SCHOOL', 69),
(24, 81, 'WESTFIELD ELEMENTARY', 220),
(22, 120, 'INDIANA HIGH SCHOOL', 756),
(27, 120, 'TEST SCHOOL', 756),
(25, 122, 'INDIANA HIGH SCHOOL', 758),
(26, 123, 'GREENSBURG INTERMEDIATE', 759);

-- --------------------------------------------------------

--
-- Table structure for table `SESSION`
--

CREATE TABLE IF NOT EXISTS `SESSION` (
  `sessionID` int(11) NOT NULL,
  `startTime` time NOT NULL,
  `endTime` time NOT NULL,
  `active` varchar(1) CHARACTER SET utf8 NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `SESSION`
--

INSERT INTO `SESSION` (`sessionID`, `startTime`, `endTime`, `active`, `name`) VALUES
(1, '09:00:00', '09:15:00', 'Y', 'Session1'),
(2, '09:15:00', '09:30:00', 'Y', 'Session2'),
(3, '09:30:00', '09:45:00', 'Y', 'Session3'),
(4, '09:45:00', '10:00:00', 'Y', 'Session4'),
(5, '10:15:00', '10:30:00', 'Y', 'Session5'),
(6, '10:30:00', '10:45:00', 'Y', 'Session6'),
(7, '10:45:00', '11:00:00', 'Y', 'Session7'),
(8, '11:00:00', '11:15:00', 'Y', 'Session8'),
(9, '11:15:00', '11:30:00', 'Y', 'Session9'),
(10, '11:30:00', '11:45:00', 'Y', 'Session10'),
(13, '23:22:00', '18:27:00', 'Y', 'SESSION 11');

-- --------------------------------------------------------

--
-- Table structure for table `STUDENT`
--

CREATE TABLE IF NOT EXISTS `STUDENT` (
  `studentID` int(11) NOT NULL,
  `firstName` varchar(50) CHARACTER SET utf8 NOT NULL,
  `lastName` varchar(50) CHARACTER SET utf8 NOT NULL,
  `middleName` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `gradeID` int(11) NOT NULL,
  `genderID` int(11) NOT NULL DEFAULT '4',
  `schoolID` int(11) NOT NULL,
  `countyID` int(11) NOT NULL,
  `cityID` int(11) NOT NULL,
  `projectID` int(11) NOT NULL,
  `year` year(4) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `STUDENT`
--

INSERT INTO `STUDENT` (`studentID`, `firstName`, `lastName`, `middleName`, `gradeID`, `genderID`, `schoolID`, `countyID`, `cityID`, `projectID`, `year`) VALUES
(35, 'JOHN', 'DOE', 'JAMES', 49, 1, 22, 120, 756, 38, 2020),
(36, 'JANE', 'DANE', '', 49, 1, 22, 120, 756, 38, 2020),
(38, 'BARON', 'PETER', 'FINN', 2, 1, 24, 81, 220, 52, 2020),
(39, 'BARLOW', 'RYAN', NULL, 38, 2, 22, 122, 758, 53, 2020),
(40, 'ANDRE', 'HEEB', 'GEORGE', 5, 1, 26, 123, 759, 52, 2020),
(41, 'CHRIS', 'SMITH', 'HARRY', 3, 1, 24, 81, 220, 54, 2020),
(42, 'ALICE', 'WARSAW', NULL, 51, 2, 24, 81, 220, 54, 2020),
(43, 'PIILO', 'DOWNING', 'QUINTON', 4, 1, 26, 123, 759, 55, 2020);

--
-- Triggers `STUDENT`
--
DELIMITER $$
CREATE TRIGGER `Extract_Year_Student` BEFORE INSERT ON `STUDENT`
 FOR EACH ROW set new.year = (select extract(YEAR from CURRENT_TIMESTAMP))
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_ADMINISTRATOR`
--
CREATE TABLE IF NOT EXISTS `v_ADMINISTRATOR` (
`adminID` int(11)
,`firstName` varchar(50)
,`lastName` varchar(50)
,`middleName` varchar(50)
,`email` varchar(70)
,`level` varchar(25)
,`active` varchar(1)
,`levelID` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_ASSIGNMENT`
--
CREATE TABLE IF NOT EXISTS `v_ASSIGNMENT` (
`assignmentID` int(11)
,`firstName` varchar(50)
,`middleName` varchar(50)
,`lastName` varchar(50)
,`email` varchar(50)
,`name` varchar(50)
,`startTime` time
,`endTime` time
,`boothNumber` int(11)
,`projectNumber` int(11)
,`title` varchar(150)
,`score` int(11)
,`sessionID` int(11)
,`judgeID` int(11)
,`projectID` int(11)
,`boothID` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_CITY`
--
CREATE TABLE IF NOT EXISTS `v_CITY` (
`cityID` int(11)
,`cityName` varchar(50)
,`CountyName` varchar(50)
,`countyID` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_JUDGE`
--
CREATE TABLE IF NOT EXISTS `v_JUDGE` (
`judgeID` int(11)
,`firstName` varchar(50)
,`lastName` varchar(50)
,`email` varchar(50)
,`password` varchar(150)
,`title` varchar(70)
,`employer` varchar(70)
,`highestDegree` varchar(30)
,`checkin` varchar(1)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_JUDGE_CATEGORY_PREFERENCE`
--
CREATE TABLE IF NOT EXISTS `v_JUDGE_CATEGORY_PREFERENCE` (
`firstName` varchar(50)
,`middleName` varchar(50)
,`lastName` varchar(50)
,`email` varchar(50)
,`categoryName` varchar(50)
,`judgeID` int(11)
,`categoryID` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_JUDGE_GRADE_PREFERENCE`
--
CREATE TABLE IF NOT EXISTS `v_JUDGE_GRADE_PREFERENCE` (
`firstName` varchar(50)
,`middleName` varchar(50)
,`lastName` varchar(50)
,`email` varchar(50)
,`levelName` varchar(30)
,`judgeID` int(11)
,`projectGradeID` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_PROJECT`
--
CREATE TABLE IF NOT EXISTS `v_PROJECT` (
`projectID` int(11)
,`projectNumber` int(11)
,`title` varchar(150)
,`abstract` varchar(1000)
,`levelName` varchar(30)
,`categoryName` varchar(50)
,`boothNumber` int(11)
,`cnID` varchar(70)
,`rank` int(11)
,`projectGradeID` int(11)
,`categoryID` int(11)
,`boothID` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_SCHOOL`
--
CREATE TABLE IF NOT EXISTS `v_SCHOOL` (
`schoolID` int(11)
,`schoolName` varchar(70)
,`cityName` varchar(50)
,`countyName` varchar(50)
,`cityID` int(11)
,`countyID` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_STUDENT`
--
CREATE TABLE IF NOT EXISTS `v_STUDENT` (
`studentID` int(11)
,`firstName` varchar(50)
,`lastName` varchar(50)
,`middleName` varchar(50)
,`grade` int(11)
,`genderName` varchar(20)
,`countyName` varchar(50)
,`cityName` varchar(50)
,`schoolName` varchar(70)
,`projectNumber` int(11)
,`gradeID` int(11)
,`genderID` int(11)
,`countyID` int(11)
,`cityID` int(11)
,`schoolID` int(11)
,`projectID` int(11)
);

-- --------------------------------------------------------

--
-- Structure for view `v_ADMINISTRATOR`
--
DROP TABLE IF EXISTS `v_ADMINISTRATOR`;

CREATE ALGORITHM=UNDEFINED DEFINER=`jwaggon`@`localhost` SQL SECURITY DEFINER VIEW `v_ADMINISTRATOR` AS select `A`.`adminID` AS `adminID`,`A`.`firstName` AS `firstName`,`A`.`lastName` AS `lastName`,`A`.`middleName` AS `middleName`,`A`.`email` AS `email`,`AL`.`level` AS `level`,`A`.`active` AS `active`,`AL`.`levelID` AS `levelID` from (`ADMINISTRATOR` `A` join `ADMIN_LEVEL` `AL`) where (`A`.`levelID` = `AL`.`levelID`);

-- --------------------------------------------------------

--
-- Structure for view `v_ASSIGNMENT`
--
DROP TABLE IF EXISTS `v_ASSIGNMENT`;

CREATE ALGORITHM=UNDEFINED DEFINER=`jwaggon`@`localhost` SQL SECURITY DEFINER VIEW `v_ASSIGNMENT` AS select `ASSIGNMENT`.`assignmentID` AS `assignmentID`,`JUDGE`.`firstName` AS `firstName`,`JUDGE`.`middleName` AS `middleName`,`JUDGE`.`lastName` AS `lastName`,`JUDGE`.`email` AS `email`,`SESSION`.`name` AS `name`,`SESSION`.`startTime` AS `startTime`,`SESSION`.`endTime` AS `endTime`,`BOOTH_NUMBER`.`boothNumber` AS `boothNumber`,`PROJECT`.`projectNumber` AS `projectNumber`,`PROJECT`.`title` AS `title`,`ASSIGNMENT`.`score` AS `score`,`ASSIGNMENT`.`sessionID` AS `sessionID`,`ASSIGNMENT`.`judgeID` AS `judgeID`,`ASSIGNMENT`.`projectID` AS `projectID`,`BOOTH_NUMBER`.`boothID` AS `boothID` from ((((`ASSIGNMENT` join `JUDGE`) join `SESSION`) join `BOOTH_NUMBER`) join `PROJECT`) where ((`ASSIGNMENT`.`sessionID` = `SESSION`.`sessionID`) and (`ASSIGNMENT`.`judgeID` = `JUDGE`.`judgeID`) and (`ASSIGNMENT`.`projectID` = `PROJECT`.`projectID`) and (`BOOTH_NUMBER`.`boothID` = `PROJECT`.`boothID`));

-- --------------------------------------------------------

--
-- Structure for view `v_CITY`
--
DROP TABLE IF EXISTS `v_CITY`;

CREATE ALGORITHM=UNDEFINED DEFINER=`jwaggon`@`localhost` SQL SECURITY DEFINER VIEW `v_CITY` AS select `CITY`.`cityID` AS `cityID`,`CITY`.`cityName` AS `cityName`,`COUNTY`.`countyName` AS `CountyName`,`COUNTY`.`countyID` AS `countyID` from (`CITY` join `COUNTY`) where (`CITY`.`countyID` = `COUNTY`.`countyID`);

-- --------------------------------------------------------

--
-- Structure for view `v_JUDGE`
--
DROP TABLE IF EXISTS `v_JUDGE`;

CREATE ALGORITHM=UNDEFINED DEFINER=`jwaggon`@`localhost` SQL SECURITY DEFINER VIEW `v_JUDGE` AS select `JUDGE`.`judgeID` AS `judgeID`,`JUDGE`.`firstName` AS `firstName`,`JUDGE`.`lastName` AS `lastName`,`JUDGE`.`email` AS `email`,`JUDGE`.`password` AS `password`,`JUDGE`.`title` AS `title`,`JUDGE`.`employer` AS `employer`,`JUDGE`.`highestDegree` AS `highestDegree`,`JUDGE`.`checkin` AS `checkin` from `JUDGE`;

-- --------------------------------------------------------

--
-- Structure for view `v_JUDGE_CATEGORY_PREFERENCE`
--
DROP TABLE IF EXISTS `v_JUDGE_CATEGORY_PREFERENCE`;

CREATE ALGORITHM=UNDEFINED DEFINER=`jwaggon`@`localhost` SQL SECURITY DEFINER VIEW `v_JUDGE_CATEGORY_PREFERENCE` AS select `JUDGE`.`firstName` AS `firstName`,`JUDGE`.`middleName` AS `middleName`,`JUDGE`.`lastName` AS `lastName`,`JUDGE`.`email` AS `email`,`CATEGORY`.`categoryName` AS `categoryName`,`JUDGE_CATEGORY_PREFERENCE`.`judgeID` AS `judgeID`,`JUDGE_CATEGORY_PREFERENCE`.`categoryID` AS `categoryID` from ((`JUDGE_CATEGORY_PREFERENCE` join `JUDGE`) join `CATEGORY`) where ((`JUDGE_CATEGORY_PREFERENCE`.`judgeID` = `JUDGE`.`judgeID`) and (`JUDGE_CATEGORY_PREFERENCE`.`categoryID` = `CATEGORY`.`categoryID`)) order by `JUDGE_CATEGORY_PREFERENCE`.`judgeID`;

-- --------------------------------------------------------

--
-- Structure for view `v_JUDGE_GRADE_PREFERENCE`
--
DROP TABLE IF EXISTS `v_JUDGE_GRADE_PREFERENCE`;

CREATE ALGORITHM=UNDEFINED DEFINER=`jwaggon`@`localhost` SQL SECURITY DEFINER VIEW `v_JUDGE_GRADE_PREFERENCE` AS select `JUDGE`.`firstName` AS `firstName`,`JUDGE`.`middleName` AS `middleName`,`JUDGE`.`lastName` AS `lastName`,`JUDGE`.`email` AS `email`,`PROJECT_GRADE_LEVEL`.`levelName` AS `levelName`,`JUDGE_GRADE_PREFERENCE`.`judgeID` AS `judgeID`,`JUDGE_GRADE_PREFERENCE`.`projectGradeID` AS `projectGradeID` from ((`JUDGE_GRADE_PREFERENCE` join `JUDGE`) join `PROJECT_GRADE_LEVEL`) where ((`JUDGE_GRADE_PREFERENCE`.`judgeID` = `JUDGE`.`judgeID`) and (`JUDGE_GRADE_PREFERENCE`.`projectGradeID` = `PROJECT_GRADE_LEVEL`.`projectGradeID`)) order by `JUDGE_GRADE_PREFERENCE`.`judgeID`;

-- --------------------------------------------------------

--
-- Structure for view `v_PROJECT`
--
DROP TABLE IF EXISTS `v_PROJECT`;

CREATE ALGORITHM=UNDEFINED DEFINER=`jwaggon`@`localhost` SQL SECURITY DEFINER VIEW `v_PROJECT` AS select `PROJECT`.`projectID` AS `projectID`,`PROJECT`.`projectNumber` AS `projectNumber`,`PROJECT`.`title` AS `title`,`PROJECT`.`abstract` AS `abstract`,`PROJECT_GRADE_LEVEL`.`levelName` AS `levelName`,`CATEGORY`.`categoryName` AS `categoryName`,`BOOTH_NUMBER`.`boothNumber` AS `boothNumber`,`PROJECT`.`cnID` AS `cnID`,`PROJECT`.`rank` AS `rank`,`PROJECT_GRADE_LEVEL`.`projectGradeID` AS `projectGradeID`,`CATEGORY`.`categoryID` AS `categoryID`,`BOOTH_NUMBER`.`boothID` AS `boothID` from (((`PROJECT` join `PROJECT_GRADE_LEVEL`) join `CATEGORY`) join `BOOTH_NUMBER`) where ((`PROJECT`.`projectGradeID` = `PROJECT_GRADE_LEVEL`.`projectGradeID`) and (`PROJECT`.`categoryID` = `CATEGORY`.`categoryID`) and (`PROJECT`.`boothID` = `BOOTH_NUMBER`.`boothID`));

-- --------------------------------------------------------

--
-- Structure for view `v_SCHOOL`
--
DROP TABLE IF EXISTS `v_SCHOOL`;

CREATE ALGORITHM=UNDEFINED DEFINER=`jwaggon`@`localhost` SQL SECURITY DEFINER VIEW `v_SCHOOL` AS select `SCHOOL`.`schoolID` AS `schoolID`,`SCHOOL`.`schoolName` AS `schoolName`,`CITY`.`cityName` AS `cityName`,`COUNTY`.`countyName` AS `countyName`,`CITY`.`cityID` AS `cityID`,`COUNTY`.`countyID` AS `countyID` from ((`SCHOOL` join `COUNTY`) join `CITY`) where ((`SCHOOL`.`countyID` = `COUNTY`.`countyID`) and (`SCHOOL`.`cityID` = `CITY`.`cityID`));

-- --------------------------------------------------------

--
-- Structure for view `v_STUDENT`
--
DROP TABLE IF EXISTS `v_STUDENT`;

CREATE ALGORITHM=UNDEFINED DEFINER=`jwaggon`@`localhost` SQL SECURITY DEFINER VIEW `v_STUDENT` AS select `STUDENT`.`studentID` AS `studentID`,`STUDENT`.`firstName` AS `firstName`,`STUDENT`.`lastName` AS `lastName`,`STUDENT`.`middleName` AS `middleName`,`GRADE`.`grade` AS `grade`,`GENDER`.`genderName` AS `genderName`,`COUNTY`.`countyName` AS `countyName`,`CITY`.`cityName` AS `cityName`,`SCHOOL`.`schoolName` AS `schoolName`,`PROJECT`.`projectNumber` AS `projectNumber`,`GRADE`.`gradeID` AS `gradeID`,`GENDER`.`genderID` AS `genderID`,`COUNTY`.`countyID` AS `countyID`,`CITY`.`cityID` AS `cityID`,`SCHOOL`.`schoolID` AS `schoolID`,`PROJECT`.`projectID` AS `projectID` from ((((((`STUDENT` join `GRADE`) join `GENDER`) join `SCHOOL`) join `COUNTY`) join `CITY`) join `PROJECT`) where ((`STUDENT`.`gradeID` = `GRADE`.`gradeID`) and (`STUDENT`.`genderID` = `GENDER`.`genderID`) and (`STUDENT`.`schoolID` = `SCHOOL`.`schoolID`) and (`STUDENT`.`countyID` = `COUNTY`.`countyID`) and (`STUDENT`.`cityID` = `CITY`.`cityID`) and (`STUDENT`.`projectID` = `PROJECT`.`projectID`));

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ADMINISTRATOR`
--
ALTER TABLE `ADMINISTRATOR`
  ADD PRIMARY KEY (`adminID`),
  ADD UNIQUE KEY `U_ADMIN-EMAIL` (`email`) USING BTREE,
  ADD KEY `I_ADMIN_ADMINLEVEL` (`levelID`);

--
-- Indexes for table `ADMIN_LEVEL`
--
ALTER TABLE `ADMIN_LEVEL`
  ADD PRIMARY KEY (`levelID`),
  ADD UNIQUE KEY `U_ADMINLEVEL` (`level`);

--
-- Indexes for table `ASSIGNMENT`
--
ALTER TABLE `ASSIGNMENT`
  ADD PRIMARY KEY (`assignmentID`),
  ADD KEY `I_ASSIGNMENT_SESSION` (`sessionID`) USING BTREE,
  ADD KEY `I_ASSIGNMENT_JUDGE` (`judgeID`) USING BTREE,
  ADD KEY `I_ASSIGNMENT_PROJECT` (`projectID`);

--
-- Indexes for table `BOOTH_NUMBER`
--
ALTER TABLE `BOOTH_NUMBER`
  ADD PRIMARY KEY (`boothID`),
  ADD UNIQUE KEY `U_BOOTH-NUMBER` (`boothNumber`) USING BTREE;

--
-- Indexes for table `CATEGORY`
--
ALTER TABLE `CATEGORY`
  ADD PRIMARY KEY (`categoryID`),
  ADD UNIQUE KEY `U_CATEGORY-NAME` (`categoryName`) USING BTREE;

--
-- Indexes for table `CITY`
--
ALTER TABLE `CITY`
  ADD PRIMARY KEY (`cityID`),
  ADD UNIQUE KEY `U_CITY-NAME` (`cityName`) USING BTREE,
  ADD KEY `I_CITY_COUNTY` (`countyID`);

--
-- Indexes for table `COUNTY`
--
ALTER TABLE `COUNTY`
  ADD PRIMARY KEY (`countyID`),
  ADD UNIQUE KEY `U_COUNTY-NAME` (`countyName`) USING BTREE;

--
-- Indexes for table `GENDER`
--
ALTER TABLE `GENDER`
  ADD PRIMARY KEY (`genderID`),
  ADD UNIQUE KEY `U_genderName` (`genderName`);

--
-- Indexes for table `GRADE`
--
ALTER TABLE `GRADE`
  ADD PRIMARY KEY (`gradeID`),
  ADD UNIQUE KEY `U_GRADE` (`grade`);

--
-- Indexes for table `JUDGE`
--
ALTER TABLE `JUDGE`
  ADD PRIMARY KEY (`judgeID`),
  ADD UNIQUE KEY `U_EMAIL` (`email`) USING BTREE;

--
-- Indexes for table `JUDGE_CATEGORY_PREFERENCE`
--
ALTER TABLE `JUDGE_CATEGORY_PREFERENCE`
  ADD PRIMARY KEY (`judgeID`,`categoryID`),
  ADD UNIQUE KEY `U_JUDGE-CATEGORY` (`categoryID`,`judgeID`) USING BTREE,
  ADD KEY `I_JUDGECATEGORYPREF_JUDGE` (`judgeID`) USING BTREE,
  ADD KEY `I_JUDGECATEGORYPREF_CATEGORY` (`categoryID`) USING BTREE;

--
-- Indexes for table `JUDGE_GRADE_PREFERENCE`
--
ALTER TABLE `JUDGE_GRADE_PREFERENCE`
  ADD PRIMARY KEY (`judgeID`,`projectGradeID`),
  ADD UNIQUE KEY `U_JUDGE-GRADE` (`judgeID`,`projectGradeID`) USING BTREE,
  ADD KEY `I_JUDGEGRADEPREF_JUDGE` (`judgeID`) USING BTREE,
  ADD KEY `I_JUDGEGRADEPREF_GRADE` (`projectGradeID`) USING BTREE;

--
-- Indexes for table `PROJECT`
--
ALTER TABLE `PROJECT`
  ADD PRIMARY KEY (`projectID`),
  ADD UNIQUE KEY `U_CNID` (`cnID`),
  ADD UNIQUE KEY `U_PROJECT-NUMBER` (`projectNumber`) USING BTREE,
  ADD UNIQUE KEY `U_BOOTH` (`boothID`) USING BTREE,
  ADD KEY `I_PROJECT_PROJECTGRADE` (`projectGradeID`),
  ADD KEY `I_PROJECT_CATEGORY` (`categoryID`),
  ADD KEY `I_PROJECT_BOOTH` (`boothID`);

--
-- Indexes for table `PROJECT_GRADE_LEVEL`
--
ALTER TABLE `PROJECT_GRADE_LEVEL`
  ADD PRIMARY KEY (`projectGradeID`),
  ADD UNIQUE KEY `U_GRADE-LEVEL-NAME` (`levelName`) USING BTREE;

--
-- Indexes for table `SCHOOL`
--
ALTER TABLE `SCHOOL`
  ADD PRIMARY KEY (`schoolID`),
  ADD UNIQUE KEY `U_COUNTY-SCHOOL-CITY` (`countyID`,`schoolName`,`cityID`) USING BTREE,
  ADD KEY `I_SCHOOL_COUNTY` (`countyID`),
  ADD KEY `I_SCHOOL_CITY` (`cityID`);

--
-- Indexes for table `SESSION`
--
ALTER TABLE `SESSION`
  ADD PRIMARY KEY (`sessionID`),
  ADD UNIQUE KEY `U_SESSION-TIME` (`startTime`,`endTime`) USING BTREE;

--
-- Indexes for table `STUDENT`
--
ALTER TABLE `STUDENT`
  ADD PRIMARY KEY (`studentID`),
  ADD KEY `I_STUDENT_PROJECT` (`projectID`) USING BTREE,
  ADD KEY `I_STUDENT_GRADE` (`gradeID`) USING BTREE,
  ADD KEY `I_STUDENT_GENDER` (`genderID`) USING BTREE,
  ADD KEY `I_STUDENT_COUNTY` (`countyID`) USING BTREE,
  ADD KEY `I_STUDENT_SCHOOL` (`schoolID`) USING BTREE,
  ADD KEY `I_STUDENT_CITY` (`cityID`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ADMINISTRATOR`
--
ALTER TABLE `ADMINISTRATOR`
  MODIFY `adminID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `ADMIN_LEVEL`
--
ALTER TABLE `ADMIN_LEVEL`
  MODIFY `levelID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ASSIGNMENT`
--
ALTER TABLE `ASSIGNMENT`
  MODIFY `assignmentID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=670;
--
-- AUTO_INCREMENT for table `BOOTH_NUMBER`
--
ALTER TABLE `BOOTH_NUMBER`
  MODIFY `boothID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=102;
--
-- AUTO_INCREMENT for table `CATEGORY`
--
ALTER TABLE `CATEGORY`
  MODIFY `categoryID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `CITY`
--
ALTER TABLE `CITY`
  MODIFY `cityID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=761;
--
-- AUTO_INCREMENT for table `COUNTY`
--
ALTER TABLE `COUNTY`
  MODIFY `countyID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=126;
--
-- AUTO_INCREMENT for table `GENDER`
--
ALTER TABLE `GENDER`
  MODIFY `genderID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `GRADE`
--
ALTER TABLE `GRADE`
  MODIFY `gradeID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT for table `JUDGE`
--
ALTER TABLE `JUDGE`
  MODIFY `judgeID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=49;
--
-- AUTO_INCREMENT for table `PROJECT`
--
ALTER TABLE `PROJECT`
  MODIFY `projectID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=56;
--
-- AUTO_INCREMENT for table `PROJECT_GRADE_LEVEL`
--
ALTER TABLE `PROJECT_GRADE_LEVEL`
  MODIFY `projectGradeID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `SCHOOL`
--
ALTER TABLE `SCHOOL`
  MODIFY `schoolID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `SESSION`
--
ALTER TABLE `SESSION`
  MODIFY `sessionID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `STUDENT`
--
ALTER TABLE `STUDENT`
  MODIFY `studentID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=44;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `ADMINISTRATOR`
--
ALTER TABLE `ADMINISTRATOR`
  ADD CONSTRAINT `ADMINISTRATOR_ibfk_1` FOREIGN KEY (`levelID`) REFERENCES `ADMIN_LEVEL` (`levelID`) ON UPDATE CASCADE;

--
-- Constraints for table `ASSIGNMENT`
--
ALTER TABLE `ASSIGNMENT`
  ADD CONSTRAINT `ASSIGNMENT_ibfk_1` FOREIGN KEY (`sessionID`) REFERENCES `SESSION` (`sessionID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ASSIGNMENT_ibfk_2` FOREIGN KEY (`judgeID`) REFERENCES `JUDGE` (`judgeID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ASSIGNMENT_ibfk_3` FOREIGN KEY (`projectID`) REFERENCES `PROJECT` (`projectID`) ON UPDATE CASCADE;

--
-- Constraints for table `CITY`
--
ALTER TABLE `CITY`
  ADD CONSTRAINT `CITY_ibfk_1` FOREIGN KEY (`countyID`) REFERENCES `COUNTY` (`countyID`) ON UPDATE CASCADE;

--
-- Constraints for table `JUDGE_CATEGORY_PREFERENCE`
--
ALTER TABLE `JUDGE_CATEGORY_PREFERENCE`
  ADD CONSTRAINT `JUDGE_CATEGORY_PREFERENCE_ibfk_2` FOREIGN KEY (`categoryID`) REFERENCES `CATEGORY` (`categoryID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `JUDGE_CATEGORY_PREFERENCE_ibfk_1` FOREIGN KEY (`judgeID`) REFERENCES `JUDGE` (`judgeID`) ON UPDATE CASCADE;

--
-- Constraints for table `JUDGE_GRADE_PREFERENCE`
--
ALTER TABLE `JUDGE_GRADE_PREFERENCE`
  ADD CONSTRAINT `JUDGE_GRADE_PREFERENCE_ibfk_2` FOREIGN KEY (`projectGradeID`) REFERENCES `PROJECT_GRADE_LEVEL` (`projectGradeID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `JUDGE_GRADE_PREFERENCE_ibfk_1` FOREIGN KEY (`judgeID`) REFERENCES `JUDGE` (`judgeID`) ON UPDATE CASCADE;

--
-- Constraints for table `PROJECT`
--
ALTER TABLE `PROJECT`
  ADD CONSTRAINT `PROJECT_ibfk_4` FOREIGN KEY (`projectGradeID`) REFERENCES `PROJECT_GRADE_LEVEL` (`projectGradeID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `PROJECT_ibfk_2` FOREIGN KEY (`categoryID`) REFERENCES `CATEGORY` (`categoryID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `PROJECT_ibfk_3` FOREIGN KEY (`boothID`) REFERENCES `BOOTH_NUMBER` (`boothID`) ON UPDATE CASCADE;

--
-- Constraints for table `SCHOOL`
--
ALTER TABLE `SCHOOL`
  ADD CONSTRAINT `SCHOOL_ibfk_1` FOREIGN KEY (`countyID`) REFERENCES `COUNTY` (`countyID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `SCHOOL_ibfk_2` FOREIGN KEY (`cityID`) REFERENCES `CITY` (`cityID`) ON UPDATE CASCADE;

--
-- Constraints for table `STUDENT`
--
ALTER TABLE `STUDENT`
  ADD CONSTRAINT `STUDENT_ibfk_7` FOREIGN KEY (`cityID`) REFERENCES `CITY` (`cityID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `STUDENT_ibfk_1` FOREIGN KEY (`gradeID`) REFERENCES `GRADE` (`gradeID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `STUDENT_ibfk_2` FOREIGN KEY (`genderID`) REFERENCES `GENDER` (`genderID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `STUDENT_ibfk_5` FOREIGN KEY (`schoolID`) REFERENCES `SCHOOL` (`schoolID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `STUDENT_ibfk_6` FOREIGN KEY (`countyID`) REFERENCES `COUNTY` (`countyID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `STUDENT_ibfk_8` FOREIGN KEY (`projectID`) REFERENCES `PROJECT` (`projectID`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
