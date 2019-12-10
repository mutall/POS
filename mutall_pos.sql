-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 10, 2019 at 12:23 PM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mutall_pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `client` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`client`, `name`, `location`) VALUES
(1, 'chicjoint', 'kiserian');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `quantity` int(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `client` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product`, `name`, `barcode`, `quantity`, `amount`, `client`) VALUES
(1, 'Tusker Lager', NULL, NULL, '170', 1),
(2, 'Tusker Malt', NULL, NULL, '170', 1),
(3, 'Tusker Lite', NULL, NULL, '170', 1),
(4, 'Tusker Cider', NULL, NULL, '170', 1),
(5, 'Tusker Cider Can', NULL, NULL, '170', 1),
(6, 'Tusker Can', NULL, NULL, '200', 1),
(7, 'Pilsner', NULL, NULL, '170', 1),
(8, 'White Cap', NULL, NULL, '170', 1),
(9, 'White Cap Lite', NULL, NULL, '170', 1),
(10, 'Guinness Kubwa', NULL, NULL, '200', 1),
(11, 'Guinness Kubwa Can', NULL, NULL, '200', 1),
(12, 'Smirnoff Ice Black', NULL, NULL, '170', 1),
(13, 'Smirnoff Ice Green', NULL, NULL, '170', 1),
(14, 'Guarana', NULL, NULL, '200', 1),
(15, 'Red Horse', NULL, NULL, '200', 1),
(16, 'Carptain Morgan 1/4', NULL, NULL, '350', 1),
(17, 'Snapps', NULL, NULL, '170', 1),
(18, 'Summit Lager', NULL, NULL, '170', 1),
(19, 'Balozi', NULL, NULL, '170', 1),
(20, 'Heineken', NULL, NULL, '250', 1),
(21, 'Tuborg', NULL, NULL, '200', 1),
(22, 'Red Bull', NULL, NULL, '200', 1),
(23, 'Alvaro', NULL, NULL, '80', 1),
(24, 'Delmonte', NULL, NULL, '250', 1),
(25, 'Soda', NULL, NULL, '50', 1),
(26, 'Kingfisher', NULL, NULL, '200', 1),
(27, 'Lime Juice', NULL, NULL, '20', 1),
(28, 'Keringet 1.5L', NULL, NULL, '150', 1),
(29, 'Keringet 1L', NULL, NULL, '100', 1),
(30, 'Keringet 1/2 L', NULL, NULL, '50', 1),
(31, 'Remy', NULL, NULL, '2500', 1),
(32, 'Black Lable 1/2', NULL, NULL, '2000', 1),
(33, 'Black Lable 3/4', NULL, NULL, '4000', 1),
(34, 'Black Lable 1L', NULL, NULL, '5000', 1),
(35, 'Double Black Lable', NULL, NULL, '7000', 1),
(36, 'Southern Comfort 1Lt 1Lt', NULL, NULL, '3000', 1),
(37, 'Viceroy 1/4', NULL, NULL, '500', 1),
(38, 'Viceroy 1/2', NULL, NULL, '700', 1),
(39, 'Viceroy 3/4', NULL, NULL, '1500', 1),
(40, 'Vodka 1/4', NULL, NULL, '500', 1),
(41, 'Vodka 1/2', NULL, NULL, '700', 1),
(42, 'Vodka 3/4', NULL, NULL, '1500', 1),
(43, 'Kanya Cane 1/4', NULL, NULL, '250', 1),
(44, 'Kenya Cane 1/2', NULL, NULL, '400', 1),
(45, 'Kenya Cane 3/4', NULL, NULL, '700', 1),
(46, 'Kibao 1/4', NULL, NULL, '250', 1),
(47, 'Kibao 1/2', NULL, NULL, '400', 1),
(48, 'Kibao 3/4', NULL, NULL, '700', 1),
(49, 'Coke Zero', NULL, NULL, '100', 1),
(50, 'Hunters 1/4', NULL, NULL, '300', 1),
(51, 'Hunters 1/2', NULL, NULL, '500', 1),
(52, 'Hunters 3/4', NULL, NULL, '800', 1),
(53, 'Grants 1/4', NULL, NULL, '500', 1),
(54, 'Grants 1/2', NULL, NULL, '1500', 1),
(55, 'Grants 3/4', NULL, NULL, '2500', 1),
(56, 'Famous 1/2', NULL, NULL, '1200', 1),
(57, 'Famous 1/4', NULL, NULL, '800', 1),
(58, 'Famous Grouse 3/4', NULL, NULL, '2000', 1),
(59, 'Single Tons', NULL, NULL, '5000', 1),
(60, 'Camino 3/4', NULL, NULL, '2500', 1),
(61, 'Capt. Morgan 3/4', NULL, NULL, '2500', 1),
(62, 'Corvoiser', NULL, NULL, '8000', 1),
(63, 'Richot 1/4', NULL, NULL, '500', 1),
(64, 'Richot 1/2', NULL, NULL, '700', 1),
(65, 'Gilbeys 1/2', NULL, NULL, '700', 1),
(66, 'Gilbeys 1/4', NULL, NULL, '500', 1),
(67, 'Gilbeys 3/4', NULL, NULL, '1500', 1),
(68, 'J&B 3/4', NULL, NULL, '1800', 1),
(69, 'Captain Morgan Gold 3/4', NULL, NULL, '1200', 1),
(70, 'Martel', NULL, NULL, '8000', 1),
(71, 'VAT 69 3/4', NULL, NULL, '1500', 1),
(72, 'Passport', NULL, NULL, '1000', 1),
(73, 'Johnnie Walker 18yrs', NULL, NULL, '8000', 1),
(74, 'Best whisky', NULL, NULL, '950', 1),
(75, 'Johnnie walker 1/4', NULL, NULL, '700', 1),
(76, 'Johnnie Walker 1/2', NULL, NULL, '1500', 1),
(77, 'Johnnie Walker 3/4', NULL, NULL, '2500', 1),
(78, 'Johnnie Walker 1Lt', NULL, NULL, '3000', 1),
(79, 'Gordons', NULL, NULL, '2500', 1),
(80, 'Pushkin 1/2', NULL, NULL, '400', 1),
(81, 'Pushkin 1/4', NULL, NULL, '250', 1),
(82, 'Pushkin 3/4', NULL, NULL, '700', 1),
(83, 'Popov 1/4', NULL, NULL, '250', 1),
(84, 'Popov 3/4', NULL, NULL, '700', 1),
(85, 'Wiliamlawson 1/2', NULL, NULL, '950', 1),
(86, 'Wiliamlawson 3/4', NULL, NULL, '2000', 1),
(87, 'Hennesy Vsop', NULL, NULL, '8000', 1),
(88, 'Hennesy 1Ltr', NULL, NULL, '6000', 1),
(89, 'Hennesy 750ml', NULL, NULL, '5000', 1),
(90, 'Nederburg', NULL, NULL, '2000', 1),
(91, 'Chivas 3/4', NULL, NULL, '3500', 1),
(92, 'Chivas 1Lt', NULL, NULL, '4000', 1),
(93, 'Tia Maria', NULL, NULL, '2500', 1),
(94, 'Remmy Martini', NULL, NULL, '8000', 1),
(95, 'Jack Daniels 1/4', NULL, NULL, '800', 1),
(96, 'Jack Daniels 1/2', NULL, NULL, '2000', 1),
(97, 'Jack Daniels 3/4', NULL, NULL, '3500', 1),
(98, 'Jameson 1Lt', NULL, NULL, '3000', 1),
(99, 'Jameson 1/2', NULL, NULL, '1600', 1),
(100, 'County 1/4', NULL, NULL, '300', 1),
(101, 'County 1/2', NULL, NULL, '400', 1),
(102, 'County 3/4', NULL, NULL, '700', 1),
(103, 'Glenfidich 18yrs', NULL, NULL, '7000', 1),
(104, 'Glenfidich 15yrs', NULL, NULL, '6000', 1),
(105, 'Glenfidich 12yrs', NULL, NULL, '5000', 1),
(106, 'Glenfidich 1/2', NULL, NULL, '3000', 1),
(107, 'Viceroy tots', NULL, NULL, '100', 1),
(108, 'Vodka tots', NULL, NULL, '100', 1),
(109, 'Johnnie Walker tots', NULL, NULL, '150', 1),
(110, 'Famous Grouse Tots', NULL, NULL, '150', 1),
(111, 'Richot tot', NULL, NULL, '100', 1),
(112, 'Malibu tot', NULL, NULL, '150', 1),
(113, 'Gold Reserve', NULL, NULL, '6000', 1),
(114, 'Jack Daniels tot', NULL, NULL, '200', 1),
(115, 'Vat 69 tot', NULL, NULL, '0', 1),
(116, 'Camino tot', NULL, NULL, '150', 1),
(117, 'southern Confort tot', NULL, NULL, '150', 1),
(118, 'Black & White 1/2 1/2', NULL, NULL, '550', 1),
(119, 'Black & White 3/4', NULL, NULL, '950', 1),
(120, 'Jameson tot', NULL, NULL, '150', 1),
(121, 'Gilbeys tot', NULL, NULL, '100', 1),
(122, 'Dry Wine', NULL, NULL, '150', 1),
(123, 'Valley Wine', NULL, NULL, '1500', 1),
(124, 'Amarula 1/2', NULL, NULL, '1500', 1),
(125, 'Amarula 3/4', NULL, NULL, '2500', 1),
(126, 'Amarula tot', NULL, NULL, '100', 1),
(127, 'Jegermister Tot', NULL, NULL, '150', 1),
(128, 'Cellar Bottles', NULL, NULL, '1200', 1),
(129, 'Cellar White', NULL, NULL, '150', 1),
(130, 'Cellar Red', NULL, NULL, '150', 1),
(131, 'Caprise', NULL, NULL, '700', 1),
(132, 'Penasol', NULL, NULL, '700', 1),
(133, 'Penasol Bottle', NULL, NULL, '1500', 1),
(134, 'Zappa White Tot', NULL, NULL, '100', 1),
(135, 'Zappa Red 3/4', NULL, NULL, '2500', 1),
(136, 'Zappa Blue 3/4', NULL, NULL, '2500', 1),
(137, 'Baron White', NULL, NULL, '1200', 1),
(138, 'Drosty Hof', NULL, NULL, NULL, 1),
(139, 'Chamdor 3/4', NULL, NULL, '2000', 1),
(140, 'Piston 1/4', NULL, NULL, '200', 1),
(141, 'St Celine', NULL, NULL, '1200', 1),
(142, 'K.W.V White', NULL, NULL, '1800', 1),
(143, 'K.W.V Red', NULL, NULL, '1800', 1),
(144, 'K.W.V Brandy', NULL, NULL, '8500', 1),
(145, 'Alcon Wine', NULL, NULL, '1500', 1),
(146, 'Cape Wine', NULL, NULL, '1200', 1),
(147, '4th Street', NULL, NULL, '1200', 1),
(148, '4 Cousins', NULL, NULL, '1200', 1),
(149, 'Horizon Wine', NULL, NULL, '1200', 1),
(150, 'Panadol', NULL, NULL, '10', 1),
(151, 'Sportman', NULL, NULL, '7', 1),
(152, 'S.M', NULL, NULL, '7', 1),
(153, 'Embassy', NULL, NULL, '10', 1),
(154, 'Dunhill', NULL, NULL, '15', 1),
(155, 'Matchbox', NULL, NULL, '5', 1),
(156, 'Salama', NULL, NULL, '100', 1),
(157, 'Rough Rider', NULL, NULL, '150', 1),
(158, 'Cards', NULL, NULL, '50', 1),
(159, 'Black Lable tot', NULL, NULL, '200', 1),
(160, 'Zappa Red tot', NULL, NULL, '100', 1),
(161, 'Maji', '79238243675', 26, '50', 1),
(163, 'Java Book', '9789812431196', 0, '1200', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`client`),
  ADD UNIQUE KEY `id1` (`name`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product`),
  ADD UNIQUE KEY `id2` (`name`),
  ADD KEY `client` (`client`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=164;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`client`) REFERENCES `client` (`client`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
