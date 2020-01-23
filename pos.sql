-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 14, 2020 at 10:53 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `business`
--

CREATE TABLE `business` (
  `business` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `business`
--

INSERT INTO `business` (`business`, `name`, `address`, `location`, `telephone`, `owner`) VALUES
(1, 'chicjoint', 'kiserian', 'kiserian', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `location` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`location`, `name`) VALUES
(2, 'Lower ChicJoint'),
(1, 'Upper ChicJoint');

-- --------------------------------------------------------

--
-- Table structure for table `owner`
--

CREATE TABLE `owner` (
  `owner` int(11) NOT NULL,
  `username` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `owner`
--

INSERT INTO `owner` (`owner`, `username`, `telephone`) VALUES
(1, 'Ken', '');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `business` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product`, `name`, `barcode`, `amount`, `business`) VALUES
(326, 'Tusker 500ml', '6161101600125', '0', 1),
(327, 'Tusker Malt', '6009603340555', '0', 1),
(328, 'Tusker Lite', '6161101602389', '0', 1),
(329, 'Tusker Cider', '6161101604246', '0', 1),
(330, 'Tusker Lite Can', '6161101602938', '0', 1),
(331, 'Pilser', '6161101600026', '0', 1),
(332, 'Whitecap', '6161101600095', '0', 1),
(333, 'WhiteCap lite', '6161101600002', '0', 1),
(334, 'Guiness', '6161101600101', '0', 1),
(335, 'black ice', '6161101600163', '0', 1),
(336, 'Summit malt', '6161100240841', '0', 1),
(337, 'Guarana can', '6161101603324', '0', 1),
(338, 'Captain Morgan 1/4', '6161101604550', '0', 1),
(339, 'Snapp', '6161101602426', '0', 1),
(340, 'Summit Lager', '6161100240858', '0', 1),
(341, 'Balozi', '6161101603256', '0', 1),
(342, 'Redbull', '90162602', '0', 1),
(343, 'Delmonte Mango', '02400015015', '0', 1),
(344, 'Delmonte mixed berry', '02400015013', '0', 1),
(345, 'Delmonte tropical', '02400015012', '0', 1),
(346, 'soda', '54492691', '0', 1),
(347, 'Kingfisher', '6161100420021', '0', 1),
(348, 'Keringet 1l', '6161104401835', '0', 1),
(349, 'Keringet 1/2', '6161104401828', '0', 1),
(350, 'Viceroy 250ml', '6001108016034', '0', 1),
(351, 'Viceroy 375', '6001496011772', '0', 1),
(352, 'Smirnoff vodka 250ml', '6161101602051', '0', 1),
(353, 'Smirnoff vodka 375ml', '6161101600941', '0', 1),
(354, 'Smirnoff vodka 750ml', '6161101600934', '0', 1),
(355, 'kenya cane 250ml', '6161101602211', '0', 1),
(356, 'Kenya cane 375ml', '5010103930840', '0', 1),
(357, 'Kenya cane 750ml', '5010103930833', '0', 1),
(358, 'Kibao 250ml', '6161100420823', '0', 1),
(359, 'Kibao 375ml', '6161100421387', '0', 1),
(360, 'Kibao 750', '6009603930992', '0', 1),
(361, 'Hunters 250ml', '6161100420786', '0', 1),
(362, 'Hunters 375ml', '6161100421363', '0', 1),
(363, 'Hunters 750ml', '6161100421356', '0', 1),
(364, 'Bond 7 250ml', '6161101602143', '0', 1),
(365, 'Bond 7 375ml', '5010103930970', '0', 1),
(366, 'Richot 250ml', '6161101602181', '0', 1),
(367, 'Richot 375ml', '5010103930871', '0', 1),
(368, 'Richot 750ml', '5010103930864', '0', 1),
(369, 'Gilbeys 375ml', '5010103930666', '0', 1),
(370, 'Gilbeys 750ml', '5010103930628', '0', 1),
(371, 'vat69 750ml', '5000292001001', '0', 1),
(372, 'vat69 375ml', '6161101601115', '0', 1),
(373, 'passport', '08043240176', '0', 1),
(374, 'Johnny Walker Red 375ml', '5000267014609', '0', 1),
(375, 'Johnny Walker Red 750ml', '5000267175508', '0', 1),
(376, 'William Lawson 750ml', '5010752000611', '0', 1),
(377, 'Jameson 750', '5011007003029', '0', 1),
(378, 'Jameson 375ml', '5011007003654', '0', 1),
(379, 'county 250ml', '6161100421233', '0', 1),
(380, 'county 375ml', '6161100421226', '0', 1),
(381, 'best cream', '6009675692422', '0', 1),
(382, 'Black and White 375ml', '50196166', '0', 1),
(383, 'Black and White 750ml', '50196081', '0', 1),
(384, '4th street 750ml', '6001108049599', '0', 1),
(385, 'Caprice 1ltr red', '6161100421271', '0', 1),
(386, 'Sportsman', '61600119', '0', 1),
(387, 'sm', '61600171', '0', 1),
(388, 'Embassy', '6008165007012', '0', 1),
(389, 'Dunhill', '6008165004578', '0', 1),
(390, 'Matchbox', '6009607673345', '0', 1),
(391, NULL, '6001108055231', '190', NULL),
(392, NULL, '6161101601979', '200', NULL),
(393, NULL, '6161101602372', '200', NULL),
(394, NULL, '5741000007624', '0', NULL),
(395, NULL, '8712000900663', '0', NULL),
(396, NULL, '90492112', '0', NULL),
(397, NULL, '6008835000947', '0', NULL),
(398, NULL, '3161420000166', '0', NULL),
(399, NULL, '5000267024011', '0', NULL),
(400, NULL, '5000267125046', '0', NULL),
(401, NULL, '5000267112077', '0', NULL),
(402, NULL, '1210000100504', '0', NULL),
(403, NULL, '6001496011796', '0', NULL),
(404, NULL, '6161100421370', '0', NULL),
(405, NULL, '5449000131836', '0', NULL),
(406, NULL, '5010327258003', '0', NULL),
(407, NULL, '5010327207117', '0', NULL),
(408, NULL, '5010327000046', '0', NULL),
(409, NULL, '5010314750008', '0', NULL),
(410, NULL, '5000267171999', '0', NULL),
(411, NULL, '5010314101015', '0', NULL),
(412, NULL, '5000281050171', '0', NULL),
(413, NULL, '3219820000184', '0', NULL),
(414, NULL, '3219820000207', '0', NULL),
(415, NULL, '6161101602167', '0', NULL),
(416, NULL, '5010103800303', '0', NULL),
(417, NULL, '5000267167053', '0', NULL),
(418, NULL, '3245990319801', '0', NULL),
(419, NULL, '3245990250302', '0', NULL),
(420, NULL, '5000267014074', '0', NULL),
(421, NULL, '3245990969419', '0', NULL),
(422, NULL, '6161101602174', '0', NULL),
(423, NULL, '5010103236645', '0', NULL),
(424, NULL, '5010752000420', '0', NULL),
(425, NULL, '3245995960015', '0', NULL),
(426, NULL, '6001108001436', '0', NULL),
(427, NULL, '08043240043', '0', NULL),
(428, NULL, '08043240039', '0', NULL),
(429, NULL, '5012523233129', '0', NULL),
(430, NULL, '3024482295126', '0', NULL),
(431, NULL, '08218409052', '0', NULL),
(432, NULL, '5099873089712', '0', NULL),
(433, NULL, '08218409047', '0', NULL),
(434, NULL, '5011007003227', '0', NULL),
(435, NULL, '5000267173115', '0', NULL),
(436, NULL, '6161100421219', '0', NULL),
(437, NULL, '5010327325132', '0', NULL),
(438, NULL, '5010327325125', '0', NULL),
(439, NULL, '5010327000176', '0', NULL),
(440, NULL, '3219820005042', '0', NULL),
(441, NULL, '5010284100001', '0', NULL),
(442, NULL, '5000196004665', '0', NULL),
(443, NULL, '6001495201600', '0', NULL),
(444, NULL, '08024400923', '0', NULL),
(445, NULL, '08024400922', '0', NULL),
(446, NULL, '6001495062478', '0', NULL),
(447, NULL, '6001495062508', '0', NULL),
(448, NULL, '4067700011015', '0', NULL),
(449, NULL, '7501012916127', '0', NULL),
(450, NULL, '6001812500034', '0', NULL),
(451, NULL, '6001496301705', '0', NULL),
(452, NULL, '6001496301743', '0', NULL),
(453, NULL, '6001108059178', '0', NULL),
(454, NULL, '6001496301842', '0', NULL),
(455, NULL, '6161100421288', '0', NULL),
(456, NULL, '8410702015271', '0', NULL),
(457, NULL, '8410310095139', '0', NULL),
(458, NULL, '6001495200146', '0', NULL),
(459, NULL, '6001495201501', '0', NULL),
(460, NULL, '6001108049582', '0', NULL),
(461, NULL, '6002269000566', '0', NULL),
(462, NULL, '6002269000559', '0', NULL),
(463, NULL, '6002323300533', '0', NULL),
(464, NULL, '6002323801337', '0', NULL),
(465, NULL, '6009676514792', '0', NULL),
(466, NULL, '6009676510497', '0', NULL),
(467, NULL, '6009611170274', '0', NULL),
(468, NULL, '61600188', '0', NULL),
(469, NULL, '6008165266853', '0', NULL),
(470, NULL, '8906005280695', '0', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `stock` int(11) NOT NULL,
  `date` date NOT NULL,
  `unit` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` int(11) NOT NULL,
  `product` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`stock`, `date`, `unit`, `quantity`, `location`, `product`) VALUES
(1, '2019-12-20', '', '128', 1, 326),
(2, '2019-12-20', '', '78', 1, 327),
(3, '2019-12-20', '', '93', 1, 328),
(4, '2019-12-20', '', '79', 1, 329),
(5, '2019-12-20', '', '11', 1, 391),
(6, '2019-12-20', '', '15', 1, 392),
(7, '2019-12-20', '', '36', 1, 331),
(8, '2019-12-20', '', '58', 1, 332),
(9, '2019-12-20', '', '66', 1, 333),
(10, '2019-12-20', '', '66', 1, 334),
(11, '2019-12-20', '', '13', 1, 393),
(12, '2019-12-20', '', '104', 1, 335),
(13, '2019-12-20', '', '9', 1, 339),
(14, '2019-12-20', '', '25', 1, 337),
(15, '2019-12-20', '', '4', 1, 394),
(16, '2019-12-20', '', '13', 1, 338),
(17, '2019-12-20', '', '85', 1, 340),
(18, '2019-12-20', '', '46', 1, 341),
(19, '2019-12-20', '', '27', 1, 395),
(20, '2019-12-20', '', '15', 1, 342),
(21, '2019-12-20', '', '23', 1, 345),
(22, '2019-12-20', '', '211', 1, 396),
(23, '2019-12-20', '', '31', 1, 347),
(24, '2019-12-20', '', '12', 1, 397),
(25, '2019-12-20', '', '21', 1, 330),
(26, '2019-12-20', '', '59', 1, 348),
(27, '2019-12-20', '', '24', 1, 349),
(28, '2019-12-20', '', '6', 1, 398),
(29, '2019-12-20', '', '5', 1, 374),
(30, '2019-12-20', '', '4', 1, 399),
(31, '2019-12-20', '', '3', 1, 400),
(32, '2019-12-20', '', '2', 1, 401),
(33, '2019-12-20', '', '3', 1, 402),
(34, '2019-12-20', '', '8', 1, 350),
(35, '2019-12-20', '', '8', 1, 351),
(36, '2019-12-20', '', '6', 1, 403),
(37, '2019-12-20', '', '11', 1, 352),
(38, '2019-12-20', '', '8', 1, 353),
(39, '2019-12-20', '', '16', 1, 356),
(40, '2019-12-20', '', '16', 1, 355),
(41, '2019-12-20', '', '4', 1, 357),
(42, '2019-12-20', '', '5', 1, 358),
(43, '2019-12-20', '', '6', 1, 359),
(44, '2019-12-20', '', '2', 1, 404),
(45, '2019-12-20', '', '23', 1, 405),
(46, '2019-12-20', '', '6', 1, 361),
(47, '2019-12-20', '', '8', 1, 362),
(48, '2019-12-20', '', '5', 1, 363),
(49, '2019-12-20', '', '4', 1, 364),
(50, '2019-12-20', '', '2', 1, 365),
(51, '2019-12-20', '', '2', 1, 406),
(52, '2019-12-20', '', '2', 1, 407),
(53, '2019-12-20', '', '3', 1, 408),
(54, '2019-12-20', '', '2', 1, 409),
(55, '2019-12-20', '', '2', 1, 410),
(56, '2019-12-20', '', '1', 1, 411),
(57, '2019-12-20', '', '2', 1, 412),
(58, '2019-12-20', '', '2', 1, 413),
(59, '2019-12-20', '', '5', 1, 414),
(60, '2019-12-20', '', '11', 1, 366),
(61, '2019-12-20', '', '12', 1, 367),
(62, '2019-12-20', '', '2', 1, 368),
(63, '2019-12-20', '', '10', 1, 415),
(64, '2019-12-20', '', '12', 1, 369),
(65, '2019-12-20', '', '8', 1, 370),
(66, '2019-12-20', '', '2', 1, 416),
(67, '2019-12-20', '', '12', 1, 372),
(68, '2019-12-20', '', '12', 1, 373),
(69, '2019-12-20', '', '4', 1, 417),
(70, '2019-12-20', '', '1', 1, 381),
(71, '2019-12-20', '', '6', 1, 418),
(72, '2019-12-20', '', '1', 1, 419),
(73, '2019-12-20', '', '5', 1, 420),
(74, '2019-12-20', '', '5', 1, 421),
(75, '2019-12-20', '', '12', 1, 422),
(76, '2019-12-20', '', '1', 1, 423),
(77, '2019-12-20', '', '8', 1, 424),
(78, '2019-12-20', '', '4', 1, 376),
(79, '2019-12-20', '', '2', 1, 425),
(80, '2019-12-20', '', '4', 1, 426),
(81, '2019-12-20', '', '3', 1, 427),
(82, '2019-12-20', '', '3', 1, 428),
(83, '2019-12-20', '', '1', 1, 429),
(84, '2019-12-20', '', '6', 1, 430),
(85, '2019-12-20', '', '14', 1, 431),
(86, '2019-12-20', '', '8', 1, 432),
(87, '2019-12-20', '', '5', 1, 433),
(88, '2019-12-20', '', '3', 1, 434),
(89, '2019-12-20', '', '3', 1, 377),
(90, '2019-12-20', '', '4', 1, 378),
(91, '2019-12-20', '', '1', 1, 435),
(92, '2019-12-20', '', '6', 1, 380),
(93, '2019-12-20', '', '2', 1, 436),
(94, '2019-12-20', '', '2', 1, 437),
(95, '2019-12-20', '', '2', 1, 438),
(96, '2019-12-20', '', '2', 1, 439),
(97, '2019-12-20', '', '3', 1, 440),
(98, '2019-12-20', '', '0', 1, 441),
(99, '2019-12-20', '', '7', 1, 382),
(100, '2019-12-20', '', '6', 1, 442),
(101, '2019-12-20', '', '20', 1, 443),
(102, '2019-12-20', '', '2', 1, 444),
(103, '2019-12-20', '', '2', 1, 445),
(104, '2019-12-20', '', '3', 1, 446),
(105, '2019-12-20', '', '3', 1, 447),
(106, '2019-12-20', '', '2', 1, 383),
(107, '2019-12-20', '', '0', 1, 448),
(108, '2019-12-20', '', '0', 1, 449),
(109, '2019-12-20', '', '0', 1, 450),
(110, '2019-12-20', '', '7', 1, 451),
(111, '2019-12-20', '', '47', 1, 452),
(112, '2019-12-20', '', '0', 1, 453),
(113, '2019-12-20', '', '0', 1, 454),
(114, '2019-12-20', '', '0', 1, 455),
(115, '2019-12-20', '', '0', 1, 385),
(116, '2019-12-20', '', '11', 1, 456),
(117, '2019-12-20', '', '3', 1, 457),
(118, '2019-12-20', '', '5', 1, 458),
(119, '2019-12-20', '', '2', 1, 459),
(120, '2019-12-20', '', '5', 1, 460),
(121, '2019-12-20', '', '2', 1, 384),
(122, '2019-12-20', '', '5', 1, 461),
(123, '2019-12-20', '', '5', 1, 462),
(124, '2019-12-20', '', '9', 1, 463),
(125, '2019-12-20', '', '2', 1, 464),
(126, '2019-12-20', '', '4', 1, 465),
(127, '2019-12-20', '', '3', 1, 466),
(128, '2019-12-20', '', '88', 1, 467),
(129, '2019-12-20', '', '276', 1, 468),
(130, '2019-12-20', '', '131', 1, 388),
(131, '2019-12-20', '', '200', 1, 469),
(132, '2019-12-20', '', '22', 1, 390),
(133, '2019-12-20', '', '16', 1, 470),
(134, '2019-12-20', '', '91', 2, 326),
(135, '2019-12-20', '', '31', 2, 327),
(136, '2019-12-20', '', '33', 2, 328),
(137, '2019-12-20', '', '33', 2, 329),
(138, '2019-12-20', '', '11', 2, 330),
(139, '2019-12-20', '', '42', 2, 331),
(140, '2019-12-20', '', '66', 2, 332),
(141, '2019-12-20', '', '23', 2, 333),
(142, '2019-12-20', '', '70', 2, 334),
(143, '2019-12-20', '', '15', 2, 335),
(144, '2019-12-20', '', '24', 2, 336),
(145, '2019-12-20', '', '24', 2, 337),
(146, '2019-12-20', '', '1', 2, 338),
(147, '2019-12-20', '', '21', 2, 339),
(148, '2019-12-20', '', '24', 2, 340),
(149, '2019-12-20', '', '63', 2, 341),
(150, '2019-12-20', '', '9', 2, 342),
(151, '2019-12-20', '', '3', 2, 343),
(152, '2019-12-20', '', '0', 2, 344),
(153, '2019-12-20', '', '0', 2, 345),
(154, '2019-12-20', '', '60', 2, 346),
(155, '2019-12-20', '', '18', 2, 347),
(156, '2019-12-20', '', '18', 2, 348),
(157, '2019-12-20', '', '11', 2, 349),
(158, '2019-12-20', '', '4', 2, 350),
(159, '2019-12-20', '', '3', 2, 351),
(160, '2019-12-20', '', '4', 2, 352),
(161, '2019-12-20', '', '1', 2, 353),
(162, '2019-12-20', '', '2', 2, 354),
(163, '2019-12-20', '', '3', 2, 355),
(164, '2019-12-20', '', '5', 2, 356),
(165, '2019-12-20', '', '2', 2, 357),
(166, '2019-12-20', '', '4', 2, 358),
(167, '2019-12-20', '', '3', 2, 359),
(168, '2019-12-20', '', '2', 2, 360),
(169, '2019-12-20', '', '4', 2, 361),
(170, '2019-12-20', '', '4', 2, 362),
(171, '2019-12-20', '', '4', 2, 363),
(172, '2019-12-20', '', '3', 2, 364),
(173, '2019-12-20', '', '2', 2, 365),
(174, '2019-12-20', '', '3', 2, 366),
(175, '2019-12-20', '', '2', 2, 367),
(176, '2019-12-20', '', '1', 2, 368),
(177, '2019-12-20', '', '3', 2, 369),
(178, '2019-12-20', '', '1', 2, 370),
(179, '2019-12-20', '', '2', 2, 371),
(180, '2019-12-20', '', '2', 2, 372),
(181, '2019-12-20', '', '3', 2, 373),
(182, '2019-12-20', '', '3', 2, 374),
(183, '2019-12-20', '', '2', 2, 375),
(184, '2019-12-20', '', '3', 2, 376),
(185, '2019-12-20', '', '1', 2, 377),
(186, '2019-12-20', '', '2', 2, 378),
(187, '2019-12-20', '', '2', 2, 379),
(188, '2019-12-20', '', '2', 2, 380),
(189, '2019-12-20', '', '1', 2, 381),
(190, '2019-12-20', '', '4', 2, 382),
(191, '2019-12-20', '', '3', 2, 383),
(192, '2019-12-20', '', '2', 2, 384),
(193, '2019-12-20', '', '1', 2, 385),
(194, '2019-12-20', '', '175', 2, 386),
(195, '2019-12-20', '', '14', 2, 387),
(196, '2019-12-20', '', '71', 2, 388),
(197, '2019-12-20', '', '60', 2, 389),
(198, '2019-12-20', '', '23', 2, 390);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `business`
--
ALTER TABLE `business`
  ADD PRIMARY KEY (`business`),
  ADD UNIQUE KEY `id` (`name`) USING BTREE,
  ADD KEY `owner` (`owner`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`location`),
  ADD UNIQUE KEY `id` (`name`) USING BTREE;

--
-- Indexes for table `owner`
--
ALTER TABLE `owner`
  ADD PRIMARY KEY (`owner`),
  ADD UNIQUE KEY `id` (`username`) USING BTREE;

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product`),
  ADD UNIQUE KEY `id2` (`name`),
  ADD UNIQUE KEY `id` (`barcode`) USING BTREE,
  ADD KEY `business` (`business`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`stock`),
  ADD UNIQUE KEY `id` (`date`,`location`,`product`) USING BTREE,
  ADD KEY `stock_ibfk_1` (`product`),
  ADD KEY `location` (`location`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `business`
--
ALTER TABLE `business`
  MODIFY `business` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `location` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `owner`
--
ALTER TABLE `owner`
  MODIFY `owner` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=471;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `stock` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=199;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `business`
--
ALTER TABLE `business`
  ADD CONSTRAINT `business_ibfk_1` FOREIGN KEY (`owner`) REFERENCES `owner` (`owner`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`business`) REFERENCES `business` (`business`);

--
-- Constraints for table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`product`) REFERENCES `product` (`product`),
  ADD CONSTRAINT `stock_ibfk_2` FOREIGN KEY (`location`) REFERENCES `location` (`location`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;