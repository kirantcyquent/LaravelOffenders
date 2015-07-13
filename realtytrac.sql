-- phpMyAdmin SQL Dump
-- version 4.4.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 13, 2015 at 05:39 AM
-- Server version: 5.5.44
-- PHP Version: 5.6.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `realtytrac`
--

-- --------------------------------------------------------

--
-- Table structure for table `de_offenders`
--

CREATE TABLE IF NOT EXISTS `de_offenders` (
  `id` int(10) unsigned NOT NULL,
  `so_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `so_offenderid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `so_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `so_alias` text COLLATE utf8_unicode_ci,
  `so_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_street` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_statesource` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `so_addressdate` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `so_ethnicity` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `so_race` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_sex` enum('Male','Female') COLLATE utf8_unicode_ci NOT NULL,
  `so_height` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_weight` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_eyes` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_hair` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_dob` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_age` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_vehicles` text COLLATE utf8_unicode_ci,
  `so_targets` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_profilegenerated` enum('Y','N') COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_duplicate` enum('Y','N') COLLATE utf8_unicode_ci DEFAULT NULL,
  `latitude` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `longitude` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `got_it_from` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `de_offenses`
--

CREATE TABLE IF NOT EXISTS `de_offenses` (
  `id` int(10) unsigned NOT NULL,
  `of_offendersid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `of_Offense` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `of_ConvictedDate` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `of_Degree` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `of_Counts` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `of_date` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hash` varchar(32) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `de_profiles`
--

CREATE TABLE IF NOT EXISTS `de_profiles` (
  `id` int(10) unsigned NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `county_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('0','1','2') COLLATE utf8_unicode_ci NOT NULL COMMENT '0-Fresh, 1-Crawled, 2-Offender-not-Found',
  `hash` varchar(32) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` int(10) unsigned NOT NULL,
  `connection` text COLLATE utf8_unicode_ci NOT NULL,
  `queue` text COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ga_counties`
--

CREATE TABLE IF NOT EXISTS `ga_counties` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pages` int(11) NOT NULL,
  `county_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL COMMENT '0-fresh, 1-done'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ga_offenders`
--

CREATE TABLE IF NOT EXISTS `ga_offenders` (
  `id` int(10) unsigned NOT NULL,
  `so_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `so_offenderid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `so_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `so_alias` text COLLATE utf8_unicode_ci,
  `so_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_street` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_statesource` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `so_addressdate` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `so_ethnicity` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `so_race` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_sex` enum('Male','Female') COLLATE utf8_unicode_ci NOT NULL,
  `so_height` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_weight` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_eyes` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_hair` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_dob` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_age` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_vehicles` text COLLATE utf8_unicode_ci,
  `so_targets` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_profilegenerated` enum('Y','N') COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_duplicate` enum('Y','N') COLLATE utf8_unicode_ci DEFAULT NULL,
  `latitude` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `longitude` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `got_it_from` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ga_offenses`
--

CREATE TABLE IF NOT EXISTS `ga_offenses` (
  `id` int(10) unsigned NOT NULL,
  `of_offendersid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `of_Offense` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `of_ConvictedDate` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `of_Degree` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `of_Counts` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `of_date` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hash` varchar(32) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) unsigned NOT NULL,
  `queue` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ks_counties`
--

CREATE TABLE IF NOT EXISTS `ks_counties` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `county_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('0','1','2','3','4') COLLATE utf8_unicode_ci NOT NULL COMMENT '0-fresh, 1-html-saved, 2-server-error, 3-links-extracted, 4-nolinks'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ks_offenders`
--

CREATE TABLE IF NOT EXISTS `ks_offenders` (
  `id` int(10) unsigned NOT NULL,
  `so_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `so_offenderid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `so_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `so_alias` text COLLATE utf8_unicode_ci,
  `so_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_street` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_statesource` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `so_addressdate` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `so_ethnicity` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `so_race` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_sex` enum('Male','Female') COLLATE utf8_unicode_ci NOT NULL,
  `so_height` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_weight` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_eyes` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_hair` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_dob` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_age` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_vehicles` text COLLATE utf8_unicode_ci,
  `so_targets` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_profilegenerated` enum('Y','N') COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_duplicate` enum('Y','N') COLLATE utf8_unicode_ci DEFAULT NULL,
  `latitude` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `longitude` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `got_it_from` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ks_offenses`
--

CREATE TABLE IF NOT EXISTS `ks_offenses` (
  `id` int(10) unsigned NOT NULL,
  `of_offendersid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `of_Offense` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `of_ConvictedDate` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `of_Degree` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `of_Counts` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `of_date` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hash` varchar(32) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ks_profiles`
--

CREATE TABLE IF NOT EXISTS `ks_profiles` (
  `id` int(10) unsigned NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `county_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('0','1','2') COLLATE utf8_unicode_ci NOT NULL COMMENT '0-Fresh, 1-Crawled, 2-Offender-not-Found',
  `hash` varchar(32) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ms_counties`
--

CREATE TABLE IF NOT EXISTS `ms_counties` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `county_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('0','1','2','3','4') COLLATE utf8_unicode_ci NOT NULL COMMENT '0-fresh, 1-html-saved, 2-server-error, 3-links-extracted, 4-nolinks'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ms_offenders`
--

CREATE TABLE IF NOT EXISTS `ms_offenders` (
  `id` int(10) unsigned NOT NULL,
  `so_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `so_offenderid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `so_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `so_alias` text COLLATE utf8_unicode_ci,
  `so_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_street` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_statesource` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `so_addressdate` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `so_ethnicity` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `so_race` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_sex` enum('Male','Female') COLLATE utf8_unicode_ci NOT NULL,
  `so_height` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_weight` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_eyes` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_hair` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_dob` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_age` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_vehicles` text COLLATE utf8_unicode_ci,
  `so_targets` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_profilegenerated` enum('Y','N') COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_duplicate` enum('Y','N') COLLATE utf8_unicode_ci DEFAULT NULL,
  `latitude` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `longitude` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `got_it_from` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ms_offenses`
--

CREATE TABLE IF NOT EXISTS `ms_offenses` (
  `id` int(10) unsigned NOT NULL,
  `of_offendersid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `of_Offense` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `of_ConvictedDate` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `of_Degree` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `of_Counts` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `of_date` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hash` varchar(32) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ms_profiles`
--

CREATE TABLE IF NOT EXISTS `ms_profiles` (
  `id` int(10) unsigned NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `county_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('0','1','2') COLLATE utf8_unicode_ci NOT NULL COMMENT '0-Fresh, 1-Crawled, 2-Offender-not-Found',
  `hash` varchar(32) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sexoffenders`
--

CREATE TABLE IF NOT EXISTS `sexoffenders` (
  `id` int(10) unsigned NOT NULL,
  `state_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `records_crawled` int(10) unsigned NOT NULL DEFAULT '0',
  `records_expected` int(10) unsigned NOT NULL DEFAULT '0',
  `crawl_state` enum('running','stopped','paused','completed','incomplete') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'incomplete',
  `paused` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '0-normal, 1-paused',
  `expected_time` timestamp NULL DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sexoffenders_stats`
--

CREATE TABLE IF NOT EXISTS `sexoffenders_stats` (
  `id` int(10) unsigned NOT NULL,
  `sexoffender_id` int(10) unsigned NOT NULL,
  `records_crawled` int(10) unsigned NOT NULL DEFAULT '0',
  `crawl_time` time NOT NULL,
  `record_time` time NOT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `de_offenders`
--
ALTER TABLE `de_offenders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `de_offenses`
--
ALTER TABLE `de_offenses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `de_offenses_hash_unique` (`hash`);

--
-- Indexes for table `de_profiles`
--
ALTER TABLE `de_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `de_profiles_hash_unique` (`hash`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ga_counties`
--
ALTER TABLE `ga_counties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ga_offenders`
--
ALTER TABLE `ga_offenders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ga_offenses`
--
ALTER TABLE `ga_offenses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ga_offenses_hash_unique` (`hash`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ks_counties`
--
ALTER TABLE `ks_counties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ks_offenders`
--
ALTER TABLE `ks_offenders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ks_offenses`
--
ALTER TABLE `ks_offenses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ks_offenses_hash_unique` (`hash`);

--
-- Indexes for table `ks_profiles`
--
ALTER TABLE `ks_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ks_profiles_hash_unique` (`hash`);

--
-- Indexes for table `ms_counties`
--
ALTER TABLE `ms_counties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ms_offenders`
--
ALTER TABLE `ms_offenders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ms_offenses`
--
ALTER TABLE `ms_offenses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ms_offenses_hash_unique` (`hash`);

--
-- Indexes for table `ms_profiles`
--
ALTER TABLE `ms_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ms_profiles_hash_unique` (`hash`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Indexes for table `sexoffenders`
--
ALTER TABLE `sexoffenders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sexoffenders_state_name_index` (`state_name`),
  ADD KEY `sexoffenders_state_code_index` (`state_code`),
  ADD KEY `sexoffenders_crawl_state_index` (`crawl_state`);

--
-- Indexes for table `sexoffenders_stats`
--
ALTER TABLE `sexoffenders_stats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sexoffenders_stats_sexoffender_id_foreign` (`sexoffender_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `de_offenders`
--
ALTER TABLE `de_offenders`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `de_offenses`
--
ALTER TABLE `de_offenses`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `de_profiles`
--
ALTER TABLE `de_profiles`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ga_counties`
--
ALTER TABLE `ga_counties`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ga_offenders`
--
ALTER TABLE `ga_offenders`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ga_offenses`
--
ALTER TABLE `ga_offenses`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ks_counties`
--
ALTER TABLE `ks_counties`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ks_offenders`
--
ALTER TABLE `ks_offenders`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ks_offenses`
--
ALTER TABLE `ks_offenses`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ks_profiles`
--
ALTER TABLE `ks_profiles`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ms_counties`
--
ALTER TABLE `ms_counties`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ms_offenders`
--
ALTER TABLE `ms_offenders`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ms_offenses`
--
ALTER TABLE `ms_offenses`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ms_profiles`
--
ALTER TABLE `ms_profiles`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sexoffenders`
--
ALTER TABLE `sexoffenders`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sexoffenders_stats`
--
ALTER TABLE `sexoffenders_stats`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `sexoffenders_stats`
--
ALTER TABLE `sexoffenders_stats`
  ADD CONSTRAINT `sexoffenders_stats_sexoffender_id_foreign` FOREIGN KEY (`sexoffender_id`) REFERENCES `sexoffenders` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
