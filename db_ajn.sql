-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2018 at 05:57 PM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 5.6.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


--
-- Database: `db_ajn`
--
CREATE DATABASE IF NOT EXISTS `db_ajn` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `db_ajn`;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

DROP TABLE IF EXISTS `tbl_admin`;
CREATE TABLE `tbl_admin` (
  `idadmin` int(8) NOT NULL,
  `user_email` varchar(128) NOT NULL,
  `user_password` varchar(128) NOT NULL,
  `nama` varchar(75) NOT NULL,
  `gender` enum('L','P') NOT NULL,
  `alamat` text NOT NULL,
  `levelnum` enum('1','2','3','99') NOT NULL DEFAULT '1',
  `lastlogin_ip` varchar(22) NOT NULL,
  `lastlogin_tgl` varchar(22) NOT NULL,
  `nowlogin_ip` varchar(22) NOT NULL,
  `nowlogin_tgl` varchar(22) NOT NULL,
  `aktif` enum('1','0') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`idadmin`, `user_email`, `user_password`, `nama`, `gender`, `alamat`, `levelnum`, `lastlogin_ip`, `lastlogin_tgl`, `nowlogin_ip`, `nowlogin_tgl`, `aktif`) VALUES
(1, 'admin@demo.com', 'db69fc039dcbd2962cb4d28f5891aae1', 'Administrator', 'L', '-', '3', '192.168.1.107', '2018-07-19 16:56:16', '192.168.1.107', '2018-07-19 17:04:05', '1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_page`
--

DROP TABLE IF EXISTS `tbl_page`;
CREATE TABLE `tbl_page` (
  `idpage` int(8) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `title` varchar(150) NOT NULL,
  `body` text NOT NULL,
  `aktif` enum('1','0') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_page`
--

INSERT INTO `tbl_page` (`idpage`, `slug`, `title`, `body`, `aktif`) VALUES
(1, 'home', 'Home', '<p>selamat datang wahai admin</p>', '1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_setting`
--

DROP TABLE IF EXISTS `tbl_setting`;
CREATE TABLE `tbl_setting` (
  `idsetting` int(8) NOT NULL,
  `setting_name` varchar(150) NOT NULL,
  `setting_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_setting`
--

INSERT INTO `tbl_setting` (`idsetting`, `setting_name`, `setting_value`) VALUES
(1, 'site_name', 'AJN Framework'),
(2, 'site_slogan', 'Modular Framework'),
(3, 'site_theme', 'default'),
(4, 'paging_web', '10'),
(5, 'paging_admin', '25'),
(6, 'company_name', 'ARI JULIANTO Network'),
(7, 'company_address', 'Tanjungsari'),
(8, 'company_city', 'Sumedang'),
(9, 'company_contact', '089657206089'),
(10, 'logo', 'logo.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`idadmin`);

--
-- Indexes for table `tbl_page`
--
ALTER TABLE `tbl_page`
  ADD PRIMARY KEY (`idpage`);

--
-- Indexes for table `tbl_setting`
--
ALTER TABLE `tbl_setting`
  ADD PRIMARY KEY (`idsetting`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `idadmin` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_page`
--
ALTER TABLE `tbl_page`
  MODIFY `idpage` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_setting`
--
ALTER TABLE `tbl_setting`
  MODIFY `idsetting` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;