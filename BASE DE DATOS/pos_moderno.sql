-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-05-2021 a las 01:30:30
-- Versión del servidor: 10.4.11-MariaDB
-- Versión de PHP: 7.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pos_moderno`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bank_accounts`
--

CREATE TABLE `bank_accounts` (
  `id` int(10) NOT NULL,
  `account_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `account_details` text CHARACTER SET utf8 DEFAULT NULL,
  `initial_balance` decimal(25,4) NOT NULL,
  `account_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contact_person` varchar(255) CHARACTER SET utf8 NOT NULL,
  `phone_number` varchar(14) COLLATE utf8_unicode_ci DEFAULT NULL,
  `opening_date` datetime DEFAULT NULL,
  `url` text CHARACTER SET utf8 DEFAULT NULL,
  `total_deposit` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `total_withdraw` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `total_transfer_from_other` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `total_transfer_to_other` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `bank_accounts`
--

INSERT INTO `bank_accounts` (`id`, `account_name`, `account_details`, `initial_balance`, `account_no`, `contact_person`, `phone_number`, `opening_date`, `url`, `total_deposit`, `total_withdraw`, `total_transfer_from_other`, `total_transfer_to_other`, `created_at`, `updated_at`) VALUES
(1, 'Default Account', 'This is a default account for any store', '0.0000', '1234567890', 'iAngryboy', '+880133333333', '2019-05-15 08:27:03', 'http://itsolution24.com', '466063.6360', '0.0000', '0.0000', '0.0000', '2019-01-16 13:23:03', '2019-07-02 11:56:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bank_account_to_store`
--

CREATE TABLE `bank_account_to_store` (
  `ba2s` int(10) UNSIGNED NOT NULL,
  `store_id` int(10) UNSIGNED NOT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `deposit` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `withdraw` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `transfer_from_other` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `transfer_to_other` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `sort_order` int(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `bank_account_to_store`
--

INSERT INTO `bank_account_to_store` (`ba2s`, `store_id`, `account_id`, `deposit`, `withdraw`, `transfer_from_other`, `transfer_to_other`, `status`, `sort_order`) VALUES
(1, 1, 1, '52136.9500', '266181.6860', '0.0000', '0.0000', 1, 0),
(2, 2, 1, '0.0000', '147745.0000', '0.0000', '0.0000', 1, 0),
(3, 3, 1, '0.0000', '0.0000', '0.0000', '0.0000', 1, 0),
(4, 4, 1, '0.0000', '0.0000', '0.0000', '0.0000', 1, 0),
(5, 5, 1, '0.0000', '0.0000', '0.0000', '0.0000', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bank_transaction_info`
--

CREATE TABLE `bank_transaction_info` (
  `info_id` int(10) UNSIGNED NOT NULL,
  `store_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `transaction_type` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_substract` tinyint(1) NOT NULL DEFAULT 0,
  `is_hide` tinyint(1) NOT NULL DEFAULT 0,
  `account_id` int(10) UNSIGNED NOT NULL,
  `source_id` int(10) DEFAULT NULL,
  `exp_category_id` int(10) DEFAULT NULL,
  `ref_no` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'e.g. Transaction ID, Check No.',
  `invoice_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `details` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `from_account_id` int(10) UNSIGNED DEFAULT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `image` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `bank_transaction_info`
--

INSERT INTO `bank_transaction_info` (`info_id`, `store_id`, `transaction_type`, `is_substract`, `is_hide`, `account_id`, `source_id`, `exp_category_id`, `ref_no`, `invoice_id`, `title`, `details`, `from_account_id`, `status`, `image`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'withdraw', 0, 0, 1, NULL, 9, 'W1', 'B2432', 'Debit for Product Purchase', 'Supplier name: No Supplier', NULL, 1, 'NULL', 1, '2019-12-15 12:18:36', NULL),
(2, 1, 'deposit', 0, 0, 1, 1, NULL, 'D2', '100000001', 'Deposit for selling', 'Customer name: Walking Customer', NULL, 1, 'NULL', 1, '2019-12-15 12:19:46', NULL),
(3, 1, 'deposit', 0, 0, 1, 1, NULL, 'D3', '100000002', 'Deposit for selling', 'Customer name: Walking Customer', NULL, 1, 'NULL', 1, '2021-01-20 19:49:33', NULL),
(4, 1, 'deposit', 0, 0, 1, 1, NULL, 'D4', '100000003', 'Deposit for selling', 'Customer name: Walking Customer', NULL, 1, 'NULL', 1, '2021-01-20 19:50:10', NULL),
(5, 1, 'deposit', 0, 0, 1, 1, NULL, 'D5', '12021/00000004', 'Deposit for selling', 'Customer name: Raul Alegre', NULL, 1, 'NULL', 1, '2021-01-20 21:17:58', NULL),
(6, 2, 'withdraw', 0, 0, 1, NULL, 9, 'W6', 'BHp core i5', 'Debit for Product Purchase', 'Supplier name: No Supplier', NULL, 1, 'NULL', 1, '2021-01-23 20:56:29', NULL),
(7, 2, 'withdraw', 0, 0, 1, NULL, 9, 'W7', 'B1', 'Debit for Product Purchase', 'Supplier name: BAKUS', NULL, 1, 'NULL', 1, '2021-01-23 21:00:12', NULL),
(8, 1, 'withdraw', 0, 0, 1, NULL, 9, 'W8', 'BINKA KOLA', 'Debit for Product Purchase', 'Supplier name: BAKUS', NULL, 1, 'NULL', 1, '2021-01-24 21:22:01', NULL),
(9, 1, 'withdraw', 0, 0, 1, NULL, 9, 'W9', 'BCHOCMAN', 'Debit for Product Purchase', 'Supplier name: COSTA', NULL, 1, 'NULL', 1, '2021-01-24 21:47:41', NULL),
(10, 1, 'withdraw', 0, 0, 1, NULL, 9, 'W10', 'BPALTA', 'Debit for Product Purchase', 'Supplier name: BAKUS', NULL, 1, 'NULL', 1, '2021-01-24 21:59:07', NULL),
(11, 1, 'withdraw', 0, 0, 1, NULL, 9, 'W11', 'BSODA', 'Debit for Product Purchase', 'Supplier name: SAN JORGE', NULL, 1, 'NULL', 1, '2021-01-24 22:04:11', NULL),
(12, 1, 'withdraw', 0, 0, 1, NULL, 9, 'W12', 'BPAVO', 'Debit for Product Purchase', 'Supplier name: SAN FERNANDO', NULL, 1, 'NULL', 1, '2021-01-24 22:06:25', NULL),
(13, 1, 'withdraw', 0, 0, 1, NULL, 9, 'W13', 'BCAT AIR', 'Debit for Product Purchase', 'Supplier name: CATERPILLAR', NULL, 1, 'NULL', 1, '2021-01-24 22:08:47', NULL),
(14, 1, 'deposit', 0, 0, 1, 1, NULL, 'D14', '12021/00000005', 'Deposit for selling', 'Customer name: JUAN DE LA VEGA', NULL, 1, 'NULL', 1, '2021-01-24 22:15:26', NULL),
(15, 1, 'deposit', 0, 0, 1, 1, NULL, 'D15', '12021/00000006', 'Deposit for selling', 'Customer name: JUAN DE LA VEGA', NULL, 1, 'NULL', 1, '2021-01-24 22:27:04', NULL),
(16, 1, 'deposit', 0, 0, 1, 1, NULL, 'D16', '12021/00000007', 'Deposit for selling', 'Customer name: JUAN DE LA VEGA', NULL, 1, 'NULL', 1, '2021-01-24 22:27:30', NULL),
(17, 1, 'withdraw', 0, 0, 1, NULL, 9, 'W17', 'BTARI', 'Debit for Product Purchase', 'Supplier name: ALICORP', NULL, 1, 'NULL', 1, '2021-01-24 22:29:07', NULL),
(18, 1, 'deposit', 0, 0, 1, 1, NULL, 'D18', '12021/00000008', 'Deposit for selling', 'Customer name: JUAN DE LA VEGA', NULL, 1, 'NULL', 1, '2021-01-24 22:44:46', NULL),
(19, 1, 'withdraw', 0, 0, 1, NULL, 9, 'W19', 'BPAVO1', 'Debit for Product Purchase', 'Supplier name: SAN FERNANDO', NULL, 1, 'NULL', 1, '2021-01-24 22:48:34', NULL),
(20, 1, 'deposit', 0, 0, 1, 1, NULL, 'D20', '12021/00000009', 'Deposit for selling', 'Customer name: JUAN DE LA VEGA', NULL, 1, 'NULL', 1, '2021-01-24 22:51:17', NULL),
(21, 1, 'deposit', 0, 0, 1, 1, NULL, 'D21', '12021/00000010', 'Deposit for selling', 'Customer name: JUAN DE LA VEGA', NULL, 1, 'NULL', 1, '2021-01-24 22:51:57', NULL),
(22, 1, 'withdraw', 0, 0, 1, NULL, 9, 'W22', 'BZAPATILLAS CATERPILLAR', 'Debit for Product Purchase', 'Supplier name: CATERPILLAR', NULL, 1, 'NULL', 1, '2021-01-24 22:53:43', NULL),
(23, 1, 'deposit', 0, 0, 1, 1, NULL, 'D23', '12021/00000011', 'Deposit for selling', 'Customer name: JUAN DE LA VEGA', NULL, 1, 'NULL', 1, '2021-01-24 22:54:35', NULL),
(24, 1, 'deposit', 0, 0, 1, 7, NULL, 'D24', '1', 'Deposit while giftcard topup', '', NULL, 1, 'NULL', 1, '2021-02-18 09:27:25', NULL),
(25, 1, 'deposit', 0, 0, 1, 1, NULL, 'D25', '12021/00000012', 'Deposit for selling', 'Customer name: JUAN DE LA VEGA', NULL, 1, 'NULL', 1, '2021-02-18 10:37:32', NULL),
(26, 1, 'withdraw', 0, 0, 1, NULL, 9, 'W26', 'B120', 'Debit for Product Purchase', 'Supplier name: CATERPILLAR', NULL, 1, 'NULL', 1, '2021-02-18 13:20:09', NULL),
(27, 1, 'deposit', 0, 0, 1, 1, NULL, 'D27', '12021/00000013', 'Deposit for selling', 'Customer name: JUAN DE LA VEGA', NULL, 1, 'NULL', 1, '2021-02-18 13:22:03', NULL),
(28, 1, 'deposit', 0, 0, 1, 1, NULL, 'D28', '12021/00000014', 'Deposit for selling', 'Customer name: JOSE TORRES', NULL, 1, 'NULL', 1, '2021-02-18 13:22:45', NULL),
(29, 1, 'deposit', 0, 0, 1, 1, NULL, 'D29', '12021/00000015', 'Deposit for selling', 'Customer name: ROBER CARLOS', NULL, 1, 'NULL', 1, '2021-02-18 13:23:27', NULL),
(30, 1, 'deposit', 0, 0, 1, 1, NULL, 'D30', '12021/00000016', 'Deposit for selling', 'Customer name: ROBER CARLOS', NULL, 1, 'NULL', 1, '2021-02-18 13:28:36', NULL),
(31, 1, 'deposit', 0, 0, 1, 1, NULL, 'D31', '12021/00000017', 'Deposit for selling', 'Customer name: JUAN DE LA VEGA', NULL, 1, 'NULL', 1, '2021-02-18 13:30:48', NULL),
(32, 1, 'deposit', 0, 0, 1, 1, NULL, 'D32', '12021/00000018', 'Deposit for selling', 'Customer name: JUAN DE LA VEGA', NULL, 1, 'NULL', 1, '2021-02-18 13:32:20', NULL),
(33, 1, 'deposit', 0, 0, 1, 1, NULL, 'D33', '12021/00000019', 'Deposit for selling', 'Customer name: JUAN DE LA VEGA', NULL, 1, 'NULL', 1, '2021-02-18 13:33:44', NULL),
(34, 1, 'deposit', 0, 0, 1, 6, NULL, 'D34', '2', 'Deposit while giftcard sell', '', NULL, 1, 'NULL', 1, '2021-02-18 13:41:57', NULL),
(35, 1, 'deposit', 0, 0, 1, 7, NULL, 'D35', '2', 'Deposit while giftcard topup', '', NULL, 1, 'NULL', 1, '2021-02-18 13:41:58', NULL),
(36, 1, 'deposit', 0, 0, 1, 1, NULL, 'D36', '12021/00000020', 'Deposit for selling', 'Customer name: JEAN TORRES', NULL, 1, 'NULL', 1, '2021-02-18 13:43:05', NULL),
(37, 1, 'deposit', 0, 0, 1, 1, NULL, 'D37', '12021/00000021', 'Deposit for selling', 'Customer name: JUAN DE LA VEGA', NULL, 1, 'NULL', 1, '2021-02-18 17:22:59', NULL),
(38, 1, 'deposit', 0, 0, 1, 1, NULL, 'D38', '12021/00000023', 'Deposit for selling', 'Customer name: JUAN DE LA VEGA', NULL, 1, 'NULL', 1, '2021-02-18 17:25:30', NULL),
(39, 1, 'deposit', 0, 0, 1, 1, NULL, 'D39', '12021/00000024', 'Deposit for selling', 'Customer name: JUAN DE LA VEGA', NULL, 1, 'NULL', 1, '2021-02-18 17:25:40', NULL),
(40, 1, 'deposit', 0, 0, 1, 1, NULL, 'D40', '12021/00000025', 'Deposit for selling', 'Customer name: JUAN DE LA VEGA', NULL, 1, 'NULL', 1, '2021-02-18 17:25:54', NULL),
(41, 1, 'deposit', 0, 0, 1, 6, NULL, 'D41', '3', 'Deposit while giftcard sell', '', NULL, 1, 'NULL', 1, '2021-02-18 19:06:39', NULL),
(42, 1, 'deposit', 0, 0, 1, 7, NULL, 'D42', '3', 'Deposit while giftcard topup', '', NULL, 1, 'NULL', 1, '2021-02-18 19:06:40', NULL),
(43, 1, 'deposit', 0, 0, 1, 1, NULL, 'D43', '12021/00000026', 'Deposit for selling', 'Customer name: JUAN SANCHEZ', NULL, 1, 'NULL', 1, '2021-02-18 19:07:46', NULL),
(44, 1, 'deposit', 0, 0, 1, 1, NULL, 'D44', '12021/00000027', 'Deposit for selling', 'Customer name: JUAN SANCHEZ', NULL, 1, 'NULL', 1, '2021-02-18 19:08:19', NULL),
(45, 1, 'deposit', 0, 0, 1, 1, NULL, 'D45', '12021/00000028', 'Deposit for selling', 'Customer name: JUAN SANCHEZ', NULL, 1, 'NULL', 1, '2021-02-18 19:09:27', NULL),
(46, 1, 'deposit', 0, 0, 1, 3, NULL, 'D46', '12021/00000022', 'Deposit for due collection', 'Customer name: JEAN TORRES', NULL, 1, 'NULL', 1, '2021-02-18 19:22:34', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bank_transaction_price`
--

CREATE TABLE `bank_transaction_price` (
  `price_id` int(10) NOT NULL,
  `store_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `info_id` int(11) DEFAULT NULL,
  `ref_no` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'e.g. Transaction ID, Check No.',
  `amount` decimal(25,4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `bank_transaction_price`
--

INSERT INTO `bank_transaction_price` (`price_id`, `store_id`, `info_id`, `ref_no`, `amount`) VALUES
(1, 1, 1, 'W1', '250000.0000'),
(2, 1, 2, 'D2', '3000.0000'),
(3, 1, 3, 'D3', '9000.0000'),
(4, 1, 4, 'D4', '6000.0000'),
(5, 1, 5, 'D5', '6000.0000'),
(6, 2, 6, 'W6', '147580.0000'),
(7, 2, 7, 'W7', '165.0000'),
(8, 1, 8, 'W8', '374.2800'),
(9, 1, 9, 'W9', '138.2840'),
(10, 1, 10, 'W10', '48.0000'),
(11, 1, 11, 'W11', '61.6420'),
(12, 1, 12, 'W12', '900.0000'),
(13, 1, 13, 'W13', '3200.0000'),
(14, 1, 14, 'D14', '80.0000'),
(15, 1, 15, 'D15', '18.5000'),
(16, 1, 16, 'D16', '772.6900'),
(17, 1, 17, 'W17', '33.0000'),
(18, 1, 18, 'D18', '20.5000'),
(19, 1, 19, 'W19', '2700.0000'),
(20, 1, 20, 'D20', '422.0000'),
(21, 1, 21, 'D21', '3001.7000'),
(22, 1, 22, 'W22', '3215.0000'),
(23, 1, 23, 'D23', '15.0000'),
(24, 1, 24, 'D24', '2000.0000'),
(25, 1, 25, 'D25', '5.5000'),
(26, 1, 26, 'W26', '5511.4800'),
(27, 1, 27, 'D27', '450.9900'),
(28, 1, 28, 'D28', '219.5000'),
(29, 1, 29, 'D29', '879.4900'),
(30, 1, 30, 'D30', '7.5000'),
(31, 1, 31, 'D31', '100.0000'),
(32, 1, 32, 'D32', '100.0000'),
(33, 1, 33, 'D33', '3000.0000'),
(34, 1, 34, 'D34', '500.0000'),
(35, 1, 35, 'D35', '5000.0000'),
(36, 1, 36, 'D36', '100.0000'),
(37, 1, 37, 'D37', '103.0000'),
(38, 1, 38, 'D38', '1.7000'),
(39, 1, 39, 'D39', '210.0000'),
(40, 1, 40, 'D40', '1.7000'),
(41, 1, 41, 'D41', '5000.0000'),
(42, 1, 42, 'D42', '5000.0000'),
(43, 1, 43, 'D43', '354.4900'),
(44, 1, 44, 'D44', '211.7000'),
(45, 1, 45, 'D45', '350.9900'),
(46, 1, 46, 'D46', '210.0000');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `boxes`
--

CREATE TABLE `boxes` (
  `box_id` int(10) UNSIGNED NOT NULL,
  `box_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `code_name` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `box_details` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `boxes`
--

INSERT INTO `boxes` (`box_id`, `box_name`, `code_name`, `box_details`) VALUES
(1, 'Common Box', 'common', 'Common Box details here...');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `box_to_store`
--

CREATE TABLE `box_to_store` (
  `id` int(11) NOT NULL,
  `box_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL DEFAULT 1,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `sort_order` int(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `box_to_store`
--

INSERT INTO `box_to_store` (`id`, `box_id`, `store_id`, `status`, `sort_order`) VALUES
(1, 1, 1, 1, 0),
(2, 1, 2, 1, 0),
(3, 1, 3, 1, 0),
(4, 1, 4, 1, 0),
(5, 1, 5, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `brands`
--

CREATE TABLE `brands` (
  `brand_id` int(10) UNSIGNED NOT NULL,
  `brand_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `code_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `brand_details` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `brand_image` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `brands`
--

INSERT INTO `brands` (`brand_id`, `brand_name`, `code_name`, `brand_details`, `brand_image`, `created_at`, `updated_at`) VALUES
(1, 'No brand', 'no_brand', '', NULL, '2019-03-31 03:36:47', '2019-07-02 11:59:26'),
(2, 'INKA KOLA', 'inka_kola', '', '', '2021-01-22 16:15:41', NULL),
(3, 'COCA COLA', 'coca_cola', '', '', '2021-01-22 16:15:51', NULL),
(4, 'SAN FERNANDO', 'san_fernando', '', '', '2021-01-22 16:17:15', NULL),
(5, 'KOLA REAL', 'kola_real', '', '', '2021-01-22 16:17:50', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `brand_to_store`
--

CREATE TABLE `brand_to_store` (
  `b2s_id` int(10) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL DEFAULT 1,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `brand_to_store`
--

INSERT INTO `brand_to_store` (`b2s_id`, `brand_id`, `store_id`, `status`, `sort_order`) VALUES
(1, 1, 1, 1, 0),
(2, 0, 1, 1, 0),
(3, 0, 2, 1, 0),
(4, 2, 1, 1, 0),
(5, 2, 2, 1, 0),
(6, 3, 1, 1, 0),
(7, 3, 2, 1, 0),
(8, 4, 1, 1, 0),
(9, 4, 2, 1, 0),
(10, 5, 1, 1, 0),
(11, 5, 2, 1, 0),
(12, 1, 2, 1, 0),
(13, 1, 3, 1, 0),
(14, 1, 4, 1, 0),
(15, 1, 5, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorys`
--

CREATE TABLE `categorys` (
  `category_id` int(10) UNSIGNED NOT NULL,
  `category_name` varchar(60) CHARACTER SET utf8 NOT NULL,
  `category_slug` varchar(60) CHARACTER SET utf8 NOT NULL,
  `parent_id` int(10) DEFAULT NULL,
  `category_details` text CHARACTER SET utf8 DEFAULT NULL,
  `category_image` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `categorys`
--

INSERT INTO `categorys` (`category_id`, `category_name`, `category_slug`, `parent_id`, `category_details`, `category_image`, `created_at`, `updated_at`) VALUES
(1, 'uncategory', 'uncategory', 0, 'this is a category details, simple text will be display here', NULL, '2018-08-17 05:28:16', '2019-07-02 12:00:23'),
(2, 'CARNES', 'carnes', 0, 'TODO TIPO DE RES', '', '2021-01-21 14:59:08', NULL),
(3, 'ABARROTES', 'abarrotes', 0, '', '', '2021-01-21 14:59:28', NULL),
(4, 'ROPA', 'ropa', 0, '', '', '2021-01-21 15:03:51', NULL),
(5, 'CALZADO', 'calzado', 0, '', '', '2021-01-21 15:03:59', NULL),
(6, 'BEBIDAS', 'bebidas', 0, '', '', '2021-01-21 15:04:22', NULL),
(7, 'LICORERES', 'licoreres', 0, '', '', '2021-01-21 15:04:43', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `category_to_store`
--

CREATE TABLE `category_to_store` (
  `c2s_id` int(10) NOT NULL,
  `ccategory_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL DEFAULT 1,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `sort_order` int(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `category_to_store`
--

INSERT INTO `category_to_store` (`c2s_id`, `ccategory_id`, `store_id`, `status`, `sort_order`) VALUES
(1, 1, 1, 1, 0),
(2, 1, 2, 1, 0),
(3, 2, 1, 1, 0),
(4, 2, 2, 1, 0),
(5, 3, 1, 1, 0),
(6, 3, 2, 1, 0),
(7, 4, 1, 1, 0),
(8, 4, 2, 1, 0),
(9, 5, 1, 1, 0),
(10, 5, 2, 1, 0),
(11, 6, 1, 1, 0),
(12, 6, 2, 1, 0),
(13, 7, 1, 1, 0),
(14, 7, 2, 1, 0),
(15, 3, 3, 1, 0),
(16, 1, 3, 1, 0),
(17, 6, 3, 1, 0),
(18, 2, 3, 1, 0),
(19, 5, 3, 1, 0),
(20, 3, 4, 1, 0),
(21, 1, 4, 1, 0),
(22, 6, 4, 1, 0),
(23, 2, 4, 1, 0),
(24, 5, 4, 1, 0),
(25, 4, 3, 1, 0),
(26, 4, 4, 1, 0),
(27, 4, 5, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `currency`
--

CREATE TABLE `currency` (
  `currency_id` int(11) NOT NULL,
  `title` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `symbol_left` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `symbol_right` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `decimal_place` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `value` decimal(25,4) NOT NULL DEFAULT 1.0000,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `currency`
--

INSERT INTO `currency` (`currency_id`, `title`, `code`, `symbol_left`, `symbol_right`, `decimal_place`, `value`, `created_at`) VALUES
(1, 'United States Dollar', 'USD', '$', '', '2', '1.0000', '2018-09-19 14:40:00'),
(2, 'Pound Sterling', 'GBP', 'Â£', '', '2', '0.6125', '2018-09-19 14:40:00'),
(3, 'Euro', 'EUR', '', 'â‚¬', '2', '0.7846', '2018-09-19 14:40:00'),
(4, 'Hong Kong Dollar', 'HKD', 'HK$', '', '2', '7.8222', '2018-09-19 12:00:00'),
(5, 'Indian Rupee', 'INR', 'â‚¹', '', '2', '64.4000', '2018-09-19 12:00:00'),
(6, 'Russian Ruble', 'RUB', 'â‚½', '', '2', '56.4036', '2018-09-19 12:00:00'),
(7, 'Chinese Yuan Renminbi', 'CNY', 'Â¥', '', '2', '6.3451', '2018-09-19 12:00:00'),
(8, 'Australian Dollar', 'AUD', '$', '', '2', '1.2654', '2018-09-19 12:00:00'),
(9, 'Bangladeshi Taka', 'BDT', 'à§³', '', '3', '0.0000', '2018-09-29 05:25:00'),
(10, 'Pakistani Rupee ', 'PKR', 'â‚¨', '', '2', '0.0000', '2018-09-29 05:31:49'),
(11, 'Croatian Kuna', 'HRK', 'kn', '', '2', '0.0000', '2018-09-29 05:33:22'),
(12, 'Malaysian Ringgit', 'MYR', 'RM', '', '2', '0.0000', '2018-09-29 05:35:15'),
(13, 'Saudi riyal', 'SAR', 'SR', '', '2', '0.0000', '2018-09-29 05:35:57'),
(14, 'Canadian Dollar', 'CAD', 'CAD $', '', '2', '0.0000', '2018-09-29 05:37:54'),
(15, 'thank you are in setting', '233', '$', '', '1', '1.0000', '2019-04-17 16:48:00'),
(16, '900', '989', '+', '@', '2', '1.0000', '2019-06-19 21:47:08'),
(17, 'Soles', 'PEN', 'S/', '', '2', '1.0000', '2021-01-20 19:56:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `currency_to_store`
--

CREATE TABLE `currency_to_store` (
  `ca2s_id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL DEFAULT 1,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `currency_to_store`
--

INSERT INTO `currency_to_store` (`ca2s_id`, `currency_id`, `store_id`, `status`, `sort_order`) VALUES
(1, 1, 1, 1, 0),
(3, 3, 1, 1, 0),
(4, 4, 1, 1, 0),
(5, 5, 1, 1, 0),
(6, 6, 1, 1, 0),
(7, 7, 1, 1, 0),
(8, 9, 1, 1, 0),
(9, 2, 1, 1, 0),
(10, 10, 1, 1, 0),
(11, 11, 1, 1, 0),
(12, 12, 1, 1, 0),
(13, 13, 1, 1, 0),
(14, 14, 1, 1, 0),
(15, 15, 1, 1, 0),
(16, 8, 1, 1, 0),
(17, 16, 1, 1, 0),
(19, 17, 1, 1, 0),
(20, 17, 2, 1, 0),
(21, 17, 3, 1, 0),
(22, 17, 4, 1, 0),
(23, 17, 5, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(10) UNSIGNED NOT NULL,
  `customer_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `customer_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_mobile` varchar(14) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_address` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `customer_sex` tinyint(1) NOT NULL DEFAULT 1,
  `customer_age` int(10) UNSIGNED DEFAULT NULL,
  `gtin` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_city` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_state` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_country` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_giftcard` tinyint(1) NOT NULL DEFAULT 0,
  `password` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `raw_password` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `customers`
--

INSERT INTO `customers` (`customer_id`, `customer_name`, `customer_email`, `customer_mobile`, `customer_address`, `dob`, `customer_sex`, `customer_age`, `gtin`, `customer_city`, `customer_state`, `customer_country`, `is_giftcard`, `password`, `raw_password`, `created_at`, `updated_at`) VALUES
(1, 'JUAN DE LA VEGA', 'juan@grupoalegres.com', '93245684', 'AV. JUAN DE LA VEGA', '1993-01-01', 1, 20, '', 'AN', 'AN', 'PE', 0, 'c33367701511b4f6020ec61ded352059', '654321', '2018-04-29 14:18:37', '2019-07-02 13:50:11'),
(2, 'JOSE TORRES', '295e6d@gmail.com', '932145632', 'AV. JUAN DE LA VEGA', '1969-12-31', 1, 20, '', 'LIMA', 'PERUANO', 'PE', 0, NULL, NULL, '2021-01-24 22:14:30', NULL),
(3, 'ROBER CARLOS', '9aff1c@gmail.com', '931240141', 'Av. Micaela Bastidas', '1999-06-09', 1, 21, '', 'Lima', 'peruano', 'PE', 1, NULL, NULL, '2021-02-18 09:14:39', NULL),
(4, 'JUAN SANCHEZ', 'e45977@gmail.com', '9312548', 'LOS POSTALES ', '1989-07-12', 1, 28, '', 'LIMA', 'PERUANO', 'PE', 1, NULL, NULL, '2021-02-18 09:24:35', NULL),
(5, 'JEAN TORRES', 'b99d6c@gmail.com', '93256458', 'AV. UNIVERSITARIA', '1969-12-31', 1, 30, '', 'Lima', 'peruano', 'PE', 1, NULL, NULL, '2021-02-18 13:26:02', NULL),
(6, 'JEAN BERRIOS', 'MM9d6c@gmail.com', '985693245', 'AV. GENIES', '2021-03-18', 1, 25, '', 'Lima', 'peruano', 'AD', 0, NULL, NULL, '2021-02-18 13:27:08', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customer_to_store`
--

CREATE TABLE `customer_to_store` (
  `c2s_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL DEFAULT 1,
  `balance` decimal(25,4) UNSIGNED NOT NULL DEFAULT 0.0000,
  `due` decimal(25,4) UNSIGNED NOT NULL DEFAULT 0.0000,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `customer_to_store`
--

INSERT INTO `customer_to_store` (`c2s_id`, `customer_id`, `store_id`, `balance`, `due`, `status`, `sort_order`) VALUES
(1, 1, 1, '697.0000', '0.0000', 1, 1),
(2, 1, 2, '0.0000', '0.0000', 1, 0),
(3, 2, 1, '5000.0000', '0.0000', 1, 0),
(4, 2, 2, '0.0000', '0.0000', 1, 0),
(5, 1, 3, '0.0000', '0.0000', 1, 0),
(6, 1, 4, '0.0000', '0.0000', 1, 0),
(7, 1, 5, '0.0000', '0.0000', 1, 0),
(8, 3, 1, '492.5000', '0.2250', 1, 0),
(9, 3, 2, '0.0000', '0.0000', 1, 0),
(10, 3, 3, '0.0000', '0.0000', 1, 0),
(11, 3, 4, '0.0000', '0.0000', 1, 0),
(12, 3, 5, '0.0000', '0.0000', 1, 0),
(13, 2, 3, '0.0000', '0.0000', 1, 0),
(14, 2, 4, '0.0000', '0.0000', 1, 0),
(15, 2, 5, '0.0000', '0.0000', 1, 0),
(16, 4, 1, '233.8100', '0.0000', 1, 0),
(17, 4, 2, '0.0000', '0.0000', 1, 0),
(18, 4, 3, '0.0000', '0.0000', 1, 0),
(19, 4, 4, '0.0000', '0.0000', 1, 0),
(20, 4, 5, '0.0000', '0.0000', 1, 0),
(21, 5, 1, '600.0000', '0.0000', 1, 0),
(22, 5, 2, '0.0000', '0.0000', 1, 0),
(23, 5, 3, '0.0000', '0.0000', 1, 0),
(24, 5, 4, '0.0000', '0.0000', 1, 0),
(25, 5, 5, '0.0000', '0.0000', 1, 0),
(26, 6, 1, '0.0000', '0.0000', 1, 0),
(27, 6, 2, '0.0000', '0.0000', 1, 0),
(28, 6, 3, '0.0000', '0.0000', 1, 0),
(29, 6, 4, '0.0000', '0.0000', 1, 0),
(30, 6, 5, '0.0000', '0.0000', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customer_transactions`
--

CREATE TABLE `customer_transactions` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` enum('purchase','add_balance','substract_balance','due_paid','others') COLLATE utf8_unicode_ci NOT NULL,
  `reference_no` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ref_invoice_id` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `store_id` int(10) UNSIGNED NOT NULL,
  `pmethod_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `notes` text CHARACTER SET utf8 DEFAULT NULL,
  `amount` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `balance` decimal(25,4) DEFAULT 0.0000,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `customer_transactions`
--

INSERT INTO `customer_transactions` (`id`, `type`, `reference_no`, `ref_invoice_id`, `customer_id`, `store_id`, `pmethod_id`, `notes`, `amount`, `balance`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'add_balance', 'CT210218100001', NULL, 3, 1, 1, '', '500.0000', '500.0000', 1, '2021-02-18 09:14:40', NULL),
(2, 'add_balance', 'CT2102181002', NULL, 2, 1, 5, 'COMPRAS FAMILIARES', '5000.0000', '5000.0000', 1, '2021-02-18 09:19:38', NULL),
(3, 'add_balance', 'CT21021811003', NULL, 1, 1, 5, 'COPRAS', '1000.0000', '1000.0000', 1, '2021-02-18 09:20:44', NULL),
(4, 'add_balance', 'CT21021811004', NULL, 4, 1, 1, '', '800.0000', '800.0000', 1, '2021-02-18 09:24:36', NULL),
(5, 'add_balance', 'CT21021811005', NULL, 5, 1, 1, '', '600.0000', '600.0000', 1, '2021-02-18 13:26:02', NULL),
(6, 'substract_balance', 'CT210218100001', '12021/00000016', 3, 1, 4, 'Substract while selling', '7.5000', '492.5000', 1, '2021-02-18 13:28:36', NULL),
(7, 'substract_balance', 'CT210218100001', '12021/00000017', 1, 1, 4, 'Substract while selling', '100.0000', '900.0000', 1, '2021-02-18 13:30:48', NULL),
(8, 'substract_balance', 'CT210218100001', '12021/00000018', 1, 1, 4, 'Substract while selling', '100.0000', '800.0000', 1, '2021-02-18 13:32:20', NULL),
(9, 'substract_balance', 'CT210218100001', '12021/00000021', 1, 1, 4, 'Substract while selling', '103.0000', '697.0000', 1, '2021-02-18 17:22:59', NULL),
(10, 'substract_balance', 'CT210218100001', '12021/00000026', 4, 1, 4, 'Substract while selling', '354.4900', '445.5100', 1, '2021-02-18 19:07:46', NULL),
(11, 'substract_balance', 'CT210218100001', '12021/00000027', 4, 1, 4, 'Substract while selling', '211.7000', '233.8100', 1, '2021-02-18 19:08:19', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `expenses`
--

CREATE TABLE `expenses` (
  `id` int(10) UNSIGNED NOT NULL,
  `store_id` int(10) UNSIGNED NOT NULL,
  `reference_no` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `amount` decimal(25,4) NOT NULL,
  `returnable` enum('no','yes') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `note` longtext CHARACTER SET utf8 NOT NULL,
  `attachment` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(10) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `expense_categorys`
--

CREATE TABLE `expense_categorys` (
  `category_id` int(10) UNSIGNED NOT NULL,
  `category_name` varchar(60) CHARACTER SET utf8 NOT NULL,
  `category_slug` varchar(60) CHARACTER SET utf8 NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `category_details` longtext CHARACTER SET utf8 DEFAULT NULL,
  `sell_return` tinyint(1) NOT NULL DEFAULT 0,
  `sell_delete` tinyint(1) NOT NULL DEFAULT 0,
  `loan_delete` tinyint(1) NOT NULL DEFAULT 0,
  `loan_payment` tinyint(1) NOT NULL DEFAULT 0,
  `giftcard_sell_delete` tinyint(1) NOT NULL DEFAULT 0,
  `topup_delete` tinyint(1) NOT NULL DEFAULT 0,
  `product_purchase` tinyint(1) NOT NULL DEFAULT 0,
  `stock_transfer` tinyint(1) NOT NULL DEFAULT 0,
  `due_paid` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `is_hide` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(10) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `expense_categorys`
--

INSERT INTO `expense_categorys` (`category_id`, `category_name`, `category_slug`, `parent_id`, `category_details`, `sell_return`, `sell_delete`, `loan_delete`, `loan_payment`, `giftcard_sell_delete`, `topup_delete`, `product_purchase`, `stock_transfer`, `due_paid`, `status`, `is_hide`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Employee Salary', 'employee_salary', 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, '2019-02-16 17:21:53', '2019-07-02 12:08:08'),
(2, 'Showroom Rent', 'showroom_rent', 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, '2019-02-16 17:22:13', '2019-07-02 12:08:10'),
(3, 'Electricity Bill + Dish', 'electricity_bill_+_dish', 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, '2019-02-16 17:22:29', '2019-07-02 12:08:12'),
(4, 'Advertisements', 'advertisements', 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, '2019-02-16 17:23:16', '2019-07-02 12:08:16'),
(5, 'Bonous', 'bonous', 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, '2019-02-16 17:23:52', '2019-07-02 12:08:18'),
(6, 'Others', 'others', 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, '2019-02-16 17:24:23', '2019-07-02 12:08:19'),
(7, 'Returnable', 'returnable', 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, '2019-03-02 05:58:46', '2019-07-02 12:08:21'),
(8, 'Sell Return', 'sell_return', 0, '', 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, '2019-03-06 07:35:14', '2019-07-02 12:08:28'),
(9, 'Product Purchase', 'product_purchase', 0, '', 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0, '2019-03-06 07:35:38', '2019-07-02 12:08:30'),
(10, 'Sell Delete', 'sell_delete', 0, '', 0, 1, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, '2019-03-06 09:54:14', '2019-07-02 12:08:34'),
(11, 'Loan Delete', 'loan_delete', 0, '', 0, 0, 1, 0, 0, 0, 0, 0, 0, 1, 1, 0, '2019-03-06 07:02:41', '2019-07-02 12:08:36'),
(12, 'Giftcard Topup Delete', 'giftcard_topup_delete', 0, '', 0, 0, 0, 0, 0, 1, 0, 0, 0, 1, 1, 0, '2019-03-07 12:54:16', '2019-07-02 12:08:38'),
(13, 'Giftcard Sell Delete', 'giftcard_sell_delete', 0, '', 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 1, 0, '2019-03-07 13:03:24', '2019-07-02 12:08:41'),
(14, 'Loan Payment', 'loan_payment', 0, '', 0, 0, 0, 1, 0, 0, 0, 0, 0, 1, 1, 0, '2019-03-08 06:43:35', '2019-07-02 12:08:43'),
(15, 'Due Paid to Supplier', 'due_paid_to_supplier', 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 0, '2019-03-08 06:43:35', '2019-07-02 12:08:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gift_cards`
--

CREATE TABLE `gift_cards` (
  `id` int(11) NOT NULL,
  `date` datetime DEFAULT NULL,
  `card_no` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `value` decimal(25,4) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `customer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `balance` decimal(25,4) NOT NULL,
  `expiry` date DEFAULT NULL,
  `created_by` varchar(55) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `gift_cards`
--

INSERT INTO `gift_cards` (`id`, `date`, `card_no`, `value`, `customer_id`, `customer`, `balance`, `expiry`, `created_by`) VALUES
(1, NULL, '4877491338288210', '0.0000', 3, NULL, '2000.0000', '2022-02-24', '1'),
(2, NULL, '4803745341067230', '500.0000', 5, NULL, '4900.0000', '2022-02-25', '1'),
(3, NULL, '7140723188493114', '5000.0000', 4, NULL, '4649.0100', '2022-03-05', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gift_card_topups`
--

CREATE TABLE `gift_card_topups` (
  `id` int(11) NOT NULL,
  `date` datetime DEFAULT NULL,
  `card_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `amount` decimal(15,4) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `gift_card_topups`
--

INSERT INTO `gift_card_topups` (`id`, `date`, `card_id`, `amount`, `created_by`) VALUES
(1, '2021-02-18 09:27:25', '4877491338288210', '2000.0000', 1),
(2, '2021-02-18 13:41:57', '4803745341067230', '5000.0000', 1),
(3, '2021-02-18 19:06:40', '7140723188493114', '5000.0000', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `holding_info`
--

CREATE TABLE `holding_info` (
  `info_id` int(10) NOT NULL,
  `store_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `order_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ref_no` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `customer_mobile` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `invoice_note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `total_items` smallint(6) DEFAULT NULL,
  `created_by` int(10) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `holding_info`
--

INSERT INTO `holding_info` (`info_id`, `store_id`, `order_title`, `ref_no`, `customer_id`, `customer_mobile`, `invoice_note`, `total_items`, `created_by`, `created_at`, `updated_at`) VALUES
(3, 1, 'CASA DE JUAN', 'ab4250', 4, '9312548', '', 1, 1, '2021-02-18 09:25:24', NULL),
(4, 1, 'dddddddddd DE JUAN', 'b504e5', 1, '93245684', '', 1, 1, '2021-02-18 19:21:51', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `holding_item`
--

CREATE TABLE `holding_item` (
  `id` int(10) NOT NULL,
  `ref_no` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `store_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `item_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `brand_id` int(10) DEFAULT NULL,
  `sup_id` int(10) NOT NULL DEFAULT 0,
  `item_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `item_price` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `item_discount` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `item_tax` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `tax_method` enum('inclusive','exclusive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'exclusive',
  `taxrate_id` int(10) UNSIGNED NOT NULL,
  `tax` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gst` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cgst` decimal(25,4) DEFAULT NULL,
  `sgst` decimal(25,4) DEFAULT NULL,
  `igst` decimal(25,4) DEFAULT NULL,
  `item_quantity` int(10) UNSIGNED NOT NULL,
  `item_total` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `holding_item`
--

INSERT INTO `holding_item` (`id`, `ref_no`, `store_id`, `item_id`, `category_id`, `brand_id`, `sup_id`, `item_name`, `item_price`, `item_discount`, `item_tax`, `tax_method`, `taxrate_id`, `tax`, `gst`, `cgst`, `sgst`, `igst`, `item_quantity`, `item_total`, `created_at`) VALUES
(3, 'ab4250', 1, 7, 3, 0, 6, 'TARI', '2.5000', '0.0000', '0.4500', 'inclusive', 2, '18.0000', '18.0000', '0.0000', '0.0000', '0.4500', 1, '2.5000', NULL),
(4, 'b504e5', 1, 2, 6, 0, 2, 'INKA KOLA', '7.5000', '0.0000', '1.3500', 'inclusive', 2, '18.0000', '18.0000', '0.6750', '0.6750', '0.0000', 1, '7.5000', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `holding_price`
--

CREATE TABLE `holding_price` (
  `price_id` int(10) NOT NULL,
  `ref_no` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `store_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `subtotal` decimal(25,4) DEFAULT 0.0000,
  `discount_type` enum('plain','percentage') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'plain',
  `discount_amount` decimal(25,4) DEFAULT 0.0000,
  `item_tax` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `order_tax` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `cgst` decimal(25,4) DEFAULT NULL,
  `sgst` decimal(25,4) DEFAULT NULL,
  `igst` decimal(25,4) DEFAULT NULL,
  `shipping_type` enum('plain','percentage') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'plain',
  `shipping_amount` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `others_charge` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `payable_amount` decimal(25,4) DEFAULT 0.0000
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `holding_price`
--

INSERT INTO `holding_price` (`price_id`, `ref_no`, `store_id`, `subtotal`, `discount_type`, `discount_amount`, `item_tax`, `order_tax`, `cgst`, `sgst`, `igst`, `shipping_type`, `shipping_amount`, `others_charge`, `payable_amount`) VALUES
(3, 'ab4250', 1, '2.5000', 'plain', '0.0000', '0.4500', '0.0000', '0.0000', '0.0000', '0.4500', 'plain', '0.0000', '0.0000', '2.5000'),
(4, 'b504e5', 1, '7.5000', 'plain', '0.0000', '1.3500', '0.0000', '0.6750', '0.6750', '0.0000', 'plain', '0.0000', '0.0000', '7.5000');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `income_sources`
--

CREATE TABLE `income_sources` (
  `source_id` int(10) UNSIGNED NOT NULL,
  `source_name` varchar(60) CHARACTER SET utf8 NOT NULL,
  `type` enum('credit','debit') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'credit',
  `source_slug` varchar(60) CHARACTER SET utf8 NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `source_details` longtext CHARACTER SET utf8 DEFAULT NULL,
  `for_sell` tinyint(1) NOT NULL DEFAULT 0,
  `for_purchase_return` tinyint(1) NOT NULL DEFAULT 0,
  `for_due_collection` tinyint(1) NOT NULL DEFAULT 0,
  `for_loan` tinyint(1) NOT NULL DEFAULT 0,
  `for_giftcard_sell` tinyint(1) NOT NULL DEFAULT 0,
  `for_topup` tinyint(1) NOT NULL DEFAULT 0,
  `for_stock_transfer` tinyint(1) NOT NULL DEFAULT 0,
  `for_purchase_delete` tinyint(1) NOT NULL DEFAULT 0,
  `for_expense_delete` tinyint(1) NOT NULL DEFAULT 0,
  `profitable` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `show_in_income` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `is_hide` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(10) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `income_sources`
--

INSERT INTO `income_sources` (`source_id`, `source_name`, `type`, `source_slug`, `parent_id`, `source_details`, `for_sell`, `for_purchase_return`, `for_due_collection`, `for_loan`, `for_giftcard_sell`, `for_topup`, `for_stock_transfer`, `for_purchase_delete`, `for_expense_delete`, `profitable`, `show_in_income`, `status`, `is_hide`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Sell', 'credit', 'sell', 0, '', 1, 0, 0, 0, 0, 0, 0, 0, 0, 'yes', 'no', 1, 1, 0, '2019-02-27 04:54:07', '2019-04-29 01:58:28'),
(2, 'Purchase Return', 'credit', 'purchase_return', 0, '', 0, 1, 0, 0, 0, 0, 0, 0, 0, 'no', 'yes', 1, 1, 0, '2019-02-27 05:17:43', '2019-04-29 01:58:31'),
(3, 'Due Collection', 'credit', 'due_collection', 0, '', 0, 0, 1, 0, 0, 0, 0, 0, 0, 'yes', 'no', 1, 1, 0, '2019-03-01 08:04:58', '2019-04-29 01:58:34'),
(4, 'Others', 'credit', 'others', 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 'yes', 'yes', 1, 1, 0, '2019-02-17 06:22:46', '2019-07-02 12:11:05'),
(5, 'Loan Taken', 'credit', 'loan_taken', 0, '', 0, 0, 0, 1, 0, 0, 0, 0, 0, 'no', 'yes', 1, 1, 0, '2019-03-06 06:04:54', '2019-07-02 12:11:08'),
(6, 'Giftcard Sell', 'credit', 'giftcard_sell', 0, '', 0, 0, 0, 0, 1, 0, 0, 0, 0, 'no', 'yes', 1, 1, 0, '2019-03-07 12:53:43', '2019-07-02 12:11:11'),
(7, 'Giftcard Topup', 'credit', 'giftcard_topup', 0, '', 0, 0, 0, 0, 0, 1, 0, 0, 0, 'no', 'yes', 1, 1, 0, '2019-03-07 12:53:55', '2019-07-02 12:11:14'),
(8, 'Stock Transfer', 'credit', 'stock_transfer', 0, '', 0, 0, 0, 0, 0, 0, 1, 0, 0, 'no', 'yes', 1, 1, 0, '2019-03-08 04:14:39', '2019-07-02 12:11:17'),
(9, 'Purchase Delete', 'credit', 'purchase_delete', 0, '', 0, 0, 0, 0, 0, 0, 0, 1, 0, 'no', 'yes', 1, 1, 0, '2019-03-08 04:14:39', '2019-07-02 12:11:19'),
(10, 'Expense Delete', 'credit', 'expense_delete', 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 1, 'no', 'yes', 1, 1, 0, '2019-03-08 04:14:39', '2019-07-02 12:11:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `installment_orders`
--

CREATE TABLE `installment_orders` (
  `id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `invoice_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `duration` int(11) NOT NULL,
  `interval_count` int(11) NOT NULL,
  `installment_count` int(11) NOT NULL,
  `interest_percentage` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `interest_amount` decimal(25,2) NOT NULL DEFAULT 0.00,
  `initial_amount` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `payment_status` enum('paid','due') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'due',
  `last_installment_date` datetime DEFAULT NULL,
  `installment_end_date` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `installment_orders`
--

INSERT INTO `installment_orders` (`id`, `store_id`, `invoice_id`, `duration`, `interval_count`, `installment_count`, `interest_percentage`, `interest_amount`, `initial_amount`, `payment_status`, `last_installment_date`, `installment_end_date`, `created_at`) VALUES
(1, 1, '12021/00000016', 15, 30, 0, '3.0000', '0.22', '7.5000', 'due', '2021-02-18 13:28:36', '2021-03-05 13:28:36', '2021-02-18 13:28:36'),
(2, 1, '12021/00000017', 16, 30, 0, '0.0000', '0.00', '100.0000', 'due', '2021-02-18 13:30:48', '2021-03-06 13:30:48', '2021-02-18 13:30:48'),
(3, 1, '12021/00000021', 15, 30, 0, '3.0000', '3.00', '103.0000', 'due', '2021-02-18 17:22:59', '2021-03-05 17:22:59', '2021-02-18 17:22:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `installment_payments`
--

CREATE TABLE `installment_payments` (
  `id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `invoice_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `payment_date` datetime DEFAULT NULL,
  `pmethod_id` int(11) NOT NULL DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `capital` decimal(25,4) NOT NULL,
  `interest` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `payable` decimal(25,4) NOT NULL,
  `paid` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `due` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `payment_status` enum('paid','due','pending','cancel') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'due'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `installment_payments`
--

INSERT INTO `installment_payments` (`id`, `store_id`, `invoice_id`, `payment_date`, `pmethod_id`, `created_by`, `note`, `capital`, `interest`, `payable`, `paid`, `due`, `payment_status`) VALUES
(1, 1, '12021/00000016', '2021-02-18 13:28:37', 1, 1, 'Initial Payment', '4.8544', '0.2184', '7.5000', '7.5000', '0.0000', 'paid'),
(2, 1, '12021/00000017', '2021-02-18 13:30:49', 1, 1, 'Initial Payment', '80.0000', '0.0000', '100.0000', '100.0000', '0.0000', 'paid'),
(3, 1, '12021/00000021', '2021-02-18 17:23:01', 1, 1, 'Initial Payment', '80.0000', '3.0000', '103.0000', '103.0000', '0.0000', 'paid');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `languages`
--

CREATE TABLE `languages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `languages`
--

INSERT INTO `languages` (`id`, `name`, `slug`, `code`) VALUES
(1, 'English', 'english', 'en'),
(2, 'Arabic', 'arabic', 'ar'),
(3, 'Bangla', 'bangla', 'bn'),
(4, 'Hindi', 'hindi', 'hi'),
(5, 'French', 'french', 'fr'),
(6, 'Germany', 'Germany', 'de'),
(7, 'Spanish', 'spanish', 'es');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `language_translations`
--

CREATE TABLE `language_translations` (
  `id` int(10) NOT NULL,
  `lang_id` int(10) NOT NULL,
  `lang_key` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `key_type` enum('specific','default') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'specific',
  `lang_value` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `language_translations`
--

INSERT INTO `language_translations` (`id`, `lang_id`, `lang_key`, `key_type`, `lang_value`) VALUES
(1, 1, 'title_dashboard', 'specific', 'Dashboard'),
(2, 1, 'text_arabic', 'specific', 'Arabic'),
(3, 1, 'text_french', 'specific', 'French'),
(4, 1, 'text_germany', 'specific', 'Germany'),
(5, 1, 'text_spanish', 'specific', 'Spanish'),
(6, 1, 'text_pos', 'specific', 'POS'),
(7, 1, 'menu_pos', 'specific', 'POS'),
(8, 1, 'text_cashbook_report', 'specific', 'Cashbook Report'),
(9, 1, 'menu_cashbook', 'specific', 'CASHBOOK'),
(10, 1, 'text_invoice', 'specific', 'Invoice'),
(11, 1, 'menu_invoice', 'specific', 'INVOICE'),
(12, 1, 'text_user_preference', 'specific', 'User Preference'),
(13, 1, 'text_settings', 'specific', 'Settings'),
(14, 1, 'text_stock_alert', 'specific', 'Stock Alert'),
(15, 1, 'text_expired', 'specific', 'Expired'),
(16, 1, 'text_itsolution24', 'specific', 'ITsolution24'),
(17, 1, 'text_reports', 'specific', 'Reports'),
(18, 1, 'text_lockscreen', 'specific', 'Lockscreen'),
(19, 1, 'text_logout', 'specific', 'Logout'),
(20, 1, 'menu_dashboard', 'specific', 'DASHBOARD'),
(21, 1, 'menu_point_of_sell', 'specific', 'POINT OF SELL'),
(22, 1, 'menu_sell', 'specific', 'SELL'),
(23, 1, 'menu_sale_list', 'specific', 'SELL LIST'),
(24, 1, 'menu_return_list', 'specific', 'RETURN LIST'),
(25, 1, 'menu_giftcard', 'specific', 'GIFTCARD'),
(26, 1, 'menu_add_giftcard', 'specific', 'ADD GIFTCARD'),
(27, 1, 'menu_giftcard_list', 'specific', 'GIFTCARD LIST'),
(28, 1, 'menu_giftcard_topup', 'specific', 'GIFTCARD TOPUP'),
(29, 1, 'menu_quotation', 'specific', 'QUOTATION'),
(30, 1, 'menu_add_quotation', 'specific', 'ADD QUOTATION'),
(31, 1, 'menu_quotation_list', 'specific', 'QUOTATION LIST'),
(32, 1, 'menu_installment', 'specific', 'INSTALLMENT'),
(33, 1, 'menu_installment_list', 'specific', 'INSTALLMENT LIST'),
(34, 1, 'menu_payment_list', 'specific', 'PAYMENT LIST'),
(35, 1, 'menu_payment_due_today', 'specific', 'PAYMENT DUE TODAY'),
(36, 1, 'menu_payment_due_all', 'specific', 'PAYMENT DUE ALL'),
(37, 1, 'menu_payment_due_expired', 'specific', 'PAYMENT DUE EXP.'),
(38, 1, 'menu_overview_report', 'specific', 'OVERVIEW REPORT'),
(39, 1, 'menu_purchase', 'specific', 'PURCHASE'),
(40, 1, 'menu_add_purchase', 'specific', 'ADD PURCHASE'),
(41, 1, 'menu_purchase_list', 'specific', 'PURCHASE LIST'),
(42, 1, 'menu_due_invoice', 'specific', 'DUE INVOICE'),
(43, 1, 'menu_transfer', 'specific', 'STOCK TRANSFER'),
(44, 1, 'menu_add_transfer', 'specific', 'ADD TRANSFER'),
(45, 1, 'menu_transfer_list', 'specific', 'TRANSFER LIST'),
(46, 1, 'menu_product', 'specific', 'PRODUCT'),
(47, 1, 'menu_product_list', 'specific', 'PRODUCT LIST'),
(48, 1, 'menu_add_product', 'specific', 'ADD PRODUCT'),
(49, 1, 'menu_barcode_print', 'specific', 'BARCODE PRINT'),
(50, 1, 'menu_category', 'specific', 'CATEGORY LIST'),
(51, 1, 'menu_add_category', 'specific', 'ADD CATEGORY'),
(52, 1, 'menu_product_import', 'specific', 'IMPORT (.xls)'),
(53, 1, 'menu_stock_alert', 'specific', 'STOCK ALERT'),
(54, 1, 'menu_expired', 'specific', 'EXPIRED'),
(55, 1, 'menu_customer', 'specific', 'CUSTOMER'),
(56, 1, 'menu_add_customer', 'specific', 'ADD CUSTOMER'),
(57, 1, 'menu_customer_list', 'specific', 'CUSTOMER LIST'),
(58, 1, 'menu_transaction_list', 'specific', 'TRANSACTION LIST'),
(59, 1, 'menu_supplier', 'specific', 'SUPPLIER'),
(60, 1, 'menu_add_supplier', 'specific', 'ADD SUPPLIER'),
(61, 1, 'menu_supplier_list', 'specific', 'SUPPLIER LIST'),
(62, 1, 'menu_accounting', 'specific', 'ACCOUNTING'),
(63, 1, 'menu_new_deposit', 'specific', 'DEPOSIT'),
(64, 1, 'menu_new_withdraw', 'specific', 'WIDTHDRAW'),
(65, 1, 'menu_new_transfer', 'specific', 'ADD TRANSFER '),
(66, 1, 'menu_list_transfer', 'specific', 'TRANSFER LIST'),
(67, 1, 'menu_add_bank_account', 'specific', 'ADD BANK ACCOUNT'),
(68, 1, 'menu_bank_accounts', 'specific', 'BANK ACCOUNT LIST'),
(69, 1, 'menu_income_source', 'specific', 'INCOME SOURCE'),
(70, 1, 'menu_list_transactions', 'specific', 'TRANSACTION LIST'),
(71, 1, 'menu_balance_sheet', 'specific', 'BALANCE SHEET'),
(72, 1, 'menu_income_monthwise', 'specific', 'INCOME MONTHWISE'),
(73, 1, 'menu_income_and_expense', 'specific', 'INCOME VS EXPENSE'),
(74, 1, 'menu_profit_and_loss', 'specific', 'PROFIT VS LOSS'),
(75, 1, 'menu_expenditure', 'specific', 'EXPANDITURE'),
(76, 1, 'menu_create_expense', 'specific', 'ADD EXPENSE'),
(77, 1, 'menu_expense_list', 'specific', 'EXPENSE LIST'),
(78, 1, 'menu_expense_monthwise', 'specific', 'EXPENSE MONTHWISE'),
(79, 1, 'menu_summary', 'specific', 'SUMMARY'),
(80, 1, 'menu_loan_manager', 'specific', 'LOAN MANAGER'),
(81, 1, 'menu_take_loan', 'specific', 'TAKE LOAN'),
(82, 1, 'menu_loan_list', 'specific', 'LOAN LIST'),
(83, 1, 'menu_loan_summary', 'specific', 'SUMMARY'),
(84, 1, 'menu_reports', 'specific', 'REPORTS'),
(85, 1, 'menu_report_overview', 'specific', 'OVERVIEW REPORT'),
(86, 1, 'menu_report_collection', 'specific', 'COLLECTION REPORT'),
(87, 1, 'menu_report_due_collection', 'specific', 'DUE COLLECTION RPT'),
(88, 1, 'menu_report_due_paid', 'specific', 'DUE PAID RPT'),
(89, 1, 'menu_sell_report', 'specific', 'SELL REPORT'),
(90, 1, 'menu_purchase_report', 'specific', 'PURCHASE REPORT'),
(91, 1, 'menu_sell_payment_report', 'specific', 'SELL PAYMENT REPORT'),
(92, 1, 'menu_purchase_payment_report', 'specific', 'PUR. PAYMENT RPT.'),
(93, 1, 'menu_tax_report', 'specific', 'SELL TAX REPORT'),
(94, 1, 'menu_purchase_tax_report', 'specific', 'PURCHASE TAX RPT.'),
(95, 1, 'menu_tax_overview_report', 'specific', 'TAX OVERVIEW RPT.'),
(96, 1, 'menu_report_stock', 'specific', 'STOCK REPORT'),
(97, 1, 'menu_analytics', 'specific', 'ANALYTICS'),
(98, 1, 'menu_sms', 'specific', 'SMS'),
(99, 1, 'menu_send_sms', 'specific', 'SEND SMS'),
(100, 1, 'menu_sms_report', 'specific', 'SMS REPORT'),
(101, 1, 'menu_sms_setting', 'specific', 'SMS SETTING'),
(102, 1, 'menu_user', 'specific', 'USER'),
(103, 1, 'menu_add_user', 'specific', 'ADD USER'),
(104, 1, 'menu_user_list', 'specific', 'USER LIST'),
(105, 1, 'menu_add_usergroup', 'specific', 'ADD USERGROUP'),
(106, 1, 'menu_usergroup_list', 'specific', 'USERGROUP LIST'),
(107, 1, 'menu_password', 'specific', 'PASSWORD'),
(108, 1, 'menu_filemanager', 'specific', 'FILEMANAGER'),
(109, 1, 'menu_system', 'specific', 'SYSTEM'),
(110, 1, 'menu_store', 'specific', 'STORE'),
(111, 1, 'menu_create_store', 'specific', 'STORE CREATE'),
(112, 1, 'menu_store_list', 'specific', 'STORE LIST'),
(113, 1, 'menu_store_setting', 'specific', 'STORE SETTING'),
(114, 1, 'menu_receipt_template', 'specific', 'RECEIPT TEMPLATE'),
(115, 1, 'menu_user_preference', 'specific', 'USER PREFERENCE'),
(116, 1, 'menu_brand', 'specific', 'BRAND'),
(117, 1, 'menu_add_brand', 'specific', 'ADD BRAND'),
(118, 1, 'menu_brand_list', 'specific', 'BRAND LIST'),
(119, 1, 'menu_currency', 'specific', 'CURRENCY'),
(120, 1, 'menu_pmethod', 'specific', 'PAYMENT METHOD'),
(121, 1, 'menu_unit', 'specific', 'UNIT'),
(122, 1, 'menu_taxrate', 'specific', 'TAXRATE'),
(123, 1, 'menu_box', 'specific', 'BOX'),
(124, 1, 'menu_printer', 'specific', 'PRINTER'),
(125, 1, 'menu_backup_restore', 'specific', 'BACKUP/RESTORE'),
(126, 1, 'menu_store_change', 'specific', 'STORE CHANGE'),
(127, 1, 'text_dashboard', 'specific', 'Dashboard'),
(128, 1, 'text_old_dashboard', 'specific', 'Old Dashboard'),
(129, 1, 'button_pos', 'specific', 'POS'),
(130, 1, 'button_invoice', 'specific', 'Invoice'),
(131, 1, 'button_overview_report', 'specific', 'Overview Report'),
(132, 1, 'button_sell_report', 'specific', 'Sell Report'),
(133, 1, 'button_purchase_report', 'specific', 'Purchase Report'),
(134, 1, 'button_stock_alert', 'specific', 'Stock Alert'),
(135, 1, 'button_expired_alert', 'specific', 'Expired'),
(136, 1, 'button_backup_restore', 'specific', 'Backup/Restore'),
(137, 1, 'button_stores', 'specific', 'Stores'),
(138, 1, 'text_total_invoice', 'specific', 'TOTAL INVOICE'),
(139, 1, 'text_total_invoice_today', 'specific', 'TOTAL INVOICE TODAY'),
(140, 1, 'text_details', 'specific', 'Details'),
(141, 1, 'text_total_customer', 'specific', 'TOTAL CUSTOMER'),
(142, 1, 'text_total_customer_today', 'specific', 'TOTAL CUSTOMER TODAY'),
(143, 1, 'text_total_supplier', 'specific', 'TOTAL SUPPLIER'),
(144, 1, 'text_total_supplier_today', 'specific', 'TOTAL SUPPLIER TODAY'),
(145, 1, 'text_total_product', 'specific', 'TOTAL PRODUCT'),
(146, 1, 'text_total_product_today', 'specific', 'TOTAL PRODUCT TODAY'),
(147, 1, 'text_deposit_today', 'specific', 'Deposit Today'),
(148, 1, 'text_withdraw_today', 'specific', 'Widthdraw Today'),
(149, 1, 'text_total_deposit', 'specific', 'Total Deposit'),
(150, 1, 'text_total_withdraw', 'specific', 'Total Widthdraw'),
(151, 1, 'text_recent_deposit', 'specific', 'Recent Deposit'),
(152, 1, 'label_date', 'specific', 'Date'),
(153, 1, 'label_description', 'specific', 'Description'),
(154, 1, 'label_amount', 'specific', 'Amount'),
(155, 1, 'button_view_all', 'specific', 'View All'),
(156, 1, 'text_recent_withdraw', 'specific', 'Recent Withdraw'),
(157, 1, 'text_collection_report', 'specific', 'Collection Report'),
(158, 1, 'label_serial_no', 'specific', 'SL'),
(159, 1, 'label_username', 'specific', 'Username'),
(160, 1, 'label_total_inv', 'specific', 'Total Inv'),
(161, 1, 'label_net_amount', 'specific', 'Net Amount'),
(162, 1, 'label_prev_due_collection', 'specific', 'Prev. Due Col.'),
(163, 1, 'label_due_collection', 'specific', 'Due Collection'),
(164, 1, 'label_due_given', 'specific', 'Due GIven'),
(165, 1, 'label_received', 'specific', 'Received'),
(166, 1, 'text_sells_analytics', 'specific', 'Sell Analytics'),
(167, 1, 'text_version', 'specific', 'Version'),
(168, 1, 'button_today', 'specific', 'Today'),
(169, 1, 'button_last_7_days', 'specific', 'Last 7 Days'),
(170, 1, 'button_last_30_days', 'specific', 'Last 30 Days'),
(171, 1, 'button_last_365_days', 'specific', 'Last 365 Days'),
(172, 1, 'button_filter', 'specific', 'Filter'),
(173, 1, 'title_language', 'specific', 'Language'),
(174, 1, 'text_language_title', 'specific', 'Language'),
(175, 1, 'text_language_list_title', 'specific', 'Language Translate'),
(176, 1, 'label_key', 'specific', 'Key'),
(177, 1, 'label_value', 'specific', 'Value'),
(178, 1, 'label_translate', 'specific', 'Translate'),
(179, 1, 'button_translate', 'specific', 'Translate'),
(180, 1, 'title_quotation', 'specific', 'Quotation'),
(181, 1, 'text_quotation_title', 'specific', 'Quotation'),
(182, 1, 'text_add', 'specific', 'Add'),
(183, 1, 'text_new_quotation_title', 'specific', 'Add New Quotation'),
(184, 1, 'label_reference_no', 'specific', 'Ref. No.'),
(185, 1, 'label_note', 'specific', 'Notes'),
(186, 1, 'label_status', 'specific', 'Status'),
(187, 1, 'text_sent', 'specific', 'Sent'),
(188, 1, 'text_pending', 'specific', 'Pending'),
(189, 1, 'text_complete', 'specific', 'Complete'),
(190, 1, 'label_customer', 'specific', 'Customer'),
(191, 1, 'text_select', 'specific', '--- Please Select ---'),
(192, 1, 'label_supplier', 'specific', 'Supplier'),
(193, 1, 'text_all_suppliers', 'specific', 'All Supplier'),
(194, 1, 'label_add_product', 'specific', 'Add Product'),
(195, 1, 'placeholder_search_product', 'specific', 'Search Product'),
(196, 1, 'label_product', 'specific', 'Product'),
(197, 1, 'label_available', 'specific', 'Available'),
(198, 1, 'label_quantity', 'specific', 'Quantity'),
(199, 1, 'label_sell_price', 'specific', 'Sell Price'),
(200, 1, 'label_item_tax', 'specific', 'Item Tax'),
(201, 1, 'label_subtotal', 'specific', 'Subtotal'),
(202, 1, 'label_order_tax', 'specific', 'Order Tax'),
(203, 1, 'label_shipping_charge', 'specific', 'Shipping Charge'),
(204, 1, 'label_others_charge', 'specific', 'Other Charge'),
(205, 1, 'label_discount_amount', 'specific', 'Discount'),
(206, 1, 'label_payable_amount', 'specific', 'Payable Amount'),
(207, 1, 'button_save', 'specific', 'Save'),
(208, 1, 'button_reset', 'specific', 'Reset'),
(209, 1, 'text_quotation_list_title', 'specific', 'Quotation List'),
(210, 1, 'button_all', 'specific', 'All'),
(211, 1, 'button_sent', 'specific', 'Sent'),
(212, 1, 'button_pending', 'specific', 'Pending'),
(213, 1, 'button_complete', 'specific', 'Complete'),
(214, 1, 'label_biller', 'specific', 'Biller'),
(215, 1, 'label_total', 'specific', 'Total'),
(216, 1, 'label_action', 'specific', 'Action'),
(217, 1, 'text_translation_success', 'specific', 'Translation Successfull'),
(218, 1, 'title_purchase', 'specific', 'Purchase'),
(219, 1, 'text_purchase_title', 'specific', 'Purchase'),
(220, 1, 'text_new_purchase_title', 'specific', 'Add New Purchase'),
(221, 1, 'text_received', 'specific', 'Received'),
(222, 1, 'text_ordered', 'specific', 'Ordered'),
(223, 1, 'label_attachment', 'specific', 'Attachment'),
(224, 1, 'label_cost', 'specific', 'Cost'),
(225, 1, 'label_item_total', 'specific', 'Item Total'),
(226, 1, 'label_payment_method', 'specific', 'Payment Method'),
(227, 1, 'label_paid_amount', 'specific', 'Paid Amount'),
(228, 1, 'label_due_amount', 'specific', 'Due Amount'),
(229, 1, 'label_change_amount', 'specific', 'Change Amount'),
(230, 1, 'button_submit', 'specific', 'Submit'),
(231, 1, 'text_purchase_list_title', 'specific', 'Purchase List'),
(232, 1, 'button_today_invoice', 'specific', 'Today Invoice'),
(233, 1, 'button_all_invoice', 'specific', 'All Invoice'),
(234, 1, 'button_due_invoice', 'specific', 'Due Invoice'),
(235, 1, 'button_all_due_invoice', 'specific', 'All Due Invoice'),
(236, 1, 'button_paid_invoice', 'specific', 'Paid Invoice'),
(237, 1, 'button_inactive_invoice', 'specific', 'Inactive Invoice'),
(238, 1, 'label_datetime', 'specific', 'Date Time'),
(239, 1, 'label_invoice_id', 'specific', 'Invoice Id'),
(240, 1, 'label_creator', 'specific', 'Creator'),
(241, 1, 'label_invoice_paid', 'specific', 'Paid Amount'),
(242, 1, 'label_due', 'specific', 'Due'),
(243, 1, 'label_pay', 'specific', 'Pay'),
(244, 1, 'label_return', 'specific', 'Return'),
(245, 1, 'label_view', 'specific', 'View'),
(246, 1, 'label_edit', 'specific', 'Edit'),
(247, 1, 'title_language_translation', 'specific', 'Language Translate'),
(248, 1, 'title_invoice', 'specific', 'Invoice'),
(249, 1, 'text_invoice_title', 'specific', 'Invoice'),
(250, 1, 'text_invoice_sub_title', 'specific', 'Invoice List'),
(251, 1, 'label_customer_name', 'specific', 'Customer Name'),
(252, 1, 'label_invoice_amount', 'specific', 'Inv. Amount'),
(253, 1, 'label_invoice_due', 'specific', 'Invoice Due'),
(254, 1, 'title_transfer', 'specific', 'Transfer'),
(255, 1, 'text_transfer_title', 'specific', 'Transfer'),
(256, 1, 'text_add_transfer_title', 'specific', 'Add New Transfer'),
(257, 1, 'label_ref_no', 'specific', 'Ref. No.'),
(258, 1, 'label_from', 'specific', 'From'),
(259, 1, 'label_to', 'specific', 'To'),
(260, 1, 'text_stock_list', 'specific', 'Stock List'),
(261, 1, 'search', 'specific', 'Search'),
(262, 1, 'text_invoice_id', 'specific', 'Invoice Id'),
(263, 1, 'text_stock', 'specific', 'Stock'),
(264, 1, 'text_transfer_list', 'specific', 'Transfer List'),
(265, 1, 'label_item_name', 'specific', 'Item Name'),
(266, 1, 'label_transfer_qty', 'specific', 'Transfer Qty.'),
(267, 1, 'button_transfer_now', 'specific', 'Transfer Now'),
(268, 1, 'text_list_transfer_title', 'specific', 'Transfer List'),
(269, 1, 'label_from_store', 'specific', 'From Store'),
(270, 1, 'label_to_store', 'specific', 'To Store'),
(271, 1, 'label_total_item', 'specific', 'Total Item'),
(272, 1, 'label_total_quantity', 'specific', 'Total Quantity'),
(273, 1, 'text_success', 'specific', 'Successfully Created'),
(274, 1, 'title_product', 'specific', 'Product'),
(275, 1, 'text_products', 'specific', 'Products'),
(276, 1, 'text_add_new', 'specific', 'Add New Product'),
(277, 1, 'text_product', 'specific', 'Product'),
(278, 1, 'text_general', 'specific', 'General'),
(279, 1, 'text_image', 'specific', 'Image'),
(280, 1, 'label_image', 'specific', 'Image'),
(281, 1, 'label_url', 'specific', 'Url'),
(282, 1, 'label_sort_order', 'specific', 'Order'),
(283, 1, 'label_thumbnail', 'specific', 'Thumbnail'),
(284, 1, 'label_product_type', 'specific', 'Product Type'),
(285, 1, 'text_standard', 'specific', 'Standard'),
(286, 1, 'text_service', 'specific', 'Service'),
(287, 1, 'label_name', 'specific', 'Name'),
(288, 1, 'label_pcode', 'specific', 'P. Code'),
(289, 1, 'label_category', 'specific', 'Category'),
(290, 1, 'label_brand', 'specific', 'Brand'),
(291, 1, 'label_barcode_symbology', 'specific', 'Barcode Symbiology'),
(292, 1, 'label_box', 'specific', 'Box'),
(293, 1, 'label_expired_date', 'specific', 'Expired Date'),
(294, 1, 'label_unit', 'specific', 'Unit'),
(295, 1, 'label_product_cost', 'specific', 'Product Cost'),
(296, 1, 'label_product_price', 'specific', 'Product Price'),
(297, 1, 'label_product_tax', 'specific', 'Product Tax'),
(298, 1, 'label_tax_method', 'specific', 'Tax Method'),
(299, 1, 'text_inclusive', 'specific', 'Inclusive'),
(300, 1, 'text_exclusive', 'specific', 'Exclusive'),
(301, 1, 'label_store', 'specific', 'Store'),
(302, 1, 'label_alert_quantity', 'specific', 'Alert Quantity'),
(303, 1, 'text_active', 'specific', 'Active'),
(304, 1, 'text_inactive', 'specific', 'Inactive'),
(305, 1, 'text_view_all', 'specific', 'View All'),
(306, 1, 'label_all_product', 'specific', 'All Product'),
(307, 1, 'button_trash', 'specific', 'Trash'),
(308, 1, 'button_bulk', 'specific', 'Bulk Action'),
(309, 1, 'button_delete_all', 'specific', 'Delete All'),
(310, 1, 'label_stock', 'specific', 'Stock'),
(311, 1, 'label_purchase_price', 'specific', 'Purchase Price'),
(312, 1, 'label_selling_price', 'specific', 'Selling Price'),
(313, 1, 'label_purchase', 'specific', 'Purchase'),
(314, 1, 'label_print_barcode', 'specific', 'Print Barcode'),
(315, 1, 'button_view', 'specific', 'View'),
(316, 1, 'button_edit', 'specific', 'Edit'),
(317, 1, 'button_purchase_product', 'specific', 'Purchase Product'),
(318, 1, 'button_barcode', 'specific', 'Barcode'),
(319, 1, 'button_delete', 'specific', 'Delete'),
(320, 1, 'error_no_selected', 'specific', 'Not Selected'),
(321, 1, 'success_delete_all', 'specific', 'Successfully Deleted'),
(322, 1, 'text_language_translation_title', 'specific', 'Language Translate'),
(323, 1, 'title_user_preference', 'specific', 'User Preference'),
(324, 1, 'text_user_preference_title', 'specific', 'User Preference'),
(325, 1, 'text_language_preference_title', 'specific', 'Language Translate'),
(326, 1, 'label_select_language', 'specific', 'Select Language'),
(327, 1, 'text_english', 'specific', 'English'),
(328, 1, 'text_color_preference_title', 'specific', 'Color Preference'),
(329, 1, 'label_base_color', 'specific', 'Base color'),
(330, 1, 'text_color_black', 'specific', 'Black'),
(331, 1, 'text_color_blue', 'specific', 'Blue'),
(332, 1, 'text_color_green', 'specific', 'Green'),
(333, 1, 'text_color_red', 'specific', 'Red'),
(334, 1, 'text_color_yellow', 'specific', 'Yellow'),
(335, 1, 'text_pos_side_panel_position_title', 'specific', 'POS Side Panel Position'),
(336, 1, 'label_pos_side_panel_position', 'specific', 'Pos Side Panel Position'),
(337, 1, 'text_right', 'specific', 'Right'),
(338, 1, 'text_left', 'specific', 'Left'),
(339, 1, 'text_pos_pattern_title', 'specific', 'POS Pattern'),
(340, 1, 'label_select_pos_pattern', 'specific', 'Select POS Pattern'),
(341, 1, 'button_update', 'specific', 'Update'),
(342, 1, 'text_login_title', 'specific', 'Login'),
(343, 1, 'text_login', 'specific', 'Login'),
(344, 1, 'button_sign_in', 'specific', 'Sign In'),
(345, 1, 'text_forgot_password', 'specific', 'Forgot Password?'),
(346, 1, 'title_forgot_password', 'specific', 'Forgot Password'),
(347, 1, 'text_forgot_password_instruction', 'specific', 'Please type your email.  We will send you a link just follow the steps.'),
(348, 1, 'button_close', 'specific', 'Close'),
(349, 1, 'title_receipt_template', 'specific', 'Receipt Template'),
(350, 1, 'text_receipt_tempalte_title', 'specific', 'Receipt Template'),
(351, 1, 'title_pos_setting', 'specific', 'POS Settings'),
(352, 1, 'text_receipt_template', 'specific', 'Receipt Template'),
(353, 1, 'text_receipt_tempalte_sub_title', 'specific', 'Receipt Template'),
(354, 1, 'button_preview', 'specific', 'Preview'),
(355, 1, 'text_tempalte_content_title', 'specific', 'Template Content'),
(356, 1, 'text_tempalte_css_title', 'specific', 'Template CSS'),
(357, 1, 'text_template_tags', 'specific', 'Template Tags'),
(358, 1, 'text_translations', 'specific', 'Translations'),
(359, 1, 'text_bangla', 'specific', 'Bangla'),
(360, 1, 'menu_language', 'specific', 'LANGUAGE'),
(361, 1, 'button_default', 'specific', 'Default'),
(362, 1, 'text_paid', 'specific', 'Paid'),
(363, 1, 'button_return', 'specific', 'Return'),
(364, 1, 'button_view_receipt', 'specific', 'View Receipt'),
(365, 1, 'label_delete', 'default', 'Delete'),
(366, 1, 'button_dublicate_entry', 'specific', 'Dublicate Entry'),
(367, 1, 'text_delete_success', 'specific', 'Successfully Deleted'),
(368, 1, 'label_delete', 'specific', 'Delete'),
(369, 1, 'text_customer_list_title', 'specific', 'Customer List'),
(370, 1, 'text_customer_title', 'specific', 'Customer'),
(371, 1, 'text_new_customer_title', 'specific', 'Add New Customer'),
(372, 1, 'label_phone', 'specific', 'Phone'),
(373, 1, 'label_date_of_birth', 'specific', 'Date of Birth'),
(374, 1, 'label_email', 'specific', 'Email'),
(375, 1, 'label_sex', 'specific', 'Sex'),
(376, 1, 'label_male', 'specific', 'Male'),
(377, 1, 'label_female', 'specific', 'Female'),
(378, 1, 'label_others', 'specific', 'Others'),
(379, 1, 'label_age', 'specific', 'Age'),
(380, 1, 'label_address', 'specific', 'Address'),
(381, 1, 'label_city', 'specific', 'City'),
(382, 1, 'label_state', 'specific', 'State'),
(383, 1, 'label_country', 'specific', 'Country'),
(384, 1, 'label_id', 'specific', 'Id'),
(385, 1, 'label_balance', 'specific', 'Balance'),
(386, 1, 'label_sell', 'specific', 'Sell'),
(387, 1, 'button_sell', 'specific', 'Sell'),
(388, 1, 'button_view_profile', 'specific', 'View Profile'),
(389, 1, 'login_success', 'specific', 'Login Successfull'),
(390, 1, 'title_installment_payment', 'specific', 'Installment Payment'),
(391, 1, 'text_installment_payment_title', 'specific', 'Installment Payment'),
(392, 1, 'text_installment', 'specific', 'Installment'),
(393, 1, 'text_installment_payment_list_title', 'specific', 'Installment Payment List'),
(394, 1, 'text_all_payment', 'specific', 'All Payment'),
(395, 1, 'button_all_payment', 'specific', 'All Payment'),
(396, 1, 'button_todays_due_payment', 'specific', 'Todays Due Payment'),
(397, 1, 'button_all_due_payment', 'specific', 'All Due Payment'),
(398, 1, 'button_expired_due_payment', 'specific', 'Expired Due Payment'),
(399, 1, 'label_payment_date', 'specific', 'Payment Date'),
(400, 1, 'label_payable', 'specific', 'Payable'),
(401, 1, 'label_paid', 'specific', 'Paid'),
(402, 1, 'button_payment', 'specific', 'Payment'),
(403, 1, 'title_backup_restore', 'specific', 'Backup/Restore'),
(404, 1, 'text_backup_restore_title', 'specific', 'Backup/Restore'),
(405, 1, 'text_backup', 'specific', 'Backup'),
(406, 1, 'text_restore', 'specific', 'Restore'),
(407, 1, 'label_databases', 'specific', 'Databases'),
(408, 1, 'label_select_all', 'specific', 'Select All'),
(409, 1, 'label_unselect_all', 'specific', 'Unselect All'),
(410, 1, 'button_export', 'specific', 'Export'),
(411, 1, 'label_progress', 'specific', 'Progress'),
(412, 1, 'button_select_sql_file', 'specific', 'Select .sql File'),
(413, 1, 'title_printer', 'specific', 'Printer'),
(414, 1, 'text_printer_title', 'specific', 'Printer'),
(415, 1, 'text_new_printer_title', 'specific', 'Add New Printer'),
(416, 1, 'label_title', 'specific', 'Title'),
(417, 1, 'label_type', 'specific', 'Type'),
(418, 1, 'label_char_per_line', 'specific', 'Char Per Line'),
(419, 1, 'label_path', 'specific', 'Path'),
(420, 1, 'label_ip_address', 'specific', 'Ip Address'),
(421, 1, 'label_port', 'specific', 'Port'),
(422, 1, 'text_printer_list_title', 'specific', 'Printer List'),
(423, 1, 'title_box', 'specific', 'Box'),
(424, 1, 'text_box_title', 'specific', 'Box'),
(425, 1, 'text_box_box_title', 'specific', 'Box '),
(426, 1, 'label_box_name', 'specific', 'Box Name'),
(427, 1, 'label_code_name', 'specific', 'Code Name'),
(428, 1, 'label_box_details', 'specific', 'Box Details'),
(429, 1, 'text_in_active', 'specific', 'Inactive'),
(430, 1, 'text_box_list_title', 'specific', 'Box List'),
(431, 1, 'title_taxrate', 'specific', 'Taxrate'),
(432, 1, 'text_taxrate_title', 'specific', 'Taxrate'),
(433, 1, 'text_new_taxrate_title', 'specific', 'Add New Taxrate'),
(434, 1, 'label_taxrate_name', 'specific', 'Taxrate Name'),
(435, 1, 'label_taxrate', 'specific', 'Taxrate'),
(436, 1, 'text_taxrate_list_title', 'specific', 'Taxrate List'),
(437, 1, 'title_unit', 'specific', 'Unit'),
(438, 1, 'text_unit_title', 'specific', 'Unit'),
(439, 1, 'text_new_unit_title', 'specific', 'Add New Unit'),
(440, 1, 'label_unit_name', 'specific', 'Unit Name'),
(441, 1, 'label_unit_details', 'specific', 'Unit Details'),
(442, 1, 'text_unit_list_title', 'specific', 'Unit List'),
(443, 1, 'title_pmethod', 'specific', 'Payment Mehtod'),
(444, 1, 'text_pmethod_title', 'specific', 'Payment Method'),
(445, 1, 'text_new_pmethod_title', 'specific', 'Add New Payment Method'),
(446, 1, 'label_details', 'specific', 'Details'),
(447, 1, 'text_pmethod_list_title', 'specific', 'Payment Method List'),
(448, 1, 'title_currency', 'specific', 'Currency'),
(449, 1, 'text_currency_title', 'specific', 'Currency'),
(450, 1, 'text_new_currency_title', 'specific', 'Add New Currency'),
(451, 1, 'label_code', 'specific', 'Code'),
(452, 1, 'hint_code', 'specific', 'Code name here'),
(453, 1, 'label_symbol_left', 'specific', 'Symbol Left'),
(454, 1, 'hint_symbol_left', 'specific', 'It will display in Left side'),
(455, 1, 'label_symbol_right', 'specific', 'Symbol Right'),
(456, 1, 'hint_symbol_right', 'specific', 'It will display in right  side'),
(457, 1, 'label_decimal_place', 'specific', 'Decimal  Place'),
(458, 1, 'hint_decimal_place', 'specific', 'It indicates number of digit after ponts. I.E.  100.00'),
(459, 1, 'text_currency_list_title', 'specific', 'Currency List'),
(460, 1, 'text_enabled', 'specific', 'Enabled'),
(461, 1, 'button_activate', 'specific', 'Active'),
(462, 1, 'button_activated', 'specific', 'Activated'),
(463, 1, 'text_brand_list_title', 'specific', 'Brand List'),
(464, 1, 'text_brand_title', 'specific', 'Brand'),
(465, 1, 'text_new_brand_title', 'specific', 'Brand'),
(466, 1, 'label_total_product', 'specific', 'Total Product'),
(467, 1, 'title_create_store', 'specific', 'Create Store'),
(468, 1, 'text_create_store_title', 'specific', 'Create Store'),
(469, 1, 'text_stores', 'specific', 'Stores'),
(470, 1, 'text_currency', 'specific', 'Currency'),
(471, 1, 'text_payment_method', 'specific', 'Paymen Method'),
(472, 1, 'text_printer', 'specific', 'Printer'),
(473, 1, 'text_email_setting', 'specific', 'Email Setting'),
(474, 1, 'text_ftp_setting', 'specific', 'FTP Setting'),
(475, 1, 'label_mobile', 'specific', 'Mobile'),
(476, 1, 'label_zip_code', 'specific', 'Zip Code'),
(477, 1, 'label_vat_reg_no', 'specific', 'VAT Reg. No.'),
(478, 1, 'label_cashier_name', 'specific', 'Cashier Name'),
(479, 1, 'label_timezone', 'specific', 'Timezone'),
(480, 1, 'label_invoice_edit_lifespan', 'specific', 'Invoice Edit Lifespan'),
(481, 1, 'hint_invoice_edit_lifespan', 'specific', 'After this time you won\'t be able to edit invoice.'),
(482, 1, 'text_minute', 'specific', 'Minute'),
(483, 1, 'text_second', 'specific', 'Second'),
(484, 1, 'label_invoice_delete_lifespan', 'specific', 'Invoice Delete Lifespan'),
(485, 1, 'hint_invoice_delete_lifespan', 'specific', 'After this time you won\'t be able to delete invoice.'),
(486, 1, 'label_after_sell_page', 'specific', 'After Sell Page'),
(487, 1, 'hint_after_sell_page', 'specific', 'After Sell Page'),
(488, 1, 'label_pos_printing', 'specific', 'POS Printing'),
(489, 1, 'label_receipt_printer', 'specific', 'Receipt Printer'),
(490, 1, 'label_auto_print_receipt', 'specific', 'Auto Print Receipt'),
(491, 1, 'label_deposit_account', 'specific', 'Deposit Account'),
(492, 1, 'label_tax', 'specific', 'TAX'),
(493, 1, 'hint_tax', 'specific', 'Tax'),
(494, 1, 'label_stock_alert_quantity', 'specific', 'Stock Alert Quantity'),
(495, 1, 'hint_stock_alert_quantity', 'specific', 'If quantity reach this value so it will be alert as stock low alert'),
(496, 1, 'label_datatable_item_limit', 'specific', 'Datatable Item Limit'),
(497, 1, 'hint_datatable_item_limit', 'specific', 'It indicates how many row you will show in any table'),
(498, 1, 'label_invoice_footer_text', 'specific', 'Invoice Footer Text'),
(499, 1, 'hint_invoice_footer_text', 'specific', 'This will display in footer of invoice'),
(500, 1, 'label_sound_effect', 'specific', 'Sound Effect'),
(501, 1, 'label_email_from', 'specific', 'Email From'),
(502, 1, 'hint_email_from', 'specific', 'Email From'),
(503, 1, 'label_email_address', 'specific', 'Email Address'),
(504, 1, 'hint_email_address', 'specific', 'Email Addrress'),
(505, 1, 'label_email_driver', 'specific', 'Email Driver'),
(506, 1, 'hint_email_driver', 'specific', 'Email Driver'),
(507, 1, 'label_smtp_host', 'specific', 'SMTP Host'),
(508, 1, 'hint_smtp_host', 'specific', 'SMTP Host'),
(509, 1, 'label_smtp_username', 'specific', 'SMTP Username'),
(510, 1, 'hint_smtp_username', 'specific', 'SMTP Username'),
(511, 1, 'label_smtp_password', 'specific', 'SMTP Password'),
(512, 1, 'hint_smtp_password', 'specific', 'SMTP Password'),
(513, 1, 'label_smtp_port', 'specific', 'SMTP Port'),
(514, 1, 'hint_smtp_port', 'specific', 'SMTP Port'),
(515, 1, 'label_ssl_tls', 'specific', 'SSL/TLS'),
(516, 1, 'hint_ssl_tls', 'specific', 'SSL/TLS'),
(517, 1, 'label_ftp_hostname', 'specific', 'FTP Hostname'),
(518, 1, 'label_ftp_username', 'specific', 'FTP Username'),
(519, 1, 'label_ftp_password', 'specific', 'FTP Password'),
(520, 1, 'button_back', 'specific', 'Back'),
(521, 1, 'title_store', 'specific', 'Store'),
(522, 1, 'text_store_title', 'specific', 'Store'),
(523, 1, 'text_store_list_title', 'specific', 'Store List'),
(524, 1, 'label_created_at', 'specific', 'Created At'),
(525, 1, 'title_edit_store', 'specific', 'Edit Store'),
(526, 1, 'text_title', 'specific', 'Title'),
(527, 1, 'text_pos_setting', 'specific', 'POS Setting'),
(528, 1, 'label_gst_reg_no', 'specific', 'GST Reg. No.'),
(529, 1, 'label_sms_gateway', 'specific', 'SMS Gateway'),
(530, 1, 'hint_sms_gateway', 'specific', 'SMS Gateway like clickatell, 91sms'),
(531, 1, 'label_sms_alert', 'specific', 'SMS Alert'),
(532, 1, 'hint_sms_alert', 'specific', 'SMS Alert'),
(533, 1, 'text_yes', 'specific', 'Yes'),
(534, 1, 'text_no', 'specific', 'No.'),
(535, 1, 'label_auto_sms', 'specific', 'Auto SMS'),
(536, 1, 'text_sms_after_creating_invoice', 'specific', 'SMS After Create Invoice'),
(537, 1, 'label_expiration_system', 'specific', 'Expiration System'),
(538, 1, 'label_invoice_prefix', 'specific', 'Invoive Prefix'),
(539, 1, 'label_receipt_template', 'specific', 'Receipt Template'),
(540, 1, 'label_invoice_view', 'specific', 'Invoice View'),
(541, 1, 'hint_invoice_view', 'specific', 'Invoice View'),
(542, 1, 'text_tax_invoice', 'specific', 'Tax Invoice'),
(543, 1, 'text_indian_gst', 'specific', 'Indian GST'),
(544, 1, 'label_change_item_price_while_billing', 'specific', 'Change Price when Billing'),
(545, 1, 'hint_change_item_price_while_billing', 'specific', 'You will be able to edit sell price when you will create a invoice.'),
(546, 1, 'label_pos_product_display_limit', 'specific', 'POS Product Display Limit'),
(547, 1, 'hint_pos_product_display_limit', 'specific', 'Number of product that will display in POS'),
(548, 1, 'label_send_mail_path', 'specific', 'Send Mail Path'),
(549, 1, 'hint_send_mail_path', 'specific', 'Type send mail path here'),
(550, 1, 'text_logo', 'specific', 'Logo'),
(551, 1, 'label_logo_size', 'specific', 'Logo Size'),
(552, 1, 'button_upload', 'specific', 'Upload'),
(553, 1, 'text_favicon', 'specific', 'Favicon'),
(554, 1, 'label_favicon_size', 'specific', 'Favicon Size'),
(555, 1, 'title_user', 'specific', 'User'),
(556, 1, 'text_user_title', 'specific', 'User'),
(557, 1, 'text_new_user_title', 'specific', 'Add New User'),
(558, 1, 'label_password', 'specific', 'Password'),
(559, 1, 'label_password_retype', 'specific', 'Retype Password'),
(560, 1, 'label_group', 'specific', 'Group'),
(561, 1, 'hint_group', 'specific', 'Group name here'),
(562, 1, 'text_user_list_title', 'specific', 'User List'),
(563, 1, 'label_profile', 'specific', 'Profile'),
(564, 1, 'title_user_group', 'specific', 'Usergroup'),
(565, 1, 'text_group_title', 'specific', 'User Group'),
(566, 1, 'text_new_group_title', 'specific', 'Add New Usergroup'),
(567, 1, 'label_slug', 'specific', 'Slug'),
(568, 1, 'text_group_list_title', 'specific', 'User Group List'),
(569, 1, 'label_total_user', 'specific', 'Total User'),
(570, 1, 'title_password', 'specific', 'Password'),
(571, 1, 'text_password_title', 'specific', 'Password'),
(572, 1, 'text_password_box_title', 'specific', 'Password'),
(573, 1, 'label_password_user', 'specific', 'Password'),
(574, 1, 'label_password_new', 'specific', 'New Password'),
(575, 1, 'label_password_confirm', 'specific', 'Password Confirm'),
(576, 1, 'title_send_sms', 'specific', 'Send SMS'),
(577, 1, 'text_sms_title', 'specific', 'SMS'),
(578, 1, 'text_send_sms', 'specific', 'Send SMS'),
(579, 1, 'text_send_sms_title', 'specific', 'Send SMS'),
(580, 1, 'text_event_sms', 'specific', 'Event SMS'),
(581, 1, 'text_single', 'specific', 'Single'),
(582, 1, 'text_group', 'specific', 'User Group'),
(583, 1, 'label_sms_for', 'specific', 'SMS For'),
(584, 1, 'text_birthday', 'specific', 'Birthday'),
(585, 1, 'label_people_type', 'specific', 'People Type'),
(586, 1, 'text_all_customer', 'specific', 'All Customer'),
(587, 1, 'text_all_user', 'specific', 'All User'),
(588, 1, 'label_people', 'specific', 'People'),
(589, 1, 'label_message', 'specific', 'Messeage'),
(590, 1, 'button_send', 'specific', 'Send'),
(591, 1, 'label_phone_number', 'specific', 'Phone Number'),
(592, 1, 'title_sms_report', 'specific', 'SMS Report'),
(593, 1, 'text_sms_report_title', 'specific', 'SMS Report'),
(594, 1, 'text_sms_list_title', 'specific', 'SMS List'),
(595, 1, 'text_all', 'specific', 'All'),
(596, 1, 'button_delivered', 'specific', 'Delivered'),
(597, 1, 'button_failed', 'specific', 'Failed'),
(598, 1, 'label_schedule_at', 'specific', 'Schedule At'),
(599, 1, 'label_campaign_name', 'specific', 'Campaign Name'),
(600, 1, 'label_people_name', 'specific', 'People Name'),
(601, 1, 'label_mobile_number', 'specific', 'Mobile Number'),
(602, 1, 'label_process_status', 'specific', 'Process Status'),
(603, 1, 'label_response_text', 'specific', 'Response Text'),
(604, 1, 'label_delivered', 'specific', 'Delivered'),
(605, 1, 'label_resend', 'specific', 'Resend'),
(606, 1, 'title_sms_setting', 'specific', 'SMS Setting'),
(607, 1, 'text_sms_setting_title', 'specific', 'SMS Setting'),
(608, 1, 'text_sms_setting', 'specific', 'SMS Setting'),
(609, 1, 'text_clickatell', 'specific', 'clickatell'),
(610, 1, 'text_twilio', 'specific', 'twilio'),
(611, 1, 'text_msg91', 'specific', 'msg91'),
(612, 1, 'text_onnorokomsms', 'specific', 'Onnorokom SMS'),
(613, 1, 'label_api_key', 'specific', 'API Key'),
(614, 1, 'label_sender_id', 'specific', 'Sender Id'),
(615, 1, 'label_auth_key', 'specific', 'Auth Key'),
(616, 1, 'label_contact', 'specific', 'Contact'),
(617, 1, 'label_country_code', 'specific', 'Country Code'),
(618, 1, 'label_maskname', 'specific', 'Maskname'),
(619, 1, 'label_optional', 'specific', 'Optional'),
(620, 1, 'label_campaignname', 'specific', 'Campaign Name'),
(621, 1, 'title_analytics', 'specific', 'Analytics'),
(622, 1, 'text_analytics_title', 'specific', 'Analytics'),
(623, 1, 'text_report_title', 'specific', 'Report'),
(624, 1, 'text_best_customer', 'specific', 'Best Customer'),
(625, 1, 'text_purchase_amount', 'specific', 'Purchase Amount'),
(626, 1, 'text_top_product', 'specific', 'Top Product'),
(627, 1, 'title_overview', 'specific', 'Overview Report'),
(628, 1, 'text_overview_title', 'specific', 'Overview Report'),
(629, 1, 'text_sell_overview', 'specific', 'Sell Overview'),
(630, 1, 'text_purchase_overview', 'specific', 'Purchase Overview'),
(631, 1, 'text_title_sells_overview', 'specific', 'Sell Overview'),
(632, 1, 'text_invoice_amount', 'specific', 'Invoice Amount'),
(633, 1, 'button_details', 'specific', 'Details'),
(634, 1, 'text_discount_amount', 'specific', 'Discount'),
(635, 1, 'text_due_given', 'specific', 'Due Given'),
(636, 1, 'text_due_collection', 'specific', 'Due Collection'),
(637, 1, 'text_others', 'specific', 'Others'),
(638, 1, 'text_shipping_charge', 'specific', 'Shipping Charge'),
(639, 1, 'text_others_charge', 'specific', 'Others Charge'),
(640, 1, 'text_profit_or_loss', 'specific', 'Profit or Loss'),
(641, 1, 'text_order_tax', 'specific', 'Order Tax'),
(642, 1, 'text_item_tax', 'specific', 'Item Tax'),
(643, 1, 'text_total_tax', 'specific', 'Total Tax'),
(644, 1, 'text_title_purchase_overview', 'specific', 'Purchase Overview'),
(645, 1, 'text_due_taken', 'specific', 'Due Taken'),
(646, 1, 'text_due_paid', 'specific', 'Due Paid'),
(647, 1, 'text_total_paid', 'specific', 'Total Paid'),
(648, 1, 'title_collection_report', 'specific', 'Collection Report'),
(649, 1, 'text_collection_report_title', 'specific', 'Collection Report'),
(650, 1, 'title_due_collection', 'specific', 'Due Collection'),
(651, 1, 'text_due_collection_title', 'specific', 'Due Collection'),
(652, 1, 'text_due_collection_sub_title', 'specific', 'Due Collection List'),
(653, 1, 'label_pmethod_name', 'specific', 'Payment Mehtod'),
(654, 1, 'label_created_by', 'specific', 'Created By'),
(655, 1, 'title_supplier_due_paid', 'specific', 'Supplier Due Paid'),
(656, 1, 'text_supplier_due_paid_title', 'specific', 'Supplier Due Paid'),
(657, 1, 'text_supplier_due_paid_sub_title', 'specific', 'Supplier Due Paid'),
(658, 1, 'title_sell_report', 'specific', 'Sell Report'),
(659, 1, 'text_selling_report_title', 'specific', 'Selling Report'),
(660, 1, 'text_selling_report_sub_title', 'specific', 'Selling Report'),
(661, 1, 'button_itemwise', 'specific', 'Itemwise'),
(662, 1, 'button_categorywise', 'specific', 'Categorywise'),
(663, 1, 'button_supplierwise', 'specific', 'Supplierwise'),
(664, 1, 'label_product_name', 'specific', 'Product Name'),
(665, 1, 'label_selling_price_tax', 'specific', 'Selling Price Tax'),
(666, 1, 'label_profit', 'specific', 'Profit'),
(667, 1, 'title_purchase_report', 'specific', 'Purchase Report'),
(668, 1, 'text_purchase_report_title', 'specific', 'Purchase Report'),
(669, 1, 'text_purchase_report_sub_title', 'specific', 'Purchase Report'),
(670, 1, 'label_sup_name', 'specific', 'Sup Name'),
(671, 1, 'title_sell_payment_report', 'specific', 'Sell Payment Report'),
(672, 1, 'text_sell_payment_report_title', 'specific', 'Sell Payment Report'),
(673, 1, 'title_purchase_payment_report', 'specific', 'Purchase Payment Report'),
(674, 1, 'text_purchase_payment_report_title', 'specific', 'Purchase Payment Report'),
(675, 1, 'title_sell_tax_report', 'specific', 'Sell Tax Report'),
(676, 1, 'text_sell_tax_report_title', 'specific', 'Sell Tax Report '),
(677, 1, 'text_sell_amount', 'specific', 'Sell Amount'),
(678, 1, 'text_product_tax_amount', 'specific', 'Product Tax'),
(679, 1, 'text_order_tax_amount', 'specific', 'Order Tax'),
(680, 1, 'text_tax_report_sub_title', 'specific', 'Tax Report '),
(681, 1, 'label_total_amount', 'specific', 'Total Amount'),
(682, 1, 'title_purchase_tax_report', 'specific', 'Purchase Tax Report'),
(683, 1, 'text_purchase_tax_report_title', 'specific', 'Purchase Tax Report'),
(684, 1, 'text_total_tax_amount', 'specific', 'Total Tax'),
(685, 1, 'text_purchase_tax_report_sub_title', 'specific', 'Purcahse Tax Report'),
(686, 1, 'label_tax_amount', 'specific', 'Tax Amount'),
(687, 1, 'title_tax_overview_report', 'specific', 'Tax Overview Report'),
(688, 1, 'text_tax_overview_report_title', 'specific', 'Tax Overview Report'),
(689, 1, 'text_sell_tax', 'specific', 'Sell Tax'),
(690, 1, 'text_purchase_tax', 'specific', 'Purchase Tax'),
(691, 1, 'label_tax_percent', 'specific', 'Tax Percent'),
(692, 1, 'label_count', 'specific', 'Count'),
(693, 1, 'title_stock_report', 'specific', 'Stock Report'),
(694, 1, 'text_stock_report_title', 'specific', 'Stock Report'),
(695, 1, 'text_stock_report', 'specific', 'Stock Report'),
(696, 1, 'supplier_name', 'specific', 'Supplier Name'),
(697, 1, 'title_filemanager', 'specific', 'Filemanager'),
(698, 1, 'title_loan', 'specific', 'Loan'),
(699, 1, 'text_loan_title', 'specific', 'Loan'),
(700, 1, 'text_take_loan_title', 'specific', 'Take Loan'),
(701, 1, 'label_loan_from', 'specific', 'Loan From'),
(702, 1, 'label_interest', 'specific', 'Interest'),
(703, 1, 'label_loan_for', 'specific', 'Loan For'),
(704, 1, 'text_loan_list_title', 'specific', 'Loan List'),
(705, 1, 'button_paid', 'specific', 'Paid'),
(706, 1, 'button_due', 'specific', 'Due'),
(707, 1, 'button_disabled', 'specific', 'Disabled'),
(708, 1, 'label_basic_amount', 'specific', 'Basic Amount'),
(709, 1, 'title_loan_summary', 'specific', 'Loan Summary'),
(710, 1, 'text_loan_summary_title', 'specific', 'Loan Summary'),
(711, 1, 'text_summary_title', 'specific', 'Summary'),
(712, 1, 'text_total_loan', 'specific', 'Total Loan'),
(713, 1, 'text_total_due', 'specific', 'Total Due'),
(714, 1, 'text_recent_payments', 'specific', 'Recent Payments'),
(715, 1, 'title_expense', 'specific', 'Expense'),
(716, 1, 'text_expense_title', 'specific', 'Expense'),
(717, 1, 'text_new_expense_title', 'specific', 'Add New Expense'),
(718, 1, 'label_returnable', 'specific', 'Returnable?'),
(719, 1, 'label_notes', 'specific', 'Notes'),
(720, 1, 'text_expense_list_title', 'specific', 'Expense Category List'),
(721, 1, 'label_category_name', 'specific', 'Category Name'),
(722, 1, 'title_expense_category', 'specific', 'Expense Category'),
(723, 1, 'text_expense_category_title', 'specific', 'Expense Category'),
(724, 1, 'text_new_expense_category_title', 'specific', 'Add New Expense Category'),
(725, 1, 'label_category_slug', 'specific', 'Category Slug'),
(726, 1, 'label_parent', 'specific', 'Parent'),
(727, 1, 'label_category_details', 'specific', 'Category Details'),
(728, 1, 'text_category_list_title', 'specific', 'Category List'),
(729, 1, 'title_expense_monthwise', 'specific', 'Expense Monthwise'),
(730, 1, 'text_expense_monthwise_title', 'specific', 'Expense Monthwise'),
(731, 1, 'text_print', 'specific', 'Print'),
(732, 1, 'text_supplier_list_title', 'specific', 'Supplier List'),
(733, 1, 'text_supplier_title', 'specific', 'Supplier '),
(734, 1, 'text_new_supplier_title', 'specific', 'Add New Supplier'),
(735, 1, 'title_purchase_transaction', 'specific', 'Purchase Transaction'),
(736, 1, 'text_purchase_transaction_title', 'specific', 'Purchase Transaction'),
(737, 1, 'text_transaction_title', 'specific', 'Transaction'),
(738, 1, 'text_transaction_list_title', 'specific', 'Transaction List'),
(739, 1, 'label_pmethod', 'specific', 'Payment Method'),
(740, 1, 'title_sell_transaction', 'specific', 'Sell Transaction'),
(741, 1, 'text_sell_transaction_title', 'specific', 'Sell Transaction'),
(742, 1, 'text_sell_transaction_list_title', 'specific', 'Sell Transaction List'),
(743, 1, 'title_barcode', 'specific', 'Barcode'),
(744, 1, 'text_barcode_title', 'specific', 'Barcode'),
(745, 1, 'text_barcode_generate_title', 'specific', 'Barcode Generate'),
(746, 1, 'label_product_name_with_code', 'specific', 'Product Name with Code'),
(747, 1, 'text_no_product', 'specific', 'No. Proudct'),
(748, 1, 'label_page_layout', 'specific', 'Page Layout'),
(749, 1, 'label_fields', 'specific', 'Fileds'),
(750, 1, 'button_generate', 'specific', 'Generate'),
(751, 1, 'title_category', 'specific', 'Category'),
(752, 1, 'text_category_title', 'specific', 'Category'),
(753, 1, 'text_new_category_title', 'specific', 'Add New Category'),
(754, 1, 'text_product_import_alert', 'specific', 'Product Import Alert'),
(755, 1, 'title_import_product', 'specific', 'Import Product'),
(756, 1, 'text_import_title', 'specific', 'Import'),
(757, 1, 'text_download', 'specific', 'Download'),
(758, 1, 'button_download', 'specific', 'Download'),
(759, 1, 'text_select_xls_file', 'specific', 'Select .xls File'),
(760, 1, 'button_import', 'specific', 'Import'),
(761, 1, 'title_stock_alert', 'specific', 'Stock Alert'),
(762, 1, 'text_stock_alert_title', 'specific', 'Stock Alert'),
(763, 1, 'text_stock_alert_box_title', 'specific', 'Stock Alert'),
(764, 1, 'title_expired', 'specific', 'Expired'),
(765, 1, 'text_expired_title', 'specific', 'Expired'),
(766, 1, 'text_expired_box_title', 'specific', 'Expired'),
(767, 1, 'button_expired', 'specific', 'Expired'),
(768, 1, 'button_expiring_soon', 'specific', 'Comming Soon'),
(769, 1, 'text_due', 'specific', 'Due'),
(770, 1, 'title_purchase_return', 'specific', 'Purchase Return'),
(771, 1, 'text_purchase_return_title', 'specific', 'Purchase Return'),
(772, 1, 'text_return_list_title', 'specific', 'Return List'),
(773, 1, 'text_purchase_return_list_title', 'specific', 'Purchase Return List'),
(774, 1, 'title_sell_return', 'specific', 'Sell Return'),
(775, 1, 'text_sell_return_title', 'specific', 'Sell Return'),
(776, 1, 'text_sell_return_list_title', 'specific', 'Sell Return List'),
(777, 1, 'title_giftcard', 'specific', 'Giftcard'),
(778, 1, 'text_giftcard_title', 'specific', 'Giftcard'),
(779, 1, 'text_new_giftcard_title', 'specific', 'Add New Giftcard'),
(780, 1, 'label_card_no', 'specific', 'Card No.'),
(781, 1, 'label_giftcard_value', 'specific', 'GIftcard Value'),
(782, 1, 'label_expiry_date', 'specific', 'Expiry Date'),
(783, 1, 'button_create_giftcard', 'specific', 'Create GIftcard'),
(784, 1, 'text_giftcard_list_title', 'specific', 'Giftcard List'),
(785, 1, 'label_expiry', 'specific', 'Expiry'),
(786, 1, 'label_topup', 'specific', 'Topup'),
(787, 1, 'title_giftcard_topup', 'specific', 'Giftcard Topup'),
(788, 1, 'text_giftcard_topup_title', 'specific', 'Giftcard Topup'),
(789, 1, 'text_topup_title', 'specific', 'Topup'),
(790, 1, 'text_giftcard_topup_list_title', 'specific', 'Giftcard Popup List'),
(791, 1, 'title_pos', 'specific', 'POS'),
(792, 1, 'text_gift_card', 'specific', 'Giftcard'),
(793, 1, 'button_sell_gift_card', 'specific', 'Giftcard'),
(794, 1, 'text_keyboard_shortcut', 'specific', 'Keyboard Shortcut '),
(795, 1, 'text_holding_order', 'specific', 'Holding Order'),
(796, 1, 'text_search_product', 'specific', 'Search/Barcode Scan'),
(797, 1, 'button_add_product', 'specific', 'Add Product'),
(798, 1, 'button_purchase_now', 'specific', 'Purchase Now'),
(799, 1, 'label_add_to_cart', 'specific', 'Add To Cart'),
(800, 1, 'text_add_note', 'specific', 'Add Note'),
(801, 1, 'label_price', 'specific', 'Price'),
(802, 1, 'label_total_items', 'specific', 'Total Item'),
(803, 1, 'label_discount', 'specific', 'Discount'),
(804, 1, 'label_total_payable', 'specific', 'Total Payable'),
(805, 1, 'button_pay', 'specific', 'Pay Now'),
(806, 1, 'button_hold', 'specific', 'Hold'),
(807, 1, 'text_update_title', 'specific', 'Update '),
(808, 1, 'text_male', 'specific', 'Male'),
(809, 1, 'text_female', 'specific', 'Female'),
(810, 1, 'text_thumbnail', 'specific', 'Thumbnail'),
(811, 1, 'text_update_success', 'specific', 'Successfully Updated'),
(812, 1, 'title_user_profile', 'specific', 'User Profile'),
(813, 1, 'text_profile_title', 'specific', 'Profile'),
(814, 1, 'text_users', 'specific', 'Users'),
(815, 1, 'text_since', 'specific', 'Since'),
(816, 1, 'text_contact_information', 'specific', 'Contact Information'),
(817, 1, 'label_collection', 'specific', 'Collection'),
(818, 1, 'text_sell_report', 'specific', 'Sell Report'),
(819, 1, 'text_purchase_report', 'specific', 'Purchase Report'),
(820, 1, 'text_payment_report', 'specific', 'Payment Report'),
(821, 1, 'text_login_log', 'specific', 'Login History'),
(822, 1, 'button_collection_report', 'specific', 'Collection Report'),
(823, 1, 'button_log', 'specific', 'Log'),
(824, 1, 'text_invoice_list', 'specific', 'Invoice List'),
(825, 1, 'label_items', 'specific', 'Items'),
(826, 1, 'label_time', 'specific', 'Time'),
(827, 1, 'title_bank_transactions', 'specific', 'Bank Transactions'),
(828, 1, 'text_bank_transaction_title', 'specific', 'Bank Transaction'),
(829, 1, 'text_bank_account_title', 'specific', 'Bank Account'),
(830, 1, 'text_bank_transaction_list_title', 'specific', 'Bank Transaction List'),
(831, 1, 'button_filtering', 'specific', 'Filtering'),
(832, 1, 'text_view_all_transactions', 'specific', 'View All Transactions'),
(833, 1, 'label_account', 'specific', 'Account'),
(834, 1, 'label_credit', 'specific', 'Credit'),
(835, 1, 'label_debit', 'specific', 'Debit'),
(836, 1, 'text_unpaid', 'specific', 'Unpaid'),
(837, 1, 'title_income_source', 'specific', 'Income Source'),
(838, 1, 'text_income_source_title', 'specific', 'Income Source'),
(839, 1, 'text_new_income_source_title', 'specific', 'Add New Income Source'),
(840, 1, 'label_source_name', 'specific', 'Source Name'),
(841, 1, 'label_source_slug', 'specific', 'Source Slug'),
(842, 1, 'label_source_details', 'specific', 'Source Details'),
(843, 1, 'text_income_source_sub_title', 'specific', 'Income Source List'),
(844, 1, 'title_income_monthwise', 'specific', 'Income Monthwise'),
(845, 1, 'text_income_monthwise_title', 'specific', 'Income Monthwise'),
(846, 1, 'label_capital', 'specific', 'Capital'),
(847, 1, 'title_bank_transfer', 'specific', 'Bank Transfer'),
(848, 1, 'text_bank_transfer_title', 'specific', 'Bank Transfer'),
(849, 1, 'text_banking_title', 'specific', 'Banking'),
(850, 1, 'text_list_bank_transfer_title', 'specific', 'Bank Transfer List'),
(851, 1, 'label_from_account', 'specific', 'From Account'),
(852, 1, 'label_to_account', 'specific', 'To Account'),
(853, 1, 'title_income_and_expense', 'specific', 'Income '),
(854, 1, 'text_income_and_expense_title', 'specific', 'Income vs Expense'),
(855, 1, 'text_date', 'specific', 'Date'),
(856, 1, 'title_income', 'specific', 'Income'),
(857, 1, 'label_this_month', 'specific', 'This Month'),
(858, 1, 'label_this_year', 'specific', 'This Year'),
(859, 1, 'label_till_now', 'specific', 'Till Now'),
(860, 1, 'error_currency_title', 'specific', 'Currency Tittle is not valid'),
(861, 1, 'text_pmethod', 'specific', 'Payment Method'),
(862, 1, 'button_full_payment', 'specific', 'FULL PAYMENT'),
(863, 1, 'button_full_due', 'specific', 'FULL DUE'),
(864, 1, 'button_sell_with_installment', 'specific', 'Sell With Installment'),
(865, 1, 'text_pay_amount', 'specific', 'Pay Amount'),
(866, 1, 'placeholder_input_an_amount', 'specific', 'Input Amount'),
(867, 1, 'placeholder_note_here', 'specific', 'Note Here'),
(868, 1, 'title_installment_details', 'specific', 'Installment Details'),
(869, 1, 'label_duration', 'specific', 'Duration'),
(870, 1, 'text_days', 'specific', 'Days'),
(871, 1, 'label_interval', 'specific', 'Interval'),
(872, 1, 'label_total_installment', 'specific', 'Total Installment'),
(873, 1, 'label_interest_percentage', 'specific', 'Interest Percentage'),
(874, 1, 'label_interest_amount', 'specific', 'Interest Amount'),
(875, 1, 'text_order_details', 'specific', 'Order Details'),
(876, 1, 'error_reference_no', 'specific', 'Reference no. is not valid'),
(877, 1, 'error_date', 'specific', 'Date is not valid'),
(878, 1, 'error_total_amount', 'specific', 'Total amount is not valid'),
(879, 1, 'error_customer', 'specific', 'Customer is not valid'),
(880, 1, 'text_quotation_create_success', 'specific', 'Successfully Created'),
(881, 1, 'button_action', 'specific', 'Action'),
(882, 1, 'button_create_sell', 'specific', 'Create Sell'),
(883, 1, 'title_installment_overview', 'specific', 'Installment Overview');
INSERT INTO `language_translations` (`id`, `lang_id`, `lang_key`, `key_type`, `lang_value`) VALUES
(884, 1, 'text_installment_overview_title', 'specific', 'Installment Overview Report'),
(885, 1, 'text_installment_overview', 'specific', 'Installment Overview'),
(886, 1, 'text_invoice_count', 'specific', 'Invoice Count'),
(887, 1, 'text_interest_amount', 'specific', 'Interest Amount'),
(888, 1, 'text_amount_received', 'specific', 'Amount Received'),
(889, 1, 'text_amount_due', 'specific', 'Amount Due'),
(890, 1, 'text_expired_due_payment', 'specific', 'Expired Due Payment'),
(891, 1, 'text_all_due_payment', 'specific', 'All Due Payment'),
(892, 1, 'text_todays_due_payment', 'specific', 'Todays Due Payment'),
(893, 1, 'title_installment', 'specific', 'Installment'),
(894, 1, 'text_installment_title', 'specific', 'Installment'),
(895, 1, 'text_installment_sub_title', 'specific', 'Installment List'),
(896, 1, 'button_all_installment', 'specific', 'All Installment'),
(897, 1, 'button_due_installment', 'specific', 'Due Installment'),
(898, 1, 'button_paid_installment', 'specific', 'Paid Installment'),
(899, 1, 'label_total_ins', 'specific', 'Total Ins.'),
(900, 1, 'text_order_summary', 'specific', 'Order Summary'),
(901, 1, 'label_previous_due', 'specific', 'Previous Due'),
(902, 1, 'text_return_item', 'specific', 'Return Item'),
(903, 1, 'label_return_quantity', 'specific', 'Return Quantity'),
(904, 1, 'placeholder_type_any_note', 'specific', 'Type Note'),
(905, 1, 'error_quantity_exceed', 'specific', 'Quantity Exceed'),
(906, 1, 'text_return_success', 'specific', 'Return Successfull'),
(907, 1, 'label_purchase_note', 'specific', 'Purchase Note'),
(908, 1, 'text_purchase_update_success', 'specific', 'Successfully Updated'),
(909, 1, 'error_items', 'specific', 'Items is not valid'),
(910, 1, 'error_store_id', 'specific', 'Store is not valid'),
(911, 1, 'text_transfer_success', 'specific', 'Successfully Transfered'),
(912, 1, 'button_transfer_edit', 'specific', 'Transfer Edit'),
(913, 1, 'text_update_transfer_status_success', 'specific', 'Successfully Updated'),
(914, 1, 'label_transferred_to', 'specific', 'Transfered To'),
(915, 1, 'text_product_list', 'specific', 'Product List'),
(916, 1, 'error_category_name', 'specific', 'Category name is not valid'),
(917, 1, 'error_sup_name', 'specific', 'Suppllier name is not valid'),
(918, 1, 'error_supplier_email_or_mobile', 'specific', 'Supplier email or mobile is not valid'),
(919, 1, 'error_sup_address', 'specific', 'Supplier Address is not valid'),
(920, 1, 'error_unit_name', 'specific', 'Unit name is not valid'),
(921, 1, 'error_product_name', 'specific', 'Product name is not valid'),
(922, 1, 'error_sup_id', 'specific', 'Supplier is not valid'),
(923, 1, 'text_product_name', 'specific', 'Product Name'),
(924, 1, 'text_quantity', 'specific', 'Quantity'),
(925, 1, 'button_print', 'specific', 'Print'),
(926, 1, 'error_walking_customer_can_not_craete_due', 'specific', 'Walking Customer Can\'t to Create a Due'),
(927, 1, 'error_stock', 'specific', 'Stock amount is not valid'),
(928, 1, 'error_installment_count', 'specific', 'Installment count is not valid'),
(929, 1, 'title_bank_account', 'specific', 'Bank Account'),
(930, 1, 'text_new_bank_account_title', 'specific', 'Add New Bank Account'),
(931, 1, 'label_account_name', 'specific', 'Account Name'),
(932, 1, 'label_account_details', 'specific', 'Account Details'),
(933, 1, 'label_account_no', 'specific', 'Account No.'),
(934, 1, 'label_contact_person', 'specific', 'Contact Person'),
(935, 1, 'label_internal_banking_url', 'specific', 'Internal Banking Url'),
(936, 1, 'text_bank_account_list_title', 'specific', 'Bank Account List'),
(937, 1, 'label_account_description', 'specific', 'Account Description'),
(938, 1, 'title_bank_account_sheet', 'specific', 'Balance Sheet'),
(939, 1, 'text_bank_account_sheet_title', 'specific', 'Balance Sheet'),
(940, 1, 'text_bank_account_sheet_list_title', 'specific', 'Balance Sheet Details'),
(941, 1, 'label_account_id', 'specific', 'Account Id'),
(942, 1, 'label_transfer_to_other', 'specific', 'Transfer To Other'),
(943, 1, 'label_transfer_from_other', 'specific', 'Transfer From Other'),
(944, 1, 'label_deposit', 'specific', 'Deposit'),
(945, 1, 'label_withdraw', 'specific', 'Widthdraw'),
(946, 1, 'text_select_store', 'specific', 'Select Store'),
(947, 1, 'text_activate_success', 'specific', 'Successfully Activated'),
(948, 1, 'text_template_content_update_success', 'specific', 'Successfully updated'),
(949, 1, 'text_template_css_update_success', 'specific', 'Successfully updated'),
(950, 1, 'title_cashbook', 'specific', 'Cashbook'),
(951, 1, 'text_cashbook_title', 'specific', 'Cashbook'),
(952, 1, 'text_cashbook_details_title', 'specific', 'Cashbook Details'),
(953, 1, 'label_opening_balance', 'specific', 'Opening Balance'),
(954, 1, 'label_today_income', 'specific', 'Today Income'),
(955, 1, 'label_total_income', 'specific', 'Total Income'),
(956, 1, 'label_today_expense', 'specific', 'Today Expense'),
(957, 1, 'label_cash_in_hand', 'specific', 'Cash In Hand'),
(958, 1, 'label_today_closing_balance', 'specific', 'Today Closing Balance'),
(959, 1, 'text_balance_update_success', 'specific', 'Successfully Updated'),
(960, 1, 'title_profit_and_loss', 'specific', 'Profit vs Loss'),
(961, 1, 'text_profit_and_loss_title', 'specific', 'Profit vs Loss'),
(962, 1, 'text_profit_and_loss_details_title', 'specific', 'Profit vs Loss Details'),
(963, 1, 'text_loss_title', 'specific', 'Loss'),
(964, 1, 'text_profit_title', 'specific', 'Profit'),
(965, 1, 'label_total_profit', 'specific', 'Total Profit'),
(966, 1, 'label_total_loss', 'specific', 'Total Loss'),
(967, 1, 'label_net_profit', 'specific', 'Net Profit'),
(968, 1, 'label_source', 'specific', 'Source'),
(969, 1, 'label_slip_no', 'specific', 'Slip No.'),
(970, 1, 'label_by', 'specific', 'By'),
(971, 1, 'label_exp_category', 'specific', 'Expense Category'),
(972, 1, 'label_about', 'specific', 'About'),
(973, 1, 'button_withdraw_now', 'specific', 'Widthdraw Now'),
(974, 1, 'label_income_source', 'specific', 'Income Source'),
(975, 1, 'button_deposit_now', 'specific', 'Deposit Now'),
(976, 1, 'text_deposit_success', 'specific', 'Deposit Successfull'),
(977, 1, 'text_delete_title', 'specific', 'Delete'),
(978, 1, 'text_delete_instruction', 'specific', 'What should be done with data belong to the content?'),
(979, 1, 'label_insert_content_to', 'specific', 'Insert Content To'),
(980, 1, 'button_add_language', 'specific', 'Add Language'),
(981, 1, 'code', 'specific', 'code'),
(982, 1, 'error_code', 'specific', 'Code is not valid'),
(983, 1, 'text_uppdate_success', 'specific', 'Successfully Updated'),
(984, 1, 'error_name', 'specific', 'Name is not valid'),
(985, 1, 'text_hindi', 'specific', 'Hindi'),
(986, 1, 'text_create_success', 'specific', 'Successfully Created'),
(987, 1, 'text_gremany', 'specific', 'Germany'),
(988, 1, 'button_add_new_language', 'specific', 'Add New Language'),
(989, 1, 'text_fullscreen', 'specific', 'Fullscreen'),
(990, 1, 'text_sales', 'specific', 'Sells'),
(991, 1, 'text_quotations', 'specific', 'Quotations'),
(992, 1, 'text_purchases', 'specific', 'Purchases'),
(993, 1, 'text_transfers', 'specific', 'Transfers'),
(994, 1, 'text_customers', 'specific', 'Customers'),
(995, 1, 'text_suppliers', 'specific', 'Suppliers'),
(996, 1, 'label_payment_status', 'specific', 'Payment Status'),
(997, 1, 'button_add_sales', 'specific', 'Add Sell'),
(998, 1, 'button_list_sales', 'specific', 'SELL LIST'),
(999, 1, 'text_sales_amount', 'specific', 'Sell Amount'),
(1000, 1, 'text_discount_given', 'specific', 'Discount Given'),
(1001, 1, 'text_received_amount', 'specific', 'Received Amount'),
(1002, 1, 'button_add_quotations', 'specific', 'Add Quotation'),
(1003, 1, 'button_list_quotations', 'specific', 'Quotation List'),
(1004, 1, 'label_supplier_name', 'specific', 'Supplier Name'),
(1005, 1, 'button_add_purchases', 'specific', 'Add Purchase'),
(1006, 1, 'button_list_purchases', 'specific', 'Purchase List'),
(1007, 1, 'button_add_transfers', 'specific', 'Add Transfer'),
(1008, 1, 'button_list_transfers', 'specific', 'Transfer List'),
(1009, 1, 'button_add_customer', 'specific', 'Add Customer'),
(1010, 1, 'button_list_customers', 'specific', 'Customer List'),
(1011, 1, 'button_add_supplier', 'specific', 'Add Supplier'),
(1012, 1, 'button_list_suppliers', 'specific', 'Supplier List'),
(1013, 1, 'text_permission', 'specific', 'Permission'),
(1014, 1, 'text_recent_activities', 'specific', 'Recent Activities'),
(1015, 1, 'text_top_products', 'specific', 'Top Products'),
(1016, 1, 'text_top_customers', 'specific', 'Top Customers'),
(1017, 1, 'text_top_suppliers', 'specific', 'Top Suppliers'),
(1018, 1, 'text_top_brands', 'specific', 'Top Brands'),
(1019, 1, 'text_amount', 'specific', 'Amount'),
(1020, 1, 'text_purchase', 'specific', 'Purchase'),
(1021, 1, 'title_login_logs', 'specific', 'Login Logs'),
(1022, 1, 'title_activity_logs', 'specific', 'Activity Logs'),
(1023, 1, 'text_birthday_today', 'specific', 'Birthday Today'),
(1024, 1, 'text_birthday_coming', 'specific', 'Birthday Coming'),
(1025, 1, 'title_income_vs_expense', 'specific', 'Income vs Expense'),
(1026, 1, 'text_download_as_jpg', 'specific', 'Download as PNG'),
(1027, 1, 'error_disabled_by_default', 'specific', 'Disabled By Default'),
(1028, 1, 'button_empty_value', 'specific', 'Empty Value'),
(1029, 1, 'text_invoice_create_success', 'specific', 'Successfully Created'),
(1030, 1, 'button_send_sms', 'specific', 'Send SMS'),
(1031, 1, 'button_email', 'specific', 'Email'),
(1032, 1, 'button_back_to_pos', 'specific', 'Back to POS'),
(1033, 1, 'error_status', 'specific', 'Status is not valid'),
(1034, 1, 'error_reference_no_exist', 'specific', 'Ref. no. is not valid'),
(1035, 1, 'text_view_invoice_title', 'specific', 'View Invoice Title'),
(1036, 1, 'text_new_dashboard', 'specific', 'New Dashboard'),
(1037, 1, 'text_recent_customer_box_title', 'specific', 'Recent Customers'),
(1038, 1, 'label_customer_mobile', 'specific', 'Customer Mobile'),
(1039, 1, 'label_invoice_note', 'specific', 'Invoice Note'),
(1040, 1, 'text_sell_update_success', 'specific', 'Successfully Updated'),
(1041, 1, 'label_customer_id', 'specific', 'Customer Id'),
(1042, 1, 'label_returened_by', 'specific', 'Returned By'),
(1043, 1, 'text_return_products', 'specific', 'Return Products'),
(1044, 1, 'button_topup', 'specific', 'Topup'),
(1045, 1, 'button_topup_now', 'specific', 'Topup Now'),
(1046, 1, 'error_amount', 'specific', 'Amount is not Valid'),
(1047, 1, 'error_expiry_date', 'specific', 'Expiry Date'),
(1048, 1, 'text_topup_success', 'specific', 'Topup Successfull'),
(1049, 1, 'label_vat_number', 'specific', 'VAT Number'),
(1050, 1, 'label_unit_price', 'specific', 'Unit Price'),
(1051, 1, 'label_shipping', 'specific', 'Shipping'),
(1052, 1, 'label_stamp_and_signature', 'specific', 'Stamp and Signature'),
(1053, 1, 'title_quotation_edit', 'specific', 'Quotation Edit'),
(1054, 1, 'text_quotation_edit_title', 'specific', 'Quotation Edit'),
(1055, 1, 'text_quotation_update_success', 'specific', 'Successfully Updated'),
(1056, 1, 'error_product_not_found', 'specific', 'Product Not Found'),
(1057, 1, 'error_invoice_product_type', 'specific', 'Invoice product type is not valid'),
(1058, 1, 'label_checkout_status', 'specific', 'Checkout Status'),
(1059, 1, 'label_sub_total', 'specific', 'Sub Total'),
(1060, 1, 'text_payments', 'specific', 'Payments'),
(1061, 1, 'error_select_at_least_one_item', 'specific', 'Select at least one product'),
(1062, 1, 'button_pay_now', 'specific', 'Pay Now'),
(1063, 1, 'text_billing_details', 'specific', 'Billing Details'),
(1064, 1, 'Print Barcode', 'specific', 'undefined'),
(1065, 1, 'error_new_category_name', 'specific', 'Please select a category'),
(1066, 1, 'error_customer_name', 'specific', 'Customer name is not valid'),
(1067, 1, 'error_expired_date_below', 'specific', 'Expired date is not valid'),
(1068, 1, 'label_insert_invoice_to', 'specific', 'Insert Invoice To'),
(1069, 1, 'error_new_customer_name', 'specific', 'Please select a customer'),
(1070, 1, 'title_customer_profile', 'specific', 'Customer Profile'),
(1071, 1, 'text_total_purchase', 'specific', 'Total Purchase'),
(1072, 1, 'label_mobile_phone', 'specific', 'Mobile Phone'),
(1073, 1, 'button_transaction_list', 'specific', 'Transaction List'),
(1074, 1, 'label_ref_invoice_Id', 'specific', 'Ref. Invoice Id'),
(1075, 1, 'error_code_name', 'specific', 'Code name is not valid'),
(1076, 1, 'title_supplier_profile', 'specific', 'Supplier Profile'),
(1077, 1, 'text_supplier_profile_title', 'specific', 'Supplier Profile'),
(1078, 1, 'text_supplier_products', 'specific', 'Supplier Products'),
(1079, 1, 'button_products', 'specific', 'Products'),
(1080, 1, 'text_balance_amount', 'specific', 'Balance Amount'),
(1081, 1, 'text_sells', 'specific', 'Sells'),
(1082, 1, 'text_chart', 'specific', 'Chart'),
(1083, 1, 'text_purchase_invoice_list', 'specific', 'Purchase Invoice List'),
(1084, 1, 'button_all_purchase', 'specific', 'All Purchase'),
(1085, 1, 'button_due_purchase', 'specific', 'Due Purchase'),
(1086, 1, 'button_paid_purchase', 'specific', 'Paid Purchase'),
(1087, 1, 'button_stock_transfer', 'specific', 'Stock Transfer'),
(1088, 1, 'text_selling_invoice_list', 'specific', 'Selling Invoice List'),
(1089, 1, 'error_account', 'specific', 'Account is not valid'),
(1090, 1, 'error_ref_no', 'specific', 'Ref. no. is not valid'),
(1091, 1, 'error_about', 'specific', 'About is not valid'),
(1092, 1, 'error_title', 'specific', 'Title is not valid'),
(1093, 1, 'text_withdraw_success', 'specific', 'successfully created'),
(1094, 1, 'error_from_account', 'specific', 'From account is not valid'),
(1095, 1, 'error_to_account', 'specific', 'To account is not valid'),
(1096, 1, 'error_same_account', 'specific', 'Receiver and sender can\'t be same'),
(1097, 1, 'error_insufficient_balance', 'specific', 'Insufficient balance'),
(1098, 1, 'error_ref_no_exist', 'specific', 'Ref. no. already exists'),
(1099, 1, 'error_account_name', 'specific', 'Account name is not valid'),
(1100, 1, 'error_account_no', 'specific', 'Account no. is not valid'),
(1101, 1, 'error_contact_person', 'specific', 'Contact person is not valid'),
(1102, 1, 'error_phone_number', 'specific', 'Phone number is not valid'),
(1103, 1, 'text_income', 'specific', 'Income'),
(1104, 1, 'text_expense', 'specific', 'Expense'),
(1105, 1, 'text_update_income_source_success', 'specific', 'Successfully updated'),
(1106, 1, 'error_new_source_name', 'specific', 'Please select a source'),
(1107, 1, 'text_delete_income_source_success', 'specific', 'Successfully deleted'),
(1108, 1, 'label_day', 'specific', 'Day'),
(1109, 1, 'error_category_id', 'specific', 'Category id is not valid'),
(1110, 1, 'button_viefw', 'specific', 'View'),
(1111, 1, 'label_summary', 'specific', 'Summary'),
(1112, 1, 'label_grand_total', 'specific', 'Grand Total'),
(1113, 1, 'label_this_week', 'specific', 'This Week'),
(1114, 1, 'error_loan_from', 'specific', 'Loan from is not valid'),
(1115, 1, 'error_loan_headline', 'specific', 'Loan headline is not valid'),
(1116, 1, 'error_loan_amount', 'specific', 'Loan amount is not valid'),
(1117, 1, 'text_take_loan_success', 'specific', 'Successfully created'),
(1118, 1, 'error_paid_amount', 'specific', 'Paid amount is not valid'),
(1119, 1, 'error_pay_amount_greater_than_due_amount', 'specific', 'Pay amount can\'t be greater than due amount'),
(1120, 1, 'text_loan_paid_success', 'specific', 'Successfully paid'),
(1121, 1, 'error_sms_text', 'specific', 'SMS text is not valid'),
(1122, 1, 'text_success_sms_sent', 'specific', 'SMS successfully sent'),
(1123, 1, 'error_user_name', 'specific', 'user name is not valid'),
(1124, 1, 'error_user_email_or_mobile', 'specific', 'Email or mobile is not valid'),
(1125, 1, 'error_user_group', 'specific', 'User Group is not valid'),
(1126, 1, 'error_user_password_match', 'specific', 'Retype password didn\'t matched'),
(1127, 1, 'error_user_password_length', 'specific', 'User password length is not valid'),
(1128, 1, 'text_income_vs_expense', 'specific', 'Income vs Expense'),
(1129, 1, 'error_mobile', 'specific', 'Mobile number is not valid'),
(1130, 1, 'error_email', 'specific', 'Email is not valid'),
(1131, 1, 'error_zip_code', 'specific', 'Zip code is not valid'),
(1132, 1, 'error_addreess', 'specific', 'Address is not valid'),
(1133, 1, 'error_preference_receipt_template', 'specific', 'Receipt template is not valid'),
(1134, 1, 'error_currency', 'specific', 'Currency is not valid'),
(1135, 1, 'error_brand_name', 'specific', 'Please select a brand name'),
(1136, 1, 'title_brand_profile', 'specific', 'Brand Profile'),
(1137, 1, 'text_brand_profile_title', 'specific', 'Brand Profile'),
(1138, 1, 'text_brands', 'specific', 'Brands'),
(1139, 1, 'text_brand_products', 'specific', 'Brand Products'),
(1140, 1, 'button_all_products', 'specific', 'All Products'),
(1141, 1, 'text_total_sell', 'specific', 'Total Sell'),
(1142, 1, 'label_brand_name', 'specific', 'Brand Name'),
(1143, 1, 'label_insert_product_to', 'specific', 'Insert Product To'),
(1144, 1, 'error_currency_code', 'specific', 'Currency code is not valid'),
(1145, 1, 'error_currency_symbol', 'specific', 'Currency symbol is not valid'),
(1146, 1, 'error_currency_decimal_place', 'specific', 'Decimal number is not valid'),
(1147, 1, 'error_pmethod_name', 'specific', 'Payment method is not valid'),
(1148, 1, 'label_invoice_to', 'specific', 'Invoice To'),
(1149, 1, 'error_delete_unit_name', 'specific', 'Please select a unit'),
(1150, 1, 'label_ip', 'specific', 'Ip'),
(1151, 1, 'error_taxrate_name', 'specific', 'Taxrate name is not valid'),
(1152, 1, 'error_taxrate', 'specific', 'Taxrate is not valid'),
(1153, 1, 'error_delete_taxrate_name', 'specific', 'Please select a taxrate'),
(1154, 1, 'error_box_name', 'specific', 'Box name is not valid'),
(1155, 1, 'error_delete_box_name', 'specific', 'Please select a box'),
(1156, 1, 'label_success', 'specific', 'Success'),
(1157, 1, 'title_customer_analysis', 'specific', 'Customer Analysis'),
(1158, 1, 'title_customer_analytics', 'specific', 'Customer Analytics'),
(1159, 1, 'error_not_found', 'specific', 'Not Found'),
(1160, 1, 'menu_sell_list', 'specific', 'SELL LIST'),
(1161, 1, 'menu_sell_log', 'specific', 'SELL LOG'),
(1162, 1, 'menu_purchase_logs', 'specific', 'PURCHASE LOG'),
(1163, 1, 'menu_receive_list', 'specific', 'RECEIVE LIST'),
(1164, 1, 'menu_statements', 'specific', 'STATEMENTS'),
(1165, 1, 'menu_data_reset', 'specific', 'DATA RESET'),
(1166, 1, 'placeholder_search_here', 'specific', 'Search Here...'),
(1167, 1, 'text_sell_list_title', 'specific', 'Sell List'),
(1168, 1, 'text_invoices', 'specific', 'Invoices'),
(1169, 1, 'placeholder_input_discount_amount', 'specific', 'Input Discount Amount'),
(1170, 1, 'label_previous_due_paid', 'specific', 'Prev. Due Paid'),
(1171, 1, 'button_add_purchase', 'specific', 'Add Purchase'),
(1172, 1, 'text_selling_tax', 'specific', 'Selling Tax'),
(1173, 1, 'text_igst', 'specific', 'IGST'),
(1174, 1, 'text_cgst', 'specific', 'CGST'),
(1175, 1, 'text_sgst', 'specific', 'SGST'),
(1176, 1, 'text_return_amount', 'specific', 'Return Amount'),
(1177, 1, 'text_sell_due_paid_success', 'specific', 'Due successfully paid'),
(1178, 1, 'text_images', 'specific', 'Images'),
(1179, 1, 'label_hsn_code', 'specific', 'HSN Code'),
(1180, 1, 'label_select', 'specific', '-- Please Select --'),
(1181, 1, 'label_sold', 'specific', 'Sold'),
(1182, 1, 'button_view_details', 'specific', 'View'),
(1183, 1, 'text_installment_details', 'specific', 'Installment Details'),
(1184, 1, 'label_initial_payment', 'specific', 'Initial Payment'),
(1185, 1, 'label_interval_count', 'specific', 'Internal Count'),
(1186, 1, 'label_installment_count', 'specific', 'Installment Count'),
(1187, 1, 'label_last_installment_date', 'specific', 'Last Installment Date'),
(1188, 1, 'label_installment_end_date', 'specific', 'Installment End Date'),
(1189, 1, 'label_sl', 'specific', 'SL'),
(1190, 1, 'text_update_installment_payment_success', 'specific', 'Installment payment successfull'),
(1191, 1, 'error_amount_exceed', 'specific', 'Amount Exceed'),
(1192, 1, 'text_expiry', 'specific', 'Expiry'),
(1193, 1, 'text_opening_balance_update_success', 'specific', 'Opening balance successfully updated'),
(1194, 1, 'title_reset_your_system', 'specific', 'Reset your system'),
(1195, 1, 'title_sell_log', 'specific', 'Sell Log'),
(1196, 1, 'text_sell_log_title', 'specific', 'Sell Log'),
(1197, 1, 'text_sell_title', 'specific', 'Sell'),
(1198, 1, 'label_gtin', 'specific', ''),
(1199, 1, 'button_add_balance', 'specific', 'Add Balance'),
(1200, 1, 'button_statement', 'specific', 'Statement'),
(1201, 1, 'text_all_invoice', 'specific', 'All Invoice'),
(1202, 1, 'label_prev_due', 'specific', 'Prev. Due'),
(1203, 1, 'error_invoice_id', 'specific', 'Invoice id is not valid'),
(1204, 1, 'title_settings', 'specific', ''),
(1205, 1, 'text_cronjob', 'specific', ''),
(1208, 1, 'button_due_invoice_list', 'specific', ''),
(1209, 1, 'text_substract_amount', 'specific', ''),
(1210, 1, 'text_balance_added', 'specific', ''),
(1211, 1, 'button_substract_balance', 'specific', ''),
(1212, 1, 'title_purchase_log', 'specific', 'Purchase log'),
(1213, 1, 'text_purchase_log_title', 'specific', 'Purchase Log'),
(1214, 1, 'title_log_in', 'specific', ''),
(1215, 1, 'text_demo', 'specific', 'This is a demo version. Data will be reset in every 6 hours interval. &lt;a style=&quot;font-weight:bold&quot; href=&quot;http://docs.itsolution24.com/pos/&quot;&gt;Online Documentation&lt;/a&gt;  |  &lt;a style=&quot;color:#aafff0;font-weight:bold&quot; href=&quot;https://codecanyon.net/cart/configure_before_adding/22702683&quot;&gt;Buy Now&lt;/a&gt;'),
(1216, 1, 'error_disabled_in_demo', 'specific', 'This feature is disable in error!'),
(1217, 1, 'text_order_title', 'specific', ''),
(1218, 1, 'error_purchase_price', 'specific', ''),
(1219, 1, 'text_list__transfer__title', 'specific', ''),
(1220, 1, 'text_download_sample_format_file', 'specific', ''),
(1221, 1, 'text_barcode_print', 'specific', ''),
(1222, 1, 'button_semd_email', 'specific', ''),
(1223, 1, 'button_send_email', 'specific', 'Send Email'),
(1224, 1, 'error_email_not_sent', 'specific', ''),
(1225, 1, 'text_success_email_sent', 'specific', ''),
(1226, 1, 'button_installment_payment', 'specific', ''),
(1227, 1, 'text_sell_log_list_title', 'specific', 'Sell Log Details'),
(1228, 1, 'text_purchase_log_list_title', 'specific', 'Purchase Log Details'),
(1229, 1, 'text_stock_transfer_title', 'specific', ''),
(1230, 1, 'title_receive', 'specific', 'Receive'),
(1231, 1, 'text_stock_receive_title', 'specific', 'Stock Receive'),
(1232, 1, 'text_receive_title', 'specific', 'Receive'),
(1233, 1, 'text_list__receive__title', 'specific', 'Receive Details'),
(1234, 1, 'label_what_for', 'specific', ''),
(1235, 1, 'error_out_of_stock', 'specific', ''),
(1236, 1, 'xxx', 'specific', ''),
(1237, 1, 'error_login', 'specific', ''),
(1238, 1, 'text_purchase_due_paid_success', 'specific', ''),
(1239, 1, 'text_trash', 'specific', ''),
(1240, 1, 'button_restore_all', 'specific', 'Restore All'),
(1241, 1, 'success_restore_all', 'specific', ''),
(1242, 1, 'title_customer_statement', 'specific', ''),
(1243, 1, 'text_statement_title', 'specific', ''),
(1244, 1, 'title_profit', 'specific', ''),
(1245, 1, 'error_return_quantity_exceed', 'specific', ''),
(1246, 1, 'label_transferred_from', 'specific', ''),
(1247, 1, 'label_member_since', 'specific', ''),
(1248, 1, 'text_not_found', 'specific', ''),
(1249, 1, 'label_logged_in', 'specific', ''),
(1250, 1, 'text_disabled', 'specific', ''),
(1251, 1, 'text_gtin', 'specific', ''),
(1252, 1, 'text_balance', 'specific', ''),
(1253, 1, 'error_invalid_username_password', 'specific', ''),
(1254, 1, 'error_installment_interest_percentage', 'specific', ''),
(1255, 1, 'error_installment_interest_amount', 'specific', ''),
(1256, 1, 'button_resend', 'specific', ''),
(1257, 1, 'error_sms_not_sent', 'specific', ''),
(1258, 1, 'text_sms_logs_title', 'specific', ''),
(1259, 1, 'text_sms_history_title', 'specific', ''),
(1260, 1, 'error_mobile_exist', 'specific', ''),
(1261, 1, 'text_success_sms_schedule', 'specific', ''),
(1262, 1, 'text_success_sms_added_to_schedule', 'specific', ''),
(1263, 1, 'text_mimsms', 'specific', ''),
(1264, 1, 'label_api_token', 'specific', ''),
(1265, 1, 'error_gateway', 'specific', ''),
(1266, 1, 'error_sms_not_send', 'specific', ''),
(1267, 1, 'invoice_sms_text', 'specific', 'Dear [customer_name],  Invoice ID: [invoice_id],  Payable: [payable_amount],  Paid: [paid_amount] ,  Due: [due]. Purchase at- [date_time]. Regards, [store_name],  [address]'),
(1268, 1, 'text_stock_register', 'specific', ''),
(1269, 1, 'text_urdu', 'specific', ''),
(1270, 1, 'error_default_language', 'specific', ''),
(1271, 1, 'error_active_or_sold', 'specific', ''),
(1272, 1, 'title_home', 'specific', 'Home'),
(1273, 1, 'error_supplier_name', 'specific', ''),
(1274, 1, 'error_expired_date_belowx', 'specific', ''),
(1275, 1, 'title_categories', 'specific', ''),
(1276, 1, 'title_products', 'specific', 'Products'),
(1277, 1, 'title_shop_on_sale', 'specific', ''),
(1278, 1, 'title_cart', 'specific', 'Cart'),
(1279, 1, 'title_wishlist', 'specific', ''),
(1280, 1, 'title_account', 'specific', ''),
(1281, 1, 'title_contact', 'specific', ''),
(1282, 1, 'title_contact_us', 'specific', ''),
(1283, 1, 'title_return_refund', 'specific', ''),
(1284, 1, 'title_faq', 'specific', ''),
(1285, 1, 'title_terms_condition', 'specific', ''),
(1286, 1, 'title_support', 'specific', ''),
(1287, 1, 'title_login', 'specific', ''),
(1288, 1, 'title_about', 'specific', ''),
(1289, 1, 'text_restore_completed', 'specific', 'Restored successfully completed'),
(1290, 1, 'error_receipt_printer', 'specific', 'Receipt printer is not valid'),
(1291, 1, 'title_checkout', 'specific', 'Checkout'),
(1292, 1, 'label_credit_balance', 'specific', 'Credit Balance'),
(1293, 1, 'label_giftcard_taken', 'specific', 'Gift Card taken'),
(1294, 1, 'text_are_you_sure', 'specific', 'Are you sure?'),
(1295, 1, 'text_information', 'specific', 'Information'),
(1296, 1, 'text_store_access_success', 'specific', 'Store  successfully activated'),
(1297, 1, 'title_cart_empty', 'specific', 'Cart is empty'),
(1298, 1, 'title_payment', 'specific', 'Payment'),
(1299, 1, 'error_installment_duration', 'specific', 'Installment duration is not valid'),
(1300, 1, 'error_password_mismatch', 'specific', 'Confirm password did\'t match'),
(1301, 1, 'error_email_exist', 'specific', 'Email already exists!'),
(9493, 1, 'error_invalid_purchase_code', 'specific', 'Invalid Purchase Code'),
(9494, 1, 'label_member_since', 'specific', 'Member Since'),
(9497, 1, 'error_printer_ip_address_or_port', 'specific', 'IP address or Port'),
(9498, 1, 'error_printer_path', 'specific', 'Printer Path'),
(9499, 1, 'text_barcode_print', 'specific', 'Barcode Print'),
(9500, 1, 'label_select', 'specific', 'Select'),
(9501, 1, 'label_sold', 'specific', 'Sold'),
(9504, 1, 'error_invalid_username_password', 'specific', 'Username or Password is invalid'),
(9505, 1, 'text_order_title', 'specific', 'Order'),
(9506, 1, 'placeholder_search_here', 'specific', 'Search here...'),
(9533, 1, 'text_order', 'specific', 'Order'),
(9534, 1, 'menu_order', 'specific', 'ORDER'),
(9535, 1, 'menu_hold_order', 'specific', 'Hold Order'),
(9543, 1, 'menu_stock_transfer', 'specific', 'Stock Transfer'),
(9552, 1, 'button_gift_card', 'specific', 'Gift Card'),
(9556, 1, 'title_settings', 'specific', 'Settings'),
(9559, 1, 'placeholder_input_discount_amount', 'specific', 'Discount Amount'),
(9560, 1, 'text_sell_due_paid_success', 'specific', 'Successfully Paid'),
(9561, 1, 'button_due_invoice_list', 'specific', 'Due Invoice List'),
(9562, 1, 'button_add_balance', 'specific', 'Add Balance'),
(9563, 1, 'error_pmethod_id', 'specific', 'Payment method is not valid'),
(9564, 1, 'text_balance_added', 'specific', 'Balance successfully added'),
(9565, 1, 'button_sell_product', 'specific', 'Sell Product'),
(9566, 1, 'error_pmethod_code', 'specific', 'Payment method code is not valid'),
(9567, 1, 'invoice_sms_text', 'specific', 'SMS'),
(9568, 1, 'error_installment_duration', 'specific', 'Installment duration is not valid'),
(9569, 1, 'button_view_details', 'specific', 'View Details'),
(9570, 1, 'text_installment_details', 'specific', 'Installment Details'),
(9571, 1, 'label_initial_payment', 'specific', 'Initial Payment'),
(9572, 1, 'label_interval_count', 'specific', 'Interval Count'),
(9573, 1, 'label_installment_count', 'specific', 'Installment Count'),
(9574, 1, 'label_last_installment_date', 'specific', 'Last Installment Date'),
(9575, 1, 'label_installment_end_date', 'specific', 'Intallment End Date'),
(9576, 1, 'text_all_invoice', 'specific', 'All Invoice'),
(9577, 1, 'text_all_due', 'specific', 'All Due'),
(9578, 1, 'button_purchase', 'specific', 'Purchase'),
(9579, 1, 'error_login_attempt', 'specific', 'Error Login Attempt'),
(9580, 1, 'error_login_attempt_exceed', 'specific', 'Login Attempt Exceed'),
(9581, 1, 'error_login_attempts_exceeded', 'specific', 'Login attempt exceeded'),
(9582, 1, 'label_logged_in', 'specific', 'Logged In'),
(9583, 1, 'error_mobile_exist', 'specific', 'Mobile number already exist.'),
(9584, 1, 'error_login', 'specific', 'Login Error.'),
(9585, 1, 'button_product_purchase', 'specific', 'Product purchase'),
(9586, 1, 'button_add_purchase', 'specific', 'Add Purchase'),
(9587, 1, 'label_change', 'specific', 'Change'),
(9588, 1, 'text_demo', 'specific', 'This is a demo version. Data will be reset in every 6 hours interval. &lt;a style=&quot;color:#aafff0;font-weight:bold&quot; href=&quot;https://codecanyon.net/cart/configure_before_adding/22702683&quot;&gt;Buy Now&lt;/a&gt;'),
(9589, 1, 'error_disabled_in_demo', 'specific', 'This feature disabled in demo.'),
(9590, 1, 'button_substract_balance', 'specific', 'Substract Balance'),
(9591, 1, 'error_amount_exceed', 'specific', 'Amount is exceed.'),
(9592, 1, 'text_balance_substracted', 'specific', 'Balance successfully substracted.'),
(9593, 1, 'title_customer_transaction', 'specific', 'Customer Transaction'),
(9594, 1, 'text_customer_transaction_title', 'specific', 'Customer Transaction'),
(9595, 1, 'text_customer_transaction_list_title', 'specific', 'Customer Transaction List'),
(9596, 1, 'title_supplier_transaction', 'specific', 'Supplier Transaction'),
(9597, 1, 'text_supplier_transaction_title', 'specific', 'Supplier Transaction'),
(9598, 1, 'error_card_no', 'specific', 'Card no. is not valid'),
(9599, 1, 'error_activate_permission', 'specific', 'Activate permission is not valid.'),
(9600, 1, 'error_discount_amount_exceed', 'specific', 'Discount amount exceed'),
(9601, 1, 'error_unexpected', 'specific', 'Unexpected error.'),
(9602, 1, 'text_returns', 'specific', 'Returns'),
(9603, 1, 'label_sl', 'specific', 'Sl'),
(9604, 1, 'label_sup_id', 'specific', 'Sup Id'),
(9605, 1, 'label_delete_all', 'specific', 'Delete All'),
(9606, 1, 'label_insert_store_to', 'specific', 'Insert Store To'),
(9607, 1, 'label_insert_store_content_into', 'specific', 'Insert Content To'),
(9608, 1, 'error_store_name', 'specific', 'Store name is not valid'),
(9609, 1, 'error_email_exist', 'specific', 'Email already exist'),
(9610, 1, 'error_customer_gift_card_exist', 'specific', 'Customer giftcard already exist'),
(9611, 1, 'text_expiry', 'specific', 'Expiry'),
(9612, 1, 'label_transferred_from', 'specific', 'Transfered From'),
(9613, 1, 'text_download_samdple_format_file', 'specific', 'Download Sample Format'),
(9614, 1, 'store_code 1 is not valid!', 'specific', 'Store code is not valid'),
(9615, 1, 'text_purchase_due_paid_success', 'specific', 'Successfully Paid'),
(9616, 1, 'error_invalid_balance', 'specific', 'Invalid Balance'),
(9617, 1, 'text_opening_balance_update_success', 'specific', 'Opening balance successfully updated'),
(9618, 1, 'button_installment_payment', 'specific', 'Installment Payment'),
(9619, 1, 'text_update_installment_payment_success', 'specific', 'Installment payment successfully updated'),
(9620, 1, 'error_email_address', 'specific', 'Email address is not valid'),
(9621, 1, 'email_sent_successful', 'specific', 'Email successfully sent'),
(9622, 1, 'error_id', 'specific', 'Id is not valid'),
(9625, 1, 'store_code store2 is not valid!', 'specific', ''),
(9626, 1, 'error_xls_sheet_not_found', 'specific', ''),
(9629, 1, 'text_cronjob', 'specific', ''),
(9714, 1, 'text_delete_holding_order_success', 'specific', 'Holding order successfully deleted'),
(9715, 1, 'label_reference_format', 'specific', 'Reference Format'),
(9716, 1, 'label_sales_reference_prefix', 'specific', 'Sales Prefix'),
(9717, 1, 'text_expired_listing_title', 'specific', 'Expired Listing'),
(9718, 1, 'label_item_quantity', 'specific', 'Item Quantity'),
(9719, 1, 'error_source', 'specific', 'Source is not valid'),
(9720, 1, 'label_returned_at', 'specific', 'Returned At'),
(9721, 1, 'error_print_permission', 'specific', 'You don\'t have permission in printing.'),
(9722, 1, 'text_due_incoice', 'specific', 'Due Invoice'),
(9723, 1, 'text_loan_details', 'specific', 'Loan Details'),
(9724, 1, 'label_paid_by', 'specific', 'Paid By'),
(9729, 1, 'error_order_title', 'specific', 'Order'),
(9730, 1, 'button_conform_order', 'specific', 'Order Confirmed'),
(9731, 1, 'text_order_successfully_placed', 'specific', 'Order Successfully Placed'),
(9732, 1, 'text_order_placed', 'specific', 'Order Placed'),
(9733, 1, 'title_order_placed', 'specific', 'Order successfully placed'),
(9734, 1, 'error_address', 'specific', 'Address field is required'),
(9735, 1, 'error_current_password', 'specific', 'Current Password'),
(9736, 1, 'error_new_password', 'specific', 'New Password'),
(9737, 1, 'error_current_password_not_matched', 'specific', 'Passwords not matched!'),
(9738, 1, 'text_password_update_success', 'specific', 'Password updated successfully'),
(9739, 1, 'error_full_name', 'specific', 'Full name'),
(9740, 1, 'title_register', 'specific', 'Register'),
(9741, 1, 'error_record_not_found', 'specific', 'Recored not found!'),
(9742, 1, 'text_account_created', 'specific', 'Account created successfully'),
(9743, 1, 'text_login_success', 'specific', 'Successfully Logged in'),
(9744, 1, 'title_view_order', 'specific', 'View Order'),
(9745, 1, 'title_order', 'specific', 'Order'),
(9746, 1, 'text_new_order_title', 'specific', 'New Order'),
(9747, 1, 'text_order_list_title', 'specific', 'Order List'),
(9748, 1, 'label_shipping_and_billing_address', 'specific', 'Shipping '),
(9749, 1, 'label_order_status', 'specific', 'Order Status'),
(9750, 1, 'title_order_edit', 'specific', 'Edit Order'),
(9751, 1, 'text_order_edit_title', 'specific', 'Edit Order'),
(9752, 1, 'text_order_update_success', 'specific', 'Order successfully updated'),
(9753, 1, 'label_insert_content_into', 'specific', 'Insert Content Into'),
(9754, 1, 'label_delete_the_product', 'specific', 'Delete The Product'),
(9755, 1, 'label_soft_delete_the_product', 'specific', 'Soft Delete The Product'),
(9756, 1, 'error_phone_exist', 'specific', 'Phone number already exists'),
(9757, 1, 'title_stores', 'specific', 'Stores'),
(9758, 1, 'text_email_update_success', 'specific', 'Successfully Updated'),
(9759, 1, 'text_phone_update_success', 'specific', 'Phone number updated successfully'),
(9760, 1, 'text_phone_number_update_success', 'specific', 'Phone number updated successfully'),
(9770, 1, 'label_link', 'specific', 'Link'),
(9771, 1, 'error_unit_code', 'specific', 'Unit code is not valid'),
(9772, 1, 'error_service_can_not_be_returned', 'specific', 'Service can\'t be returned'),
(9773, 1, 'error_invalid_product_type', 'specific', 'Product type is not valid'),
(9774, 1, 'error_invalid_barcode_symbology', 'specific', 'Barcode symbology is not valid'),
(9775, 1, 'store_code store1111 is not valid!', 'specific', 'Store code is not valid'),
(9776, 1, 'error_category_slug', 'specific', 'Category slug is not valid'),
(9777, 1, 'error_invalid_category_slug', 'specific', 'Category slug is not valid'),
(9778, 1, 'error_invalid_unit_code', 'specific', 'Unit code is not valid'),
(9779, 1, 'error_invalid_taxrate_code', 'specific', 'Taxrate code is not valid'),
(9780, 1, 'error_invalid_tax_method', 'specific', 'Tax method is not valid'),
(9781, 1, 'error_invalid_supplier_code', 'specific', 'Supplier code is not valid'),
(9782, 1, 'error_invalid_brand_code', 'specific', 'Brand code is not valid'),
(9783, 1, 'error_invalid_box_code', 'specific', 'Box code is not valid'),
(9784, 1, 'error_invalid_cost_price', 'specific', 'Cost price is not valid'),
(9785, 1, 'button_sell_list', 'specific', 'Sell List'),
(9786, 1, 'text_redirecting_to_dashbaord', 'specific', NULL),
(9787, 7, 'title_language_translation', 'specific', 'TRADUCCION'),
(9788, 7, 'text_english', 'specific', 'Ingles'),
(9789, 7, 'text_arabic', 'specific', 'Arabe'),
(9790, 7, 'text_bangla', 'specific', 'Bangladesh'),
(9791, 7, 'text_hindi', 'specific', 'Hindi'),
(9792, 7, 'text_french', 'specific', 'Frances'),
(9793, 7, 'text_Germany', 'specific', 'Aleman'),
(9794, 7, 'text_spanish', 'specific', 'Español'),
(9795, 7, 'text_pos', 'specific', 'pos'),
(9796, 7, 'menu_pos', 'specific', 'pos'),
(9797, 7, 'text_cashbook_report', 'specific', 'Informe de caja'),
(9798, 7, 'menu_cashbook', 'specific', 'LIBRO DE PAGO'),
(9799, 7, 'text_invoice', 'specific', 'Factura'),
(9800, 7, 'menu_invoice', 'specific', 'Factura'),
(9801, 7, 'text_user_preference', 'specific', 'Preferencia de usuario'),
(9802, 7, 'text_settings', 'specific', 'Configuraciones'),
(9803, 7, 'text_stock_alert', 'specific', 'Alerta de stock'),
(9804, 7, 'text_itsolution24', 'specific', 'Expira'),
(9805, 7, 'text_fullscreen', 'specific', 'tusolutionweb'),
(9806, 7, 'text_reports', 'specific', 'Reportes'),
(9807, 7, 'text_lockscreen', 'specific', 'Bloquear pantalla'),
(9808, 7, 'text_logout', 'specific', 'Cerrar sesion'),
(9809, 7, 'menu_dashboard', 'specific', 'DASHBOARD'),
(9810, 7, 'menu_point_of_sell', 'specific', 'PUNTO DE VENTA'),
(9811, 7, 'menu_sell', 'specific', 'VENTAS'),
(9812, 7, 'menu_sell_list', 'specific', 'LISTAS VENTAS'),
(9813, 7, 'menu_return_list', 'specific', 'LISTA DEVUELTOS'),
(9814, 7, 'menu_sell_log', 'specific', 'PEDIDOS'),
(9815, 7, 'menu_giftcard', 'specific', 'TARJETA REGALO'),
(9816, 7, 'menu_add_giftcard', 'specific', 'AGREGAR TARJETA REGALO'),
(9817, 7, 'menu_giftcard_list', 'specific', 'LISTA TARJETA REGALO'),
(9818, 7, 'menu_giftcard_topup', 'specific', 'TARJETA DE REGALO'),
(9819, 7, 'menu_quotation', 'specific', 'COTIZACIÓN'),
(9820, 7, 'menu_add_quotation', 'specific', 'AGREGAR COTIZACION'),
(9821, 7, 'menu_quotation_list', 'specific', 'LISTA DE COTIZACIONES'),
(9822, 7, 'menu_installment', 'specific', 'PAGOS'),
(9823, 7, 'menu_installment_list', 'specific', 'LISTA PAGOS'),
(9824, 7, 'menu_payment_list', 'specific', 'LISTA DE PAGOS'),
(9825, 7, 'menu_payment_due_today', 'specific', 'PAGO DEBIDO HOY'),
(9826, 7, 'menu_payment_due_all', 'specific', 'PAGO DEBIDO A TODOS'),
(9827, 7, 'menu_payment_due_expired', 'specific', 'PAGO POR EXP.'),
(9828, 7, 'menu_overview_report', 'specific', 'INFORME GENERAL'),
(9829, 7, 'menu_purchase', 'specific', 'COMPRA'),
(9830, 7, 'menu_add_purchase', 'specific', 'AGREGAR COMPRA'),
(9831, 7, 'menu_purchase_list', 'specific', 'LISTA DE COMPRAS'),
(9832, 7, 'menu_due_invoice', 'specific', 'FACTURA DEBIDA'),
(9833, 7, 'menu_purchase_logs', 'specific', 'REGISTROS COMPRAS'),
(9834, 7, 'menu_transfer', 'specific', 'TRANSFERENCIA DE STOCK'),
(9835, 7, 'menu_add_transfer', 'specific', 'AGREGAR TRANSFERENCIA'),
(9836, 7, 'menu_transfer_list', 'specific', 'LISTA DE TRANSFERENCIA'),
(9837, 7, 'menu_receive_list', 'specific', 'RECIBIR LISTA'),
(9838, 7, 'menu_product', 'specific', 'PRODUCTO'),
(9839, 7, 'menu_product_list', 'specific', 'LISTA DE PRODUCTOS'),
(9840, 7, 'menu_add_product', 'specific', 'AGREGAR PRODUCTO'),
(9841, 7, 'menu_barcode_print', 'specific', ' CÓDIGO DE BARRAS'),
(9842, 7, 'menu_category', 'specific', 'LISTA DE CATEGORÍA'),
(9843, 7, 'menu_add_category', 'specific', 'AGREGAR CATEGORÍA'),
(9844, 7, 'menu_product_import', 'specific', 'IMPORTACIÓN (.xls)'),
(9845, 7, 'menu_stock_alert', 'specific', 'ALERTA DE STOCK'),
(9846, 7, 'menu_customer', 'specific', 'CLIENTE'),
(9847, 7, 'menu_add_customer', 'specific', 'AGREGAR CLIENTE'),
(9848, 7, 'menu_customer_list', 'specific', 'LISTA DE CLIENTES'),
(9849, 7, 'menu_statements', 'specific', 'DECLARACIONES'),
(9850, 7, 'menu_supplier', 'specific', 'PROVEEDOR'),
(9851, 7, 'menu_add_supplier', 'specific', 'AGREGAR PROVEEDOR'),
(9852, 7, 'menu_supplier_list', 'specific', 'LISTA DE PROVEEDORES'),
(9853, 7, 'menu_accounting', 'specific', 'CONTABILIDAD'),
(9854, 7, 'menu_new_deposit', 'specific', 'DEPOSITAR'),
(9855, 7, 'menu_new_withdraw', 'specific', 'RETIRAR'),
(9856, 7, 'menu_list_transactions', 'specific', 'LISTA TRANSACCIONES'),
(9857, 7, 'menu_new_transfer', 'specific', 'AGREGAR TRANSFERENCIA'),
(9858, 7, 'menu_list_transfer', 'specific', 'LISTA DE TRANSFERENCIA'),
(9859, 7, 'menu_add_bank_account', 'specific', 'AGREGAR CUENTA BANCARIA'),
(9860, 7, 'menu_bank_accounts', 'specific', 'LISTA DE CUENTAS BANCARIAS'),
(9861, 7, 'menu_income_source', 'specific', 'FUENTE DE INGRESOS'),
(9862, 7, 'menu_balance_sheet', 'specific', 'FUENTE DE INGRESOS'),
(9863, 7, 'menu_income_monthwise', 'specific', 'INGRESOS MESES'),
(9864, 7, 'menu_expense_monthwise', 'specific', 'GASTOS MESES'),
(9865, 7, 'menu_income_and_expense', 'specific', 'INGRESOS VS GASTOS'),
(9866, 7, 'menu_profit_and_loss', 'specific', 'INGRESOS VS GASTOS'),
(9867, 7, 'menu_expenditure', 'specific', 'GASTO'),
(9868, 7, 'menu_create_expense', 'specific', 'AGREGAR GASTOS'),
(9869, 7, 'menu_expense_list', 'specific', 'LISTA DE GASTOS'),
(9870, 7, 'menu_summary', 'specific', 'RESUMEN'),
(9871, 7, 'menu_loan_manager', 'specific', 'GERENTE DE PRESTAMO'),
(9872, 7, 'menu_loan_list', 'specific', 'LISTA DE PRESTAMOS'),
(9873, 7, 'menu_take_loan', 'specific', 'TOMAR PRESTAMO'),
(9874, 7, 'menu_loan_summary', 'specific', 'RESUMEN'),
(9875, 7, 'menu_reports', 'specific', 'REPORTES'),
(9876, 7, 'menu_report_overview', 'specific', 'INFORME GENERAL'),
(9877, 7, 'menu_report_collection', 'specific', 'INFORME DE COLECCIÓN'),
(9878, 7, 'menu_report_due_collection', 'specific', 'INFORME COLECCION  DEBIDA '),
(9879, 7, 'menu_report_due_paid', 'specific', 'INFORME PAGOS DEBIDO '),
(9880, 7, 'menu_sell_report', 'specific', 'INFORME DE VENTA'),
(9881, 7, 'menu_purchase_report', 'specific', 'INFORME DE COMPRA'),
(9882, 7, 'menu_sell_payment_report', 'specific', 'VENDER INFORME DE PAGO'),
(9883, 7, 'menu_purchase_payment_report', 'specific', 'INFORME PAGO'),
(9884, 7, 'menu_tax_report', 'specific', 'IMPUESTOS'),
(9885, 7, 'menu_purchase_tax_report', 'specific', 'INFORME IMPUESTOS '),
(9886, 7, 'menu_tax_overview_report', 'specific', 'INFORME RESUMEN DE IMPUESTOS'),
(9887, 7, 'menu_report_stock', 'specific', 'INFORME DE STOCK'),
(9888, 7, 'menu_analytics', 'specific', 'ANALITICA'),
(9889, 7, 'menu_sms', 'specific', 'SMS'),
(9890, 7, 'menu_send_sms', 'specific', 'ENVIAR SMS'),
(9891, 7, 'menu_sms_report', 'specific', 'INFORME SMS'),
(9892, 7, 'menu_sms_setting', 'specific', 'CONFIGURACIÓN DE SMS'),
(9893, 7, 'menu_user', 'specific', 'USUARIO'),
(9894, 7, 'menu_add_user', 'specific', 'AGREGAR USUARIO'),
(9895, 7, 'menu_user_list', 'specific', 'LISTA USUARIO'),
(9896, 7, 'menu_add_usergroup', 'specific', 'AGREGAR GRUPO DE USUARIOS'),
(9897, 7, 'menu_usergroup_list', 'specific', 'LISTA GRUPO DE USUARIOS'),
(9898, 7, 'menu_password', 'specific', 'PASSWORD'),
(9899, 7, 'menu_filemanager', 'specific', 'ADMINISTRADOR DE ARCHIVOS'),
(9900, 7, 'menu_system', 'specific', 'SISTEMA'),
(9901, 7, 'menu_store', 'specific', 'ALMACENAR'),
(9902, 7, 'menu_create_store', 'specific', 'CREAR TIENDA '),
(9903, 7, 'menu_store_list', 'specific', 'LISTA TIENDA'),
(9904, 7, 'menu_store_setting', 'specific', 'AJUSTE DE LA TIENDA'),
(9905, 7, 'menu_receipt_template', 'specific', 'PLANTILLA DE RECIBO'),
(9906, 7, 'menu_user_preference', 'specific', 'PREFERENCIA DE USUARIO'),
(9907, 7, 'menu_brand', 'specific', 'MARCA'),
(9908, 7, 'menu_add_brand', 'specific', 'AGREGAR MARCA'),
(9909, 7, 'menu_brand_list', 'specific', 'LISTA MARCA'),
(9910, 7, 'menu_currency', 'specific', 'MONEDA'),
(9911, 7, 'menu_pmethod', 'specific', 'METODO DE PAGO'),
(9912, 7, 'menu_unit', 'specific', 'UNIDAD'),
(9913, 7, 'menu_taxrate', 'specific', 'TASA DE IMPUESTO'),
(9914, 7, 'menu_box', 'specific', 'CAJA'),
(9915, 7, 'menu_printer', 'specific', 'IMPRESORA'),
(9916, 7, 'menu_language', 'specific', 'LENGUAJE'),
(9917, 7, 'menu_backup_restore', 'specific', 'COPIA DE SEGURIDAD DE RESTAURACION'),
(9918, 7, 'menu_data_reset', 'specific', 'RESETEAR DATOS'),
(9919, 7, 'menu_store_change', 'specific', 'CAMBIO DE TIENDA'),
(9920, 7, 'text_language_translation_title', 'specific', 'TRADUCCION LENGUAJE'),
(9921, 7, 'text_dashboard', 'specific', 'Dashboard'),
(9922, 7, 'text_translations', 'specific', 'TRADUCCION'),
(9923, 7, 'button_add_new_language', 'specific', 'AGREGAR LENGUAJE'),
(9924, 7, 'button_edit', 'specific', 'EDITAR'),
(9925, 7, 'button_delete', 'specific', 'BORRAR'),
(9926, 7, 'button_default', 'specific', 'POR DEFECTO'),
(9927, 7, 'button_dublicate_entry', 'specific', 'DUPLICAR'),
(9928, 7, 'button_empty_value', 'specific', 'VACIO'),
(9929, 7, 'label_key', 'specific', 'CLAVE'),
(9930, 7, 'label_value', 'specific', 'VALOR'),
(9931, 7, 'label_translate', 'specific', 'TRADUCIR'),
(9932, 7, 'label_delete', 'specific', 'BORRAR'),
(9933, 7, 'placeholder_search_here', 'specific', 'BUSCAR AQUI'),
(9934, 7, 'text_version', 'specific', 'VERSION'),
(9935, 7, 'button_today', 'specific', 'HOY'),
(9936, 7, 'button_last_7_days', 'specific', 'ULTIMOS 7 DIAS'),
(9937, 7, 'button_last_30_days', 'specific', 'ULTIMOS 30 DIAS'),
(9938, 7, 'button_last_365_days', 'specific', 'ULTIMO AÑO'),
(9939, 7, 'button_filter', 'specific', 'FILTRAR'),
(9940, 6, 'title_language_translation', 'specific', NULL),
(9941, 6, 'text_english', 'specific', NULL),
(9942, 6, 'text_arabic', 'specific', NULL),
(9943, 6, 'text_bangla', 'specific', NULL),
(9944, 6, 'text_hindi', 'specific', NULL),
(9945, 6, 'text_french', 'specific', NULL),
(9946, 6, 'text_Germany', 'specific', NULL),
(9947, 6, 'text_spanish', 'specific', NULL),
(9948, 6, 'text_pos', 'specific', NULL),
(9949, 6, 'menu_pos', 'specific', NULL),
(9950, 6, 'text_cashbook_report', 'specific', NULL),
(9951, 6, 'menu_cashbook', 'specific', NULL),
(9952, 6, 'text_invoice', 'specific', NULL),
(9953, 6, 'menu_invoice', 'specific', NULL),
(9954, 6, 'text_user_preference', 'specific', NULL),
(9955, 6, 'text_settings', 'specific', NULL),
(9956, 6, 'text_stock_alert', 'specific', NULL),
(9957, 6, 'text_itsolution24', 'specific', NULL),
(9958, 6, 'text_fullscreen', 'specific', NULL),
(9959, 6, 'text_reports', 'specific', NULL),
(9960, 6, 'text_lockscreen', 'specific', NULL),
(9961, 6, 'text_logout', 'specific', NULL),
(9962, 6, 'menu_dashboard', 'specific', NULL),
(9963, 6, 'menu_point_of_sell', 'specific', NULL),
(9964, 6, 'menu_sell', 'specific', NULL),
(9965, 6, 'menu_sell_list', 'specific', NULL),
(9966, 6, 'menu_return_list', 'specific', NULL),
(9967, 6, 'menu_sell_log', 'specific', NULL),
(9968, 6, 'menu_giftcard', 'specific', NULL),
(9969, 6, 'menu_add_giftcard', 'specific', NULL),
(9970, 6, 'menu_giftcard_list', 'specific', NULL),
(9971, 6, 'menu_giftcard_topup', 'specific', NULL),
(9972, 6, 'menu_quotation', 'specific', NULL),
(9973, 6, 'menu_add_quotation', 'specific', NULL),
(9974, 6, 'menu_quotation_list', 'specific', NULL),
(9975, 6, 'menu_installment', 'specific', NULL),
(9976, 6, 'menu_installment_list', 'specific', NULL),
(9977, 6, 'menu_payment_list', 'specific', NULL),
(9978, 6, 'menu_payment_due_today', 'specific', NULL),
(9979, 6, 'menu_payment_due_all', 'specific', NULL),
(9980, 6, 'menu_payment_due_expired', 'specific', NULL),
(9981, 6, 'menu_overview_report', 'specific', NULL),
(9982, 6, 'menu_purchase', 'specific', NULL),
(9983, 6, 'menu_add_purchase', 'specific', NULL),
(9984, 6, 'menu_purchase_list', 'specific', NULL),
(9985, 6, 'menu_due_invoice', 'specific', NULL),
(9986, 6, 'menu_purchase_logs', 'specific', NULL),
(9987, 6, 'menu_transfer', 'specific', NULL),
(9988, 6, 'menu_add_transfer', 'specific', NULL),
(9989, 6, 'menu_transfer_list', 'specific', NULL),
(9990, 6, 'menu_receive_list', 'specific', NULL),
(9991, 6, 'menu_product', 'specific', NULL),
(9992, 6, 'menu_product_list', 'specific', NULL),
(9993, 6, 'menu_add_product', 'specific', NULL),
(9994, 6, 'menu_barcode_print', 'specific', NULL),
(9995, 6, 'menu_category', 'specific', NULL),
(9996, 6, 'menu_add_category', 'specific', NULL),
(9997, 6, 'menu_product_import', 'specific', NULL),
(9998, 6, 'menu_stock_alert', 'specific', NULL),
(9999, 6, 'menu_customer', 'specific', NULL),
(10000, 6, 'menu_add_customer', 'specific', NULL),
(10001, 6, 'menu_customer_list', 'specific', NULL),
(10002, 6, 'menu_statements', 'specific', NULL),
(10003, 6, 'menu_supplier', 'specific', NULL),
(10004, 6, 'menu_add_supplier', 'specific', NULL),
(10005, 6, 'menu_supplier_list', 'specific', NULL),
(10006, 6, 'menu_accounting', 'specific', NULL),
(10007, 6, 'menu_new_deposit', 'specific', NULL),
(10008, 6, 'menu_new_withdraw', 'specific', NULL),
(10009, 6, 'menu_list_transactions', 'specific', NULL),
(10010, 6, 'menu_new_transfer', 'specific', NULL),
(10011, 6, 'menu_list_transfer', 'specific', NULL),
(10012, 6, 'menu_add_bank_account', 'specific', NULL),
(10013, 6, 'menu_bank_accounts', 'specific', NULL),
(10014, 6, 'menu_income_source', 'specific', NULL),
(10015, 6, 'menu_balance_sheet', 'specific', NULL),
(10016, 6, 'menu_income_monthwise', 'specific', NULL),
(10017, 6, 'menu_expense_monthwise', 'specific', NULL),
(10018, 6, 'menu_income_and_expense', 'specific', NULL),
(10019, 6, 'menu_profit_and_loss', 'specific', NULL),
(10020, 6, 'menu_expenditure', 'specific', NULL),
(10021, 6, 'menu_create_expense', 'specific', NULL),
(10022, 6, 'menu_expense_list', 'specific', NULL),
(10023, 6, 'menu_summary', 'specific', NULL),
(10024, 6, 'menu_loan_manager', 'specific', NULL),
(10025, 6, 'menu_loan_list', 'specific', NULL),
(10026, 6, 'menu_take_loan', 'specific', NULL),
(10027, 6, 'menu_loan_summary', 'specific', NULL),
(10028, 6, 'menu_reports', 'specific', NULL),
(10029, 6, 'menu_report_overview', 'specific', NULL),
(10030, 6, 'menu_report_collection', 'specific', NULL),
(10031, 6, 'menu_report_due_collection', 'specific', NULL),
(10032, 6, 'menu_report_due_paid', 'specific', NULL),
(10033, 6, 'menu_sell_report', 'specific', NULL),
(10034, 6, 'menu_purchase_report', 'specific', NULL),
(10035, 6, 'menu_sell_payment_report', 'specific', NULL),
(10036, 6, 'menu_purchase_payment_report', 'specific', NULL),
(10037, 6, 'menu_tax_report', 'specific', NULL),
(10038, 6, 'menu_purchase_tax_report', 'specific', NULL),
(10039, 6, 'menu_tax_overview_report', 'specific', NULL),
(10040, 6, 'menu_report_stock', 'specific', NULL),
(10041, 6, 'menu_analytics', 'specific', NULL),
(10042, 6, 'menu_sms', 'specific', NULL),
(10043, 6, 'menu_send_sms', 'specific', NULL),
(10044, 6, 'menu_sms_report', 'specific', NULL),
(10045, 6, 'menu_sms_setting', 'specific', NULL),
(10046, 6, 'menu_user', 'specific', NULL),
(10047, 6, 'menu_add_user', 'specific', NULL),
(10048, 6, 'menu_user_list', 'specific', NULL),
(10049, 6, 'menu_add_usergroup', 'specific', NULL),
(10050, 6, 'menu_usergroup_list', 'specific', NULL),
(10051, 6, 'menu_password', 'specific', NULL),
(10052, 6, 'menu_filemanager', 'specific', NULL);
INSERT INTO `language_translations` (`id`, `lang_id`, `lang_key`, `key_type`, `lang_value`) VALUES
(10053, 6, 'menu_system', 'specific', NULL),
(10054, 6, 'menu_store', 'specific', NULL),
(10055, 6, 'menu_create_store', 'specific', NULL),
(10056, 6, 'menu_store_list', 'specific', NULL),
(10057, 6, 'menu_store_setting', 'specific', NULL),
(10058, 6, 'menu_receipt_template', 'specific', NULL),
(10059, 6, 'menu_user_preference', 'specific', NULL),
(10060, 6, 'menu_brand', 'specific', NULL),
(10061, 6, 'menu_add_brand', 'specific', NULL),
(10062, 6, 'menu_brand_list', 'specific', NULL),
(10063, 6, 'menu_currency', 'specific', NULL),
(10064, 6, 'menu_pmethod', 'specific', NULL),
(10065, 6, 'menu_unit', 'specific', NULL),
(10066, 6, 'menu_taxrate', 'specific', NULL),
(10067, 6, 'menu_box', 'specific', NULL),
(10068, 6, 'menu_printer', 'specific', NULL),
(10069, 6, 'menu_language', 'specific', NULL),
(10070, 6, 'menu_backup_restore', 'specific', NULL),
(10071, 6, 'menu_data_reset', 'specific', NULL),
(10072, 6, 'menu_store_change', 'specific', NULL),
(10073, 6, 'text_language_translation_title', 'specific', NULL),
(10074, 6, 'text_dashboard', 'specific', NULL),
(10075, 6, 'text_translations', 'specific', NULL),
(10076, 6, 'button_add_new_language', 'specific', NULL),
(10077, 6, 'button_edit', 'specific', NULL),
(10078, 6, 'button_delete', 'specific', NULL),
(10079, 6, 'button_default', 'specific', NULL),
(10080, 6, 'button_dublicate_entry', 'specific', NULL),
(10081, 6, 'button_empty_value', 'specific', NULL),
(10082, 6, 'label_key', 'specific', NULL),
(10083, 6, 'label_value', 'specific', NULL),
(10084, 6, 'label_translate', 'specific', NULL),
(10085, 6, 'label_delete', 'specific', NULL),
(10086, 6, 'placeholder_search_here', 'specific', NULL),
(10087, 6, 'text_version', 'specific', NULL),
(10088, 6, 'button_today', 'specific', NULL),
(10089, 6, 'button_last_7_days', 'specific', NULL),
(10090, 6, 'button_last_30_days', 'specific', NULL),
(10091, 6, 'button_last_365_days', 'specific', NULL),
(10092, 6, 'button_filter', 'specific', NULL),
(10093, 7, 'button_translate', 'specific', 'TRADUCIR'),
(10094, 7, 'title_dashboard', 'specific', 'DASHBOARD'),
(10095, 7, 'button_pos', 'specific', 'POS'),
(10096, 7, 'button_sell_list', 'specific', 'LISTA VENTAS'),
(10097, 7, 'button_overview_report', 'specific', 'REPORTES GENERAL'),
(10098, 7, 'button_sell_report', 'specific', 'REPORTE VENTAS'),
(10099, 7, 'button_purchase_report', 'specific', 'REPORTE COMPRA'),
(10100, 7, 'button_stock_alert', 'specific', 'REPORTE ALERTA STOCK'),
(10101, 7, 'button_expired_alert', 'specific', 'REPORTE ALERTA EXPERADOS'),
(10102, 7, 'button_backup_restore', 'specific', 'RESTAURAR BACKUP'),
(10103, 7, 'button_stores', 'specific', 'TIENDAS'),
(10104, 7, 'text_total_invoice', 'specific', 'FACTURA TOTAL'),
(10105, 7, 'text_total_invoice_today', 'specific', 'FACTURA TOTAL HOY'),
(10106, 7, 'text_details', 'specific', 'DETALLES'),
(10107, 7, 'text_total_customer', 'specific', 'CLIENTE'),
(10108, 7, 'text_total_customer_today', 'specific', 'CLIENTE HOY'),
(10109, 7, 'text_total_supplier', 'specific', 'TOTAL PROVEEDORES'),
(10110, 7, 'text_total_supplier_today', 'specific', 'TOTAL PROVEEDORES HOY'),
(10111, 7, 'text_total_product', 'specific', 'TOTAL PRODUCTOS'),
(10112, 7, 'text_total_product_today', 'specific', 'TOTAL PRODUCTOS HOY'),
(10113, 7, 'text_recent_activities', 'specific', 'ACTIVIDADES RECIENTES'),
(10114, 7, 'text_sales', 'specific', 'VENTAS'),
(10115, 7, 'text_quotations', 'specific', 'CITAS'),
(10116, 7, 'text_purchases', 'specific', 'COMPRAS'),
(10117, 7, 'text_transfers', 'specific', 'TRANSFERENCIAS'),
(10118, 7, 'text_customers', 'specific', 'CLIENTES'),
(10119, 7, 'text_suppliers', 'specific', 'PROVEEDORES'),
(10120, 7, 'label_invoice_id', 'specific', 'ID FACTURA'),
(10121, 7, 'label_created_at', 'specific', 'CREANDO EN'),
(10122, 7, 'label_customer_name', 'specific', 'NOMBRE CLIENTE'),
(10123, 7, 'label_amount', 'specific', 'CANTIDAD'),
(10124, 7, 'label_payment_status', 'specific', 'ESTADO PAGO'),
(10125, 7, 'button_add_sales', 'specific', 'AGREGAR VENTAS'),
(10126, 7, 'button_list_sales', 'specific', 'LISTA VENTAS'),
(10127, 7, 'text_sales_amount', 'specific', 'CANTIDAD VENTA'),
(10128, 7, 'text_discount_given', 'specific', 'DESCUENTO'),
(10129, 7, 'text_due_given', 'specific', 'DEBIDO'),
(10130, 7, 'text_received_amount', 'specific', 'CANTIDAD RECIBIDA'),
(10131, 7, 'label_date', 'specific', 'FECHA'),
(10132, 7, 'label_reference_no', 'specific', 'NRO REFERENCIA'),
(10133, 7, 'label_customer', 'specific', 'CLIENTE'),
(10134, 7, 'label_status', 'specific', 'ESTADO'),
(10135, 7, 'button_add_quotations', 'specific', 'AGREGAR COTIZACION'),
(10136, 7, 'button_list_quotations', 'specific', 'LISTA COTIZACION'),
(10137, 7, 'label_supplier_name', 'specific', 'NOMBRE PROVEEDOR'),
(10138, 7, 'button_add_purchases', 'specific', 'AGREGAR COMPRAS'),
(10139, 7, 'button_list_purchases', 'specific', 'LISTA COMPRAS'),
(10140, 7, 'label_from', 'specific', 'DE'),
(10141, 7, 'label_to', 'specific', 'A'),
(10142, 7, 'label_quantity', 'specific', 'CANTIDAD'),
(10143, 7, 'button_add_transfers', 'specific', 'AGREGAR TRANSFERENCIA'),
(10144, 7, 'button_list_transfers', 'specific', 'LISTA TRANSFERENCIA'),
(10145, 7, 'label_phone', 'specific', 'CELULAR'),
(10146, 7, 'label_email', 'specific', 'EMAIL'),
(10147, 7, 'label_address', 'specific', 'DIRECCION'),
(10148, 7, 'button_add_customer', 'specific', 'CLIENTE'),
(10149, 7, 'button_list_customers', 'specific', 'CLIENTES'),
(10150, 7, 'button_add_supplier', 'specific', 'PROVEEDOR'),
(10151, 7, 'button_list_suppliers', 'specific', 'PROVEEDORES'),
(10152, 7, 'text_deposit_today', 'specific', 'DEPOSITADO HOY'),
(10153, 7, 'text_withdraw_today', 'specific', 'RETIRAR HOY'),
(10154, 7, 'text_recent_deposit', 'specific', 'DEPOSITO RECIENTE'),
(10155, 7, 'label_description', 'specific', 'DESCRIPCION'),
(10156, 7, 'button_view_all', 'specific', 'VER TODO'),
(10157, 7, 'text_recent_withdraw', 'specific', 'RETIRO RECIENTE'),
(10158, 7, 'title_income_vs_expense', 'specific', 'INGRESO VS GASTO'),
(10159, 7, 'text_download_as_jpg', 'specific', 'DESCARGAR IMAGEN'),
(10160, 7, 'label_day', 'specific', 'DIA'),
(10161, 7, 'text_income', 'specific', 'INGRESOS'),
(10162, 7, 'text_expense', 'specific', 'GASTOS'),
(10163, 7, 'text_income_vs_expense', 'specific', 'INGRESO VS GASTO'),
(10164, 2, 'title_dashboard', 'specific', NULL),
(10165, 2, 'text_english', 'specific', NULL),
(10166, 2, 'text_arabic', 'specific', NULL),
(10167, 2, 'text_bangla', 'specific', NULL),
(10168, 2, 'text_hindi', 'specific', NULL),
(10169, 2, 'text_french', 'specific', NULL),
(10170, 2, 'text_Germany', 'specific', NULL),
(10171, 2, 'text_spanish', 'specific', NULL),
(10172, 2, 'text_pos', 'specific', NULL),
(10173, 2, 'menu_pos', 'specific', NULL),
(10174, 2, 'text_cashbook_report', 'specific', NULL),
(10175, 2, 'menu_cashbook', 'specific', NULL),
(10176, 2, 'text_invoice', 'specific', NULL),
(10177, 2, 'menu_invoice', 'specific', NULL),
(10178, 2, 'text_user_preference', 'specific', NULL),
(10179, 2, 'text_settings', 'specific', NULL),
(10180, 2, 'text_stock_alert', 'specific', NULL),
(10181, 2, 'text_itsolution24', 'specific', NULL),
(10182, 2, 'text_fullscreen', 'specific', NULL),
(10183, 2, 'text_reports', 'specific', NULL),
(10184, 2, 'text_lockscreen', 'specific', NULL),
(10185, 2, 'text_logout', 'specific', NULL),
(10186, 2, 'menu_dashboard', 'specific', NULL),
(10187, 2, 'menu_point_of_sell', 'specific', NULL),
(10188, 2, 'menu_sell', 'specific', NULL),
(10189, 2, 'menu_sell_list', 'specific', NULL),
(10190, 2, 'menu_return_list', 'specific', NULL),
(10191, 2, 'menu_sell_log', 'specific', NULL),
(10192, 2, 'menu_giftcard', 'specific', NULL),
(10193, 2, 'menu_add_giftcard', 'specific', NULL),
(10194, 2, 'menu_giftcard_list', 'specific', NULL),
(10195, 2, 'menu_giftcard_topup', 'specific', NULL),
(10196, 2, 'menu_quotation', 'specific', NULL),
(10197, 2, 'menu_add_quotation', 'specific', NULL),
(10198, 2, 'menu_quotation_list', 'specific', NULL),
(10199, 2, 'menu_installment', 'specific', NULL),
(10200, 2, 'menu_installment_list', 'specific', NULL),
(10201, 2, 'menu_payment_list', 'specific', NULL),
(10202, 2, 'menu_payment_due_today', 'specific', NULL),
(10203, 2, 'menu_payment_due_all', 'specific', NULL),
(10204, 2, 'menu_payment_due_expired', 'specific', NULL),
(10205, 2, 'menu_overview_report', 'specific', NULL),
(10206, 2, 'menu_purchase', 'specific', NULL),
(10207, 2, 'menu_add_purchase', 'specific', NULL),
(10208, 2, 'menu_purchase_list', 'specific', NULL),
(10209, 2, 'menu_due_invoice', 'specific', NULL),
(10210, 2, 'menu_purchase_logs', 'specific', NULL),
(10211, 2, 'menu_transfer', 'specific', NULL),
(10212, 2, 'menu_add_transfer', 'specific', NULL),
(10213, 2, 'menu_transfer_list', 'specific', NULL),
(10214, 2, 'menu_receive_list', 'specific', NULL),
(10215, 2, 'menu_product', 'specific', NULL),
(10216, 2, 'menu_product_list', 'specific', NULL),
(10217, 2, 'menu_add_product', 'specific', NULL),
(10218, 2, 'menu_barcode_print', 'specific', NULL),
(10219, 2, 'menu_category', 'specific', NULL),
(10220, 2, 'menu_add_category', 'specific', NULL),
(10221, 2, 'menu_product_import', 'specific', NULL),
(10222, 2, 'menu_stock_alert', 'specific', NULL),
(10223, 2, 'menu_customer', 'specific', NULL),
(10224, 2, 'menu_add_customer', 'specific', NULL),
(10225, 2, 'menu_customer_list', 'specific', NULL),
(10226, 2, 'menu_statements', 'specific', NULL),
(10227, 2, 'menu_supplier', 'specific', NULL),
(10228, 2, 'menu_add_supplier', 'specific', NULL),
(10229, 2, 'menu_supplier_list', 'specific', NULL),
(10230, 2, 'menu_accounting', 'specific', NULL),
(10231, 2, 'menu_new_deposit', 'specific', NULL),
(10232, 2, 'menu_new_withdraw', 'specific', NULL),
(10233, 2, 'menu_list_transactions', 'specific', NULL),
(10234, 2, 'menu_new_transfer', 'specific', NULL),
(10235, 2, 'menu_list_transfer', 'specific', NULL),
(10236, 2, 'menu_add_bank_account', 'specific', NULL),
(10237, 2, 'menu_bank_accounts', 'specific', NULL),
(10238, 2, 'menu_income_source', 'specific', NULL),
(10239, 2, 'menu_balance_sheet', 'specific', NULL),
(10240, 2, 'menu_income_monthwise', 'specific', NULL),
(10241, 2, 'menu_expense_monthwise', 'specific', NULL),
(10242, 2, 'menu_income_and_expense', 'specific', NULL),
(10243, 2, 'menu_profit_and_loss', 'specific', NULL),
(10244, 2, 'menu_expenditure', 'specific', NULL),
(10245, 2, 'menu_create_expense', 'specific', NULL),
(10246, 2, 'menu_expense_list', 'specific', NULL),
(10247, 2, 'menu_summary', 'specific', NULL),
(10248, 2, 'menu_loan_manager', 'specific', NULL),
(10249, 2, 'menu_loan_list', 'specific', NULL),
(10250, 2, 'menu_take_loan', 'specific', NULL),
(10251, 2, 'menu_loan_summary', 'specific', NULL),
(10252, 2, 'menu_reports', 'specific', NULL),
(10253, 2, 'menu_report_overview', 'specific', NULL),
(10254, 2, 'menu_report_collection', 'specific', NULL),
(10255, 2, 'menu_report_due_collection', 'specific', NULL),
(10256, 2, 'menu_report_due_paid', 'specific', NULL),
(10257, 2, 'menu_sell_report', 'specific', NULL),
(10258, 2, 'menu_purchase_report', 'specific', NULL),
(10259, 2, 'menu_sell_payment_report', 'specific', NULL),
(10260, 2, 'menu_purchase_payment_report', 'specific', NULL),
(10261, 2, 'menu_tax_report', 'specific', NULL),
(10262, 2, 'menu_purchase_tax_report', 'specific', NULL),
(10263, 2, 'menu_tax_overview_report', 'specific', NULL),
(10264, 2, 'menu_report_stock', 'specific', NULL),
(10265, 2, 'menu_analytics', 'specific', NULL),
(10266, 2, 'menu_sms', 'specific', NULL),
(10267, 2, 'menu_send_sms', 'specific', NULL),
(10268, 2, 'menu_sms_report', 'specific', NULL),
(10269, 2, 'menu_sms_setting', 'specific', NULL),
(10270, 2, 'menu_user', 'specific', NULL),
(10271, 2, 'menu_add_user', 'specific', NULL),
(10272, 2, 'menu_user_list', 'specific', NULL),
(10273, 2, 'menu_add_usergroup', 'specific', NULL),
(10274, 2, 'menu_usergroup_list', 'specific', NULL),
(10275, 2, 'menu_password', 'specific', NULL),
(10276, 2, 'menu_filemanager', 'specific', NULL),
(10277, 2, 'menu_system', 'specific', NULL),
(10278, 2, 'menu_store', 'specific', NULL),
(10279, 2, 'menu_create_store', 'specific', NULL),
(10280, 2, 'menu_store_list', 'specific', NULL),
(10281, 2, 'menu_store_setting', 'specific', NULL),
(10282, 2, 'menu_receipt_template', 'specific', NULL),
(10283, 2, 'menu_user_preference', 'specific', NULL),
(10284, 2, 'menu_brand', 'specific', NULL),
(10285, 2, 'menu_add_brand', 'specific', NULL),
(10286, 2, 'menu_brand_list', 'specific', NULL),
(10287, 2, 'menu_currency', 'specific', NULL),
(10288, 2, 'menu_pmethod', 'specific', NULL),
(10289, 2, 'menu_unit', 'specific', NULL),
(10290, 2, 'menu_taxrate', 'specific', NULL),
(10291, 2, 'menu_box', 'specific', NULL),
(10292, 2, 'menu_printer', 'specific', NULL),
(10293, 2, 'menu_language', 'specific', NULL),
(10294, 2, 'menu_backup_restore', 'specific', NULL),
(10295, 2, 'menu_data_reset', 'specific', NULL),
(10296, 2, 'menu_store_change', 'specific', NULL),
(10297, 2, 'text_dashboard', 'specific', NULL),
(10298, 2, 'button_pos', 'specific', NULL),
(10299, 2, 'button_sell_list', 'specific', NULL),
(10300, 2, 'button_overview_report', 'specific', NULL),
(10301, 2, 'button_sell_report', 'specific', NULL),
(10302, 2, 'button_purchase_report', 'specific', NULL),
(10303, 2, 'button_stock_alert', 'specific', NULL),
(10304, 2, 'button_expired_alert', 'specific', NULL),
(10305, 2, 'button_backup_restore', 'specific', NULL),
(10306, 2, 'button_stores', 'specific', NULL),
(10307, 2, 'text_total_invoice', 'specific', NULL),
(10308, 2, 'text_total_invoice_today', 'specific', NULL),
(10309, 2, 'text_details', 'specific', NULL),
(10310, 2, 'text_total_customer', 'specific', NULL),
(10311, 2, 'text_total_customer_today', 'specific', NULL),
(10312, 2, 'text_total_supplier', 'specific', NULL),
(10313, 2, 'text_total_supplier_today', 'specific', NULL),
(10314, 2, 'text_total_product', 'specific', NULL),
(10315, 2, 'text_total_product_today', 'specific', NULL),
(10316, 2, 'text_recent_activities', 'specific', NULL),
(10317, 2, 'text_sales', 'specific', NULL),
(10318, 2, 'text_quotations', 'specific', NULL),
(10319, 2, 'text_purchases', 'specific', NULL),
(10320, 2, 'text_transfers', 'specific', NULL),
(10321, 2, 'text_customers', 'specific', NULL),
(10322, 2, 'text_suppliers', 'specific', NULL),
(10323, 2, 'label_invoice_id', 'specific', NULL),
(10324, 2, 'label_created_at', 'specific', NULL),
(10325, 2, 'label_customer_name', 'specific', NULL),
(10326, 2, 'label_amount', 'specific', NULL),
(10327, 2, 'label_payment_status', 'specific', NULL),
(10328, 2, 'button_add_sales', 'specific', NULL),
(10329, 2, 'button_list_sales', 'specific', NULL),
(10330, 2, 'text_sales_amount', 'specific', NULL),
(10331, 2, 'text_discount_given', 'specific', NULL),
(10332, 2, 'text_due_given', 'specific', NULL),
(10333, 2, 'text_received_amount', 'specific', NULL),
(10334, 2, 'label_date', 'specific', NULL),
(10335, 2, 'label_reference_no', 'specific', NULL),
(10336, 2, 'label_customer', 'specific', NULL),
(10337, 2, 'label_status', 'specific', NULL),
(10338, 2, 'button_add_quotations', 'specific', NULL),
(10339, 2, 'button_list_quotations', 'specific', NULL),
(10340, 2, 'label_supplier_name', 'specific', NULL),
(10341, 2, 'button_add_purchases', 'specific', NULL),
(10342, 2, 'button_list_purchases', 'specific', NULL),
(10343, 2, 'label_from', 'specific', NULL),
(10344, 2, 'label_to', 'specific', NULL),
(10345, 2, 'label_quantity', 'specific', NULL),
(10346, 2, 'button_add_transfers', 'specific', NULL),
(10347, 2, 'button_list_transfers', 'specific', NULL),
(10348, 2, 'label_phone', 'specific', NULL),
(10349, 2, 'label_email', 'specific', NULL),
(10350, 2, 'label_address', 'specific', NULL),
(10351, 2, 'button_add_customer', 'specific', NULL),
(10352, 2, 'button_list_customers', 'specific', NULL),
(10353, 2, 'button_add_supplier', 'specific', NULL),
(10354, 2, 'button_list_suppliers', 'specific', NULL),
(10355, 2, 'text_deposit_today', 'specific', NULL),
(10356, 2, 'text_withdraw_today', 'specific', NULL),
(10357, 2, 'text_recent_deposit', 'specific', NULL),
(10358, 2, 'label_description', 'specific', NULL),
(10359, 2, 'button_view_all', 'specific', NULL),
(10360, 2, 'text_recent_withdraw', 'specific', NULL),
(10361, 2, 'title_income_vs_expense', 'specific', NULL),
(10362, 2, 'text_download_as_jpg', 'specific', NULL),
(10363, 2, 'label_day', 'specific', NULL),
(10364, 2, 'text_income', 'specific', NULL),
(10365, 2, 'text_expense', 'specific', NULL),
(10366, 2, 'text_income_vs_expense', 'specific', NULL),
(10367, 2, 'placeholder_search_here', 'specific', NULL),
(10368, 2, 'text_version', 'specific', NULL),
(10369, 2, 'button_today', 'specific', NULL),
(10370, 2, 'button_last_7_days', 'specific', NULL),
(10371, 2, 'button_last_30_days', 'specific', NULL),
(10372, 2, 'button_last_365_days', 'specific', NULL),
(10373, 2, 'button_filter', 'specific', NULL),
(10374, 7, 'text_translation_success', 'specific', 'TRADUCCION EXITOSA'),
(10375, 7, 'title_pos', 'specific', 'POS'),
(10376, 7, 'text_gift_card', 'specific', 'TARJETA DE REGALO'),
(10377, 7, 'button_sell_gift_card', 'specific', 'VENTA TARJETA DE REGALO'),
(10378, 7, 'text_keyboard_shortcut', 'specific', 'ATAJOS TECLADO'),
(10379, 7, 'text_holding_order', 'specific', 'ORDEN DE ESPERA'),
(10380, 7, 'text_search_product', 'specific', 'BUSCAR PRODUCTO'),
(10381, 7, 'text_view_all', 'specific', 'VER TODOS'),
(10382, 7, 'button_add_product', 'specific', 'AGREGAR PRODUCTO'),
(10383, 7, 'button_add_purchase', 'specific', 'AGREGAR  COMPRA'),
(10384, 7, 'label_add_to_cart', 'specific', 'AGREGAR A CARRITO'),
(10385, 7, 'text_add_note', 'specific', 'AGREGAR NOTA'),
(10386, 7, 'label_due', 'specific', 'DEBIDO'),
(10387, 7, 'label_product', 'specific', 'PRODUCTO'),
(10388, 7, 'label_price', 'specific', 'PRECIO'),
(10389, 7, 'label_subtotal', 'specific', 'SUB TOTAL'),
(10390, 7, 'label_total_items', 'specific', 'TOTAL ARTICULOS'),
(10391, 7, 'label_total', 'specific', 'TOTAL'),
(10392, 7, 'label_discount', 'specific', 'DESCUENTO'),
(10393, 7, 'label_tax_amount', 'specific', 'MONTO IMPUESTO'),
(10394, 7, 'label_shipping_charge', 'specific', 'COSTO ENVIO'),
(10395, 7, 'label_others_charge', 'specific', 'OTROS COBROS'),
(10396, 7, 'label_total_payable', 'specific', 'TOTAL PAGO'),
(10397, 7, 'button_pay', 'specific', 'PAGA'),
(10398, 7, 'button_hold', 'specific', 'PEDIDOS'),
(10399, 7, 'text_general', 'specific', 'GENERAL'),
(10400, 7, 'text_images', 'specific', 'IMAGENES'),
(10401, 7, 'label_serial_no', 'specific', 'NRO SERIAL'),
(10402, 7, 'label_image', 'specific', 'IMAGEN'),
(10403, 7, 'label_url', 'specific', 'URL'),
(10404, 7, 'label_sort_order', 'specific', 'ORDEN DE CLASIFICACION'),
(10405, 7, 'label_action', 'specific', 'ACCION'),
(10406, 7, 'label_thumbnail', 'specific', 'MINIATURA'),
(10407, 7, 'label_product_type', 'specific', 'TIPO PRODUCTO'),
(10408, 7, 'text_standard', 'specific', 'STANDARD'),
(10409, 7, 'text_service', 'specific', 'SERVICIO'),
(10410, 7, 'label_name', 'specific', 'NOMBRE'),
(10411, 7, 'text_product', 'specific', 'PRODUCTO'),
(10412, 7, 'label_pcode', 'specific', 'CODIGO'),
(10413, 7, 'label_category', 'specific', 'CATEGORIA'),
(10414, 7, 'text_select', 'specific', 'SELECCIONAR'),
(10415, 7, 'label_supplier', 'specific', 'PROVEEDOR'),
(10416, 7, 'label_brand', 'specific', 'MARCA'),
(10417, 7, 'label_barcode_symbology', 'specific', 'SOMBOLA CODIGO DE BARRA'),
(10418, 7, 'label_box', 'specific', 'CAJA'),
(10419, 7, 'label_expired_date', 'specific', 'FECHA EXPIRA'),
(10420, 7, 'label_unit', 'specific', 'UNIDAD'),
(10421, 7, 'label_product_cost', 'specific', 'COSTO PRODUCTO'),
(10422, 7, 'label_product_price', 'specific', 'PRECIO PRODUCTO'),
(10423, 7, 'label_product_tax', 'specific', 'IMPUESTO PRODUCTO'),
(10424, 7, 'label_tax_method', 'specific', 'METODO DE IMPUESTOS'),
(10425, 7, 'text_inclusive', 'specific', 'INCLUSIVO'),
(10426, 7, 'text_exclusive', 'specific', 'EXCLUSIVO'),
(10427, 7, 'label_store', 'specific', 'ALMACENAR'),
(10428, 7, 'search', 'specific', 'BUSCAR'),
(10429, 7, 'label_alert_quantity', 'specific', 'CANTIDAD DE ALERTA'),
(10430, 7, 'text_active', 'specific', 'ACTIVO'),
(10431, 7, 'text_inactive', 'specific', 'INACTIVO'),
(10432, 7, 'button_save', 'specific', 'GUARDAR'),
(10433, 7, 'button_reset', 'specific', 'RESETEAR'),
(10434, 7, 'title_purchase', 'specific', 'COMPRA'),
(10435, 7, 'text_purchase_title', 'specific', 'TITULO COMPRA'),
(10436, 7, 'text_add', 'specific', 'AGREGAR'),
(10437, 7, 'text_new_purchase_title', 'specific', 'NUEVO TITULO DE COMPRA'),
(10438, 7, 'label_note', 'specific', 'NOTA'),
(10439, 7, 'text_received', 'specific', 'RECIBIDA'),
(10440, 7, 'text_pending', 'specific', 'PENDIENTE'),
(10441, 7, 'text_ordered', 'specific', 'ORDENADA'),
(10442, 7, 'label_attachment', 'specific', 'ARCHIVO ADJUNTO'),
(10443, 7, 'label_add_product', 'specific', 'AGREGAR PRODUCTO'),
(10444, 7, 'placeholder_search_product', 'specific', 'BUSCAR PRODUCTO'),
(10445, 7, 'label_available', 'specific', 'DISPONIBLE'),
(10446, 7, 'label_cost', 'specific', 'COSTO'),
(10447, 7, 'label_sell_price', 'specific', 'PRECIO VENTA'),
(10448, 7, 'label_item_tax', 'specific', 'IMPUESTO ARTICULO'),
(10449, 7, 'label_item_total', 'specific', 'TOTAL ARTICULO'),
(10450, 7, 'label_order_tax', 'specific', 'ORDEN IMPUESTO'),
(10451, 7, 'label_discount_amount', 'specific', 'IMPORTE DESCUENTO'),
(10452, 7, 'label_payable_amount', 'specific', 'IMPORTA A PAGAR'),
(10453, 7, 'label_payment_method', 'specific', 'METODO DE PAGO'),
(10454, 7, 'label_paid_amount', 'specific', 'MONTO DE PAGO'),
(10455, 7, 'label_due_amount', 'specific', 'CANTIDAD DEBIDA'),
(10456, 7, 'label_change_amount', 'specific', 'CAMBIAR CANTIDAD'),
(10457, 7, 'button_submit', 'specific', 'ENVIAR'),
(10458, 7, 'text_purchase_list_title', 'specific', 'LITA DE COMPRAS'),
(10459, 7, 'button_today_invoice', 'specific', 'FACTURA HOY'),
(10460, 7, 'button_all_invoice', 'specific', 'TODAS LAS FACTURAS'),
(10461, 7, 'button_due_invoice', 'specific', 'FACTURA DEBIDA'),
(10462, 7, 'button_all_due_invoice', 'specific', 'TODAS LAS FACTURAS DEBIDAS'),
(10463, 7, 'button_paid_invoice', 'specific', 'FACTURA PAGADA'),
(10464, 7, 'button_inactive_invoice', 'specific', 'FACTURA INACTIVA'),
(10465, 7, 'label_datetime', 'specific', 'FECHA Y HORA'),
(10466, 7, 'label_creator', 'specific', 'CREADOR'),
(10467, 7, 'label_invoice_paid', 'specific', 'FACTURA PAGADA'),
(10468, 7, 'label_pay', 'specific', 'PAGO'),
(10469, 7, 'label_return', 'specific', 'RETORNO'),
(10470, 7, 'label_view', 'specific', 'VER'),
(10471, 7, 'label_edit', 'specific', 'EDITAR'),
(10472, 7, 'label_credit_balance', 'specific', 'BALANCE CREDITO'),
(10473, 7, 'label_date_of_birth', 'specific', 'FECHA NACIMIENTO'),
(10474, 7, 'label_sex', 'specific', 'SEXO'),
(10475, 7, 'label_male', 'specific', 'MASCULINO'),
(10476, 7, 'label_female', 'specific', 'FEMENINO'),
(10477, 7, 'label_others', 'specific', 'OTROS'),
(10478, 7, 'label_age', 'specific', 'EDAD'),
(10479, 7, 'label_city', 'specific', 'CIUDAD'),
(10480, 7, 'label_state', 'specific', 'ESTADO'),
(10481, 7, 'label_country', 'specific', 'PAIS'),
(10482, 7, 'text_redirecting_to_dashbaord', 'specific', 'REDIRIGIENDO A DASHBOARD'),
(10483, 7, 'title_product', 'specific', 'PRODUCTO'),
(10484, 7, 'text_products', 'specific', 'PRODUCTOS'),
(10485, 7, 'text_add_new', 'specific', 'AGREGAR NUEVO'),
(10486, 7, 'label_all_product', 'specific', 'TODOS LOS PRODUCTOS'),
(10487, 7, 'button_trash', 'specific', 'BASURA'),
(10488, 7, 'button_bulk', 'specific', 'ABULTAR'),
(10489, 7, 'button_delete_all', 'specific', 'ELIMINAR TODOS'),
(10490, 7, 'label_stock', 'specific', 'STOCK'),
(10491, 7, 'label_purchase_price', 'specific', 'PRECIO COMPRA'),
(10492, 7, 'label_selling_price', 'specific', 'PRECIO VENTA'),
(10493, 7, 'label_purchase', 'specific', 'COMPRA'),
(10494, 7, 'label_print_barcode', 'specific', 'IMPRIMIR CODIGO DE BARRA'),
(10495, 7, 'text_select_store', 'specific', 'SELECCIONE TIENDA'),
(10496, 7, 'title_user_preference', 'specific', 'PREFERENCIA DE USUARIO'),
(10497, 7, 'text_user_preference_title', 'specific', 'TITULO PREFERENCIA DE USUARIO'),
(10498, 7, 'text_language_preference_title', 'specific', 'TITULO PREFERENCIA DE IDIOMA'),
(10499, 7, 'label_select_language', 'specific', 'SELECCIONAR LENGUAJE'),
(10500, 7, 'text_color_preference_title', 'specific', 'TITULO DE PREFERENCIA COLOR'),
(10501, 7, 'label_base_color', 'specific', 'COLOR BASE'),
(10502, 7, 'text_color_black', 'specific', 'COLOR NEGRO'),
(10503, 7, 'text_color_blue', 'specific', 'COLOR AZUL'),
(10504, 7, 'text_color_green', 'specific', 'COLOR VERDE'),
(10505, 7, 'text_color_red', 'specific', 'COLOR ROJO'),
(10506, 7, 'text_color_yellow', 'specific', 'COLOR AMARILLO'),
(10507, 7, 'text_pos_side_panel_position_title', 'specific', 'TITULO POSICION SILDER POS'),
(10508, 7, 'label_pos_side_panel_position', 'specific', 'POSICION SILDER POS'),
(10509, 7, 'text_right', 'specific', 'DERECHA'),
(10510, 7, 'text_left', 'specific', 'IZQUIERDA'),
(10511, 7, 'text_pos_pattern_title', 'specific', 'TITULO PATRON POS'),
(10512, 7, 'label_select_pos_pattern', 'specific', 'SELECCIONE PATRON POS'),
(10513, 7, 'button_update', 'specific', 'ACTUALIZAR'),
(10514, 7, 'text_product_created', 'specific', 'CREADA'),
(10515, 7, 'button_view', 'specific', 'VER'),
(10516, 7, 'button_purchase_product', 'specific', 'COMPRA PRODUCTO'),
(10517, 7, 'button_barcode', 'specific', 'CODIGO DE BARRA'),
(10518, 7, 'label_card_no', 'specific', 'NRO TARJETA'),
(10519, 7, 'label_giftcard_value', 'specific', 'VALOR DE TARJETA DE REGALO'),
(10520, 7, 'label_balance', 'specific', 'BALANCE'),
(10521, 7, 'label_expiry_date', 'specific', 'FECHA EXPIRA'),
(10522, 7, 'button_create_giftcard', 'specific', 'CREAR TARJETA REGALO'),
(10523, 7, 'title_stock_alert', 'specific', 'ALERTA STOCK'),
(10524, 7, 'text_stock_alert_title', 'specific', 'TITULO ALERTA STOCK'),
(10525, 7, 'text_stock_alert_box_title', 'specific', 'TITULO CUADRO DE ALERTA STOCK'),
(10526, 7, 'label_id', 'specific', 'ID'),
(10527, 7, 'label_mobile', 'specific', 'CELULAR'),
(10528, 7, 'error_sell_price_must_be_greated_that_purchase_price', 'specific', 'EL PRECIO DE VENTA DEBE SER MAYOR QUE EL DE COMPRA'),
(10529, 7, 'text_success', 'specific', 'EXITO'),
(10530, 7, 'text_paid', 'specific', 'PAGADA'),
(10531, 7, 'button_return', 'specific', 'RETORNAR'),
(10532, 7, 'button_view_receipt', 'specific', 'VER RECIBO'),
(10533, 7, 'text_order_title', 'specific', 'TITULO PEDIDO'),
(10534, 7, 'text_order_details', 'specific', 'DETALLES ORDEN'),
(10535, 7, 'text_pmethod', 'specific', 'METODO'),
(10536, 7, 'button_full_payment', 'specific', 'PAGO COMPLETO'),
(10537, 7, 'button_full_due', 'specific', 'DEBIDO COMPLETO'),
(10538, 7, 'button_sell_with_installment', 'specific', 'VENDER PLAZOS'),
(10539, 7, 'text_pay_amount', 'specific', 'MONTO PAGO'),
(10540, 7, 'placeholder_input_an_amount', 'specific', 'INGRESE CANTIDAD'),
(10541, 7, 'placeholder_note_here', 'specific', 'NOTA AQUI'),
(10542, 7, 'title_installment_details', 'specific', 'DETALLES INSTALACION'),
(10543, 7, 'label_duration', 'specific', 'DURACION'),
(10544, 7, 'text_days', 'specific', 'DIAS'),
(10545, 7, 'label_interval', 'specific', 'INTERVALO'),
(10546, 7, 'label_total_installment', 'specific', 'TOTAL INSTALACION'),
(10547, 7, 'label_interest_percentage', 'specific', 'PORCENTAJE INTERES'),
(10548, 7, 'label_interest_amount', 'specific', 'MONTO INTERES'),
(10549, 7, 'label_previous_due', 'specific', 'DEBIDO ANTERIOR'),
(10550, 7, 'error_walking_customer_can_not_craete_due', 'specific', 'CLIENTE NORMAL NO PUEDE CREAR DEBIDO'),
(10551, 7, 'text_invoice_create_success', 'specific', 'FACTURA CREAR EXITO'),
(10552, 7, 'text_update_title', 'specific', 'TITULO ACTUALIZACION'),
(10553, 7, 'text_product_updated', 'specific', 'ACTUALIZAR PRODUCTO'),
(10554, 7, 'title_settings', 'specific', 'AJUSTES'),
(10555, 7, 'title_store', 'specific', 'ALMACENAR'),
(10556, 7, 'text_pos_setting', 'specific', 'AJUSTES POS '),
(10557, 7, 'text_email_setting', 'specific', 'AJUSTE EMAIL'),
(10558, 7, 'text_ftp_setting', 'specific', 'CONFIGURACION FTP'),
(10559, 7, 'text_cronjob', 'specific', NULL),
(10560, 7, 'label_deposit_account', 'specific', 'CUENTA DEPOSITO'),
(10561, 7, 'label_code_name', 'specific', 'NOMBRE CODIGO'),
(10562, 7, 'label_zip_code', 'specific', 'CODIGO POSTAL'),
(10563, 7, 'label_gst_reg_no', 'specific', 'REG NRO GST'),
(10564, 7, 'label_vat_reg_no', 'specific', 'REG NRO VAY'),
(10565, 7, 'label_cashier_name', 'specific', 'NOMBRE CAJERO'),
(10566, 7, 'label_timezone', 'specific', 'ZONA HORARIA'),
(10567, 7, 'label_invoice_edit_lifespan', 'specific', 'VIDA UTIL EDICION FACTURA'),
(10568, 7, 'hint_invoice_edit_lifespan', 'specific', 'VIDA UTIL EDICION FACTURA'),
(10569, 7, 'text_minute', 'specific', 'MINUTO'),
(10570, 7, 'text_second', 'specific', 'SEGUNDO'),
(10571, 7, 'label_invoice_delete_lifespan', 'specific', 'ELIMINAR VIDA UTIL FACTURA '),
(10572, 7, 'hint_invoice_delete_lifespan', 'specific', 'ELIMINAR VIDA UTIL FACTURA'),
(10573, 7, 'label_tax', 'specific', 'IMPUESTO'),
(10574, 7, 'hint_tax', 'specific', 'IMPUESTO'),
(10575, 7, 'label_sms_gateway', 'specific', 'SMS GATEWAY'),
(10576, 7, 'hint_sms_gateway', 'specific', 'GATEWAY'),
(10577, 7, 'label_sms_alert', 'specific', 'SMS ALERTA'),
(10578, 7, 'hint_sms_alert', 'specific', 'SMS ALERTA'),
(10579, 7, 'text_yes', 'specific', 'SI'),
(10580, 7, 'text_no', 'specific', 'NO'),
(10581, 7, 'label_auto_sms', 'specific', 'AUTO SMS '),
(10582, 7, 'text_sms_after_creating_invoice', 'specific', 'SMS DESPUES DE CREAR FACTURA'),
(10583, 7, 'label_expiration_system', 'specific', 'SISTEMA DE CADUCIDAD'),
(10584, 7, 'label_datatable_item_limit', 'specific', 'LIMITE DE ELEMENTO DE TABLA DE DATOS'),
(10585, 7, 'hint_datatable_item_limit', 'specific', 'LIMITE DE ELEMENTO DE TABLA DE DATOS'),
(10586, 7, 'label_reference_format', 'specific', 'FORMATO REFERENCIA'),
(10587, 7, 'label_sales_reference_prefix', 'specific', 'PREFIJO REFERENCIA DE VENTAS'),
(10588, 7, 'label_receipt_template', 'specific', 'PLANTILLA RECIBO'),
(10589, 7, 'label_pos_printing', 'specific', 'IMPRIMIR POS'),
(10590, 7, 'label_receipt_printer', 'specific', 'RECIBO IMPRESORA'),
(10591, 7, 'label_auto_print_receipt', 'specific', 'AUTO RECIBO IMPRESORA'),
(10592, 7, 'label_invoice_view', 'specific', 'VER FACTURA'),
(10593, 7, 'hint_invoice_view', 'specific', 'VER FACTURA'),
(10594, 7, 'text_tax_invoice', 'specific', 'IMPUESTO FACTURA'),
(10595, 7, 'text_indian_gst', 'specific', 'INDIA'),
(10596, 7, 'label_change_item_price_while_billing', 'specific', 'CAMBIAR EL PRECIO DEL ARTICULO DURANTE FACTURACION'),
(10597, 7, 'hint_change_item_price_while_billing', 'specific', 'CAMBIAR EL PRECIO DEL ARTICULO DURANTE FACTURACION'),
(10598, 7, 'label_pos_product_display_limit', 'specific', 'LIMITE VISUALIZACION PRUDUCTO POS'),
(10599, 7, 'hint_pos_product_display_limit', 'specific', 'LIMITE VISUALIZACION PRUDUCTO POS'),
(10600, 7, 'label_after_sell_page', 'specific', 'DESPUES DE LA PAGINA DE VENTA'),
(10601, 7, 'hint_after_sell_page', 'specific', 'ANTES DE LA PAGINA DE VENTA'),
(10602, 7, 'label_invoice_footer_text', 'specific', 'TEXTO DEL PIE DE PAGINA DE FACTURA'),
(10603, 7, 'hint_invoice_footer_text', 'specific', 'TEXTO DEL PIE DE PAGINA DE FACTURA'),
(10604, 7, 'label_sound_effect', 'specific', 'EFECTO SONIDO'),
(10605, 7, 'label_email_from', 'specific', 'EMAIL DE'),
(10606, 7, 'hint_email_from', 'specific', 'EMAIL DE'),
(10607, 7, 'label_email_address', 'specific', 'DIRECCION EMAIL'),
(10608, 7, 'hint_email_address', 'specific', 'DIRECCION EMAIL'),
(10609, 7, 'label_email_driver', 'specific', 'DRIVER EMAIL'),
(10610, 7, 'hint_email_driver', 'specific', 'DRIVER EMAIL'),
(10611, 7, 'label_send_mail_path', 'specific', 'ENVIAR RUTA CORREO'),
(10612, 7, 'hint_send_mail_path', 'specific', 'ENVIAR RUTA CORREO'),
(10613, 7, 'label_smtp_host', 'specific', 'HOST SMTP'),
(10614, 7, 'hint_smtp_host', 'specific', 'HOST SMTP'),
(10615, 7, 'label_smtp_username', 'specific', 'USUARIO SMTP'),
(10616, 7, 'hint_smtp_username', 'specific', 'USUARIO SMTP'),
(10617, 7, 'label_smtp_password', 'specific', 'PASSWORD SMTP'),
(10618, 7, 'hint_smtp_password', 'specific', 'PASSWORD SMTP'),
(10619, 7, 'label_smtp_port', 'specific', 'PUERTO SMTP'),
(10620, 7, 'hint_smtp_port', 'specific', 'PUERTO SMTP'),
(10621, 7, 'label_ssl_tls', 'specific', 'SSL TLS'),
(10622, 7, 'hint_ssl_tls', 'specific', 'SSL TLS'),
(10623, 7, 'label_ftp_hostname', 'specific', 'NOMRE HOST FTP'),
(10624, 7, 'label_ftp_username', 'specific', 'USUARIO FTP'),
(10625, 7, 'label_ftp_password', 'specific', 'PASSWORD FTP'),
(10626, 7, 'button_back', 'specific', 'ATRAS'),
(10627, 7, 'text_logo', 'specific', 'LOGO'),
(10628, 7, 'button_upload', 'specific', 'SUBIR'),
(10629, 7, 'text_favicon', 'specific', 'ICONO'),
(10630, 7, 'title_invoice', 'specific', 'FACTURA'),
(10631, 7, 'text_sell_list_title', 'specific', 'TITULO LISTA VENTA'),
(10632, 7, 'text_invoices', 'specific', 'FACTURAS'),
(10633, 7, 'title_cashbook', 'specific', 'LIBRO DE PAGO'),
(10634, 7, 'text_cashbook_title', 'specific', 'TITULO LIBRO DE PAGO'),
(10635, 7, 'text_cashbook_details_title', 'specific', 'DETALLES  TITULO LIBRO DE PAGO'),
(10636, 7, 'text_print', 'specific', 'IMPRIMIR'),
(10637, 7, 'label_opening_balance', 'specific', 'SALDO APERTURA'),
(10638, 7, 'title_income', 'specific', 'INGRESOS'),
(10639, 7, 'label_title', 'specific', 'TITULO'),
(10640, 7, 'title_expense', 'specific', 'GASTOS'),
(10641, 7, 'button_details', 'specific', 'DETALLES'),
(10642, 7, 'label_today_income', 'specific', 'INGRESO HOY'),
(10643, 7, 'label_total_income', 'specific', 'INGRESOS TOTALES'),
(10644, 7, 'label_today_expense', 'specific', 'GATOS HOY'),
(10645, 7, 'label_cash_in_hand', 'specific', 'DINERO EFECTIVO'),
(10646, 7, 'label_today_closing_balance', 'specific', 'SALDO FINAL HOY'),
(10647, 7, 'text_opening_balance_update_success', 'specific', 'ACTUALIZACION DE SALDO INICIAL EXITOSA'),
(10648, 7, 'title_pmethod', 'specific', 'METODO PAGO'),
(10649, 7, 'text_pmethod_title', 'specific', 'TITULO METODO DE PAGO'),
(10650, 7, 'text_new_pmethod_title', 'specific', 'NUEVO METODO DE PAGO'),
(10651, 7, 'label_details', 'specific', 'DETALLES'),
(10652, 7, 'text_in_active', 'specific', 'INACTIVO'),
(10653, 7, 'text_pmethod_list_title', 'specific', 'LISTA METODO PAGO'),
(10654, 7, 'title_unit', 'specific', 'UNIDAD'),
(10655, 7, 'text_unit_title', 'specific', 'UNIDAD'),
(10656, 7, 'text_new_unit_title', 'specific', 'NUEVA UNIDAD'),
(10657, 7, 'label_unit_name', 'specific', 'NOMBRE UNIDAD'),
(10658, 7, 'label_unit_details', 'specific', 'DETALLES UNIDAD'),
(10659, 7, 'text_unit_list_title', 'specific', 'LISTA UNIDAD'),
(10660, 7, 'title_filemanager', 'specific', 'ADMINISTRADOR ARCHIVOS'),
(10661, 7, 'label_product_name', 'specific', 'NOMBRE PRODUCTO'),
(10662, 7, 'title_bank_account_sheet', 'specific', 'HOJA CUENTA BANCARIA'),
(10663, 7, 'text_bank_account_sheet_title', 'specific', 'TITULO HOJA CUENTA BANCARIA'),
(10664, 7, 'text_bank_account_title', 'specific', 'TITULO  CUENTA BANCARIA'),
(10665, 7, 'text_bank_account_sheet_list_title', 'specific', 'TITULO  LISTA  HOJA CUENTA BANCARIA'),
(10666, 7, 'label_account_id', 'specific', 'ID CUENTA'),
(10667, 7, 'label_account_name', 'specific', 'NOMBRE CUENTA'),
(10668, 7, 'label_credit', 'specific', 'CREDITO'),
(10669, 7, 'label_debit', 'specific', 'DEBIDO'),
(10670, 7, 'label_transfer_to_other', 'specific', 'TRANSFERIR A OTRO'),
(10671, 7, 'label_transfer_from_other', 'specific', 'TRANSFERIR DE OTRO'),
(10672, 7, 'label_deposit', 'specific', 'DEPOSITAR'),
(10673, 7, 'label_withdraw', 'specific', 'RETIRAR'),
(10674, 7, 'text_due', 'specific', 'DEBIDA'),
(10675, 7, 'title_purchase_return', 'specific', 'TITULO DEVOLUCION DE COMPRA'),
(10676, 7, 'text_purchase_return_title', 'specific', 'TITULO DEVOLUCION DE COMPRA'),
(10677, 7, 'text_return_list_title', 'specific', 'TITULO LISTA DEVOLUCION'),
(10678, 7, 'text_purchase_return_list_title', 'specific', 'TITULO LISTA DEVOLUCION COMPRA'),
(10679, 7, 'title_purchase_log', 'specific', 'TITULO REGISTROS COMPRA'),
(10680, 7, 'text_purchase_log_title', 'specific', 'TITULO REGISTROS COMPRA'),
(10681, 7, 'text_purchase_log_list_title', 'specific', 'TITULO LISTA REGISTROS COMPRA'),
(10682, 7, 'label_type', 'specific', 'TIPO'),
(10683, 7, 'label_pmethod', 'specific', 'METODO PAGO'),
(10684, 7, 'label_created_by', 'specific', 'CREADO POR'),
(10685, 7, 'title_quotation', 'specific', 'COTIZACION'),
(10686, 7, 'text_quotation_title', 'specific', 'TITULO COTIZACION'),
(10687, 7, 'text_new_quotation_title', 'specific', 'TITULO NUEVA COTIZACION'),
(10688, 7, 'text_sent', 'specific', 'EXPEDIDA'),
(10689, 7, 'text_complete', 'specific', 'COMPLETAR'),
(10690, 7, 'text_all_suppliers', 'specific', 'PROVEEDORES'),
(10691, 7, 'text_quotation_list_title', 'specific', 'TITULO LISTA COTIZACION'),
(10692, 7, 'button_all', 'specific', 'TODOS'),
(10693, 7, 'button_sent', 'specific', 'EXPEDIDO'),
(10694, 7, 'button_pending', 'specific', 'PENDIENTE'),
(10695, 7, 'button_complete', 'specific', 'COMPLETO'),
(10696, 7, 'label_biller', 'specific', 'FACTURADOR'),
(10697, 7, 'title_sell_log', 'specific', 'REGISTROS VENTA'),
(10698, 7, 'text_sell_log_title', 'specific', 'TITULO REGISTROS VENTA'),
(10699, 7, 'text_sell_title', 'specific', 'VENDER'),
(10700, 7, 'text_sell_log_list_title', 'specific', 'TITULO LISTA REGISTROS VENTAS'),
(10701, 7, 'title_giftcard_topup', 'specific', 'RECARGAR TARJETA REGALO'),
(10702, 7, 'text_giftcard_topup_title', 'specific', 'TITULO RECARGAR TARJETA REGALO'),
(10703, 7, 'text_giftcard_title', 'specific', 'TITULO  TARJETA REGALO'),
(10704, 7, 'text_topup_title', 'specific', 'TITULO   RECARGA'),
(10705, 7, 'text_giftcard_topup_list_title', 'specific', 'TITULO   LISTA RECARGA'),
(10706, 7, 'error_order_title', 'specific', 'TITULO PEDIDO'),
(10707, 7, 'title_sell_return', 'specific', 'VENTAS DEVUELTAS'),
(10708, 7, 'text_sell_return_title', 'specific', 'TITULO VENTAS DEVUELTAS'),
(10709, 7, 'text_sell_return_list_title', 'specific', 'TITULO LISTA VENTAS DEVUELTAS'),
(10710, 7, 'label_ref_invoice_Id', 'specific', 'ID FACTURA REFERENCIA'),
(10711, 7, 'label_pmethod_name', 'specific', 'NOMBRE METODO PAGO'),
(10712, 7, 'title_barcode', 'specific', 'CODIGO BARRAS'),
(10713, 7, 'text_barcode_title', 'specific', 'TITULO CODIGO BARRAS'),
(10714, 7, 'text_barcode_generate_title', 'specific', 'GENERAR TITULO CODIGO BARRAS'),
(10715, 7, 'label_product_name_with_code', 'specific', 'NOMBRE PRODUCTO CON CODIGO'),
(10716, 7, 'label_page_layout', 'specific', 'DISEÑO PAGINA'),
(10717, 7, 'label_fields', 'specific', 'CAMPOS'),
(10718, 7, 'button_generate', 'specific', 'GENERAR'),
(10719, 7, 'text_category_title', 'specific', 'TITULO CATEGORIA'),
(10720, 7, 'text_new_category_title', 'specific', 'NOMBRE NUEVA CATEGORIA'),
(10721, 7, 'label_category_name', 'specific', 'NOMBRE CATEGORIA'),
(10722, 7, 'label_category_slug', 'specific', 'SLUG CATEGORIA'),
(10723, 7, 'label_parent', 'specific', 'PARENTESCO'),
(10724, 7, 'label_category_details', 'specific', 'DETALLES CATEGORIA'),
(10725, 7, 'text_category_list_title', 'specific', 'TITULO LISTA CATEGORIA'),
(10726, 7, 'label_total_item', 'specific', 'TOTAL ARTICULOS'),
(10727, 7, 'title_import_product', 'specific', 'PRODUCTO IMPORTADO'),
(10728, 7, 'text_import_title', 'specific', 'TITULO IMPORTADO'),
(10729, 7, 'text_download_sample_format_file', 'specific', 'DESCARGAR ARCHIVO FORMATO MUESTRA'),
(10730, 7, 'button_download', 'specific', 'DESCARGAR'),
(10731, 7, 'text_select_xls_file', 'specific', 'SELECCIONA ARCHIVO XLS '),
(10732, 7, 'button_import', 'specific', 'IMPORTAR'),
(10733, 7, 'title_create_store', 'specific', 'CREAR TIENDA'),
(10734, 7, 'text_create_store_title', 'specific', 'TITULO CREAR TIENDA'),
(10735, 7, 'text_stores', 'specific', 'TIENDAS'),
(10736, 7, 'text_currency', 'specific', 'MONEDA'),
(10737, 7, 'text_payment_method', 'specific', 'METODO PAGO'),
(10738, 7, 'text_receipt_template', 'specific', 'PLANTILLA RECIBO'),
(10739, 7, 'text_printer', 'specific', 'IMPRESORA'),
(10740, 7, 'label_stock_alert_quantity', 'specific', 'ALERTA CANTIDAD STOCK'),
(10741, 7, 'hint_stock_alert_quantity', 'specific', 'ALERTA CANTIDAD STOCK'),
(10742, 7, 'text_store_title', 'specific', 'TIENDA'),
(10743, 7, 'text_store_list_title', 'specific', 'LISTA TIENDA'),
(10744, 7, 'button_activated', 'specific', 'ACTIVADA'),
(10745, 7, 'title_receipt_template', 'specific', 'PLANTILLA RECIBO'),
(10746, 7, 'text_receipt_tempalte_title', 'specific', 'TITULO PLANTILLA RECIBO'),
(10747, 7, 'title_pos_setting', 'specific', 'AJUSTES POS'),
(10748, 7, 'text_receipt_tempalte_sub_title', 'specific', 'SUBTITULO PLANTILLA RECIBO'),
(10749, 7, 'button_preview', 'specific', 'VISTA PREVIA'),
(10750, 7, 'text_tempalte_content_title', 'specific', 'TITULO PLANTILLA CONTENIDO'),
(10751, 7, 'text_tempalte_css_title', 'specific', 'TITULO PLANTILLA  CSS'),
(10752, 7, 'text_template_tags', 'specific', 'PLANTILLA  ETIQUETAS'),
(10753, 7, 'text_brand_list_title', 'specific', 'TITULO LISTA MARCAS'),
(10754, 7, 'text_brand_title', 'specific', 'TITULO MARCAS'),
(10755, 7, 'text_new_brand_title', 'specific', 'NUEVO TITULO MARCAS'),
(10756, 7, 'label_total_product', 'specific', 'TOTAL PRODUCTO'),
(10757, 7, 'button_view_profile', 'specific', 'VER PERFIL'),
(10758, 7, 'title_currency', 'specific', 'MONEDA'),
(10759, 7, 'text_currency_title', 'specific', 'TITULO MONEDA'),
(10760, 7, 'text_new_currency_title', 'specific', 'NUEVO TITULO MONEDA'),
(10761, 7, 'label_code', 'specific', 'CODIGO'),
(10762, 7, 'hint_code', 'specific', 'CODIGO'),
(10763, 7, 'label_symbol_left', 'specific', 'SIMBOLO IZQUIERDO'),
(10764, 7, 'hint_symbol_left', 'specific', 'SIMBOLO IZQUIERDO'),
(10765, 7, 'label_symbol_right', 'specific', 'SIMBOLO DERECHO'),
(10766, 7, 'hint_symbol_right', 'specific', 'SIMBOLO DERECHO'),
(10767, 7, 'label_decimal_place', 'specific', 'LUGAR DECIMAL'),
(10768, 7, 'hint_decimal_place', 'specific', 'LUGAR DECIMAL'),
(10769, 7, 'text_currency_list_title', 'specific', 'TITULO LISTA MONEDA'),
(10770, 7, 'text_enabled', 'specific', 'HABILITADA'),
(10771, 7, 'button_activate', 'specific', 'ACTIVAR'),
(10772, 7, 'title_taxrate', 'specific', 'TASA IMPUESTO'),
(10773, 7, 'text_taxrate_title', 'specific', 'TITULO TASA IMPUESTO'),
(10774, 7, 'text_new_taxrate_title', 'specific', 'NUEVO TITULO TASA IMPUESTO'),
(10775, 7, 'label_taxrate_name', 'specific', 'NOMBRE TASA IMPUESTO'),
(10776, 7, 'label_taxrate', 'specific', 'TASA IMPUESTO'),
(10777, 7, 'text_taxrate_list_title', 'specific', 'LISTA TASA IMPUESTO'),
(10778, 7, 'title_box', 'specific', 'CAJA'),
(10779, 7, 'text_box_title', 'specific', 'TITULO CAJA'),
(10780, 7, 'label_box_name', 'specific', 'NOMBRE CAJA'),
(10781, 7, 'label_box_details', 'specific', 'DETALLES CAJA'),
(10782, 7, 'text_box_list_title', 'specific', 'TITULO LISTA CAJAS'),
(10783, 7, 'title_printer', 'specific', 'IMPRESORA'),
(10784, 7, 'text_printer_title', 'specific', 'TITULO IMPRESORA'),
(10785, 7, 'text_new_printer_title', 'specific', 'NUEVA IMPRESORA'),
(10786, 7, 'label_char_per_line', 'specific', 'CHAR POR LINEA'),
(10787, 7, 'label_path', 'specific', 'DIRECCION'),
(10788, 7, 'label_ip_address', 'specific', 'DIRECCION IP'),
(10789, 7, 'label_port', 'specific', 'PUERTO'),
(10790, 7, 'text_printer_list_title', 'specific', 'TITULO LISTA IMPRESORAS'),
(10791, 7, 'title_user', 'specific', 'USUARIO'),
(10792, 7, 'text_user_title', 'specific', 'TITULO USUARIO'),
(10793, 7, 'text_new_user_title', 'specific', 'NUEVO TITULO USUARIO'),
(10794, 7, 'label_password', 'specific', 'PASSWORD'),
(10795, 7, 'label_password_retype', 'specific', 'REPETIR PASSWORD'),
(10796, 7, 'label_group', 'specific', 'GRUPO'),
(10797, 7, 'hint_group', 'specific', 'GRUPO'),
(10798, 7, 'text_user_list_title', 'specific', 'LISTA USUARIO'),
(10799, 7, 'label_profile', 'specific', 'PERFIL'),
(10800, 7, 'title_user_group', 'specific', 'GRUPO USUARIO'),
(10801, 7, 'text_group_title', 'specific', 'GRUPO'),
(10802, 7, 'text_new_group_title', 'specific', 'NUEVO GRUPO'),
(10803, 7, 'label_slug', 'specific', 'SLUG'),
(10804, 7, 'text_group_list_title', 'specific', 'LISTA GRUPO'),
(10805, 7, 'label_total_user', 'specific', 'TOTAL USUARIO'),
(10806, 7, 'title_password', 'specific', 'PASSWORD'),
(10807, 7, 'text_password_title', 'specific', 'PASSWORD'),
(10808, 7, 'text_password_box_title', 'specific', 'PASSWORD CAJA'),
(10809, 7, 'label_password_user', 'specific', 'USUARIO PASSWORD '),
(10810, 7, 'label_password_new', 'specific', 'NUEVO PASSWORD'),
(10811, 7, 'label_password_confirm', 'specific', 'CONFIRMAR PASSWORD'),
(10812, 7, 'title_send_sms', 'specific', 'ENVIAR SMS'),
(10813, 7, 'text_sms_title', 'specific', 'TITULO  SMS'),
(10814, 7, 'text_send_sms', 'specific', 'ENVIAR SMS'),
(10815, 7, 'text_send_sms_title', 'specific', 'ENVIAR TITULO  SMS'),
(10816, 7, 'text_single', 'specific', 'INDIVIDUAL'),
(10817, 7, 'text_group', 'specific', 'GRUPO'),
(10818, 7, 'label_phone_number', 'specific', 'NUMERO CELULAR'),
(10819, 7, 'label_message', 'specific', 'MENSAJE'),
(10820, 7, 'button_send', 'specific', 'ENVIAR'),
(10821, 7, 'label_people_type', 'specific', 'TIPO PERSONA'),
(10822, 7, 'text_all_customer', 'specific', 'TODOS LOS CLIENTES'),
(10823, 7, 'text_all_user', 'specific', 'TODOS LOS USUARIOS'),
(10824, 7, 'label_people', 'specific', 'PERSONA'),
(10825, 7, 'title_sms_report', 'specific', 'INFORME SMS'),
(10826, 7, 'text_sms_report_title', 'specific', 'REPORTE SMS'),
(10827, 7, 'text_sms_history_title', 'specific', 'HISTORIA SMS'),
(10828, 7, 'text_all', 'specific', 'TODOS'),
(10829, 7, 'button_delivered', 'specific', 'ENTREGADO'),
(10830, 7, 'button_failed', 'specific', 'HA FALLADO'),
(10831, 7, 'label_schedule_at', 'specific', 'PROGRAMAR A LAS'),
(10832, 7, 'label_campaign_name', 'specific', 'NOMBRE CAMPAÑA'),
(10833, 7, 'label_people_name', 'specific', 'NOMBRE PERSONA'),
(10834, 7, 'label_mobile_number', 'specific', 'NUMERO CELULAR'),
(10835, 7, 'label_process_status', 'specific', 'ESTADO PROCESO'),
(10836, 7, 'label_response_text', 'specific', 'TEXTO RESPUESTA'),
(10837, 7, 'label_delivered', 'specific', 'ENTREGADA'),
(10838, 7, 'label_resend', 'specific', 'REENVIAR'),
(10839, 7, 'title_sms_setting', 'specific', 'AJUSTES SMS'),
(10840, 7, 'text_sms_setting_title', 'specific', 'AJUSTES SMS'),
(10841, 7, 'text_sms_setting', 'specific', 'AJUSTES SMS'),
(10842, 7, 'text_clickatell', 'specific', 'clickatell'),
(10843, 7, 'text_twilio', 'specific', 'twilio'),
(10844, 7, 'text_msg91', 'specific', 'msg91'),
(10845, 7, 'text_mimsms', 'specific', 'mimsms'),
(10846, 7, 'text_onnorokomsms', 'specific', 'onnorokomsms'),
(10847, 7, 'label_username', 'specific', 'USUARIO'),
(10848, 7, 'label_api_key', 'specific', 'API KEY'),
(10849, 7, 'label_sender_id', 'specific', 'ID REMITENTE'),
(10850, 7, 'label_auth_key', 'specific', 'CLAVE AUTENTIFICACION'),
(10851, 7, 'label_contact', 'specific', 'CONTACTO'),
(10852, 7, 'label_country_code', 'specific', 'CODIGO PAIS'),
(10853, 7, 'label_api_token', 'specific', 'API TOKEN'),
(10854, 7, 'label_maskname', 'specific', 'NOMBRE MASCARA'),
(10855, 7, 'label_optional', 'specific', 'OPCIONAL'),
(10856, 7, 'label_campaignname', 'specific', 'NOMBRE CAMPAÑA'),
(10857, 7, 'title_analytics', 'specific', 'ANALITICA'),
(10858, 7, 'text_analytics_title', 'specific', 'ANALITICA'),
(10859, 7, 'text_top_products', 'specific', 'MEJORES PRODUCTOS'),
(10860, 7, 'text_top_customers', 'specific', 'MEJORES CLIENTES'),
(10861, 7, 'text_top_suppliers', 'specific', 'MEJORES PROVEEDORES'),
(10862, 7, 'text_top_brands', 'specific', 'MEJORES MARCAS'),
(10863, 7, 'title_customer_analytics', 'specific', 'CLIENTE ANALITICA'),
(10864, 7, 'text_birthday_today', 'specific', 'CUMPLEAÑOS HOY'),
(10865, 7, 'text_birthday_coming', 'specific', 'FIESTA CUMPLEAÑOS'),
(10866, 7, 'label_member_since', 'specific', 'MIEMBRO DESDE'),
(10867, 7, 'text_not_found', 'specific', 'NO ENCONTRADO'),
(10868, 7, 'text_best_customer', 'specific', 'MEJOR CLIENTE'),
(10869, 7, 'text_purchase', 'specific', 'COMPRA'),
(10870, 7, 'title_login_logs', 'specific', 'REGISTROS LOGIN'),
(10871, 7, 'label_ip', 'specific', 'IP'),
(10872, 7, 'label_logged_in', 'specific', 'CONECTADO'),
(10873, 7, 'title_overview', 'specific', 'VISION GENERAL'),
(10874, 7, 'text_overview_title', 'specific', 'VISION GENERAL'),
(10875, 7, 'text_sell_overview', 'specific', 'RESUMEN VENTAS'),
(10876, 7, 'text_purchase_overview', 'specific', 'RESUMEN COMPRAS'),
(10877, 7, 'text_title_sells_overview', 'specific', 'RESUMEN VENTAS'),
(10878, 7, 'text_invoice_amount', 'specific', 'IMPORTE FACTURA'),
(10879, 7, 'text_discount_amount', 'specific', 'IMPORTE DESCUENTO'),
(10880, 7, 'text_due_collection', 'specific', 'DEBIDO COBRO'),
(10881, 7, 'text_shipping_charge', 'specific', 'COSTO ENVIO'),
(10882, 7, 'text_others_charge', 'specific', 'OTROS COBRAN'),
(10883, 7, 'text_order_tax', 'specific', 'IMPUESTO ORDEN'),
(10884, 7, 'text_item_tax', 'specific', 'IMPUESTO ORDEN'),
(10885, 7, 'text_total_tax', 'specific', 'TOTAL IMPUESTO '),
(10886, 7, 'text_title_purchase_overview', 'specific', 'RESUMEN COMPRA'),
(10887, 7, 'text_purchase_amount', 'specific', 'MONTO COMPRA'),
(10888, 7, 'text_due_taken', 'specific', 'DEBIDO'),
(10889, 7, 'text_due_paid', 'specific', 'DEBIDO PAGADO'),
(10890, 7, 'text_total_paid', 'specific', 'TOTAL PAGADO'),
(10891, 7, 'text_return_amount', 'specific', 'IMPORTE DEVOLUCION'),
(10892, 7, 'title_collection_report', 'specific', 'INFORME RECOLECCION'),
(10893, 7, 'text_collection_report_title', 'specific', 'INFORME RECOLECCION'),
(10894, 7, 'text_collection_report', 'specific', 'INFORME RECOLECCION'),
(10895, 7, 'label_total_inv', 'specific', 'TOTAL INV'),
(10896, 7, 'label_net_amount', 'specific', 'IMPORTE NETO'),
(10897, 7, 'label_prev_due_collection', 'specific', 'DEBIDO COLECCION PREVIO'),
(10898, 7, 'label_due_collection', 'specific', 'DEBIDO COLECCION '),
(10899, 7, 'label_due_given', 'specific', 'DEBIDO DADO'),
(10900, 7, 'label_received', 'specific', 'RECIBIDA'),
(10901, 7, 'title_due_collection', 'specific', 'DEBIDO COLECCION'),
(10902, 7, 'text_due_collection_title', 'specific', 'DEBIDO COLECCION'),
(10903, 7, 'text_due_collection_sub_title', 'specific', 'SUB TITULO DEBIDO COLECCION'),
(10904, 7, 'title_supplier_due_paid', 'specific', 'DEUDAS PAGADA PROVEEDOR'),
(10905, 7, 'text_supplier_due_paid_title', 'specific', 'DEUDAS PAGADA PROVEEDOR'),
(10906, 7, 'text_supplier_due_paid_sub_title', 'specific', 'SUB TITULO DEUDAS PAGADA PROVEEDOR'),
(10907, 7, 'title_sell_report', 'specific', 'INFORME VENTA'),
(10908, 7, 'text_selling_report_title', 'specific', 'INFORME VENTA'),
(10909, 7, 'text_selling_report_sub_title', 'specific', 'SUB TITULO INFORME VENTA'),
(10910, 7, 'button_itemwise', 'specific', 'ARTICULO '),
(10911, 7, 'button_categorywise', 'specific', 'CATEGORIA'),
(10912, 7, 'button_supplierwise', 'specific', 'PROVEEDOR'),
(10913, 7, 'title_loan', 'specific', 'PRESTAMO'),
(10914, 7, 'text_loan_title', 'specific', 'PRESTAMO'),
(10915, 7, 'text_take_loan_title', 'specific', 'TITULO PRESTAMO'),
(10916, 7, 'label_loan_from', 'specific', 'PRESTAMO DE'),
(10917, 7, 'label_ref_no', 'specific', 'NRO REF'),
(10918, 7, 'label_interest', 'specific', 'INTERESAR'),
(10919, 7, 'label_payable', 'specific', 'PAGADERA'),
(10920, 7, 'label_loan_for', 'specific', 'PRESTAMO PARA'),
(10921, 7, 'text_loan_list_title', 'specific', 'LISTA PRESTAMO PARA'),
(10922, 7, 'button_paid', 'specific', 'PAGADA'),
(10923, 7, 'button_due', 'specific', 'DEBIDA'),
(10924, 7, 'label_basic_amount', 'specific', 'CANTIDAD BASICA'),
(10925, 7, 'label_paid', 'specific', 'PAGADA');
INSERT INTO `language_translations` (`id`, `lang_id`, `lang_key`, `key_type`, `lang_value`) VALUES
(10926, 7, 'title_loan_summary', 'specific', 'RESUMEN PRESTAMO'),
(10927, 7, 'text_loan_summary_title', 'specific', 'RESUMEN PRESTAMO'),
(10928, 7, 'text_summary_title', 'specific', 'RESUMEN'),
(10929, 7, 'text_total_loan', 'specific', 'TOTAL PRESTAMO'),
(10930, 7, 'text_total_due', 'specific', 'TOTAL DEBIDO'),
(10931, 7, 'text_recent_payments', 'specific', 'PAGOS RECIENTES'),
(10932, 7, 'text_expense_title', 'specific', 'GASTOS'),
(10933, 7, 'text_new_expense_title', 'specific', 'NUEVO GASTOS'),
(10934, 7, 'label_what_for', 'specific', 'PARA QUE'),
(10935, 7, 'label_returnable', 'specific', 'RETORNABLE'),
(10936, 7, 'label_notes', 'specific', 'NOTAS'),
(10937, 7, 'text_expense_list_title', 'specific', 'LISTA GASTOS'),
(10938, 7, 'title_expense_category', 'specific', 'CATEGORIA GASTOS'),
(10939, 7, 'text_expense_category_title', 'specific', 'CATEGORIA GASTOS'),
(10940, 7, 'text_new_expense_category_title', 'specific', 'NUEVO CATEGORIA GASTOS'),
(10941, 7, 'title_expense_monthwise', 'specific', 'GASTO MENSUAL'),
(10942, 7, 'text_expense_monthwise_title', 'specific', 'GASTO MENSUAL'),
(10943, 7, 'label_summary', 'specific', 'RESUMEN'),
(10944, 7, 'label_grand_total', 'specific', 'GRAN TOTAL'),
(10945, 7, 'label_this_week', 'specific', 'ESTA SEMANA'),
(10946, 7, 'label_this_month', 'specific', 'ESTE MES'),
(10947, 7, 'label_this_year', 'specific', 'ESTE AÑO'),
(10948, 7, 'label_income_source', 'specific', 'FUENTE INGRESOS'),
(10949, 7, 'label_account', 'specific', 'CUENTA'),
(10950, 7, 'label_about', 'specific', 'ACERCA DE'),
(10951, 7, 'label_capital', 'specific', 'CAPITAL'),
(10952, 7, 'button_deposit_now', 'specific', 'DEPOSITAR AHORA'),
(10953, 7, 'label_exp_category', 'specific', 'CATEGORIA EXP'),
(10954, 7, 'button_withdraw_now', 'specific', 'RETIRAR AHORA'),
(10955, 7, 'title_bank_transactions', 'specific', 'TRANSACCIONES BANCARIAS'),
(10956, 7, 'text_bank_transaction_title', 'specific', 'TRANSACCION BANCARIA'),
(10957, 7, 'text_bank_transaction_list_title', 'specific', 'LISTA TRANSACCION BANCARIA'),
(10958, 7, 'button_filtering', 'specific', 'FILTRACION'),
(10959, 7, 'text_view_all_transactions', 'specific', 'VER TODAS LAS TRANSACCIONES '),
(10960, 7, 'button_transfer_now', 'specific', 'TRANSFERIR AHORA'),
(10961, 7, 'title_bank_transfer', 'specific', 'TRANSFERENCIA BANCARIA'),
(10962, 7, 'text_bank_transfer_title', 'specific', 'TRANSFERENCIA BANCARIA'),
(10963, 7, 'text_banking_title', 'specific', 'BANCARIA'),
(10964, 7, 'text_list_bank_transfer_title', 'specific', 'LISTA TRANSFERENCIA BANCARIA'),
(10965, 7, 'label_from_account', 'specific', 'DE LA CUENTA'),
(10966, 7, 'label_to_account', 'specific', 'A LA CUENTA'),
(10967, 7, 'title_bank_account', 'specific', 'CUENTA BANCARIA'),
(10968, 7, 'text_new_bank_account_title', 'specific', 'NUEVA CUENTA BANCARIA'),
(10969, 7, 'label_account_details', 'specific', 'DETALLES CUENTA '),
(10970, 7, 'label_account_no', 'specific', 'NRO CUENTA'),
(10971, 7, 'label_contact_person', 'specific', 'PERSONA CONTACTO'),
(10972, 7, 'label_internal_banking_url', 'specific', 'URL BANCA INTERNA'),
(10973, 7, 'text_bank_account_list_title', 'specific', 'LISTA  CUENTA BANCARIA'),
(10974, 7, 'label_account_description', 'specific', 'DESCRIPCION CUENTA '),
(10975, 7, 'title_income_source', 'specific', 'FUENTE INGRESOS'),
(10976, 7, 'text_income_source_title', 'specific', 'FUENTE INGRESOS'),
(10977, 7, 'text_new_income_source_title', 'specific', 'NUEVA FUENTE INGRESOS'),
(10978, 7, 'label_source_name', 'specific', 'NOMBRE FUENTE'),
(10979, 7, 'label_source_slug', 'specific', 'SLUG FUENTE'),
(10980, 7, 'label_source_details', 'specific', 'DETALLES FUENTE'),
(10981, 7, 'text_income_source_sub_title', 'specific', 'SUB FUENTE INGRESOS'),
(10982, 7, 'title_income_monthwise', 'specific', 'INGRESO MENSUAL'),
(10983, 7, 'text_income_monthwise_title', 'specific', 'INGRESO MENSUAL'),
(10984, 7, 'label_profit', 'specific', 'GANANCIA'),
(10985, 7, 'title_income_and_expense', 'specific', 'INGRESOS Y GASTOS'),
(10986, 7, 'text_income_and_expense_title', 'specific', 'INGRESOS Y GASTOS'),
(10987, 7, 'text_date', 'specific', 'FECHA'),
(10988, 7, 'label_till_now', 'specific', 'HASTA AHORA'),
(10989, 7, 'title_profit_and_loss', 'specific', 'GANANCIA Y PERDIDA'),
(10990, 7, 'text_profit_and_loss_title', 'specific', 'GANANCIA Y PERDIDA'),
(10991, 7, 'text_profit_and_loss_details_title', 'specific', 'DETALLES GANANCIA Y PERDIDA'),
(10992, 7, 'text_loss_title', 'specific', 'PERDIDA'),
(10993, 7, 'text_profit_title', 'specific', 'GANANCIA '),
(10994, 7, 'title_profit', 'specific', 'GANANCIA '),
(10995, 7, 'label_total_profit', 'specific', 'TOTAL GANANCIA '),
(10996, 7, 'label_total_loss', 'specific', 'TOTAL PERDIDA'),
(10997, 7, 'label_net_profit', 'specific', 'BENEFICIO NETO'),
(10998, 7, 'text_supplier_list_title', 'specific', 'LISTA PROVEEDORES'),
(10999, 7, 'text_supplier_title', 'specific', 'PROVEEDORES'),
(11000, 7, 'text_new_supplier_title', 'specific', 'NUEVO PROVEEDOR'),
(11001, 7, 'text_customer_list_title', 'specific', 'LISTA CLIENTE'),
(11002, 7, 'text_customer_title', 'specific', 'CLIENTE'),
(11003, 7, 'text_new_customer_title', 'specific', 'NUEVO CLIENTE'),
(11004, 7, 'label_sell', 'specific', 'VENDER'),
(11005, 7, 'button_sell', 'specific', 'VENDER'),
(11006, 7, 'title_transfer', 'specific', 'TRANSFERIR'),
(11007, 7, 'text_stock_transfer_title', 'specific', 'TRANSFERENCIA STOCK'),
(11008, 7, 'text_transfer_title', 'specific', 'TRANSFERENCIA '),
(11009, 7, 'text_add_transfer_title', 'specific', 'AGREGAR TRANSFERENCIA'),
(11010, 7, 'text_stock_list', 'specific', 'LISTA STOCK'),
(11011, 7, 'text_invoice_id', 'specific', 'ID FACTURA'),
(11012, 7, 'text_stock', 'specific', 'STOCK'),
(11013, 7, 'text_transfer_list', 'specific', 'LISTA TRANSFERENCIA'),
(11014, 7, 'label_item_name', 'specific', 'NOMBRE ARTICULO'),
(11015, 7, 'label_transfer_qty', 'specific', 'CANTIDAD TRANSFERENCIA'),
(11016, 7, 'text_list__transfer__title', 'specific', 'LISTA  TRANSFERENCIA'),
(11017, 7, 'label_from_store', 'specific', 'DE LA TIENDA'),
(11018, 7, 'label_to_store', 'specific', 'A LA TIENDA'),
(11019, 7, 'label_total_quantity', 'specific', 'TOTAL CANTIDAD'),
(11020, 7, 'title_receive', 'specific', 'RECIBIR'),
(11021, 7, 'text_stock_receive_title', 'specific', 'RECIBIR STOCK'),
(11022, 7, 'text_receive_title', 'specific', 'RECIBIR '),
(11023, 7, 'text_list__receive__title', 'specific', 'LISTA RECIBIR'),
(11024, 7, 'title_installment', 'specific', 'ENTREGA'),
(11025, 7, 'text_installment_title', 'specific', 'ENTREGA'),
(11026, 7, 'text_installment_sub_title', 'specific', 'SUB PLAZOS'),
(11027, 7, 'button_all_installment', 'specific', 'TODA LA ENTREGA'),
(11028, 7, 'button_due_installment', 'specific', 'DEUDA ENTREGA'),
(11029, 7, 'button_paid_installment', 'specific', 'PAGO ENTREGA'),
(11030, 7, 'label_total_ins', 'specific', 'TOTAL ENTREGA'),
(11031, 7, 'title_installment_payment', 'specific', 'PAGO ENTREGA'),
(11032, 7, 'text_installment_payment_title', 'specific', 'PAGO ENTREGA'),
(11033, 7, 'text_installment', 'specific', 'ENTREGA'),
(11034, 7, 'text_installment_payment_list_title', 'specific', 'LISTA PAGO ENTREGA'),
(11035, 7, 'text_all_payment', 'specific', 'TODOS LOS PAGOS'),
(11036, 7, 'button_all_payment', 'specific', 'TODOS LOS PAGOS'),
(11037, 7, 'button_todays_due_payment', 'specific', 'PAGO VENCIDO HOY'),
(11038, 7, 'button_all_due_payment', 'specific', 'TODAS LAS DEUDAS PAGADAS'),
(11039, 7, 'button_expired_due_payment', 'specific', 'DEUDAS PAGADAS EXPERIDAS'),
(11040, 7, 'label_payment_date', 'specific', 'FECHA PAGO'),
(11041, 7, 'button_payment', 'specific', 'PAGO'),
(11042, 7, 'text_todays_due_payment', 'specific', 'PAGOS DEUDAS HOY'),
(11043, 7, 'text_all_due_payment', 'specific', 'TOTAL PAGOS DEUDAS'),
(11044, 7, 'text_expired_due_payment', 'specific', 'PAGOS DEUDAS EXPIRADOS'),
(11045, 7, 'title_installment_overview', 'specific', 'RESUMEN ENTREGA'),
(11046, 7, 'text_installment_overview_title', 'specific', 'RESUMEN ENTREGA'),
(11047, 7, 'text_installment_overview', 'specific', 'RESUMEN ENTREGA'),
(11048, 7, 'text_invoice_count', 'specific', 'CUENTA FACTURA'),
(11049, 7, 'text_sell_amount', 'specific', 'CANTIDAD VENTA'),
(11050, 7, 'text_interest_amount', 'specific', 'CANTIDAD INTERES'),
(11051, 7, 'text_amount_received', 'specific', 'CANTIDAD  RECIBIDA'),
(11052, 7, 'text_amount_due', 'specific', 'CANTIDAD  DEUDA'),
(11053, 7, 'title_giftcard', 'specific', 'TARJETA DE REGALO'),
(11054, 7, 'text_new_giftcard_title', 'specific', 'NUEVA TARJETA DE REGALO'),
(11055, 7, 'text_giftcard_list_title', 'specific', 'LISTA TARJETA DE REGALO'),
(11056, 7, 'label_expiry', 'specific', 'EXPIRACION'),
(11057, 7, 'label_topup', 'specific', 'REPOSICION'),
(11058, 7, 'title_purchase_report', 'specific', 'INFORME COMPRA'),
(11059, 7, 'text_purchase_report_title', 'specific', 'INFORME COMPRA'),
(11060, 7, 'text_purchase_report_sub_title', 'specific', 'SUB INFORME COMPRA'),
(11061, 7, 'title_backup_restore', 'specific', 'RESTAURAR BACKUP'),
(11062, 7, 'text_backup_restore_title', 'specific', 'RESTAURAR BACKUP'),
(11063, 7, 'text_backup', 'specific', 'BACKUP'),
(11064, 7, 'text_restore', 'specific', 'RESTAURAR '),
(11065, 7, 'label_databases', 'specific', 'BASE DE DATOS'),
(11066, 7, 'label_select_all', 'specific', 'SELECCIONAR TODO'),
(11067, 7, 'label_unselect_all', 'specific', 'DESELECCIONAR TODO'),
(11068, 7, 'button_export', 'specific', 'EXPORTAR'),
(11069, 7, 'label_progress', 'specific', 'PROGRESO'),
(11070, 7, 'button_select_sql_file', 'specific', 'SELECCIONAR ARCHIVO SQL'),
(11071, 7, 'text_male', 'specific', 'MASCULINO'),
(11072, 7, 'text_female', 'specific', 'FEMENINO'),
(11073, 7, 'title_tax_overview_report', 'specific', 'INFORME GENERAL IMPUESTO'),
(11074, 7, 'text_tax_overview_report_title', 'specific', 'INFORME GENERAL IMPUESTO'),
(11075, 7, 'text_sell_tax', 'specific', 'IMPUESTO VENTA'),
(11076, 7, 'text_purchase_tax', 'specific', 'IMPUESTO COMPRA'),
(11077, 7, 'label_tax_percent', 'specific', 'PORCENTAJE IMPUESTO '),
(11078, 7, 'label_count', 'specific', 'CONTAR'),
(11079, 7, 'text_login_title', 'specific', 'LOGIN'),
(11080, 7, 'text_invoice_title', 'specific', NULL),
(11081, 7, 'button_print', 'specific', NULL),
(11082, 7, 'button_send_email', 'specific', NULL),
(11083, 7, 'button_back_to_pos', 'specific', NULL),
(11084, 7, 'error_gateway', 'specific', NULL),
(11085, 7, 'text_update_success', 'specific', NULL),
(11086, 7, 'error_mobile', 'specific', NULL),
(11087, 7, 'error_currency', 'specific', NULL),
(11088, 7, 'text_create_success', 'specific', NULL),
(11089, 7, 'title_user_profile', 'specific', NULL),
(11090, 7, 'text_profile_title', 'specific', NULL),
(11091, 7, 'text_users', 'specific', NULL),
(11092, 7, 'text_since', 'specific', NULL),
(11093, 7, 'text_contact_information', 'specific', NULL),
(11094, 7, 'label_collection', 'specific', NULL),
(11095, 7, 'text_sell_report', 'specific', NULL),
(11096, 7, 'text_purchase_report', 'specific', NULL),
(11097, 7, 'text_payment_report', 'specific', NULL),
(11098, 7, 'text_login_log', 'specific', NULL),
(11099, 7, 'button_collection_report', 'specific', NULL),
(11100, 7, 'button_log', 'specific', NULL),
(11101, 7, 'text_invoice_list', 'specific', NULL),
(11102, 7, 'label_items', 'specific', NULL),
(11103, 7, 'label_time', 'specific', NULL),
(11104, 7, 'title_supplier_profile', 'specific', NULL),
(11105, 7, 'text_supplier_profile_title', 'specific', NULL),
(11106, 7, 'text_supplier_products', 'specific', NULL),
(11107, 7, 'button_products', 'specific', NULL),
(11108, 7, 'label_mobile_phone', 'specific', NULL),
(11109, 7, 'text_balance', 'specific', NULL),
(11110, 7, 'text_sells', 'specific', NULL),
(11111, 7, 'text_chart', 'specific', NULL),
(11112, 7, 'button_transaction_list', 'specific', NULL),
(11113, 7, 'text_purchase_invoice_list', 'specific', NULL),
(11114, 7, 'button_all_purchase', 'specific', NULL),
(11115, 7, 'button_due_purchase', 'specific', NULL),
(11116, 7, 'button_paid_purchase', 'specific', NULL),
(11117, 7, 'button_stock_transfer', 'specific', NULL),
(11118, 7, 'label_invoice_amount', 'specific', NULL),
(11119, 7, 'text_selling_invoice_list', 'specific', NULL),
(11120, 7, 'label_sup_name', 'specific', NULL),
(11121, 7, 'error_total_amount', 'specific', NULL),
(11122, 7, 'error_reference_no', 'specific', NULL),
(11123, 7, 'error_unit_name', 'specific', NULL),
(11124, 7, 'error_product_exist', 'specific', NULL),
(11125, 7, 'error_supplier_name', 'specific', NULL),
(11126, 7, 'text_stock_register', 'specific', NULL),
(11127, 7, 'label_select', 'specific', NULL),
(11128, 7, 'label_sold', 'specific', NULL),
(11129, 7, 'error_product_item', 'specific', NULL),
(11130, 7, 'text_barcode_print', 'specific', NULL),
(11131, 7, 'label_purchase_note', 'specific', NULL),
(11132, 7, 'text_purchase_update_success', 'specific', NULL),
(11133, 7, 'error_email_not_sent', 'specific', NULL),
(11134, 7, 'title_customer_profile', 'specific', NULL),
(11135, 7, 'text_total_purchase', 'specific', NULL),
(11136, 7, 'text_information', 'specific', NULL),
(11137, 7, 'label_giftcard_taken', 'specific', NULL),
(11138, 7, 'button_add_balance', 'specific', NULL),
(11139, 7, 'button_statement', 'specific', NULL),
(11140, 7, 'label_prev_due', 'specific', NULL),
(11141, 7, 'error_pmethod', 'specific', NULL),
(11142, 7, 'title_reset_your_system', 'specific', NULL),
(11143, 7, 'error_reference_no_exist', 'specific', NULL),
(11144, 1, 'error_invalid_password', 'specific', NULL),
(11145, 7, 'error_insufficient_balance', 'specific', NULL),
(11146, 7, 'text_all_invoice', 'specific', NULL),
(11147, 7, 'text_balance_added', 'specific', NULL),
(11148, 7, 'error_customer', 'specific', NULL),
(11149, 7, 'text_expiry', 'specific', NULL),
(11150, 7, 'error_category_exist', 'specific', NULL),
(11151, 7, 'error_mobile_exist', 'specific', NULL),
(11152, 7, 'error_email_exist', 'specific', NULL),
(11153, 7, 'error_not_found_or_insufficient_balance', 'specific', NULL),
(11154, 7, 'text_delete_holding_order_success', 'specific', NULL),
(11155, 7, 'button_view_details', 'specific', NULL),
(11156, 7, 'text_unpaid', 'specific', NULL),
(11157, 7, 'text_payments', 'specific', NULL),
(11158, 7, 'text_installment_details', 'specific', NULL),
(11159, 7, 'label_initial_payment', 'specific', NULL),
(11160, 7, 'label_interval_count', 'specific', NULL),
(11161, 7, 'label_installment_count', 'specific', NULL),
(11162, 7, 'label_last_installment_date', 'specific', NULL),
(11163, 7, 'label_installment_end_date', 'specific', NULL),
(11164, 7, 'error_order_name_exist', 'specific', NULL),
(11165, 7, 'placeholder_input_discount_amount', 'specific', NULL),
(11166, 7, 'text_billing_details', 'specific', NULL),
(11167, 7, 'label_previous_due_paid', 'specific', NULL),
(11168, 7, 'error_paid_amount', 'specific', NULL),
(11169, 7, 'text_sell_due_paid_success', 'specific', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `loans`
--

CREATE TABLE `loans` (
  `loan_id` int(10) UNSIGNED NOT NULL,
  `ref_no` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `loan_from` varchar(100) CHARACTER SET utf8 NOT NULL,
  `title` varchar(250) CHARACTER SET utf8 NOT NULL,
  `amount` decimal(25,4) UNSIGNED NOT NULL,
  `interest` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `payable` decimal(25,4) UNSIGNED NOT NULL,
  `paid` decimal(25,4) UNSIGNED NOT NULL DEFAULT 0.0000,
  `due` decimal(25,4) UNSIGNED NOT NULL DEFAULT 0.0000,
  `details` longtext CHARACTER SET utf8 NOT NULL,
  `attachment` text CHARACTER SET utf8 DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `loan_payments`
--

CREATE TABLE `loan_payments` (
  `id` int(11) UNSIGNED NOT NULL,
  `lloan_id` int(11) UNSIGNED NOT NULL,
  `ref_no` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paid` decimal(25,4) NOT NULL,
  `note` text CHARACTER SET utf8 DEFAULT NULL,
  `created_by` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `login_logs`
--

CREATE TABLE `login_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('success','error') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'success',
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `login_logs`
--

INSERT INTO `login_logs` (`id`, `user_id`, `username`, `ip`, `status`, `created_at`) VALUES
(1, 1, 'tusolutionweb@gmail.com', '201.240.205.187', 'success', NULL),
(2, 1, 'tusolutionweb@gmail.com', '201.240.205.187', 'success', NULL),
(3, 1, 'tusolutionweb@gmail.com', '201.240.205.187', 'success', NULL),
(4, 1, 'tusolutionweb@gmail.com', '201.240.205.187', 'success', NULL),
(5, 2, 'cashier@gmail.com ', '201.240.205.187', 'success', NULL),
(6, 3, 'salesman@gmail.com ', '201.240.205.187', 'success', NULL),
(7, 2, 'cashier@gmail.com ', '201.240.205.187', 'success', NULL),
(8, 1, 'tusolutionweb@gmail.com ', '201.240.205.187', 'success', NULL),
(9, 1, 'tusolutionweb@gmail.com', '201.240.205.187', 'success', NULL),
(10, 1, 'tusolutionweb@gmail.com', '201.240.205.187', 'success', NULL),
(11, 1, 'tusolutionweb@gmail.com', '201.240.205.187', 'success', NULL),
(12, 1, 'tusolutionweb@gmail.com', '201.240.205.187', 'success', NULL),
(13, 1, 'tusolutionweb@gmail.com', '201.240.205.187', 'success', NULL),
(14, 1, 'tusolutionweb@gmail.com', '201.240.205.187', 'success', NULL),
(15, 1, 'tusolutionweb@gmail.com', '201.240.205.187', 'success', NULL),
(16, 1, 'tusolutionweb@gmail.com', '201.240.205.187', 'success', NULL),
(17, 1, 'tusolutionweb@gmail.com', '::1', 'success', NULL),
(20, 1, 'fredyalegre@grupoalegres.com', '::1', 'success', NULL),
(21, 1, 'yheder.12@gmail.com', '::1', 'success', NULL),
(22, 1, 'yheder.12@gmail.com', '::1', 'success', NULL),
(23, 1, 'yheder.12@gmail.com', '::1', 'success', NULL),
(24, 1, 'yheder.12@gmail.com', '127.0.0.1', 'success', NULL),
(25, 1, 'yheder.12@gmail.com', '127.0.0.1', 'success', NULL),
(26, 1, 'yheder.12@gmail.com', '127.0.0.1', 'success', NULL),
(27, 1, 'yheder.12@gmail.com', '127.0.0.1', 'success', NULL),
(28, 1, 'yheder.12@gmail.com', '127.0.0.1', 'success', NULL),
(29, 1, 'yheder.12@gmail.com', '127.0.0.1', 'success', NULL),
(30, 1, 'yheder.12@gmail.com', '127.0.0.1', 'success', NULL),
(31, 1, 'yheder.12@gmail.com', '127.0.0.1', 'success', NULL),
(32, 1, 'yheder.12@gmail.com', '127.0.0.1', 'success', NULL),
(33, 1, 'yheder.12@gmail.com', '127.0.0.1', 'success', NULL),
(34, 1, 'mitienda@gmail.com', '127.0.0.1', 'success', NULL),
(35, 1, 'mitienda@gmail.com', '127.0.0.1', 'success', NULL),
(36, 1, 'mitienda@gmail.com', '127.0.0.1', 'success', NULL),
(37, 1, 'mitienda@gmail.com', '::1', 'success', NULL),
(38, 1, 'mitienda@gmail.com', '::1', 'success', NULL),
(39, 1, 'mitienda@gmail.com', '::1', 'success', NULL),
(40, 1, 'mitienda@gmail.com', '::1', 'success', NULL),
(41, 1, 'mitienda@gmail.com', '::1', 'success', NULL),
(42, 1, 'mitienda@gmail.com', '::1', 'success', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mail_sms_tag`
--

CREATE TABLE `mail_sms_tag` (
  `tag_id` int(11) UNSIGNED NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tagname` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `mail_sms_tag`
--

INSERT INTO `mail_sms_tag` (`tag_id`, `type`, `tagname`, `created_at`) VALUES
(1, 'invoice', '[customer_name]', '2019-03-08 14:50:39'),
(2, 'invoice', '[payable_amount]', '2019-07-02 12:12:59'),
(3, 'invoice', '[paid_amount]', '2019-07-02 12:13:02'),
(4, 'invoice', '[due]', '2019-07-02 12:13:04'),
(5, 'invoice', '[store_name]', '2019-07-02 12:13:07'),
(6, 'invoice', '[payment_status]', '2019-07-02 12:13:09'),
(7, 'invoice', '[customer_mobile]', '2019-07-02 12:13:11'),
(8, 'invoice', '[payment_method]', '2019-07-02 12:13:13'),
(9, 'invoice', '[date_time]', '2019-07-02 12:13:15'),
(10, 'invoice', '[date]', '2019-07-02 12:13:18'),
(11, 'invoice', '[tax]', '2019-07-02 12:13:20'),
(12, 'invoice', '[discount]', '2019-07-02 12:13:21'),
(13, 'invoice', '[address]', '2019-07-02 12:13:23'),
(14, 'invoice', '[invoice_id]', '2019-07-02 12:13:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'sell',
  `is_profit` tinyint(1) NOT NULL DEFAULT 1,
  `is_hide` tinyint(1) NOT NULL DEFAULT 0,
  `store_id` int(10) UNSIGNED NOT NULL,
  `invoice_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reference_no` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pmethod_id` int(10) UNSIGNED DEFAULT NULL,
  `transaction_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `capital` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `amount` decimal(25,4) NOT NULL,
  `details` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `attachment` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `total_paid` decimal(25,4) DEFAULT 0.0000,
  `pos_balance` decimal(25,4) DEFAULT 0.0000,
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `payments`
--

INSERT INTO `payments` (`id`, `type`, `is_profit`, `is_hide`, `store_id`, `invoice_id`, `reference_no`, `pmethod_id`, `transaction_id`, `capital`, `amount`, `details`, `attachment`, `note`, `total_paid`, `pos_balance`, `created_by`, `created_at`) VALUES
(1, 'sell', 1, 0, 1, '100000001', NULL, 1, NULL, '2500.0000', '3000.0000', 'a:0:{}', NULL, '', '4000.0000', '1000.0000', 1, '2019-12-15 12:19:46'),
(2, 'sell', 1, 0, 1, '100000002', NULL, 2, NULL, '7500.0000', '9000.0000', 'a:2:{s:12:\"bkash_trx_id\";s:0:\"\";s:14:\"bkash_phone_no\";s:0:\"\";}', NULL, '', '10000.0000', '1000.0000', 1, '2021-01-20 19:49:33'),
(3, 'sell', 1, 0, 1, '100000003', NULL, 1, NULL, '5000.0000', '6000.0000', 'a:0:{}', NULL, '', '6000.0000', '0.0000', 1, '2021-01-20 19:50:10'),
(4, 'sell', 1, 0, 1, '12021/00000004', NULL, 1, NULL, '5000.0000', '6000.0000', 'a:0:{}', NULL, '', '6000.0000', '0.0000', 1, '2021-01-20 21:17:58'),
(5, 'sell', 1, 0, 1, '12021/00000005', NULL, 1, NULL, '5.0000', '80.0000', 'a:0:{}', NULL, '', '80.0000', '0.0000', 1, '2021-01-24 22:15:26'),
(6, 'sell', 1, 0, 1, '12021/00000006', NULL, 1, NULL, '13.2000', '18.5000', 'a:0:{}', NULL, '', '18.5000', '0.0000', 1, '2021-01-24 22:27:04'),
(7, 'sell', 1, 0, 1, '12021/00000007', NULL, 5, NULL, '681.5000', '772.6900', 'a:0:{}', NULL, '', '772.6900', '0.0000', 1, '2021-01-24 22:27:30'),
(8, 'sell', 1, 0, 1, '12021/00000008', NULL, 1, NULL, '15.0000', '20.5000', 'a:0:{}', NULL, '', '20.5000', '0.0000', 1, '2021-01-24 22:44:46'),
(9, 'sell', 1, 0, 1, '12021/00000009', NULL, 1, NULL, '361.8000', '422.0000', 'a:0:{}', NULL, '', '422.0000', '0.0000', 1, '2021-01-24 22:51:17'),
(10, 'sell', 1, 0, 1, '12021/00000010', NULL, 1, NULL, '2501.5000', '3001.7000', 'a:0:{}', NULL, 'VENTA CASH', '3001.7000', '0.0000', 1, '2021-01-24 22:51:57'),
(11, 'sell', 1, 0, 1, '12021/00000011', NULL, 1, NULL, '10.0000', '15.0000', 'a:0:{}', NULL, '', '15.0000', '0.0000', 1, '2021-01-24 22:54:35'),
(12, 'sell', 1, 0, 1, '12021/00000012', NULL, 5, NULL, '5.0000', '5.5000', 'a:0:{}', NULL, '', '20.0000', '14.5000', 1, '2021-02-18 10:37:32'),
(13, 'sell', 1, 0, 1, '12021/00000013', NULL, 1, NULL, '400.0000', '450.9900', 'a:0:{}', NULL, '', '450.9900', '0.0000', 1, '2021-02-18 13:22:03'),
(14, 'sell', 1, 0, 1, '12021/00000014', NULL, 1, NULL, '186.8000', '219.5000', 'a:0:{}', NULL, '', '219.5000', '0.0000', 1, '2021-02-18 13:22:45'),
(15, 'sell', 1, 0, 1, '12021/00000015', NULL, 1, NULL, '753.2000', '879.4900', 'a:0:{}', NULL, '', '879.4900', '0.0000', 1, '2021-02-18 13:23:27'),
(16, 'sell', 1, 0, 1, '12021/00000016', NULL, 4, NULL, '5.0000', '7.5000', 'a:0:{}', NULL, '', '7.5000', '0.0000', 1, '2021-02-18 13:28:36'),
(17, 'sell', 1, 0, 1, '12021/00000017', NULL, 4, NULL, '80.0000', '100.0000', 'a:0:{}', NULL, '', '100.0000', '0.0000', 1, '2021-02-18 13:30:48'),
(18, 'sell', 1, 0, 1, '12021/00000018', NULL, 4, NULL, '80.0000', '100.0000', 'a:0:{}', NULL, '', '100.0000', '0.0000', 1, '2021-02-18 13:32:20'),
(19, 'sell', 1, 0, 1, '12021/00000019', NULL, 5, NULL, '2500.0000', '3000.0000', 'a:0:{}', NULL, '', '3000.0000', '0.0000', 1, '2021-02-18 13:33:44'),
(20, 'sell', 1, 0, 1, '12021/00000020', NULL, 3, NULL, '80.0000', '100.0000', 'a:1:{s:7:\"card_no\";s:16:\"4803745341067230\";}', NULL, '', '0.0000', '4900.0000', 1, '2021-02-18 13:43:05'),
(21, 'sell', 1, 0, 1, '12021/00000021', NULL, 4, NULL, '82.4000', '103.0000', 'a:0:{}', NULL, '', '103.0000', '0.0000', 1, '2021-02-18 17:22:59'),
(22, 'sell', 1, 0, 1, '12021/00000023', NULL, 5, NULL, '1.5000', '1.7000', 'a:0:{}', NULL, '', '1.7000', '0.0000', 1, '2021-02-18 17:25:30'),
(23, 'sell', 1, 0, 1, '12021/00000024', NULL, 5, NULL, '180.0000', '210.0000', 'a:0:{}', NULL, '', '210.0000', '0.0000', 1, '2021-02-18 17:25:40'),
(24, 'sell', 1, 0, 1, '12021/00000025', NULL, 5, NULL, '1.5000', '1.7000', 'a:0:{}', NULL, '', '36.0000', '34.3000', 1, '2021-02-18 17:25:54'),
(25, 'sell', 1, 0, 1, '12021/00000026', NULL, 4, NULL, '323.2000', '354.4900', 'a:0:{}', NULL, '', '354.4900', '0.0000', 1, '2021-02-18 19:07:46'),
(26, 'sell', 1, 0, 1, '12021/00000027', NULL, 4, NULL, '181.5000', '211.7000', 'a:0:{}', NULL, '', '211.7000', '0.0000', 1, '2021-02-18 19:08:19'),
(27, 'sell', 1, 0, 1, '12021/00000028', NULL, 3, NULL, '320.0000', '350.9900', 'a:1:{s:7:\"card_no\";s:16:\"7140723188493114\";}', NULL, '', '0.0000', '4649.0100', 1, '2021-02-18 19:09:27'),
(28, 'due_paid', 1, 0, 1, '12021/00000022', NULL, 5, NULL, '180.0000', '210.0000', 'a:0:{}', NULL, '', '250.0000', '40.0000', 1, '2021-02-18 19:22:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pmethods`
--

CREATE TABLE `pmethods` (
  `pmethod_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `code_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `details` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `pmethods`
--

INSERT INTO `pmethods` (`pmethod_id`, `name`, `code_name`, `details`, `created_at`, `updated_at`) VALUES
(1, 'Cash on Delivery', 'cod', 'Payment method details goes here...', '2018-03-23 18:00:00', '2019-05-08 18:18:30'),
(2, 'Bkash', 'bkash', 'Bkash a Brack Bank Service', '2019-01-09 18:00:00', '2019-07-02 16:13:57'),
(3, 'Gift card', 'gift_card', 'Details of giftcard payment method', '2019-02-04 11:32:44', '2019-07-02 21:41:57'),
(4, 'Credit', 'credit', 'Payment by customer credited balance', '2019-05-08 12:46:05', '2019-07-02 16:14:03'),
(5, 'VISA', 'visa', 'TARJETA VISA', '2021-01-22 16:13:10', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pmethod_to_store`
--

CREATE TABLE `pmethod_to_store` (
  `p2s_id` int(11) NOT NULL,
  `ppmethod_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL DEFAULT 1,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `pmethod_to_store`
--

INSERT INTO `pmethod_to_store` (`p2s_id`, `ppmethod_id`, `store_id`, `status`, `sort_order`) VALUES
(1, 1, 1, 1, 1),
(2, 2, 1, 1, 2),
(4, 4, 1, 1, 4),
(5, 3, 1, 1, 3),
(6, 1, 2, 1, 0),
(7, 2, 2, 1, 0),
(8, 3, 2, 1, 0),
(9, 4, 2, 1, 0),
(10, 5, 1, 1, 0),
(11, 5, 2, 1, 0),
(12, 5, 3, 1, 0),
(13, 1, 3, 1, 0),
(14, 2, 3, 1, 0),
(15, 3, 3, 1, 0),
(16, 4, 3, 1, 0),
(17, 5, 4, 1, 0),
(18, 1, 4, 1, 0),
(19, 2, 4, 1, 0),
(20, 3, 4, 1, 0),
(21, 4, 4, 1, 0),
(22, 5, 5, 1, 0),
(23, 1, 5, 1, 0),
(24, 2, 5, 1, 0),
(25, 3, 5, 1, 0),
(26, 4, 5, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pos_register`
--

CREATE TABLE `pos_register` (
  `id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `opening_balance` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `closing_balance` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `pos_register`
--

INSERT INTO `pos_register` (`id`, `store_id`, `opening_balance`, `closing_balance`, `note`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '100.0000', '0.0000', NULL, 1, '2019-12-15 12:23:49', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pos_templates`
--

CREATE TABLE `pos_templates` (
  `template_id` int(10) NOT NULL,
  `template_name` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `template_content` longtext CHARACTER SET ucs2 NOT NULL,
  `template_css` longtext CHARACTER SET utf8 DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `pos_templates`
--

INSERT INTO `pos_templates` (`template_id`, `template_name`, `template_content`, `template_css`, `created_at`, `updated_at`, `created_by`) VALUES
(1, 'Default', '&lt;section class=&quot;receipt-template&quot;&gt;\n\n        &lt;header class=&quot;receipt-header&quot;&gt;\n            &lt;div class=&quot;logo-area&quot;&gt;\n                &lt;img class=&quot;logo&quot; src=&quot;{{ logo_url }}&quot;&gt;\n            &lt;/div&gt;\n            &lt;h2 class=&quot;store-name&quot;&gt;{{ store_name }}&lt;/h2&gt;\n            &lt;div class=&quot;address-area&quot;&gt;\n                &lt;span class=&quot;info address&quot;&gt;{{ store_address }}&lt;/span&gt;\n                &lt;div class=&quot;block&quot;&gt;\n                    &lt;span class=&quot;info phone&quot;&gt;Mobile: {{ store_phone }}&lt;/span&gt;, &lt;span class=&quot;info email&quot;&gt;Email: {{ store_email }}&lt;/span&gt;\n                &lt;/div&gt;\n            &lt;/div&gt;\n        &lt;/header&gt;\n        \n        &lt;section class=&quot;info-area&quot;&gt;\n            &lt;table&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-30&quot;&gt;&lt;span&gt;Invoice ID:&lt;/td&gt;\n                    &lt;td&gt;{{ invoice_id }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-30&quot;&gt;VAT-Reg:&lt;/td&gt;\n                    &lt;td&gt;{{ vat_reg }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-30&quot;&gt;&lt;span&gt;Date:&lt;/td&gt;\n                    &lt;td&gt;{{ date_time }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-30&quot;&gt;&lt;span&gt;GST Reg:&lt;/td&gt;\n                    &lt;td&gt;{{ gst_reg }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-30&quot;&gt;Customer Name:&lt;/td&gt;\n                    &lt;td&gt;{{ customer_name }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-30&quot;&gt;Customer Phone:&lt;/td&gt;\n                    &lt;td&gt;{{ customer_phone }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-30&quot;&gt;GTIN:&lt;/td&gt;\n                    &lt;td&gt;{{ gtin }}&lt;/td&gt;\n                &lt;/tr&gt;\n            &lt;/table&gt;\n        &lt;/section&gt;\n        \n        &lt;h4 class=&quot;main-title&quot;&gt;INVOICE&lt;/h4&gt;\n        \n        {{ if items }}\n        &lt;section class=&quot;listing-area item-list&quot;&gt;\n            &lt;table&gt;\n                &lt;thead&gt;\n                    &lt;tr&gt;\n                        &lt;td class=&quot;w-10 text-center&quot;&gt;Sl.&lt;/td&gt;\n                        &lt;td class=&quot;w-40 text-center&quot;&gt;Name&lt;/td&gt;\n                        &lt;td class=&quot;w-15 text-center&quot;&gt;Qty&lt;/td&gt;\n                        &lt;td class=&quot;w-15 text-right&quot;&gt;Price&lt;/td&gt;\n                        &lt;td class=&quot;w-20 text-right&quot;&gt;Amount&lt;/td&gt;\n                    &lt;/tr&gt;\n                &lt;/thead&gt;\n                &lt;tbody&gt;\n                    {{ items }}\n                        &lt;tr&gt;\n                            &lt;td class=&quot;text-center&quot;&gt;{{ sl }}&lt;/td&gt;\n                            &lt;td&gt;{{ item_name }} \n                                {{ if invoice_view == indian_gst }} \n                                    &lt;small&gt;[HSN-{{ hsn_code }}]&lt;/small&gt;\n                                {{ endif }}\n                            &lt;/td&gt;\n                            &lt;td class=&quot;text-center&quot;&gt;{{ item_quantity }} {{ unitName }}&lt;/td&gt;\n                            &lt;td class=&quot;text-right&quot;&gt;{{ item_price }}&lt;/td&gt;\n                            &lt;td class=&quot;text-right&quot;&gt;{{ item_total }}&lt;/td&gt;   \n                        &lt;/tr&gt;\n                    {{ /items }}\n                &lt;/tbody&gt;\n            &lt;/table&gt;\n        &lt;/section&gt;\n        {{ endif }}\n        \n        &lt;section class=&quot;info-area calculation-area&quot;&gt;\n            &lt;table&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Total Amt:&lt;/td&gt;\n                    &lt;td&gt;{{ subtotal }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Order Tax:&lt;/td&gt;\n                    &lt;td&gt;{{ order_tax }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Discount:&lt;/td&gt;\n                    &lt;td&gt;{{ discount_amount }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Shipping Chrg:&lt;/td&gt;\n                    &lt;td&gt;{{ shipping_amount }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Others Chrg:&lt;/td&gt;\n                    &lt;td&gt;{{ others_charge }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Previous Due:&lt;/td&gt;\n                    &lt;td&gt;{{ previous_due }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Total Due:&lt;/td&gt;\n                    &lt;td&gt;{{ payable_amount }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Amount Paid:&lt;/td&gt;\n                    &lt;td&gt;{{ paid_amount }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Change:&lt;/td&gt;\n                    &lt;td&gt;{{ change }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Due:&lt;/td&gt;\n                    &lt;td&gt;{{ due }}&lt;/td&gt;\n                &lt;/tr&gt;\n            &lt;/table&gt;\n        &lt;/section&gt;\n        \n        &lt;section class=&quot;info-area italic&quot;&gt;\n            &lt;span class=&quot;text-small&quot;&gt;&lt;b&gt;In Text:&lt;/b&gt; {{ amount_in_text }}&lt;/span&gt;&lt;br&gt;\n            &lt;span class=&quot;text-small&quot;&gt;&lt;b&gt;Note:&lt;/b&gt; {{ invoice_note }}&lt;/span&gt;\n        &lt;/section&gt;\n\n        {{ if return_items }}\n        &lt;section class=&quot;listing-area payment-list&quot;&gt;\n            &lt;div class=&quot;heading&quot;&gt;\n                &lt;h2 class=&quot;sub-title&quot;&gt;Returns&lt;/h2&gt;\n            &lt;/div&gt;\n            &lt;table&gt;\n                &lt;thead&gt;\n                    &lt;td class=&quot;w-5 text-center&quot;&gt;Sl.&lt;/td&gt;\n                    &lt;td class=&quot;w-25 text-center&quot;&gt;Returned at&lt;/td&gt;\n                    &lt;td class=&quot;w-30 text-center&quot;&gt;Item Name&lt;/td&gt;\n                    &lt;td class=&quot;w-20 text-right&quot;&gt;Qty&lt;/td&gt;\n                    &lt;td class=&quot;w-20 text-right&quot;&gt;Amt&lt;/td&gt;\n                &lt;/thead&gt;\n                &lt;tbody&gt;\n                    {{return_items}}\n                        &lt;tr&gt;\n                            &lt;td class=&quot;text-center&quot;&gt;{{ sl }}&lt;/td&gt;\n                            &lt;td class=&quot;text-center&quot;&gt;{{ created_at }}&lt;/td&gt;\n                            &lt;td class=&quot;text-center&quot;&gt;{{ item_name }}&lt;/td&gt;\n                            &lt;td class=&quot;text-right&quot;&gt;{{ item_quantity }}&lt;/td&gt;\n                            &lt;td class=&quot;text-right&quot;&gt;{{ item_total }}&lt;/td&gt; \n                        &lt;/tr&gt;\n                    {{/return_items}}\n                &lt;/tbody&gt;\n            &lt;/table&gt;\n        &lt;/section&gt;\n        {{ endif }}\n        \n        {{ if payments }}\n        &lt;section class=&quot;listing-area payment-list&quot;&gt;\n            &lt;div class=&quot;heading&quot;&gt;\n                &lt;h2 class=&quot;sub-title&quot;&gt;Payments&lt;/h2&gt;\n            &lt;/div&gt;\n            &lt;table&gt;\n                &lt;thead&gt;\n                    &lt;td class=&quot;w-10 text-center&quot;&gt;Sl.&lt;/td&gt;\n                    &lt;td class=&quot;w-50 text-center&quot;&gt;Payment Method&lt;/td&gt;\n                    &lt;td class=&quot;w-20 text-right&quot;&gt;Amount&lt;/td&gt;\n                    &lt;td class=&quot;w-20 text-right&quot;&gt;Balance&lt;/td&gt;\n                &lt;/thead&gt;\n                &lt;tbody&gt;\n                    {{ payments }}\n                        &lt;tr&gt;\n                            &lt;td class=&quot;text-center&quot;&gt;{{ sl }}&lt;/td&gt;\n                            &lt;td&gt;{{ name }} by {{ by }} on {{ created_at }}&lt;/td&gt;\n                            &lt;td class=&quot;text-right&quot;&gt;{{ amount }}&lt;/td&gt;\n                            &lt;td class=&quot;text-right&quot;&gt;{{ pos_balance }}&lt;/td&gt; \n                        &lt;/tr&gt;\n                    {{ /payments }}\n                &lt;/tbody&gt;\n            &lt;/table&gt;\n        &lt;/section&gt;\n        {{ endif }}\n           \n        {{ if invoice_view != \'standard\' }}\n        {{ if taxes }}\n        &lt;section class=&quot;listing-area payment-list&quot;&gt;\n            &lt;div class=&quot;heading&quot;&gt;\n                &lt;h2 class=&quot;sub-title&quot;&gt;Tax Information&lt;/h2&gt;\n            &lt;/div&gt;\n            &lt;table&gt;\n                &lt;thead&gt;\n                    &lt;td class=&quot;w-25&quot;&gt;Name&lt;/td&gt;\n                    &lt;td class=&quot;w-25 text-center&quot;&gt;Code&lt;/td&gt;\n                    &lt;td class=&quot;w-25 text-right&quot;&gt;Qty&lt;/td&gt;\n                    &lt;td class=&quot;w-25 text-right&quot;&gt;Tax Amt.&lt;/td&gt;\n                &lt;/thead&gt;\n                &lt;tbody&gt;\n                    {{ taxes }}\n                        &lt;tr&gt;\n                            &lt;td&gt;{{ taxrate_name }}&lt;/td&gt;\n                            &lt;td class=&quot;text-center&quot;&gt;{{ code_name }}&lt;/td&gt;\n                            &lt;td class=&quot;text-right&quot;&gt;{{ qty }}&lt;/td&gt; \n                            &lt;td class=&quot;text-right&quot;&gt;{{ item_tax }}&lt;/td&gt; \n                        &lt;/tr&gt;\n                    {{ /taxes }}\n                    {{ if invoice_view == \'indian_gst\' }}\n                        &lt;tr class=&quot;bg-gray&quot;&gt;\n                            &lt;td&gt;Order Tax: {{ order_tax }}&lt;/td&gt;\n                            &lt;td class=&quot;text-right&quot;&gt;CGST: {{ cgst }}&lt;/td&gt;\n                            &lt;td class=&quot;text-right&quot;&gt;SGST: {{ sgst }}&lt;/td&gt;\n                            &lt;td class=&quot;text-right&quot;&gt;IGST: {{ igst }}&lt;/td&gt;\n                        &lt;/tr&gt;\n                     {{ endif }}\n                &lt;/tbody&gt;\n            &lt;/table&gt;\n        &lt;/section&gt;\n        {{ endif }}\n        {{ endif }}\n        \n        {{ if barcode }}\n        &lt;section class=&quot;info-area barcode-area&quot;&gt;\n            {{ barcode }}\n        &lt;/section&gt;\n        {{ endif }}\n        \n        &lt;section class=&quot;info-area align-center footer-area&quot;&gt;\n            &lt;span class=&quot;block&quot;&gt;Sold product No Claim. No Change, New product One Month Warranty.&lt;/span&gt;\n            &lt;span class=&quot;block bold&quot;&gt;{{ footer_text }}&lt;/span&gt;\n        &lt;/section&gt;\n        \n &lt;/section&gt;', '/*Common CSS*/\n        .receipt-template {\n            width:302px;\n            margin:0 auto;\n        }\n        .receipt-template .text-small {\n            font-size: 10px;\n        }\n        .receipt-template .block {\n            display: block;\n        }\n        .receipt-template .inline-block {\n            display: inline-block;\n        }\n        .receipt-template .bold {\n            font-weight: 700;\n        }\n        .receipt-template .italic {\n            font-style: italic;\n        }\n        .receipt-template .align-right {\n            text-align: right;\n        }\n        .receipt-template .align-center {\n            text-align: center;\n        }\n        .receipt-template .main-title {\n            font-size: 14px;\n            font-weight: 700;\n            text-align: center;\n            margin: 10px 0 5px 0;\n            padding: 0;\n        }\n        .receipt-template .heading {\n            position: relation;\n        }\n        .receipt-template .title {\n            font-size: 16px;\n            font-weight: 700;\n            margin: 10px 0 5px 0;\n        }\n        .receipt-template .sub-title {\n            font-size: 12px;\n            font-weight: 700;\n            margin: 10px 0 5px 0;\n        }\n        .receipt-template table {\n            width: 100%;\n        }\n        .receipt-template td,\n        .receipt-template th {\n            font-size:12px;\n        }\n        .receipt-template .info-area {\n            font-size: 12px;      \n            line-height: 1.222;  \n        }\n        .receipt-template .listing-area {\n            line-height: 1.222;\n        }\n        .receipt-template .listing-area table {}\n        .receipt-template .listing-area table thead tr {\n            border-top: 1px solid #000;\n            border-bottom: 1px solid #000;\n            font-weight: 700;\n        }\n        .receipt-template .listing-area table tbody tr {\n            border-top: 1px dashed #000;\n            border-bottom: 1px dashed #000;\n        }\n        .receipt-template .listing-area table tbody tr:last-child {\n            border-bottom: none;\n        }\n        .receipt-template .listing-area table td {\n            vertical-align: top;\n        }\n        .receipt-template .info-area table {}\n        .receipt-template .info-area table thead tr {\n            border-top: 1px solid #000;\n            border-bottom: 1px solid #000;\n        }\n    \n /*Receipt Heading*/\n        .receipt-template .receipt-header {\n            text-align: center;\n        }\n        .receipt-template .receipt-header .logo-area {\n            width: 80px;\n            height: 80px;\n            margin: 0 auto;\n        }\n        .receipt-template .receipt-header .logo-area img.logo {\n            display: inline-block;\n            max-width: 100%;\n            max-height: 100%;\n        }\n        .receipt-template .receipt-header .address-area {\n            margin-bottom: 5px;\n            line-height: 1;\n        }\n        .receipt-template .receipt-header .info {\n            font-size: 12px;\n        }\n        .receipt-template .receipt-header .store-name {\n            font-size: 24px;\n            font-weight: 700;\n            margin: 0;\n            padding: 0;\n        }\n        \n        \n/*Invoice Info Area*/ \n    .receipt-template .invoice-info-area {}\n    \n/*Customer Customer Area*/\n    .receipt-template .customer-area {\n        margin-top:10px;\n    }\n\n/*Calculation Area*/\n    .receipt-template .calculation-area {\n        border-top: 2px solid #000;\n        font-weight: bold;\n    }\n    .receipt-template .calculation-area table td {\n        text-align: right;\n    }\n    .receipt-template .calculation-area table td:nth-child(2) {\n        border-bottom: 1px dashed #000;\n    }\n\n/*Item Listing*/\n    .receipt-template .item-list table tr {\n    }\n    \n/*Barcode Area*/\n    .receipt-template .barcode-area {\n        margin-top: 10px;\n        text-align: center;\n    }\n    .receipt-template .barcode-area img {\n        max-width: 100%;\n        display: inline-block;\n    }\n    \n/*Footer Area*/\n    .receipt-template .footer-area {\n        line-height: 1.222;\n        font-size: 10px;\n    }\n \n/*Media Query*/\n    @media print {\n        .receipt-template {\n            width: 100%;\n        }\n    }\n    @media all and (max-width: 215px) {}', '2019-05-10 12:35:05', '2019-07-02 21:06:02', 1),
(2, 'Minimal', '&lt;section class=&quot;receipt-template&quot;&gt;\n\n        &lt;header class=&quot;receipt-header&quot;&gt;\n            &lt;div class=&quot;logo-area&quot;&gt;\n                &lt;img class=&quot;logo&quot; src=&quot;{{  logo_url  }}&quot;&gt;\n            &lt;/div&gt;\n            &lt;h2 class=&quot;store-name&quot;&gt;{{ store_name }}&lt;/h2&gt;\n            &lt;div class=&quot;address-area&quot;&gt;\n                &lt;span class=&quot;info address&quot;&gt;{{ store_address }}&lt;/span&gt;\n                &lt;div class=&quot;block&quot;&gt;\n                    &lt;span class=&quot;info phone&quot;&gt;Mobile: {{ store_phone }}&lt;/span&gt;, &lt;span class=&quot;info email&quot;&gt;Email: {{ store_email }}&lt;/span&gt;\n                &lt;/div&gt;\n            &lt;/div&gt;\n        &lt;/header&gt;\n        \n        &lt;div class=&quot;heading&quot;&gt;\n            &lt;h2 class=&quot;title text-center zero-around title-lg&quot;&gt;INVOICE&lt;/h2&gt;\n        &lt;/div&gt;\n        \n        &lt;section class=&quot;info-area&quot;&gt;\n            &lt;table&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-10&quot;&gt;&lt;span&gt;Bill #:&lt;/td&gt;\n                    &lt;td class=&quot;w-40 text-center sinfo billno&quot;&gt;&lt;span&gt;{{ invoice_id }}&lt;/span&gt;&lt;/td&gt;\n                    &lt;td class=&quot;w-10 text-right&quot;&gt;&lt;span&gt;Date:&lt;/td&gt;\n                    &lt;td class=&quot;w-40 text-center sinfo date&quot;&gt;&lt;span&gt;{{ date_time }}&lt;/span&gt;&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-10&quot;&gt;M/S:&lt;/td&gt;\n                    &lt;td class=&quot;w-90&quot; colspan=&quot;3&quot;&gt;&lt;span class=&quot;text-lg&quot;&gt;{{ customer_name }}&lt;/span&gt;&lt;/td&gt;\n                &lt;/tr&gt;\n            &lt;/table&gt;\n        &lt;/section&gt;\n        \n        &lt;section class=&quot;listing-area item-list&quot;&gt;\n            &lt;table&gt;\n                &lt;thead&gt;\n                    &lt;tr&gt;\n                        &lt;td class=&quot;w-40 text-center&quot;&gt;DESC.&lt;/td&gt;\n                        &lt;td class=&quot;w-15 text-center&quot;&gt;Qty&lt;/td&gt;\n                        &lt;td class=&quot;w-15 text-right&quot;&gt;Price&lt;/td&gt;\n                        &lt;td class=&quot;w-20 text-right&quot;&gt;AMT&lt;/td&gt;\n                    &lt;/tr&gt;\n                &lt;/thead&gt;\n                &lt;tbody&gt;\n                    {{ items }}\n                        &lt;tr&gt;\n                            &lt;td&gt;{{ item_name }}&lt;/td&gt;\n                            &lt;td class=&quot;text-center&quot;&gt;{{ item_quantity }}&lt;/td&gt;\n                            &lt;td class=&quot;text-right&quot;&gt;{{ item_price }}&lt;/td&gt;\n                            &lt;td class=&quot;text-right&quot;&gt;{{ item_total }}&lt;/td&gt;   \n                        &lt;/tr&gt;\n                    {{ /items }}\n                &lt;/tbody&gt;\n            &lt;/table&gt;\n        &lt;/section&gt;\n        \n        &lt;section class=&quot;info-area calculation-area&quot;&gt;\n            &lt;table&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Subtotal:&lt;/td&gt;\n                    &lt;td&gt;{{ subtotal }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Order Tax:&lt;/td&gt;\n                    &lt;td&gt;{{ order_tax }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Discount:&lt;/td&gt;\n                    &lt;td&gt;{{ discount_amount }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Shipping Chrg:&lt;/td&gt;\n                    &lt;td&gt;{{ shipping_amount }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Others Chrg:&lt;/td&gt;\n                    &lt;td&gt;{{ others_charge }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Previous Due:&lt;/td&gt;\n                    &lt;td&gt;{{ previous_due }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Amount Total:&lt;/td&gt;\n                    &lt;td&gt;{{ payable_amount }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Amount Paid:&lt;/td&gt;\n                    &lt;td&gt;{{ paid_amount }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Due:&lt;/td&gt;\n                    &lt;td&gt;{{ due }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Change:&lt;/td&gt;\n                    &lt;td&gt;{{ change }}&lt;/td&gt;\n                &lt;/tr&gt;\n            &lt;/table&gt;\n        &lt;/section&gt;\n        \n        &lt;section class=&quot;stylish-footer text-center&quot;&gt;\n            &lt;span&gt;Printed On: {{ printed_on }}&lt;/span&gt;\n        &lt;/section&gt;\n        \n        &lt;section class=&quot;info-area align-center footer-area&quot;&gt;\n            &lt;span class=&quot;block&quot;&gt;Sold product No Claim. No Change, New product One Month Warranty.&lt;/span&gt;\n            &lt;span class=&quot;block bold&quot;&gt;Thank You for Choosing Us.&lt;/span&gt;\n        &lt;/section&gt;\n        \n &lt;/section&gt;', '/*Common CSS*/\n        .receipt-template {\n            width:302px;\n            margin:0 auto;\n        }\n        .receipt-template .text-small {\n            font-size: 10px;\n        }\n        .receipt-template .block {\n            display: block;\n        }\n        .receipt-template .inline-block {\n            display: inline-block;\n        }\n        .receipt-template .bold {\n            font-weight: 700;\n        }\n        .receipt-template .italic {\n            font-style: italic;\n        }\n        .receipt-template .align-right {\n            text-align: right;\n        }\n        .receipt-template .align-center {\n            text-align: center;\n        }\n        .receipt-template .heading {\n            position: relation;\n        }\n        .receipt-template .title {\n            font-size: 16px;\n            font-weight: 700;\n            margin: 10px 0 5px 0;\n        }\n        .receipt-template .sub-title {\n            font-size: 12px;\n            font-weight: 700;\n            margin: 10px 0 5px 0;\n        }\n        .receipt-template table {\n            width: 100%;\n        }\n        .receipt-template td,\n        .receipt-template th {\n            font-size:10px;\n        }\n        .receipt-template .info-area {\n            font-size: 12px;      \n            line-height: 1.222;  \n        }\n        .receipt-template .listing-area {\n            line-height: 1.222;\n        }\n        .receipt-template .listing-area table {}\n        .receipt-template .listing-area table thead tr {\n            border-top: 1px solid #000;\n            border-bottom: 1px solid #000;\n            font-weight: 700;\n        }\n        .receipt-template .listing-area table tbody tr {\n            border-top: 1px dashed #000;\n            border-bottom: 1px dashed #000;\n        }\n        .receipt-template .listing-area table tbody tr:last-child {\n            border-bottom: none;\n        }\n        .receipt-template .listing-area table td {\n            vertical-align: top;\n        }\n        .receipt-template .info-area table {}\n        .receipt-template .info-area table thead tr {\n            border-top: 1px solid #000;\n            border-bottom: 1px solid #000;\n        }\n\n /*Receipt Heading*/\n        .receipt-template .receipt-header {\n            text-align: center;\n        }\n        .receipt-template .receipt-header .logo-area {\n            width: 80px;\n            height: 80px;\n            margin: 0 auto;\n        }\n        .receipt-template .receipt-header .logo-area img.logo {\n            display: inline-block;\n            max-width: 100%;\n            max-height: 100%;\n        }\n        .receipt-template .receipt-header .address-area {\n            margin-bottom: 5px;\n            line-height: 1;\n        }\n        .receipt-template .receipt-header .info {\n            font-size: 12px;\n        }\n        .receipt-template .receipt-header .store-name {\n            font-size: 28px;\n            font-weight: 700;\n            margin: 0;\n            padding: 0;\n        }\n        \n        \n/*Invoice Info Area*/ \n    .receipt-template .invoice-info-area {}\n    \n/*Customer Info Area*/\n    .receipt-template .customer-area {\n        margin-top:10px;\n    }\n\n/*Calculation Area*/\n    .receipt-template .calculation-area {\n        border-top: 2px solid #000;\n    }\n    .receipt-template .calculation-area table td {\n        text-align: right;\n    }\n    .receipt-template .calculation-area table td:nth-child(2) {\n        border-bottom: 1px dashed #000;\n    }\n\n/*Item Listing*/\n    .receipt-template .item-list table tr {\n    }\n    \n/*Barcode Area*/\n    .receipt-template .barcode-area {\n        margin-top: 10px;\n        text-align: center;\n    }\n    .receipt-template .barcode-area img {\n        max-width: 100%;\n        display: inline-block;\n    }\n    \n/*Footer Area*/\n    .receipt-template .footer-area {\n        line-height: 1.222;\n        font-size: 10px;\n    }\n \n/*Media Query*/\n    @media print {\n        .receipt-template {\n            width: 100%;\n        }\n    }\n    @media all and (max-width: 215px) {}\n    \n    \n/* Additional */\n        .receipt-template .zero-around {\n            margin:0;\n            padding: 0;\n        }\n        .receipt-template .title-lg {\n            font-size: 18px!important;\n            margin-bottom: 5px;\n         }\n         .receipt-template .text-lg {\n             font-size: 18px;\n             font-weight: 700;\n         }\n         .receipt-template .info-area td {\n             vertical-align: center;\n         }\n         .receipt-template .info-area td.sinfo {\n             padding: 1px!important;\n         }\n         .receipt-template .info-area td.sinfo span {\n             display: block;\n             font-weight: 700;\n             border: 1px solid #000;\n             padding: 2px;\n         }\n         .receipt-template .listing-area td, .receipt-template .listing-area th, .receipt-template .calculation-area table td {\n             font-size: 13px;\n             font-weight: 700;\n         }\n         .receipt-template .item-list table thead td {\n             text-align: center;\n             padding: 3px;\n             border: 2px solid #000;\n          }\n          .receipt-template .stylish-footer {\n              margin: 10px 0 5px 0;\n          }\n          .receipt-template .stylish-footer span {\n              display: inline-block;\n              font-size: 12px;\n              border-top: 1px dashed #000;\n              border-bottom: 1px dashed #000; \n          }\n', '2019-05-14 19:02:11', '2019-07-02 16:16:59', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pos_template_to_store`
--

CREATE TABLE `pos_template_to_store` (
  `pt2s` int(10) NOT NULL,
  `store_id` int(10) NOT NULL,
  `ttemplate_id` int(10) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `pos_template_to_store`
--

INSERT INTO `pos_template_to_store` (`pt2s`, `store_id`, `ttemplate_id`, `is_active`, `status`, `sort_order`) VALUES
(1, 1, 1, 1, 1, 1),
(2, 1, 2, 1, 1, 2),
(3, 2, 1, 1, 1, 0),
(4, 3, 1, 1, 1, 0),
(5, 4, 1, 1, 1, 0),
(6, 5, 1, 1, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `printers`
--

CREATE TABLE `printers` (
  `printer_id` int(11) NOT NULL,
  `title` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `profile` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `char_per_line` tinyint(3) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `printers`
--

INSERT INTO `printers` (`printer_id`, `title`, `type`, `profile`, `char_per_line`, `created_at`) VALUES
(1, 'Common Printer', 'network', '', 200, '2018-09-27 13:52:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `printer_to_store`
--

CREATE TABLE `printer_to_store` (
  `p2s_id` int(10) NOT NULL,
  `pprinter_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL DEFAULT 1,
  `path` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `port` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `sort_order` int(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `printer_to_store`
--

INSERT INTO `printer_to_store` (`p2s_id`, `pprinter_id`, `store_id`, `path`, `ip_address`, `port`, `status`, `sort_order`) VALUES
(1, 1, 1, '', NULL, '9100', 1, 0),
(2, 1, 2, NULL, NULL, NULL, 1, 0),
(3, 1, 3, NULL, NULL, NULL, 1, 0),
(4, 1, 4, NULL, NULL, NULL, 1, 0),
(5, 1, 5, NULL, NULL, NULL, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE `products` (
  `p_id` int(10) UNSIGNED NOT NULL,
  `p_type` enum('standard','service') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'standard',
  `p_code` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hsn_code` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `barcode_symbology` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `p_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `unit_id` int(10) UNSIGNED NOT NULL,
  `p_image` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `products`
--

INSERT INTO `products` (`p_id`, `p_type`, `p_code`, `hsn_code`, `barcode_symbology`, `p_name`, `category_id`, `unit_id`, `p_image`, `description`) VALUES
(1, 'standard', '84719278', NULL, 'code128', 'hp core i5 ', 1, 2, '/computadora3-e1537911659471.jpg', '&lt;p&gt;hp core i5&lt;/p&gt;\r\n&lt;p&gt;disco duro 100gb&lt;/p&gt;\r\n&lt;p&gt;memoria ram 8gb&lt;/p&gt;'),
(2, 'standard', '93376297', NULL, 'code128', 'INKA KOLA', 6, 2, '/descarga-1.jpg', ''),
(3, 'standard', '63168163', NULL, 'code128', 'PALTA', 6, 2, '/palta660x550-1-600x400.jpg', ''),
(4, 'standard', '54738453', NULL, 'code128', 'SODA FIELD', 3, 2, '/alletas-oda-an-orge-olsa-85-g-5209.jpg', ''),
(5, 'standard', '931240157', NULL, 'code128', 'CHOCMAN', 3, 2, '/descarga-2.jpg', ''),
(6, 'standard', '41137501', NULL, 'code128', 'PAVO', 2, 2, '/2330.jpg', ''),
(7, 'standard', '932658942', NULL, 'code128', 'TARI', 3, 2, '/2.jpg', ''),
(8, 'standard', '13495958', NULL, 'code128', 'ZAPATILLA', 5, 2, '/310997-1.jpg', ''),
(9, 'standard', '77725277', NULL, 'code128', 'CASACA', 4, 2, '/ff985ad4231986d5676e3adcd584de5f.png', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_images`
--

CREATE TABLE `product_images` (
  `image_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `product_images`
--

INSERT INTO `product_images` (`image_id`, `product_id`, `url`, `sort_order`) VALUES
(4, 1, '/computadora3-e1537911659471.jpg', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_to_store`
--

CREATE TABLE `product_to_store` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL DEFAULT 1,
  `purchase_price` float NOT NULL DEFAULT 0,
  `sell_price` float NOT NULL DEFAULT 0,
  `quantity_in_stock` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `alert_quantity` decimal(25,4) NOT NULL DEFAULT 10.0000,
  `sup_id` int(10) UNSIGNED NOT NULL,
  `brand_id` int(10) DEFAULT NULL,
  `box_id` int(10) UNSIGNED DEFAULT NULL,
  `taxrate_id` int(10) UNSIGNED DEFAULT NULL,
  `tax_method` varchar(55) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'inclusive',
  `preference` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `e_date` date DEFAULT NULL,
  `p_date` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `product_to_store`
--

INSERT INTO `product_to_store` (`id`, `product_id`, `store_id`, `purchase_price`, `sell_price`, `quantity_in_stock`, `alert_quantity`, `sup_id`, `brand_id`, `box_id`, `taxrate_id`, `tax_method`, `preference`, `e_date`, `p_date`, `status`, `sort_order`) VALUES
(1, 1, 1, 2500, 3000, '90.0000', '10.0000', 1, 1, 1, 2, 'inclusive', 'a:0:{}', '2020-12-14', '2019-12-15', 1, 1),
(2, 1, 2, 2500, 3000, '50.0000', '10.0000', 1, 1, 1, 2, 'inclusive', 'a:0:{}', '2020-12-14', '2021-01-20', 1, 0),
(3, 2, 1, 5, 7.5, '49.0000', '10.0000', 2, 0, 1, 2, 'inclusive', 'a:0:{}', '2022-01-21', '2021-01-21', 1, 0),
(4, 2, 2, 5, 7.5, '50.0000', '10.0000', 2, 0, 1, 2, 'inclusive', 'a:0:{}', '2022-01-21', '2021-01-21', 1, 50),
(5, 3, 1, 3.2, 3.5, '10.0000', '10.0000', 2, 0, 1, 2, 'inclusive', 'a:0:{}', '2022-01-22', '2021-01-22', 1, 0),
(6, 3, 2, 0, 90, '0.0000', '10.0000', 2, 0, 1, 2, 'inclusive', 'a:0:{}', '2022-01-22', '2021-01-22', 1, 50),
(7, 4, 1, 1.8, 2, '21.0000', '10.0000', 4, 0, 1, 2, 'inclusive', 'a:0:{}', '2022-01-24', '2021-01-24', 1, 0),
(8, 4, 2, 1.8, 2, '0.0000', '10.0000', 4, 0, 1, 2, 'inclusive', 'a:0:{}', '2022-01-24', '2021-01-24', 1, 0),
(9, 5, 1, 1.5, 1.7, '55.0000', '10.0000', 3, 0, 1, 2, 'inclusive', 'a:0:{}', '2022-01-24', '2021-01-24', 1, 0),
(10, 5, 2, 0, 1, '0.0000', '10.0000', 3, 0, 1, 2, 'inclusive', 'a:0:{}', '2022-01-24', '2021-01-24', 1, 0),
(11, 6, 1, 180, 210, '11.0000', '10.0000', 5, 0, 1, 2, 'inclusive', 'a:0:{}', '2022-01-24', '2021-01-24', 1, 0),
(12, 6, 2, 0, 30, '0.0000', '10.0000', 5, 0, 1, 2, 'inclusive', 'a:0:{}', '2022-01-24', '2021-01-24', 1, 0),
(13, 7, 1, 2.2, 2.5, '15.0000', '10.0000', 6, 0, 1, 2, 'inclusive', 'a:0:{}', '2022-01-24', '2021-01-24', 1, 0),
(14, 7, 2, 0, 2.5, '0.0000', '10.0000', 6, 0, 1, 2, 'inclusive', 'a:0:{}', '2022-01-24', '2021-01-24', 1, 0),
(15, 8, 1, 320, 350.99, '15.0000', '10.0000', 7, 0, 1, 2, 'inclusive', 'a:0:{}', '2022-01-24', '2021-01-24', 1, 0),
(16, 8, 2, 0, 350.99, '0.0000', '10.0000', 7, 0, 1, 2, 'inclusive', 'a:0:{}', '2022-01-24', '2021-01-24', 1, 0),
(17, 5, 3, 0, 0, '0.0000', '10.0000', 3, NULL, 1, NULL, 'inclusive', NULL, '2022-01-24', '2021-01-24', 1, 0),
(18, 1, 3, 0, 0, '0.0000', '10.0000', 1, NULL, 1, NULL, 'inclusive', NULL, '2020-12-14', '2021-01-24', 1, 0),
(19, 2, 3, 0, 0, '0.0000', '10.0000', 2, NULL, 1, NULL, 'inclusive', NULL, '2022-01-21', '2021-01-24', 1, 0),
(20, 3, 3, 0, 0, '0.0000', '10.0000', 2, NULL, 1, NULL, 'inclusive', NULL, '2022-01-22', '2021-01-24', 1, 0),
(21, 6, 3, 0, 0, '0.0000', '10.0000', 5, NULL, 1, NULL, 'inclusive', NULL, '2022-01-24', '2021-01-24', 1, 0),
(22, 4, 3, 0, 0, '0.0000', '10.0000', 4, NULL, 1, NULL, 'inclusive', NULL, '2022-01-24', '2021-01-24', 1, 0),
(23, 7, 3, 0, 0, '0.0000', '10.0000', 6, NULL, 1, NULL, 'inclusive', NULL, '2022-01-24', '2021-01-24', 1, 0),
(24, 8, 3, 0, 0, '0.0000', '10.0000', 7, NULL, 1, NULL, 'inclusive', NULL, '2022-01-24', '2021-01-24', 1, 0),
(25, 5, 4, 0, 0, '0.0000', '10.0000', 3, NULL, 1, NULL, 'inclusive', NULL, '2022-01-24', '2021-01-24', 1, 0),
(26, 1, 4, 0, 0, '0.0000', '10.0000', 1, NULL, 1, NULL, 'inclusive', NULL, '2020-12-14', '2021-01-24', 1, 0),
(27, 2, 4, 0, 0, '0.0000', '10.0000', 2, NULL, 1, NULL, 'inclusive', NULL, '2022-01-21', '2021-01-24', 1, 0),
(28, 3, 4, 0, 0, '0.0000', '10.0000', 2, NULL, 1, NULL, 'inclusive', NULL, '2022-01-22', '2021-01-24', 1, 0),
(29, 6, 4, 0, 0, '0.0000', '10.0000', 5, NULL, 1, NULL, 'inclusive', NULL, '2022-01-24', '2021-01-24', 1, 0),
(30, 4, 4, 0, 0, '0.0000', '10.0000', 4, NULL, 1, NULL, 'inclusive', NULL, '2022-01-24', '2021-01-24', 1, 0),
(31, 7, 4, 0, 0, '0.0000', '10.0000', 6, NULL, 1, NULL, 'inclusive', NULL, '2022-01-24', '2021-01-24', 1, 0),
(32, 8, 4, 0, 0, '0.0000', '10.0000', 7, NULL, 1, NULL, 'inclusive', NULL, '2022-01-24', '2021-01-24', 1, 0),
(33, 9, 1, 80, 100, '52.0000', '10.0000', 7, 1, 1, 2, 'inclusive', 'a:0:{}', '2022-02-18', '2021-02-18', 1, 0),
(34, 9, 2, 0, 80, '0.0000', '10.0000', 7, 1, 1, 2, 'inclusive', 'a:0:{}', '2022-02-18', '2021-02-18', 1, 0),
(35, 9, 3, 0, 80, '0.0000', '10.0000', 7, 1, 1, 2, 'inclusive', 'a:0:{}', '2022-02-18', '2021-02-18', 1, 0),
(36, 9, 4, 0, 80, '0.0000', '10.0000', 7, 1, 1, 2, 'inclusive', 'a:0:{}', '2022-02-18', '2021-02-18', 1, 0),
(37, 9, 5, 0, 80, '0.0000', '10.0000', 7, 1, 1, 2, 'inclusive', 'a:0:{}', '2022-02-18', '2021-02-18', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `purchase_info`
--

CREATE TABLE `purchase_info` (
  `info_id` int(10) UNSIGNED NOT NULL,
  `invoice_id` varchar(100) CHARACTER SET latin1 NOT NULL,
  `inv_type` enum('purchase','transfer') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'purchase',
  `store_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `sup_id` int(10) UNSIGNED DEFAULT NULL,
  `total_item` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `status` enum('stock','active','sold') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'stock',
  `total_sell` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `purchase_note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `attachment` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_visible` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `payment_status` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'due',
  `checkout_status` tinyint(1) NOT NULL DEFAULT 0,
  `shipping_status` enum('received','pending','ordered') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'received',
  `created_by` int(10) NOT NULL,
  `purchase_date` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `purchase_info`
--

INSERT INTO `purchase_info` (`info_id`, `invoice_id`, `inv_type`, `store_id`, `sup_id`, `total_item`, `status`, `total_sell`, `purchase_note`, `attachment`, `is_visible`, `payment_status`, `checkout_status`, `shipping_status`, `created_by`, `purchase_date`, `created_at`, `updated_at`) VALUES
(1, 'B2432', 'purchase', 1, 1, '1.0000', 'stock', '0.0000', 'compra de cor i5', '/computadora3-e1537911659471.jpg', 1, 'paid', 1, 'received', 1, '2019-12-15 00:00:00', '2019-12-15 12:18:36', NULL),
(2, 'BHp core i5', 'purchase', 2, 1, '1.0000', 'stock', '0.0000', '', '', 1, 'paid', 1, 'received', 1, '2021-01-23 00:00:00', '2021-01-23 20:56:29', NULL),
(3, 'B1', 'purchase', 2, 2, '1.0000', 'stock', '0.0000', 'Inka Kola', '', 1, 'paid', 1, 'received', 1, '2021-01-23 00:00:00', '2021-01-23 21:00:11', NULL),
(4, 'BINKA KOLA', 'purchase', 1, 2, '1.0000', 'stock', '0.0000', '', '', 1, 'paid', 1, 'received', 1, '2021-01-24 00:00:00', '2021-01-24 21:22:00', NULL),
(5, 'BCHOCMAN', 'purchase', 1, 3, '1.0000', 'stock', '0.0000', '', '/descarga-2.jpg', 1, 'paid', 1, 'received', 1, '2021-01-24 00:00:00', '2021-01-24 21:47:40', NULL),
(6, 'BPALTA', 'purchase', 1, 2, '1.0000', 'stock', '0.0000', '', '/palta660x550-1-600x400.jpg', 1, 'paid', 1, 'received', 1, '2021-01-24 00:00:00', '2021-01-24 21:59:06', NULL),
(7, 'BSODA', 'purchase', 1, 4, '1.0000', 'stock', '0.0000', '', '/alletas-oda-an-orge-olsa-85-g-5209.jpg', 1, 'paid', 1, 'received', 1, '2021-01-24 00:00:00', '2021-01-24 22:04:10', NULL),
(8, 'BPAVO', 'purchase', 1, 5, '1.0000', 'stock', '0.0000', '', '/2330.jpg', 1, 'paid', 1, 'received', 1, '2021-01-24 00:00:00', '2021-01-24 22:06:25', NULL),
(9, 'BCAT AIR', 'purchase', 1, 7, '1.0000', 'stock', '0.0000', '', '/310997-1.jpg', 1, 'paid', 1, 'received', 1, '2021-01-24 00:00:00', '2021-01-24 22:08:46', NULL),
(10, 'BTARI', 'purchase', 1, 6, '1.0000', 'stock', '0.0000', '', '/2.jpg', 1, 'paid', 1, 'received', 1, '2021-01-24 00:00:00', '2021-01-24 22:29:06', NULL),
(11, 'BPAVO1', 'purchase', 1, 5, '1.0000', 'stock', '0.0000', '', '/2330.jpg', 1, 'paid', 1, 'received', 1, '2021-01-24 00:00:00', '2021-01-24 22:48:33', NULL),
(12, 'BZAPATILLAS CATERPILLAR', 'purchase', 1, 7, '1.0000', 'stock', '0.0000', '', '/310997-1.jpg', 1, 'paid', 1, 'received', 1, '2021-01-24 00:00:00', '2021-01-24 22:53:42', NULL),
(13, 'B120', 'purchase', 1, 7, '1.0000', 'stock', '0.0000', '', '/ff985ad4231986d5676e3adcd584de5f.png', 1, 'paid', 1, 'received', 1, '2021-02-18 00:00:00', '2021-02-18 13:20:08', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `purchase_item`
--

CREATE TABLE `purchase_item` (
  `id` int(10) NOT NULL,
  `invoice_id` varchar(100) CHARACTER SET latin1 NOT NULL,
  `store_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `item_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `category_id` int(10) NOT NULL DEFAULT 0,
  `brand_id` int(10) DEFAULT NULL,
  `item_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `item_purchase_price` decimal(25,4) NOT NULL,
  `item_selling_price` decimal(25,4) NOT NULL,
  `item_quantity` decimal(25,4) NOT NULL,
  `total_sell` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `status` enum('stock','active','sold') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'stock',
  `item_total` decimal(25,4) NOT NULL,
  `item_tax` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `tax_method` enum('exclusive','inclusive') COLLATE utf8_unicode_ci DEFAULT 'exclusive',
  `tax` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gst` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cgst` decimal(25,4) DEFAULT NULL,
  `sgst` decimal(25,4) DEFAULT NULL,
  `igst` decimal(25,4) DEFAULT NULL,
  `return_quantity` decimal(25,4) NOT NULL DEFAULT 0.0000
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `purchase_item`
--

INSERT INTO `purchase_item` (`id`, `invoice_id`, `store_id`, `item_id`, `category_id`, `brand_id`, `item_name`, `item_purchase_price`, `item_selling_price`, `item_quantity`, `total_sell`, `status`, `item_total`, `item_tax`, `tax_method`, `tax`, `gst`, `cgst`, `sgst`, `igst`, `return_quantity`) VALUES
(1, 'B2432', 1, 1, 1, 1, 'hp core i5 ', '2500.0000', '3000.0000', '100.0000', '10.0000', 'active', '250000.0000', '45000.0000', 'inclusive', '18.0000', '18.0000', '22500.0000', '22500.0000', '0.0000', '0.0000'),
(2, 'BHp core i5', 2, 1, 1, NULL, 'hp core i5 ', '2500.0000', '3000.0000', '50.0000', '0.0000', 'active', '125000.0000', '0.0000', 'inclusive', '0', '0', '0.0000', '0.0000', '0.0000', '0.0000'),
(3, 'B1', 2, 2, 6, 0, 'INKA KOLA', '3.0000', '5.0000', '50.0000', '0.0000', 'active', '150.0000', '27.0000', 'inclusive', '18.0000', '18.0000', '0.0000', '0.0000', '27.0000', '0.0000'),
(4, 'BINKA KOLA', 1, 2, 6, 0, 'INKA KOLA', '5.0000', '80.0000', '60.0000', '11.0000', 'active', '300.0000', '54.0000', 'inclusive', '18.0000', '18.0000', '0.0000', '0.0000', '54.0000', '0.0000'),
(5, 'BCHOCMAN', 1, 5, 3, 0, 'CHOCMAN', '1.5000', '1.7000', '60.0000', '5.0000', 'active', '90.0000', '16.2000', 'inclusive', '18.0000', '18.0000', '0.0000', '0.0000', '16.2000', '0.0000'),
(6, 'BPALTA', 1, 3, 6, 0, 'PALTA', '3.2000', '3.5000', '15.0000', '5.0000', 'active', '48.0000', '8.6400', 'inclusive', '18.0000', '18.0000', '0.0000', '0.0000', '8.6400', '0.0000'),
(7, 'BSODA', 1, 4, 3, 0, 'SOSA FIELD', '1.8000', '2.0000', '25.0000', '4.0000', 'active', '45.0000', '8.1000', 'inclusive', '18.0000', '18.0000', '0.0000', '0.0000', '8.1000', '0.0000'),
(8, 'BPAVO', 1, 6, 2, 0, 'PAVO', '180.0000', '210.0000', '5.0000', '5.0000', 'sold', '900.0000', '162.0000', 'inclusive', '18.0000', '18.0000', '0.0000', '0.0000', '162.0000', '0.0000'),
(9, 'BCAT AIR', 1, 8, 5, 0, 'ZAPATILLA', '320.0000', '350.9900', '10.0000', '5.0000', 'active', '3200.0000', '576.0000', 'inclusive', '18.0000', '18.0000', '0.0000', '0.0000', '576.0000', '0.0000'),
(10, 'BTARI', 1, 7, 3, 0, 'TARI', '2.2000', '2.5000', '15.0000', '0.0000', 'active', '33.0000', '5.9400', 'inclusive', '18.0000', '18.0000', '0.0000', '0.0000', '5.9400', '0.0000'),
(11, 'BPAVO1', 1, 6, 2, 0, 'PAVO', '180.0000', '210.0000', '15.0000', '4.0000', 'active', '2700.0000', '486.0000', 'inclusive', '18.0000', '18.0000', '0.0000', '0.0000', '486.0000', '0.0000'),
(12, 'BZAPATILLAS CATERPILLAR', 1, 8, 5, 0, 'ZAPATILLA', '320.0000', '350.9900', '10.0000', '0.0000', 'stock', '3200.0000', '576.0000', 'inclusive', '18.0000', '18.0000', '0.0000', '0.0000', '576.0000', '0.0000'),
(13, 'B120', 1, 9, 4, 1, 'CASACA', '80.0000', '100.0000', '60.0000', '8.0000', 'active', '4800.0000', '864.0000', 'inclusive', '18.0000', '18.0000', '0.0000', '0.0000', '864.0000', '0.0000');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `purchase_logs`
--

CREATE TABLE `purchase_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `sup_id` int(10) UNSIGNED NOT NULL,
  `reference_no` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ref_invoice_id` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `pmethod_id` int(11) DEFAULT NULL,
  `description` text CHARACTER SET utf8 DEFAULT NULL,
  `amount` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `store_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `purchase_logs`
--

INSERT INTO `purchase_logs` (`id`, `sup_id`, `reference_no`, `ref_invoice_id`, `type`, `pmethod_id`, `description`, `amount`, `store_id`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'CT191215100001', 'B2432', 'purchase', 1, 'Paid while purchasing', '250000.0000', 1, 1, '2019-12-15 12:18:36', NULL),
(2, 1, 'CT210123200001', 'BHp core i5', 'purchase', 1, 'Paid while purchasing', '147580.0000', 2, 1, '2021-01-23 20:56:29', NULL),
(3, 2, 'CT2101232002', 'B1', 'purchase', 1, 'Paid while purchasing', '165.0000', 2, 1, '2021-01-23 21:00:11', NULL),
(4, 2, 'CT2101241002', 'BINKA KOLA', 'purchase', 1, 'Paid while purchasing', '374.2800', 1, 1, '2021-01-24 21:22:00', NULL),
(5, 3, 'CT21012411003', 'BCHOCMAN', 'purchase', 1, 'Paid while purchasing', '138.2840', 1, 1, '2021-01-24 21:47:40', NULL),
(6, 2, 'CT21012411004', 'BPALTA', 'purchase', 1, 'Paid while purchasing', '48.0000', 1, 1, '2021-01-24 21:59:06', NULL),
(7, 4, 'CT21012411005', 'BSODA', 'purchase', 5, 'Paid while purchasing', '61.6420', 1, 1, '2021-01-24 22:04:10', NULL),
(8, 5, 'CT21012411006', 'BPAVO', 'purchase', 1, 'Paid while purchasing', '900.0000', 1, 1, '2021-01-24 22:06:25', NULL),
(9, 7, 'CT21012411007', 'BCAT AIR', 'purchase', 5, 'Paid while purchasing', '3200.0000', 1, 1, '2021-01-24 22:08:46', NULL),
(10, 6, 'CT21012411008', 'BTARI', 'purchase', 5, 'Paid while purchasing', '33.0000', 1, 1, '2021-01-24 22:29:06', NULL),
(11, 5, 'CT21012411009', 'BPAVO1', 'purchase', 1, 'Paid while purchasing', '2700.0000', 1, 1, '2021-01-24 22:48:33', NULL),
(12, 7, 'CT21012411010', 'BZAPATILLAS CATERPILLAR', 'purchase', 1, 'Paid while purchasing', '3215.0000', 1, 1, '2021-01-24 22:53:42', NULL),
(13, 7, 'CT21021811011', 'B120', 'purchase', 5, 'Paid while purchasing', '5511.4800', 1, 1, '2021-02-18 13:20:08', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `purchase_payments`
--

CREATE TABLE `purchase_payments` (
  `id` int(11) NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'purchase',
  `is_hide` tinyint(1) NOT NULL DEFAULT 0,
  `store_id` int(10) UNSIGNED NOT NULL,
  `invoice_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reference_no` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pmethod_id` int(10) UNSIGNED DEFAULT NULL,
  `transaction_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `details` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `attachment` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` decimal(25,4) NOT NULL,
  `total_paid` decimal(25,4) DEFAULT 0.0000,
  `balance` decimal(25,4) DEFAULT 0.0000,
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `purchase_payments`
--

INSERT INTO `purchase_payments` (`id`, `type`, `is_hide`, `store_id`, `invoice_id`, `reference_no`, `pmethod_id`, `transaction_id`, `details`, `attachment`, `note`, `amount`, `total_paid`, `balance`, `created_by`, `created_at`) VALUES
(1, 'purchase', 0, 1, 'B2432', NULL, 1, NULL, NULL, NULL, 'compra de cor i5', '250000.0000', '250000.0000', '0.0000', 1, '2019-12-15 12:18:36'),
(2, 'purchase', 0, 2, 'BHp core i5', NULL, 1, NULL, NULL, NULL, '', '147580.0000', '147580.0000', '0.0000', 1, '2021-01-23 20:56:29'),
(3, 'purchase', 0, 2, 'B1', NULL, 1, NULL, NULL, NULL, '', '165.0000', '165.0000', '0.0000', 1, '2021-01-23 21:00:11'),
(4, 'purchase', 0, 1, 'BINKA KOLA', NULL, 1, NULL, NULL, NULL, '', '374.2800', '374.2800', '0.0000', 1, '2021-01-24 21:22:00'),
(5, 'purchase', 0, 1, 'BCHOCMAN', NULL, 1, NULL, NULL, NULL, '', '138.2840', '138.2840', '0.0000', 1, '2021-01-24 21:47:40'),
(6, 'purchase', 0, 1, 'BPALTA', NULL, 1, NULL, NULL, NULL, '', '48.0000', '48.0000', '0.0000', 1, '2021-01-24 21:59:06'),
(7, 'purchase', 0, 1, 'BSODA', NULL, 5, NULL, NULL, NULL, '', '61.6420', '61.6420', '0.0000', 1, '2021-01-24 22:04:10'),
(8, 'purchase', 0, 1, 'BPAVO', NULL, 1, NULL, NULL, NULL, '', '900.0000', '900.0000', '0.0000', 1, '2021-01-24 22:06:25'),
(9, 'purchase', 0, 1, 'BCAT AIR', NULL, 5, NULL, NULL, NULL, '', '3200.0000', '3200.0000', '0.0000', 1, '2021-01-24 22:08:46'),
(10, 'purchase', 0, 1, 'BTARI', NULL, 5, NULL, NULL, NULL, '', '33.0000', '33.0000', '0.0000', 1, '2021-01-24 22:29:06'),
(11, 'purchase', 0, 1, 'BPAVO1', NULL, 1, NULL, NULL, NULL, '', '2700.0000', '2700.0000', '0.0000', 1, '2021-01-24 22:48:33'),
(12, 'purchase', 0, 1, 'BZAPATILLAS CATERPILLAR', NULL, 1, NULL, NULL, NULL, '', '3215.0000', '3215.0000', '0.0000', 1, '2021-01-24 22:53:42'),
(13, 'purchase', 0, 1, 'B120', NULL, 5, NULL, NULL, NULL, '', '5511.4800', '5511.4800', '0.0000', 1, '2021-02-18 13:20:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `purchase_price`
--

CREATE TABLE `purchase_price` (
  `price_id` int(10) NOT NULL,
  `invoice_id` varchar(100) CHARACTER SET latin1 NOT NULL,
  `store_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `subtotal` decimal(25,4) DEFAULT NULL,
  `discount_type` enum('percentage','plain') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'plain',
  `discount_amount` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `shipping_type` enum('plain','percentage') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'plain',
  `shipping_amount` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `others_charge` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `item_tax` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `order_tax` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `cgst` decimal(25,4) DEFAULT NULL,
  `sgst` decimal(25,4) DEFAULT NULL,
  `igst` decimal(25,4) DEFAULT NULL,
  `payable_amount` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `paid_amount` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `due_paid` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `due` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `return_amount` decimal(25,4) UNSIGNED NOT NULL DEFAULT 0.0000,
  `balance` decimal(25,4) NOT NULL DEFAULT 0.0000
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `purchase_price`
--

INSERT INTO `purchase_price` (`price_id`, `invoice_id`, `store_id`, `subtotal`, `discount_type`, `discount_amount`, `shipping_type`, `shipping_amount`, `others_charge`, `item_tax`, `order_tax`, `cgst`, `sgst`, `igst`, `payable_amount`, `paid_amount`, `due_paid`, `due`, `return_amount`, `balance`) VALUES
(1, 'B2432', 1, '250000.0000', 'plain', '0.0000', 'plain', '0.0000', '0.0000', '45000.0000', '0.0000', '22500.0000', '22500.0000', '0.0000', '250000.0000', '250000.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(2, 'BHp core i5', 2, '125000.0000', 'plain', '0.0000', 'plain', '50.0000', '30.0000', '0.0000', '22500.0000', '0.0000', '0.0000', '22500.0000', '147580.0000', '147580.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(3, 'B1', 2, '150.0000', 'plain', '0.0000', 'plain', '10.0000', '5.0000', '27.0000', '0.0000', '0.0000', '0.0000', '27.0000', '165.0000', '165.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(4, 'BINKA KOLA', 1, '300.0000', 'plain', '0.0000', 'plain', '20.0000', '10.0000', '54.0000', '44.2800', '0.0000', '0.0000', '98.2800', '374.2800', '374.2800', '0.0000', '0.0000', '0.0000', '0.0000'),
(5, 'BCHOCMAN', 1, '90.0000', 'plain', '0.0000', 'plain', '25.0000', '10.0000', '16.2000', '13.2840', '0.0000', '0.0000', '29.4840', '138.2840', '138.2840', '0.0000', '0.0000', '0.0000', '0.0000'),
(6, 'BPALTA', 1, '48.0000', 'plain', '0.0000', 'plain', '0.0000', '0.0000', '8.6400', '0.0000', '0.0000', '0.0000', '8.6400', '48.0000', '48.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(7, 'BSODA', 1, '45.0000', 'plain', '0.0000', 'plain', '5.0000', '5.0000', '8.1000', '6.6420', '0.0000', '0.0000', '14.7420', '61.6420', '61.6420', '0.0000', '0.0000', '0.0000', '0.0000'),
(8, 'BPAVO', 1, '900.0000', 'plain', '0.0000', 'plain', '0.0000', '0.0000', '162.0000', '0.0000', '0.0000', '0.0000', '162.0000', '900.0000', '900.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(9, 'BCAT AIR', 1, '3200.0000', 'plain', '0.0000', 'plain', '0.0000', '0.0000', '576.0000', '0.0000', '0.0000', '0.0000', '576.0000', '3200.0000', '3200.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(10, 'BTARI', 1, '33.0000', 'plain', '0.0000', 'plain', '0.0000', '0.0000', '5.9400', '0.0000', '0.0000', '0.0000', '5.9400', '33.0000', '33.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(11, 'BPAVO1', 1, '2700.0000', 'plain', '0.0000', 'plain', '0.0000', '0.0000', '486.0000', '0.0000', '0.0000', '0.0000', '486.0000', '2700.0000', '2700.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(12, 'BZAPATILLAS CATERPILLAR', 1, '3200.0000', 'plain', '0.0000', 'plain', '10.0000', '5.0000', '576.0000', '0.0000', '0.0000', '0.0000', '576.0000', '3215.0000', '3215.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(13, 'B120', 1, '4800.0000', 'plain', '0.0000', 'plain', '0.0000', '3.0000', '864.0000', '708.4800', '0.0000', '0.0000', '1572.4800', '5511.4800', '5511.4800', '0.0000', '0.0000', '0.0000', '0.0000');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `purchase_returns`
--

CREATE TABLE `purchase_returns` (
  `id` int(11) NOT NULL,
  `store_id` int(10) NOT NULL,
  `reference_no` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `sup_id` int(10) NOT NULL,
  `note` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `total_item` int(10) DEFAULT NULL,
  `total_quantity` decimal(25,4) NOT NULL,
  `subtotal` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `total_amount` decimal(25,4) NOT NULL,
  `item_tax` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `cgst` decimal(25,4) DEFAULT NULL,
  `sgst` decimal(25,4) DEFAULT NULL,
  `igst` decimal(25,4) DEFAULT NULL,
  `attachment` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `purchase_return_items`
--

CREATE TABLE `purchase_return_items` (
  `id` int(11) NOT NULL,
  `store_id` int(10) NOT NULL,
  `invoice_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `item_quantity` decimal(15,4) NOT NULL,
  `item_price` decimal(25,4) NOT NULL,
  `item_tax` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `cgst` decimal(25,4) DEFAULT NULL,
  `sgst` decimal(25,4) DEFAULT NULL,
  `igst` decimal(25,4) DEFAULT NULL,
  `item_total` decimal(25,4) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quotation_info`
--

CREATE TABLE `quotation_info` (
  `info_id` int(10) NOT NULL,
  `reference_no` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `invoice_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `store_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `customer_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `customer_mobile` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('sent','pending','complete','canceled') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'sent',
  `payment_status` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `quotation_note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_installment` tinyint(1) NOT NULL DEFAULT 0,
  `is_order` tinyint(1) NOT NULL DEFAULT 0,
  `total_items` int(10) NOT NULL DEFAULT 0,
  `address` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `pmethod_details` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quotation_item`
--

CREATE TABLE `quotation_item` (
  `id` int(10) NOT NULL,
  `reference_no` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `store_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `sup_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `brand_id` int(10) DEFAULT NULL,
  `item_id` int(10) UNSIGNED NOT NULL,
  `item_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `item_price` decimal(25,4) NOT NULL,
  `item_discount` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `item_tax` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `tax_method` enum('exclusive','inclusive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'exclusive',
  `taxrate_id` int(10) UNSIGNED DEFAULT NULL,
  `tax` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gst` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cgst` decimal(25,4) DEFAULT NULL,
  `sgst` decimal(25,4) DEFAULT NULL,
  `igst` decimal(25,4) DEFAULT NULL,
  `item_quantity` decimal(25,4) NOT NULL,
  `item_purchase_price` decimal(25,4) DEFAULT NULL,
  `item_total` decimal(25,4) NOT NULL,
  `purchase_invoice_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quotation_price`
--

CREATE TABLE `quotation_price` (
  `price_id` int(10) NOT NULL,
  `reference_no` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `store_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `subtotal` decimal(25,4) DEFAULT NULL,
  `discount_type` enum('plain','percentage') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'plain',
  `discount_amount` decimal(25,4) DEFAULT 0.0000,
  `interest_amount` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `interest_percentage` int(10) NOT NULL DEFAULT 0,
  `item_tax` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `order_tax` decimal(25,4) DEFAULT 0.0000,
  `cgst` decimal(25,4) DEFAULT NULL,
  `sgst` decimal(25,4) DEFAULT NULL,
  `igst` decimal(25,4) DEFAULT NULL,
  `total_purchase_price` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `shipping_type` enum('plain','percentage') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'plain',
  `shipping_amount` decimal(25,4) DEFAULT 0.0000,
  `others_charge` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `payable_amount` decimal(25,4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `returns`
--

CREATE TABLE `returns` (
  `id` int(11) NOT NULL,
  `store_id` int(10) NOT NULL,
  `reference_no` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `customer_id` int(10) NOT NULL,
  `note` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `total_item` decimal(25,4) DEFAULT NULL,
  `total_quantity` decimal(25,4) NOT NULL,
  `subtotal` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `total_amount` decimal(25,4) NOT NULL,
  `item_tax` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `cgst` decimal(25,4) DEFAULT NULL,
  `sgst` decimal(25,4) DEFAULT NULL,
  `igst` decimal(25,4) DEFAULT NULL,
  `total_purchase_price` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `profit` decimal(25,4) UNSIGNED NOT NULL DEFAULT 0.0000,
  `attachment` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `return_items`
--

CREATE TABLE `return_items` (
  `id` int(11) NOT NULL,
  `store_id` int(10) NOT NULL,
  `invoice_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `item_quantity` decimal(25,4) NOT NULL,
  `item_purchase_price` decimal(25,4) DEFAULT NULL,
  `item_price` decimal(25,4) NOT NULL,
  `item_tax` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `cgst` decimal(25,4) DEFAULT NULL,
  `sgst` decimal(25,4) DEFAULT NULL,
  `igst` decimal(25,4) DEFAULT NULL,
  `item_total` decimal(25,4) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `selling_info`
--

CREATE TABLE `selling_info` (
  `info_id` int(10) NOT NULL,
  `invoice_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `edit_counter` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `inv_type` enum('sell') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'sell',
  `store_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `customer_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `customer_mobile` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ref_invoice_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ref_user_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `invoice_note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `total_items` smallint(6) DEFAULT NULL,
  `is_installment` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `pmethod_id` int(10) DEFAULT NULL,
  `payment_status` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `checkout_status` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `selling_info`
--

INSERT INTO `selling_info` (`info_id`, `invoice_id`, `edit_counter`, `inv_type`, `store_id`, `customer_id`, `customer_mobile`, `ref_invoice_id`, `ref_user_id`, `invoice_note`, `total_items`, `is_installment`, `status`, `pmethod_id`, `payment_status`, `checkout_status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, '100000001', 0, 'sell', 1, 1, '0170000000000', NULL, 0, '', 1, 0, 1, 1, 'paid', 1, 1, '2019-12-15 12:19:46', NULL),
(2, '100000002', 0, 'sell', 1, 1, '0170000000000', NULL, 0, '', 1, 0, 1, 2, 'paid', 1, 1, '2021-01-20 19:49:33', NULL),
(3, '100000003', 0, 'sell', 1, 1, '0170000000000', NULL, 0, '', 1, 0, 1, 1, 'paid', 1, 1, '2021-01-20 19:50:10', NULL),
(4, '12021/00000004', 0, 'sell', 1, 1, '93245684', NULL, 0, '', 1, 0, 1, 1, 'paid', 1, 1, '2021-01-20 21:17:58', NULL),
(5, '12021/00000005', 0, 'sell', 1, 1, '93245684', NULL, 0, '', 1, 0, 1, 1, 'paid', 1, 1, '2021-01-24 22:15:26', NULL),
(6, '12021/00000006', 0, 'sell', 1, 1, '93245684', NULL, 0, '', 2, 0, 1, 1, 'paid', 1, 1, '2021-01-24 22:27:04', NULL),
(7, '12021/00000007', 0, 'sell', 1, 1, '93245684', NULL, 0, '', 3, 0, 1, 5, 'paid', 1, 1, '2021-01-24 22:27:30', NULL),
(8, '12021/00000008', 0, 'sell', 1, 1, '93245684', NULL, 0, '', 3, 0, 1, 1, 'paid', 1, 1, '2021-01-24 22:44:46', NULL),
(9, '12021/00000009', 0, 'sell', 1, 1, '93245684', NULL, 0, '', 2, 0, 1, 1, 'paid', 1, 1, '2021-01-24 22:51:17', NULL),
(10, '12021/00000010', 0, 'sell', 1, 1, '93245684', NULL, 0, 'VENTA CASH', 2, 0, 1, 1, 'paid', 1, 1, '2021-01-24 22:51:57', NULL),
(11, '12021/00000011', 0, 'sell', 1, 1, '93245684', NULL, 0, '', 1, 0, 1, 1, 'paid', 1, 1, '2021-01-24 22:54:35', NULL),
(12, '12021/00000012', 0, 'sell', 1, 1, '93245684', NULL, 0, '', 2, 0, 1, 5, 'paid', 1, 1, '2021-02-18 10:37:32', NULL),
(13, '12021/00000013', 0, 'sell', 1, 1, '93245684', NULL, 0, '', 2, 0, 1, 1, 'paid', 1, 1, '2021-02-18 13:22:03', NULL),
(14, '12021/00000014', 0, 'sell', 1, 2, '932145632', NULL, 0, '', 3, 0, 1, 1, 'paid', 1, 1, '2021-02-18 13:22:45', NULL),
(15, '12021/00000015', 0, 'sell', 1, 3, '931240141', NULL, 0, '', 5, 0, 1, 1, 'paid', 1, 1, '2021-02-18 13:23:27', NULL),
(16, '12021/00000016', 0, 'sell', 1, 3, '931240141', NULL, 0, '', 1, 1, 1, 4, 'due', 1, 1, '2021-02-18 13:28:36', NULL),
(17, '12021/00000017', 0, 'sell', 1, 1, '93245684', NULL, 0, '', 1, 1, 1, 4, 'paid', 1, 1, '2021-02-18 13:30:48', NULL),
(18, '12021/00000018', 0, 'sell', 1, 1, '93245684', NULL, 0, '', 1, 0, 1, 4, 'paid', 1, 1, '2021-02-18 13:32:20', NULL),
(19, '12021/00000019', 0, 'sell', 1, 1, '0170000000000', NULL, 0, '', 1, 0, 1, 5, 'paid', 1, 1, '2021-02-18 13:33:44', NULL),
(20, '12021/00000020', 0, 'sell', 1, 5, '93256458', NULL, 0, '', 1, 0, 1, 3, 'paid', 1, 1, '2021-02-18 13:43:05', NULL),
(21, '12021/00000021', 0, 'sell', 1, 1, '93245684', NULL, 0, '', 1, 1, 1, 4, 'paid', 1, 1, '2021-02-18 17:22:59', NULL),
(22, '12021/00000022', 0, 'sell', 1, 5, '93256458', NULL, 0, '', 1, 0, 1, NULL, 'paid', 1, 1, '2021-02-18 17:24:24', NULL),
(23, '12021/00000023', 0, 'sell', 1, 1, '93245684', NULL, 0, '', 1, 0, 1, 5, 'paid', 1, 1, '2021-02-18 17:25:30', NULL),
(24, '12021/00000024', 0, 'sell', 1, 1, '93245684', NULL, 0, '', 1, 0, 1, 5, 'paid', 1, 1, '2021-02-18 17:25:40', NULL),
(25, '12021/00000025', 0, 'sell', 1, 1, '93245684', NULL, 0, '', 1, 0, 1, 5, 'paid', 1, 1, '2021-02-18 17:25:54', NULL),
(26, '12021/00000026', 0, 'sell', 1, 4, '9312548', NULL, 0, '', 2, 0, 1, 4, 'paid', 1, 1, '2021-02-18 19:07:46', NULL),
(27, '12021/00000027', 0, 'sell', 1, 4, '9312548', NULL, 0, '', 2, 0, 1, 4, 'paid', 1, 1, '2021-02-18 19:08:19', NULL),
(28, '12021/00000028', 0, 'sell', 1, 4, '9312548', NULL, 0, '', 1, 0, 1, 3, 'paid', 1, 1, '2021-02-18 19:09:27', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `selling_item`
--

CREATE TABLE `selling_item` (
  `id` int(10) NOT NULL,
  `invoice_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `brand_id` int(10) DEFAULT NULL,
  `sup_id` int(10) UNSIGNED NOT NULL,
  `store_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `item_id` int(10) UNSIGNED NOT NULL,
  `item_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `item_price` decimal(25,4) NOT NULL,
  `item_discount` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `item_tax` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `tax_method` enum('inclusive','exclusive') COLLATE utf8_unicode_ci DEFAULT 'exclusive',
  `taxrate_id` int(10) UNSIGNED DEFAULT NULL,
  `tax` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gst` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cgst` decimal(25,4) DEFAULT NULL,
  `sgst` decimal(25,4) DEFAULT NULL,
  `igst` decimal(25,4) DEFAULT NULL,
  `item_quantity` decimal(25,4) NOT NULL,
  `item_purchase_price` decimal(25,4) DEFAULT NULL,
  `item_total` decimal(25,4) NOT NULL,
  `purchase_invoice_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `print_counter` int(10) UNSIGNED DEFAULT 0,
  `print_counter_time` datetime DEFAULT NULL,
  `printed_by` int(10) DEFAULT NULL,
  `return_quantity` decimal(25,4) DEFAULT 0.0000,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `selling_item`
--

INSERT INTO `selling_item` (`id`, `invoice_id`, `category_id`, `brand_id`, `sup_id`, `store_id`, `item_id`, `item_name`, `item_price`, `item_discount`, `item_tax`, `tax_method`, `taxrate_id`, `tax`, `gst`, `cgst`, `sgst`, `igst`, `item_quantity`, `item_purchase_price`, `item_total`, `purchase_invoice_id`, `print_counter`, `print_counter_time`, `printed_by`, `return_quantity`, `created_at`) VALUES
(1, '100000001', 1, 1, 1, 1, 1, 'hp core i5 ', '3000.0000', '0.0000', '540.0000', 'inclusive', 2, '18.0000', '18.0000', '0.0000', '0.0000', '540.0000', '1.0000', '2500.0000', '3000.0000', 'B2432', 0, NULL, NULL, '0.0000', NULL),
(2, '100000002', 1, 1, 1, 1, 1, 'hp core i5 ', '3000.0000', '0.0000', '1620.0000', 'inclusive', 2, '18.0000', '18.0000', '0.0000', '0.0000', '1620.0000', '3.0000', '7500.0000', '9000.0000', 'B2432', 0, NULL, NULL, '0.0000', NULL),
(3, '100000003', 1, 1, 1, 1, 1, 'hp core i5 ', '3000.0000', '0.0000', '1080.0000', 'inclusive', 2, '18.0000', '18.0000', '0.0000', '0.0000', '1080.0000', '2.0000', '5000.0000', '6000.0000', 'B2432', 0, NULL, NULL, '0.0000', NULL),
(4, '12021/00000004', 1, 1, 1, 1, 1, 'hp core i5 ', '3000.0000', '0.0000', '1080.0000', 'inclusive', 2, '18.0000', '18.0000', '540.0000', '540.0000', '0.0000', '2.0000', '5000.0000', '6000.0000', 'B2432', 0, NULL, NULL, '0.0000', NULL),
(5, '12021/00000005', 6, 0, 2, 1, 2, 'INKA KOLA', '80.0000', '0.0000', '14.4000', 'inclusive', 2, '18.0000', '18.0000', '7.2000', '7.2000', '0.0000', '1.0000', '5.0000', '80.0000', 'BINKA KOLA', 0, NULL, NULL, '0.0000', NULL),
(6, '12021/00000006', 6, 0, 2, 1, 2, 'INKA KOLA', '7.5000', '0.0000', '2.7000', 'inclusive', 2, '18.0000', '18.0000', '1.3500', '1.3500', '0.0000', '2.0000', '10.0000', '15.0000', 'BINKA KOLA', 0, NULL, NULL, '0.0000', NULL),
(7, '12021/00000006', 6, 0, 2, 1, 3, 'PALTA', '3.5000', '0.0000', '0.6300', 'inclusive', 2, '18.0000', '18.0000', '0.3150', '0.3150', '0.0000', '1.0000', '3.2000', '3.5000', 'BPALTA', 0, NULL, NULL, '0.0000', NULL),
(8, '12021/00000007', 3, 0, 3, 1, 5, 'CHOCMAN', '1.7000', '0.0000', '0.3060', 'inclusive', 2, '18.0000', '18.0000', '0.1530', '0.1530', '0.0000', '1.0000', '1.5000', '1.7000', 'BCHOCMAN', 0, NULL, NULL, '0.0000', NULL),
(9, '12021/00000007', 2, 0, 5, 1, 6, 'PAVO', '210.0000', '0.0000', '75.6000', 'inclusive', 2, '18.0000', '18.0000', '37.8000', '37.8000', '0.0000', '2.0000', '360.0000', '420.0000', 'BPAVO', 0, NULL, NULL, '0.0000', NULL),
(10, '12021/00000007', 5, 0, 7, 1, 8, 'ZAPATILLA', '350.9900', '0.0000', '63.1782', 'inclusive', 2, '18.0000', '18.0000', '31.5891', '31.5891', '0.0000', '1.0000', '320.0000', '350.9900', 'BCAT AIR', 0, NULL, NULL, '0.0000', NULL),
(11, '12021/00000008', 6, 0, 2, 1, 2, 'INKA KOLA', '7.5000', '0.0000', '2.7000', 'inclusive', 2, '18.0000', '18.0000', '1.3500', '1.3500', '0.0000', '2.0000', '10.0000', '15.0000', 'BINKA KOLA', 0, NULL, NULL, '0.0000', NULL),
(12, '12021/00000008', 6, 0, 2, 1, 3, 'PALTA', '3.5000', '0.0000', '0.6300', 'inclusive', 2, '18.0000', '18.0000', '0.3150', '0.3150', '0.0000', '1.0000', '3.2000', '3.5000', 'BPALTA', 0, NULL, NULL, '0.0000', NULL),
(13, '12021/00000008', 3, 0, 4, 1, 4, 'SODA FIELD', '2.0000', '0.0000', '0.3600', 'inclusive', 2, '18.0000', '18.0000', '0.1800', '0.1800', '0.0000', '1.0000', '1.8000', '2.0000', 'BSODA', 0, NULL, NULL, '0.0000', NULL),
(14, '12021/00000009', 2, 0, 5, 1, 6, 'PAVO', '210.0000', '0.0000', '75.6000', 'inclusive', 2, '18.0000', '18.0000', '37.8000', '37.8000', '0.0000', '2.0000', '360.0000', '420.0000', 'BPAVO', 0, NULL, NULL, '0.0000', NULL),
(15, '12021/00000009', 3, 0, 4, 1, 4, 'SODA FIELD', '2.0000', '0.0000', '0.3600', 'inclusive', 2, '18.0000', '18.0000', '0.1800', '0.1800', '0.0000', '1.0000', '1.8000', '2.0000', 'BSODA', 0, NULL, NULL, '0.0000', NULL),
(16, '12021/00000010', 1, 1, 1, 1, 1, 'hp core i5 ', '3000.0000', '0.0000', '540.0000', 'inclusive', 2, '18.0000', '18.0000', '270.0000', '270.0000', '0.0000', '1.0000', '2500.0000', '3000.0000', 'B2432', 0, NULL, NULL, '0.0000', NULL),
(17, '12021/00000010', 3, 0, 3, 1, 5, 'CHOCMAN', '1.7000', '0.0000', '0.3060', 'inclusive', 2, '18.0000', '18.0000', '0.1530', '0.1530', '0.0000', '1.0000', '1.5000', '1.7000', 'BCHOCMAN', 0, NULL, NULL, '0.0000', NULL),
(18, '12021/00000011', 6, 0, 2, 1, 2, 'INKA KOLA', '7.5000', '0.0000', '2.7000', 'inclusive', 2, '18.0000', '18.0000', '1.3500', '1.3500', '0.0000', '2.0000', '10.0000', '15.0000', 'BINKA KOLA', 0, NULL, NULL, '0.0000', NULL),
(19, '12021/00000012', 3, 0, 4, 1, 4, 'SODA FIELD', '2.0000', '0.0000', '0.3600', 'inclusive', 2, '18.0000', '18.0000', '0.1800', '0.1800', '0.0000', '1.0000', '1.8000', '2.0000', 'BSODA', 0, NULL, NULL, '0.0000', NULL),
(20, '12021/00000012', 6, 0, 2, 1, 3, 'PALTA', '3.5000', '0.0000', '0.6300', 'inclusive', 2, '18.0000', '18.0000', '0.3150', '0.3150', '0.0000', '1.0000', '3.2000', '3.5000', 'BPALTA', 0, NULL, NULL, '0.0000', NULL),
(21, '12021/00000013', 5, 0, 7, 1, 8, 'ZAPATILLA', '350.9900', '0.0000', '63.1782', 'inclusive', 2, '18.0000', '18.0000', '31.5891', '31.5891', '0.0000', '1.0000', '320.0000', '350.9900', 'BCAT AIR', 0, NULL, NULL, '0.0000', NULL),
(22, '12021/00000013', 4, 1, 7, 1, 9, 'CASACA', '100.0000', '0.0000', '18.0000', 'inclusive', 2, '18.0000', '18.0000', '9.0000', '9.0000', '0.0000', '1.0000', '80.0000', '100.0000', 'B120', 0, NULL, NULL, '0.0000', NULL),
(23, '12021/00000014', 6, 0, 2, 1, 2, 'INKA KOLA', '7.5000', '0.0000', '1.3500', 'inclusive', 2, '18.0000', '18.0000', '0.0000', '0.0000', '1.3500', '1.0000', '5.0000', '7.5000', 'BINKA KOLA', 0, NULL, NULL, '0.0000', NULL),
(24, '12021/00000014', 2, 0, 5, 1, 6, 'PAVO', '210.0000', '0.0000', '37.8000', 'inclusive', 2, '18.0000', '18.0000', '0.0000', '0.0000', '37.8000', '1.0000', '180.0000', '210.0000', 'BPAVO', 0, NULL, NULL, '0.0000', NULL),
(25, '12021/00000014', 3, 0, 4, 1, 4, 'SODA FIELD', '2.0000', '0.0000', '0.3600', 'inclusive', 2, '18.0000', '18.0000', '0.0000', '0.0000', '0.3600', '1.0000', '1.8000', '2.0000', 'BSODA', 0, NULL, NULL, '0.0000', NULL),
(26, '12021/00000015', 6, 0, 2, 1, 3, 'PALTA', '3.5000', '0.0000', '0.6300', 'inclusive', 2, '18.0000', '18.0000', '0.0000', '0.0000', '0.6300', '1.0000', '3.2000', '3.5000', 'BPALTA', 0, NULL, NULL, '0.0000', NULL),
(27, '12021/00000015', 2, 0, 5, 1, 6, 'PAVO', '210.0000', '0.0000', '37.8000', 'inclusive', 2, '18.0000', '18.0000', '0.0000', '0.0000', '37.8000', '1.0000', '180.0000', '210.0000', 'BPAVO1', 0, NULL, NULL, '0.0000', NULL),
(28, '12021/00000015', 5, 0, 7, 1, 8, 'ZAPATILLA', '350.9900', '0.0000', '63.1782', 'inclusive', 2, '18.0000', '18.0000', '0.0000', '0.0000', '63.1782', '1.0000', '320.0000', '350.9900', 'BCAT AIR', 0, NULL, NULL, '0.0000', NULL),
(29, '12021/00000015', 6, 0, 2, 1, 2, 'INKA KOLA', '7.5000', '0.0000', '2.7000', 'inclusive', 2, '18.0000', '18.0000', '0.0000', '0.0000', '2.7000', '2.0000', '10.0000', '15.0000', 'BINKA KOLA', 0, NULL, NULL, '0.0000', NULL),
(30, '12021/00000015', 4, 1, 7, 1, 9, 'CASACA', '100.0000', '0.0000', '54.0000', 'inclusive', 2, '18.0000', '18.0000', '0.0000', '0.0000', '54.0000', '3.0000', '240.0000', '300.0000', 'B120', 0, NULL, NULL, '0.0000', NULL),
(31, '12021/00000016', 6, 0, 2, 1, 2, 'INKA KOLA', '7.5000', '0.0000', '1.3500', 'inclusive', 2, '18.0000', '18.0000', '0.0000', '0.0000', '1.3500', '1.0000', '5.0000', '7.5000', 'BINKA KOLA', 0, NULL, NULL, '0.0000', NULL),
(32, '12021/00000017', 4, 1, 7, 1, 9, 'CASACA', '100.0000', '0.0000', '18.0000', 'inclusive', 2, '18.0000', '18.0000', '9.0000', '9.0000', '0.0000', '1.0000', '80.0000', '100.0000', 'B120', 0, NULL, NULL, '0.0000', NULL),
(33, '12021/00000018', 4, 1, 7, 1, 9, 'CASACA', '100.0000', '0.0000', '18.0000', 'inclusive', 2, '18.0000', '18.0000', '9.0000', '9.0000', '0.0000', '1.0000', '80.0000', '100.0000', 'B120', 0, NULL, NULL, '0.0000', NULL),
(34, '12021/00000019', 1, 1, 1, 1, 1, 'hp core i5 ', '3000.0000', '0.0000', '540.0000', 'inclusive', 2, '18.0000', '18.0000', '270.0000', '270.0000', '0.0000', '1.0000', '2500.0000', '3000.0000', 'B2432', 0, NULL, NULL, '0.0000', NULL),
(35, '12021/00000020', 4, 1, 7, 1, 9, 'CASACA', '100.0000', '0.0000', '18.0000', 'inclusive', 2, '18.0000', '18.0000', '0.0000', '0.0000', '18.0000', '1.0000', '80.0000', '100.0000', 'B120', 0, NULL, NULL, '0.0000', NULL),
(36, '12021/00000021', 4, 1, 7, 1, 9, 'CASACA', '100.0000', '0.0000', '18.0000', 'inclusive', 2, '18.0000', '18.0000', '9.0000', '9.0000', '0.0000', '1.0000', '80.0000', '100.0000', 'B120', 0, NULL, NULL, '0.0000', NULL),
(37, '12021/00000022', 2, 0, 5, 1, 6, 'PAVO', '210.0000', '0.0000', '37.8000', 'inclusive', 2, '18.0000', '18.0000', '0.0000', '0.0000', '37.8000', '1.0000', '180.0000', '210.0000', 'BPAVO1', 0, NULL, NULL, '0.0000', NULL),
(38, '12021/00000023', 3, 0, 3, 1, 5, 'CHOCMAN', '1.7000', '0.0000', '0.3060', 'inclusive', 2, '18.0000', '18.0000', '0.1530', '0.1530', '0.0000', '1.0000', '1.5000', '1.7000', 'BCHOCMAN', 0, NULL, NULL, '0.0000', NULL),
(39, '12021/00000024', 2, 0, 5, 1, 6, 'PAVO', '210.0000', '0.0000', '37.8000', 'inclusive', 2, '18.0000', '18.0000', '18.9000', '18.9000', '0.0000', '1.0000', '180.0000', '210.0000', 'BPAVO1', 0, NULL, NULL, '0.0000', NULL),
(40, '12021/00000025', 3, 0, 3, 1, 5, 'CHOCMAN', '1.7000', '0.0000', '0.3060', 'inclusive', 2, '18.0000', '18.0000', '0.1530', '0.1530', '0.0000', '1.0000', '1.5000', '1.7000', 'BCHOCMAN', 0, NULL, NULL, '0.0000', NULL),
(41, '12021/00000026', 6, 0, 2, 1, 3, 'PALTA', '3.5000', '0.0000', '0.6300', 'inclusive', 2, '18.0000', '18.0000', '0.0000', '0.0000', '0.6300', '1.0000', '3.2000', '3.5000', 'BPALTA', 0, NULL, NULL, '0.0000', NULL),
(42, '12021/00000026', 5, 0, 7, 1, 8, 'ZAPATILLA', '350.9900', '0.0000', '63.1782', 'inclusive', 2, '18.0000', '18.0000', '0.0000', '0.0000', '63.1782', '1.0000', '320.0000', '350.9900', 'BCAT AIR', 0, NULL, NULL, '0.0000', NULL),
(43, '12021/00000027', 2, 0, 5, 1, 6, 'PAVO', '210.0000', '0.0000', '37.8000', 'inclusive', 2, '18.0000', '18.0000', '0.0000', '0.0000', '37.8000', '1.0000', '180.0000', '210.0000', 'BPAVO1', 0, NULL, NULL, '0.0000', NULL),
(44, '12021/00000027', 3, 0, 3, 1, 5, 'CHOCMAN', '1.7000', '0.0000', '0.3060', 'inclusive', 2, '18.0000', '18.0000', '0.0000', '0.0000', '0.3060', '1.0000', '1.5000', '1.7000', 'BCHOCMAN', 0, NULL, NULL, '0.0000', NULL),
(45, '12021/00000028', 5, 0, 7, 1, 8, 'ZAPATILLA', '350.9900', '0.0000', '63.1782', 'inclusive', 2, '18.0000', '18.0000', '0.0000', '0.0000', '63.1782', '1.0000', '320.0000', '350.9900', 'BCAT AIR', 0, NULL, NULL, '0.0000', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `selling_price`
--

CREATE TABLE `selling_price` (
  `price_id` int(10) NOT NULL,
  `invoice_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `store_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `subtotal` decimal(25,4) DEFAULT NULL,
  `discount_type` enum('plain','percentage') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'plain',
  `discount_amount` decimal(25,4) DEFAULT 0.0000,
  `interest_amount` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `interest_percentage` int(10) NOT NULL DEFAULT 0,
  `item_tax` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `order_tax` decimal(25,4) DEFAULT 0.0000,
  `cgst` decimal(25,4) DEFAULT NULL,
  `sgst` decimal(25,4) DEFAULT NULL,
  `igst` decimal(25,4) DEFAULT NULL,
  `total_purchase_price` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `shipping_type` enum('plain','percentage') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'plain',
  `shipping_amount` decimal(25,4) DEFAULT 0.0000,
  `others_charge` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `payable_amount` decimal(25,4) DEFAULT NULL,
  `paid_amount` decimal(25,4) NOT NULL,
  `due` decimal(25,4) UNSIGNED NOT NULL DEFAULT 0.0000,
  `due_paid` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `return_amount` decimal(25,4) UNSIGNED NOT NULL DEFAULT 0.0000,
  `balance` decimal(25,4) DEFAULT 0.0000,
  `profit` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `previous_due` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `prev_due_paid` decimal(25,4) NOT NULL DEFAULT 0.0000
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `selling_price`
--

INSERT INTO `selling_price` (`price_id`, `invoice_id`, `store_id`, `subtotal`, `discount_type`, `discount_amount`, `interest_amount`, `interest_percentage`, `item_tax`, `order_tax`, `cgst`, `sgst`, `igst`, `total_purchase_price`, `shipping_type`, `shipping_amount`, `others_charge`, `payable_amount`, `paid_amount`, `due`, `due_paid`, `return_amount`, `balance`, `profit`, `previous_due`, `prev_due_paid`) VALUES
(1, '100000001', 1, '3000.0000', 'plain', '0.0000', '0.0000', 0, '540.0000', '0.0000', '0.0000', '0.0000', '540.0000', '2500.0000', 'plain', '0.0000', '0.0000', '3000.0000', '3000.0000', '0.0000', '0.0000', '0.0000', '1000.0000', '500.0000', '0.0000', '0.0000'),
(2, '100000002', 1, '9000.0000', 'plain', '0.0000', '0.0000', 0, '1620.0000', '0.0000', '0.0000', '0.0000', '1620.0000', '7500.0000', 'plain', '0.0000', '0.0000', '9000.0000', '9000.0000', '0.0000', '0.0000', '0.0000', '1000.0000', '1500.0000', '0.0000', '0.0000'),
(3, '100000003', 1, '6000.0000', 'plain', '0.0000', '0.0000', 0, '1080.0000', '0.0000', '0.0000', '0.0000', '1080.0000', '5000.0000', 'plain', '0.0000', '0.0000', '6000.0000', '6000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '1000.0000', '0.0000', '0.0000'),
(4, '12021/00000004', 1, '6000.0000', 'plain', '0.0000', '0.0000', 0, '1080.0000', '0.0000', '540.0000', '540.0000', '0.0000', '5000.0000', 'plain', '0.0000', '0.0000', '6000.0000', '6000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '1000.0000', '0.0000', '0.0000'),
(5, '12021/00000005', 1, '80.0000', 'plain', '0.0000', '0.0000', 0, '14.4000', '0.0000', '7.2000', '7.2000', '0.0000', '5.0000', 'plain', '0.0000', '0.0000', '80.0000', '80.0000', '0.0000', '0.0000', '0.0000', '0.0000', '75.0000', '0.0000', '0.0000'),
(6, '12021/00000006', 1, '18.5000', 'plain', '0.0000', '0.0000', 0, '3.3300', '0.0000', '1.6650', '1.6650', '0.0000', '13.2000', 'plain', '0.0000', '0.0000', '18.5000', '18.5000', '0.0000', '0.0000', '0.0000', '0.0000', '5.3000', '0.0000', '0.0000'),
(7, '12021/00000007', 1, '772.6900', 'plain', '0.0000', '0.0000', 0, '139.0842', '0.0000', '69.5421', '69.5421', '0.0000', '681.5000', 'plain', '0.0000', '0.0000', '772.6900', '772.6900', '0.0000', '0.0000', '0.0000', '0.0000', '91.1900', '0.0000', '0.0000'),
(8, '12021/00000008', 1, '20.5000', 'plain', '0.0000', '0.0000', 0, '3.6900', '0.0000', '1.8450', '1.8450', '0.0000', '15.0000', 'plain', '0.0000', '0.0000', '20.5000', '20.5000', '0.0000', '0.0000', '0.0000', '0.0000', '5.5000', '0.0000', '0.0000'),
(9, '12021/00000009', 1, '422.0000', 'plain', '0.0000', '0.0000', 0, '75.9600', '0.0000', '37.9800', '37.9800', '0.0000', '361.8000', 'plain', '0.0000', '0.0000', '422.0000', '422.0000', '0.0000', '0.0000', '0.0000', '0.0000', '60.2000', '0.0000', '0.0000'),
(10, '12021/00000010', 1, '3001.7000', 'plain', '0.0000', '0.0000', 0, '540.3060', '0.0000', '270.1530', '270.1530', '0.0000', '2501.5000', 'plain', '0.0000', '0.0000', '3001.7000', '3001.7000', '0.0000', '0.0000', '0.0000', '0.0000', '500.2000', '0.0000', '0.0000'),
(11, '12021/00000011', 1, '15.0000', 'plain', '0.0000', '0.0000', 0, '2.7000', '0.0000', '1.3500', '1.3500', '0.0000', '10.0000', 'plain', '0.0000', '0.0000', '15.0000', '15.0000', '0.0000', '0.0000', '0.0000', '0.0000', '5.0000', '0.0000', '0.0000'),
(12, '12021/00000012', 1, '5.5000', 'plain', '0.0000', '0.0000', 0, '0.9900', '0.0000', '0.4950', '0.4950', '0.0000', '5.0000', 'plain', '0.0000', '0.0000', '5.5000', '5.5000', '0.0000', '0.0000', '0.0000', '14.5000', '0.5000', '0.0000', '0.0000'),
(13, '12021/00000013', 1, '450.9900', 'plain', '0.0000', '0.0000', 0, '81.1782', '0.0000', '40.5891', '40.5891', '0.0000', '400.0000', 'plain', '0.0000', '0.0000', '450.9900', '450.9900', '0.0000', '0.0000', '0.0000', '0.0000', '50.9900', '0.0000', '0.0000'),
(14, '12021/00000014', 1, '219.5000', 'plain', '0.0000', '0.0000', 0, '39.5100', '0.0000', '0.0000', '0.0000', '39.5100', '186.8000', 'plain', '0.0000', '0.0000', '219.5000', '219.5000', '0.0000', '0.0000', '0.0000', '0.0000', '32.7000', '0.0000', '0.0000'),
(15, '12021/00000015', 1, '879.4900', 'plain', '0.0000', '0.0000', 0, '158.3082', '0.0000', '0.0000', '0.0000', '158.3082', '753.2000', 'plain', '0.0000', '0.0000', '879.4900', '879.4900', '0.0000', '0.0000', '0.0000', '0.0000', '126.2900', '0.0000', '0.0000'),
(16, '12021/00000016', 1, '7.5000', 'plain', '0.0000', '0.2200', 3, '1.3500', '0.0000', '0.0000', '0.0000', '1.3500', '5.0000', 'plain', '0.0000', '0.0000', '7.7250', '7.5000', '0.2250', '0.0000', '0.0000', '0.0000', '2.5000', '0.0000', '0.0000'),
(17, '12021/00000017', 1, '100.0000', 'plain', '0.0000', '0.0000', 0, '18.0000', '0.0000', '9.0000', '9.0000', '0.0000', '80.0000', 'plain', '0.0000', '0.0000', '100.0000', '100.0000', '0.0000', '0.0000', '0.0000', '0.0000', '20.0000', '0.0000', '0.0000'),
(18, '12021/00000018', 1, '100.0000', 'plain', '0.0000', '0.0000', 0, '18.0000', '0.0000', '9.0000', '9.0000', '0.0000', '80.0000', 'plain', '0.0000', '0.0000', '100.0000', '100.0000', '0.0000', '0.0000', '0.0000', '0.0000', '20.0000', '0.0000', '0.0000'),
(19, '12021/00000019', 1, '3000.0000', 'plain', '0.0000', '0.0000', 0, '540.0000', '0.0000', '270.0000', '270.0000', '0.0000', '2500.0000', 'plain', '0.0000', '0.0000', '3000.0000', '3000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '500.0000', '0.0000', '0.0000'),
(20, '12021/00000020', 1, '100.0000', 'plain', '0.0000', '0.0000', 0, '18.0000', '0.0000', '0.0000', '0.0000', '18.0000', '80.0000', 'plain', '0.0000', '0.0000', '100.0000', '100.0000', '0.0000', '0.0000', '0.0000', '4900.0000', '20.0000', '0.0000', '0.0000'),
(21, '12021/00000021', 1, '100.0000', 'plain', '0.0000', '3.0000', 3, '18.0000', '0.0000', '9.0000', '9.0000', '0.0000', '80.0000', 'plain', '0.0000', '0.0000', '103.0000', '103.0000', '0.0000', '0.0000', '0.0000', '0.0000', '20.0000', '0.0000', '0.0000'),
(22, '12021/00000022', 1, '210.0000', 'plain', '0.0000', '0.0000', 0, '37.8000', '0.0000', '0.0000', '0.0000', '37.8000', '180.0000', 'plain', '0.0000', '0.0000', '210.0000', '210.0000', '0.0000', '210.0000', '0.0000', '40.0000', '30.0000', '0.0000', '0.0000'),
(23, '12021/00000023', 1, '1.7000', 'plain', '0.0000', '0.0000', 0, '0.3060', '0.0000', '0.1530', '0.1530', '0.0000', '1.5000', 'plain', '0.0000', '0.0000', '1.7000', '1.7000', '0.0000', '0.0000', '0.0000', '0.0000', '0.2000', '0.0000', '0.0000'),
(24, '12021/00000024', 1, '210.0000', 'plain', '0.0000', '0.0000', 0, '37.8000', '0.0000', '18.9000', '18.9000', '0.0000', '180.0000', 'plain', '0.0000', '0.0000', '210.0000', '210.0000', '0.0000', '0.0000', '0.0000', '0.0000', '30.0000', '0.0000', '0.0000'),
(25, '12021/00000025', 1, '1.7000', 'plain', '0.0000', '0.0000', 0, '0.3060', '0.0000', '0.1530', '0.1530', '0.0000', '1.5000', 'plain', '0.0000', '0.0000', '1.7000', '1.7000', '0.0000', '0.0000', '0.0000', '34.3000', '0.2000', '0.0000', '0.0000'),
(26, '12021/00000026', 1, '354.4900', 'plain', '0.0000', '0.0000', 0, '63.8082', '0.0000', '0.0000', '0.0000', '63.8082', '323.2000', 'plain', '0.0000', '0.0000', '354.4900', '354.4900', '0.0000', '0.0000', '0.0000', '0.0000', '31.2900', '0.0000', '0.0000'),
(27, '12021/00000027', 1, '211.7000', 'plain', '0.0000', '0.0000', 0, '38.1060', '0.0000', '0.0000', '0.0000', '38.1060', '181.5000', 'plain', '0.0000', '0.0000', '211.7000', '211.7000', '0.0000', '0.0000', '0.0000', '0.0000', '30.2000', '0.0000', '0.0000'),
(28, '12021/00000028', 1, '350.9900', 'plain', '0.0000', '0.0000', 0, '63.1782', '0.0000', '0.0000', '0.0000', '63.1782', '320.0000', 'plain', '0.0000', '0.0000', '350.9900', '350.9900', '0.0000', '0.0000', '0.0000', '4649.0100', '30.9900', '0.0000', '0.0000');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sell_logs`
--

CREATE TABLE `sell_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `reference_no` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ref_invoice_id` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `pmethod_id` int(11) DEFAULT NULL,
  `description` text CHARACTER SET utf8 DEFAULT NULL,
  `amount` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `store_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `sell_logs`
--

INSERT INTO `sell_logs` (`id`, `customer_id`, `reference_no`, `ref_invoice_id`, `type`, `pmethod_id`, `description`, `amount`, `store_id`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'CT191215100001', '100000001', 'sell', 1, 'Paid while selling', '3000.0000', 1, 1, '2019-12-15 12:19:46', NULL),
(2, 1, 'CT2101201002', '100000002', 'sell', 2, 'Paid while selling', '9000.0000', 1, 1, '2021-01-20 19:49:33', NULL),
(3, 1, 'CT21012011003', '100000003', 'sell', 1, 'Paid while selling', '6000.0000', 1, 1, '2021-01-20 19:50:10', NULL),
(4, 1, 'CT21012011004', '12021/00000004', 'sell', 1, 'Paid while selling', '6000.0000', 1, 1, '2021-01-20 21:17:58', NULL),
(5, 1, 'CT21012411005', '12021/00000005', 'sell', 1, 'Paid while selling', '80.0000', 1, 1, '2021-01-24 22:15:26', NULL),
(6, 1, 'CT21012411006', '12021/00000006', 'sell', 1, 'Paid while selling', '18.5000', 1, 1, '2021-01-24 22:27:04', NULL),
(7, 1, 'CT21012411007', '12021/00000007', 'sell', 5, 'Paid while selling', '772.6900', 1, 1, '2021-01-24 22:27:30', NULL),
(8, 1, 'CT21012411008', '12021/00000008', 'sell', 1, 'Paid while selling', '20.5000', 1, 1, '2021-01-24 22:44:46', NULL),
(9, 1, 'CT21012411009', '12021/00000009', 'sell', 1, 'Paid while selling', '422.0000', 1, 1, '2021-01-24 22:51:17', NULL),
(10, 1, 'CT21012411010', '12021/00000010', 'sell', 1, 'Paid while selling', '3001.7000', 1, 1, '2021-01-24 22:51:57', NULL),
(11, 1, 'CT21012411011', '12021/00000011', 'sell', 1, 'Paid while selling', '15.0000', 1, 1, '2021-01-24 22:54:35', NULL),
(12, 1, 'CT21021811012', '12021/00000012', 'sell', 5, 'Paid while selling', '5.5000', 1, 1, '2021-02-18 10:37:32', NULL),
(13, 1, 'CT21021811013', '12021/00000013', 'sell', 1, 'Paid while selling', '450.9900', 1, 1, '2021-02-18 13:22:03', NULL),
(14, 2, 'CT21021811014', '12021/00000014', 'sell', 1, 'Paid while selling', '219.5000', 1, 1, '2021-02-18 13:22:45', NULL),
(15, 3, 'CT21021811015', '12021/00000015', 'sell', 1, 'Paid while selling', '879.4900', 1, 1, '2021-02-18 13:23:27', NULL),
(16, 3, 'CT21021811016', '12021/00000016', 'sell', 4, 'Paid while selling', '7.5000', 1, 1, '2021-02-18 13:28:36', NULL),
(17, 1, 'CT21021811017', '12021/00000017', 'sell', 4, 'Paid while selling', '100.0000', 1, 1, '2021-02-18 13:30:48', NULL),
(18, 1, 'CT21021811018', '12021/00000018', 'sell', 4, 'Paid while selling', '100.0000', 1, 1, '2021-02-18 13:32:20', NULL),
(19, 1, 'CT21021811019', '12021/00000019', 'sell', 5, 'Paid while selling', '3000.0000', 1, 1, '2021-02-18 13:33:44', NULL),
(20, 5, 'CT21021811020', '12021/00000020', 'sell', 3, 'Paid while selling', '100.0000', 1, 1, '2021-02-18 13:43:05', NULL),
(21, 1, 'CT21021811021', '12021/00000021', 'sell', 4, 'Paid while selling', '103.0000', 1, 1, '2021-02-18 17:22:59', NULL),
(22, 5, 'CT21021811022', '12021/00000022', 'sell', 5, 'Paid while selling', '0.0000', 1, 1, '2021-02-18 17:24:24', NULL),
(23, 1, 'CT21021811023', '12021/00000023', 'sell', 5, 'Paid while selling', '1.7000', 1, 1, '2021-02-18 17:25:30', NULL),
(24, 1, 'CT21021811024', '12021/00000024', 'sell', 5, 'Paid while selling', '210.0000', 1, 1, '2021-02-18 17:25:40', NULL),
(25, 1, 'CT21021811025', '12021/00000025', 'sell', 5, 'Paid while selling', '1.7000', 1, 1, '2021-02-18 17:25:54', NULL),
(26, 4, 'CT21021811026', '12021/00000026', 'sell', 4, 'Paid while selling', '354.4900', 1, 1, '2021-02-18 19:07:46', NULL),
(27, 4, 'CT21021811027', '12021/00000027', 'sell', 4, 'Paid while selling', '211.7000', 1, 1, '2021-02-18 19:08:19', NULL),
(28, 4, 'CT21021811028', '12021/00000028', 'sell', 3, 'Paid while selling', '350.9900', 1, 1, '2021-02-18 19:09:27', NULL),
(29, 5, 'CT210218100001', '12021/00000022', 'due_paid', 5, 'Due paid', '210.0000', 1, 1, '2021-02-18 19:22:34', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `settings`
--

CREATE TABLE `settings` (
  `id` int(10) NOT NULL,
  `version` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `is_update_available` tinyint(1) NOT NULL DEFAULT 0,
  `update_version` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_link` text CHARACTER SET utf8 DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `settings`
--

INSERT INTO `settings` (`id`, `version`, `is_update_available`, `update_version`, `update_link`, `created_at`, `updated_at`) VALUES
(1, '3.0', 0, '', '', '2018-09-14 18:00:00', '2019-06-12 15:34:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shortcut_links`
--

CREATE TABLE `shortcut_links` (
  `id` int(11) NOT NULL,
  `type` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `href` text CHARACTER SET utf8 DEFAULT NULL,
  `target` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '_self',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `thumbnail` text CHARACTER SET utf8 DEFAULT NULL,
  `permission_slug` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `shortcut_links`
--

INSERT INTO `shortcut_links` (`id`, `type`, `href`, `target`, `title`, `icon`, `thumbnail`, `permission_slug`, `status`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'report', '/admin/report_overview.php', '', 'Overview Report', 'fa-angle-right', '', 'read_overview_report', 1, 1, '2019-02-03 12:00:00', '2019-07-01 03:59:30'),
(2, 'report', '/admin/report_collection.php', '', 'Collection Report', 'fa-angle-right', '', 'read_collection_report', 1, 2, '2019-02-03 12:00:00', '2019-07-01 03:59:30'),
(3, 'report', '/admin/report_customer_due_collection.php', '', 'Due Collection Report', 'fa-angle-right', '', 'read_customer_due_collection_report', 1, 3, '2019-02-03 12:00:00', '2019-07-01 03:59:30'),
(4, 'report', '/admin/report_supplier_due_paid.php', '', 'Supplier Due Paid Report', 'fa-angle-right', '', 'read_supplier_due_paid_report', 1, 4, '2019-02-03 12:00:00', '2019-07-01 03:59:30'),
(5, 'report', '/admin/report_sell_itemwise.php', '', 'Sell Report', 'fa-angle-right', '', 'read_sell_report', 1, 5, '2019-02-03 12:00:00', '2019-07-01 03:59:30'),
(6, 'report', '/admin/report_purchase_supplierwise.php', '', 'Purchase Report', 'fa-angle-right', '', 'read_purchase_report', 1, 6, '2019-02-03 12:00:00', '2019-07-01 03:59:30'),
(7, 'report', '/admin/report_sell_payment.php', '', 'Sell Payment Report', 'fa-angle-right', '', 'read_sell_payment_report', 1, 7, '2019-02-03 12:00:00', '2019-07-01 03:59:30'),
(8, 'report', '/admin/report_purchase_payment.php', '', 'Purchase Payment Report', 'fa-angle-right', '', 'read_purchase_payment_report', 1, 8, '2019-02-03 12:00:00', '2019-07-01 03:59:30'),
(9, 'report', '/admin/report_sell_tax.php', '', 'Sell Tax Report', 'fa-angle-right', '', 'read_sell_tax_report', 1, 9, '2019-02-03 12:00:00', '2019-07-01 03:59:30'),
(10, 'report', '/admin/report_purchase_tax.php', '', 'Purchase Tax Report', 'fa-angle-right', '', 'read_purchase_tax_report', 1, 10, '2019-02-03 12:00:00', '2019-07-01 03:59:30'),
(11, 'report', '/admin/report_tax_overview.php', '', 'Tax Overview Report', 'fa-angle-right', '', 'read_tax_overview_report', 1, 11, '2019-02-03 12:00:00', '2019-07-01 03:59:30'),
(12, 'report', '/admin/report_stock.php', '', 'Stock Report', 'fa-angle-right', '', 'read_stock_report', 1, 12, '2019-02-03 12:00:00', '2019-07-01 03:59:30'),
(13, 'report', '/admin/bank_transactions.php', '', 'Bank Transaction', 'fa-angle-right', '', 'read_bank_transactions', 1, 13, '2019-02-03 12:00:00', '2019-07-01 03:59:30'),
(14, 'report', '/admin/bank_account_sheet.php', '', 'Balance Sheet', 'fa-angle-right', '', 'read_bank_account_sheet', 1, 14, '2019-02-03 12:00:00', '2019-07-01 03:59:30'),
(15, 'report', '/admin/income_monthwise.php', '', 'Income Monthwise Report', 'fa-angle-right', '', 'read_income_monthwise', 1, 15, '2019-02-03 12:00:00', '2019-07-01 03:59:30'),
(16, 'report', '/admin/report_income_and_expense.php', '', 'Income vs Expense Report', 'fa-angle-right', '', 'read_income_and_expense_report', 1, 16, '2019-02-03 12:00:00', '2019-07-01 03:59:30'),
(17, 'report', '/admin/report_profit_and_loss.php', '', 'Profit vs Loss Report', 'fa-angle-right', '', 'read_profit_and_loss_report', 1, 17, '2019-02-03 12:00:00', '2019-07-01 03:59:30'),
(18, 'report', '/admin/report_cashbook.php', '', 'Cashbook', 'fa-angle-right', '', 'read_cashbook_report', 1, 18, '2019-02-03 12:00:00', '2019-07-01 03:59:30'),
(19, 'report', '/admin/expense_monthwise.php', '', 'Expense Monthwise Report', 'fa-angle-right', '', 'read_income_monthwise', 1, 15, '2019-02-03 12:00:00', '2019-07-01 03:59:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sms_schedule`
--

CREATE TABLE `sms_schedule` (
  `id` int(11) UNSIGNED NOT NULL,
  `schedule_datetime` datetime DEFAULT NULL,
  `store_id` int(10) UNSIGNED NOT NULL,
  `people_type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `people_sms_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile_number` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `people_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `sms_text` text CHARACTER SET utf8 DEFAULT NULL,
  `sms_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'TEXT',
  `campaign_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `process_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `total_try` int(11) NOT NULL DEFAULT 0,
  `response_text` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `delivery_status` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sms_setting`
--

CREATE TABLE `sms_setting` (
  `id` int(11) NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `api_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `auth_key` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sender_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `contact` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `maskname` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `campaignname` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unicode` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_code` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `sort_order` int(10) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `sms_setting`
--

INSERT INTO `sms_setting` (`id`, `type`, `api_id`, `auth_key`, `sender_id`, `contact`, `username`, `password`, `maskname`, `campaignname`, `unicode`, `country_code`, `url`, `status`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Clickatell', NULL, '', '', '', NULL, NULL, '', '', '', '', '', 1, 0, '0000-00-00 00:00:00', '2019-07-02 16:23:32'),
(2, 'Twilio', '', '', '', '', '', '', '', '', '', '', '', 1, 0, '0000-00-00 00:00:00', '2019-07-02 16:23:16'),
(3, 'Msg91', '', '', '', '', '', '', '', '', '', '', 'http://api.msg91.com/api/v2/', 1, 0, '0000-00-00 00:00:00', '2019-07-02 16:23:17'),
(4, 'Onnorokomsms', '', '', '', '', '', '', '', '', '', '', 'https://api2.onnorokomsms.com/HttpSendSms.ashx?', 1, 0, '0000-00-00 00:00:00', '2019-07-02 16:23:19'),
(5, 'Mimsms', '', '', '', '', '', '', '', '', '', '', 'https://www.mimsms.com.bd/smsAPI?', 1, 0, '0000-00-00 00:00:00', '2019-07-02 16:23:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stores`
--

CREATE TABLE `stores` (
  `store_id` int(10) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `code_name` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `zip_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `currency` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'USD',
  `vat_reg_no` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cashier_id` int(10) UNSIGNED NOT NULL,
  `address` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `receipt_printer` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cash_drawer_codes` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `char_per_line` tinyint(4) NOT NULL DEFAULT 42,
  `remote_printing` tinyint(1) NOT NULL DEFAULT 1,
  `printer` int(11) DEFAULT NULL,
  `order_printers` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `auto_print` tinyint(1) NOT NULL DEFAULT 0,
  `local_printers` tinyint(1) DEFAULT NULL,
  `logo` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `favicon` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `preference` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `sound_effect` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `feedback_status` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ready',
  `feedback_at` datetime DEFAULT NULL,
  `deposit_account_id` int(11) DEFAULT NULL,
  `thumbnail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `stores`
--

INSERT INTO `stores` (`store_id`, `name`, `code_name`, `mobile`, `email`, `country`, `zip_code`, `currency`, `vat_reg_no`, `cashier_id`, `address`, `receipt_printer`, `cash_drawer_codes`, `char_per_line`, `remote_printing`, `printer`, `order_printers`, `auto_print`, `local_printers`, `logo`, `favicon`, `preference`, `sound_effect`, `sort_order`, `feedback_status`, `feedback_at`, `deposit_account_id`, `thumbnail`, `status`, `created_at`) VALUES
(1, 'MI TIENDAWEB', 'GrupoAlegre', '231321', 'yheder.12@gmail.com', 'PE', '051', 'PEN', '', 4, 'Av. Micaela cuadra 1084- 1088', '1', NULL, 42, 0, 1, '[\"1\"]', 0, 1, '1_logo.png', '1_favicon.png', 'a:32:{s:10:\"gst_reg_no\";s:0:\"\";s:8:\"timezone\";s:12:\"America/Lima\";s:21:\"invoice_edit_lifespan\";s:4:\"1440\";s:26:\"invoice_edit_lifespan_unit\";s:6:\"minute\";s:23:\"invoice_delete_lifespan\";s:4:\"1440\";s:28:\"invoice_delete_lifespan_unit\";s:6:\"minute\";s:3:\"tax\";s:1:\"0\";s:11:\"sms_gateway\";s:10:\"Clickatell\";s:9:\"sms_alert\";s:1:\"0\";s:24:\"expiring_soon_alert_days\";s:0:\"\";s:20:\"datatable_item_limit\";s:2:\"25\";s:16:\"reference_format\";s:13:\"year_sequence\";s:22:\"sales_reference_prefix\";s:0:\"\";s:16:\"receipt_template\";s:1:\"1\";s:12:\"invoice_view\";s:8:\"standard\";s:14:\"business_state\";s:2:\"AN\";s:31:\"change_item_price_while_billing\";s:1:\"0\";s:25:\"pos_product_display_limit\";s:3:\"100\";s:15:\"after_sell_page\";s:3:\"pos\";s:19:\"invoice_footer_text\";s:26:\"Thank you for choosing us!\";s:10:\"email_from\";s:13:\"tusolutionweb\";s:13:\"email_address\";s:2:\"US\";s:12:\"email_driver\";s:11:\"smtp_server\";s:14:\"send_mail_path\";s:18:\"/usr/sbin/sendmail\";s:9:\"smtp_host\";s:15:\"smtp.google.com\";s:13:\"smtp_username\";s:0:\"\";s:13:\"smtp_password\";s:0:\"\";s:9:\"smtp_port\";s:3:\"465\";s:7:\"ssl_tls\";s:3:\"ssl\";s:12:\"ftp_hostname\";s:0:\"\";s:12:\"ftp_username\";s:0:\"\";s:12:\"ftp_password\";s:0:\"\";}', 1, 0, 'ready', '2019-03-01 14:29:18', 1, NULL, 1, '2018-09-24 18:00:00'),
(2, 'Berisso', '51', '931240141', 'yheder.12@gmail.com', 'PE', '051', 'USD', '', 2, 'Av. Arenales, cuadra 10 con Mariátegui Nro 1', '1', NULL, 42, 0, NULL, NULL, 0, NULL, NULL, NULL, 'a:32:{s:10:\"gst_reg_no\";s:0:\"\";s:8:\"timezone\";s:12:\"America/Lima\";s:21:\"invoice_edit_lifespan\";s:4:\"1440\";s:26:\"invoice_edit_lifespan_unit\";s:6:\"minute\";s:23:\"invoice_delete_lifespan\";s:4:\"1440\";s:28:\"invoice_delete_lifespan_unit\";s:6:\"minute\";s:3:\"tax\";s:2:\"18\";s:11:\"sms_gateway\";s:10:\"Clickatell\";s:9:\"sms_alert\";s:1:\"0\";s:24:\"expiring_soon_alert_days\";s:0:\"\";s:20:\"datatable_item_limit\";s:2:\"25\";s:16:\"reference_format\";s:13:\"year_sequence\";s:22:\"sales_reference_prefix\";s:0:\"\";s:16:\"receipt_template\";s:1:\"1\";s:12:\"invoice_view\";s:8:\"standard\";s:14:\"business_state\";s:2:\"AN\";s:31:\"change_item_price_while_billing\";s:1:\"0\";s:25:\"pos_product_display_limit\";s:0:\"\";s:15:\"after_sell_page\";s:3:\"pos\";s:19:\"invoice_footer_text\";s:21:\"La bodega de Lima SAC\";s:10:\"email_from\";s:19:\"yheder.12@gmail.com\";s:13:\"email_address\";s:2:\"US\";s:12:\"email_driver\";s:11:\"smtp_server\";s:14:\"send_mail_path\";s:18:\"/usr/sbin/sendmail\";s:9:\"smtp_host\";s:15:\"smtp.google.com\";s:13:\"smtp_username\";s:0:\"\";s:13:\"smtp_password\";s:0:\"\";s:9:\"smtp_port\";s:3:\"465\";s:7:\"ssl_tls\";s:3:\"ssl\";s:12:\"ftp_hostname\";s:0:\"\";s:12:\"ftp_username\";s:0:\"\";s:12:\"ftp_password\";s:0:\"\";}', 1, 0, 'ready', NULL, 1, NULL, 1, '2021-01-20 20:06:48'),
(3, 'WANKITA', 'wankita', '932568945', 'WANKITA@GMAIL.COM', 'PE', '051', 'USD', '', 2, 'AV. LAS FLORES', '1', NULL, 42, 0, NULL, NULL, 0, NULL, '3_logo.png', '3_favicon.png', 'a:32:{s:10:\"gst_reg_no\";s:0:\"\";s:8:\"timezone\";s:12:\"America/Lima\";s:21:\"invoice_edit_lifespan\";s:4:\"1440\";s:26:\"invoice_edit_lifespan_unit\";s:6:\"minute\";s:23:\"invoice_delete_lifespan\";s:4:\"1440\";s:28:\"invoice_delete_lifespan_unit\";s:6:\"minute\";s:3:\"tax\";s:1:\"0\";s:11:\"sms_gateway\";s:10:\"Clickatell\";s:9:\"sms_alert\";s:1:\"0\";s:24:\"expiring_soon_alert_days\";s:0:\"\";s:20:\"datatable_item_limit\";s:2:\"25\";s:16:\"reference_format\";s:13:\"year_sequence\";s:22:\"sales_reference_prefix\";s:0:\"\";s:16:\"receipt_template\";s:1:\"1\";s:12:\"invoice_view\";s:8:\"standard\";s:14:\"business_state\";s:2:\"AN\";s:31:\"change_item_price_while_billing\";s:1:\"0\";s:25:\"pos_product_display_limit\";s:0:\"\";s:15:\"after_sell_page\";s:3:\"pos\";s:19:\"invoice_footer_text\";s:0:\"\";s:10:\"email_from\";s:13:\"tusolutionweb\";s:13:\"email_address\";s:2:\"US\";s:12:\"email_driver\";s:11:\"smtp_server\";s:14:\"send_mail_path\";s:18:\"/usr/sbin/sendmail\";s:9:\"smtp_host\";s:15:\"smtp.google.com\";s:13:\"smtp_username\";s:0:\"\";s:13:\"smtp_password\";s:0:\"\";s:9:\"smtp_port\";s:3:\"465\";s:7:\"ssl_tls\";s:3:\"ssl\";s:12:\"ftp_hostname\";s:0:\"\";s:12:\"ftp_username\";s:0:\"\";s:12:\"ftp_password\";s:0:\"\";}', 1, 0, 'ready', NULL, 1, NULL, 1, '2021-01-24 22:36:32'),
(4, 'TAMBO', 'tambo', '931256894', 'pruebaa@gmail.com', 'PE', '051', 'USD', '', 2, 'AV. UNIVERSITARIA', '1', NULL, 42, 0, NULL, NULL, 0, NULL, NULL, NULL, 'a:32:{s:10:\"gst_reg_no\";s:0:\"\";s:8:\"timezone\";s:12:\"America/Lima\";s:21:\"invoice_edit_lifespan\";s:4:\"1440\";s:26:\"invoice_edit_lifespan_unit\";s:6:\"minute\";s:23:\"invoice_delete_lifespan\";s:4:\"1440\";s:28:\"invoice_delete_lifespan_unit\";s:6:\"minute\";s:3:\"tax\";s:1:\"0\";s:11:\"sms_gateway\";s:10:\"Clickatell\";s:9:\"sms_alert\";s:1:\"0\";s:24:\"expiring_soon_alert_days\";s:0:\"\";s:20:\"datatable_item_limit\";s:2:\"25\";s:16:\"reference_format\";s:13:\"year_sequence\";s:22:\"sales_reference_prefix\";s:0:\"\";s:16:\"receipt_template\";s:1:\"1\";s:12:\"invoice_view\";s:8:\"standard\";s:14:\"business_state\";s:2:\"AN\";s:31:\"change_item_price_while_billing\";s:1:\"0\";s:25:\"pos_product_display_limit\";s:0:\"\";s:15:\"after_sell_page\";s:3:\"pos\";s:19:\"invoice_footer_text\";s:0:\"\";s:10:\"email_from\";s:13:\"tusolutionweb\";s:13:\"email_address\";s:2:\"US\";s:12:\"email_driver\";s:11:\"smtp_server\";s:14:\"send_mail_path\";s:18:\"/usr/sbin/sendmail\";s:9:\"smtp_host\";s:15:\"smtp.google.com\";s:13:\"smtp_username\";s:0:\"\";s:13:\"smtp_password\";s:0:\"\";s:9:\"smtp_port\";s:3:\"465\";s:7:\"ssl_tls\";s:3:\"ssl\";s:12:\"ftp_hostname\";s:0:\"\";s:12:\"ftp_username\";s:0:\"\";s:12:\"ftp_password\";s:0:\"\";}', 1, 0, 'ready', NULL, 1, NULL, 1, '2021-01-24 22:39:49'),
(5, 'EL VECINOSTORE', 'el_vecinostore', '953684521', 'prueba2@gmail.com', 'PE', '051', 'USD', '', 2, 'AV. JUAN DE LA VEGA', '1', NULL, 42, 0, NULL, NULL, 0, NULL, NULL, NULL, 'a:32:{s:10:\"gst_reg_no\";s:0:\"\";s:8:\"timezone\";s:12:\"America/Lima\";s:21:\"invoice_edit_lifespan\";s:4:\"1440\";s:26:\"invoice_edit_lifespan_unit\";s:6:\"minute\";s:23:\"invoice_delete_lifespan\";s:4:\"1440\";s:28:\"invoice_delete_lifespan_unit\";s:6:\"minute\";s:3:\"tax\";s:1:\"0\";s:11:\"sms_gateway\";s:10:\"Clickatell\";s:9:\"sms_alert\";s:1:\"0\";s:24:\"expiring_soon_alert_days\";s:0:\"\";s:20:\"datatable_item_limit\";s:2:\"25\";s:16:\"reference_format\";s:13:\"year_sequence\";s:22:\"sales_reference_prefix\";s:0:\"\";s:16:\"receipt_template\";s:1:\"1\";s:12:\"invoice_view\";s:8:\"standard\";s:14:\"business_state\";s:2:\"AN\";s:31:\"change_item_price_while_billing\";s:1:\"0\";s:25:\"pos_product_display_limit\";s:0:\"\";s:15:\"after_sell_page\";s:3:\"pos\";s:19:\"invoice_footer_text\";s:0:\"\";s:10:\"email_from\";s:13:\"tusolutionweb\";s:13:\"email_address\";s:2:\"US\";s:12:\"email_driver\";s:11:\"smtp_server\";s:14:\"send_mail_path\";s:18:\"/usr/sbin/sendmail\";s:9:\"smtp_host\";s:15:\"smtp.google.com\";s:13:\"smtp_username\";s:0:\"\";s:13:\"smtp_password\";s:0:\"\";s:9:\"smtp_port\";s:3:\"465\";s:7:\"ssl_tls\";s:3:\"ssl\";s:12:\"ftp_hostname\";s:0:\"\";s:12:\"ftp_username\";s:0:\"\";s:12:\"ftp_password\";s:0:\"\";}', 1, 0, 'ready', NULL, 1, NULL, 1, '2021-01-24 22:58:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `suppliers`
--

CREATE TABLE `suppliers` (
  `sup_id` int(10) UNSIGNED NOT NULL,
  `sup_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `code_name` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sup_mobile` varchar(14) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sup_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gtin` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sup_address` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `sup_city` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sup_state` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sup_country` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sup_details` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `suppliers`
--

INSERT INTO `suppliers` (`sup_id`, `sup_name`, `code_name`, `sup_mobile`, `sup_email`, `gtin`, `sup_address`, `sup_city`, `sup_state`, `sup_country`, `sup_details`, `created_at`) VALUES
(1, 'No Supplier', 'no_supplier', '016000000', 'supplier@itsolution24.com', '', 'USA', '', '', 'AD', '', '2018-03-24 20:19:51'),
(2, 'BAKUS', '51', '961240141', 'bakusp@gmail.com', '', 'Av. Micaela Bastidas', 'Lima', 'peruano', 'PE', '', '2021-01-21 15:08:10'),
(3, 'COSTA', '51', '936240141', 'costas@gmail.com', '', 'Av. Micaela Bastidas', 'Lima', 'peruano', 'AD', '', '2021-01-21 15:09:06'),
(4, 'SAN JORGE', '51', '931240569', 'sanjorge@gmail.com', '', 'Av. Micaela Bastidas', 'Lima', 'peruano', 'AD', '', '2021-01-21 15:09:41'),
(5, 'SAN FERNANDO', '51', '9658440141', 'sanfer@gmail.com', '', 'Av. Universitaria numero 256', 'Lima', 'peruano', 'AD', '', '2021-01-21 15:10:33'),
(6, 'ALICORP', '51', '935245681', 'consultasali@alicorp.com', '', 'Av. Universitaria 1257', 'Lima', 'peruano', 'AD', '', '2021-01-23 21:19:10'),
(7, 'CATERPILLAR', '51', '953687485', 'coliseum@cat.com', '', 'Av. José Galavez', 'Lima', 'peruano', 'AD', '', '2021-01-23 21:20:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `supplier_to_store`
--

CREATE TABLE `supplier_to_store` (
  `s2s_id` int(10) NOT NULL,
  `sup_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL DEFAULT 1,
  `balance` decimal(25,4) NOT NULL DEFAULT 0.0000,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `supplier_to_store`
--

INSERT INTO `supplier_to_store` (`s2s_id`, `sup_id`, `store_id`, `balance`, `status`, `sort_order`) VALUES
(1, 1, 1, '0.0000', 1, 0),
(2, 1, 2, '0.0000', 1, 0),
(3, 2, 1, '0.0000', 1, 0),
(4, 2, 2, '0.0000', 1, 0),
(5, 3, 1, '0.0000', 1, 0),
(6, 3, 2, '0.0000', 1, 0),
(7, 4, 1, '0.0000', 1, 0),
(8, 4, 2, '0.0000', 1, 0),
(9, 5, 1, '0.0000', 1, 0),
(10, 5, 2, '0.0000', 1, 0),
(11, 6, 1, '0.0000', 1, 0),
(12, 6, 2, '0.0000', 1, 0),
(13, 7, 1, '0.0000', 1, 0),
(14, 7, 2, '0.0000', 1, 0),
(15, 3, 3, '0.0000', 1, 0),
(16, 1, 3, '0.0000', 1, 0),
(17, 2, 3, '0.0000', 1, 0),
(18, 5, 3, '0.0000', 1, 0),
(19, 4, 3, '0.0000', 1, 0),
(20, 6, 3, '0.0000', 1, 0),
(21, 7, 3, '0.0000', 1, 0),
(22, 3, 4, '0.0000', 1, 0),
(23, 1, 4, '0.0000', 1, 0),
(24, 2, 4, '0.0000', 1, 0),
(25, 5, 4, '0.0000', 1, 0),
(26, 4, 4, '0.0000', 1, 0),
(27, 6, 4, '0.0000', 1, 0),
(28, 7, 4, '0.0000', 1, 0),
(29, 7, 5, '0.0000', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `taxrates`
--

CREATE TABLE `taxrates` (
  `taxrate_id` int(11) NOT NULL,
  `taxrate_name` varchar(55) CHARACTER SET utf8 NOT NULL,
  `code_name` varchar(55) CHARACTER SET utf8 DEFAULT NULL,
  `taxrate` decimal(25,4) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `taxrates`
--

INSERT INTO `taxrates` (`taxrate_id`, `taxrate_name`, `code_name`, `taxrate`, `status`, `sort_order`) VALUES
(1, 'GST @0%', 'GG0', '0.0000', 1, 4),
(2, 'GST @18%', 'GGX', '18.0000', 1, 5),
(3, 'No Tax', 'no_tax', '0.0000', 1, 1),
(4, 'VAT @10%', 'VVX', '10.0000', 1, 2),
(5, 'Tax @20%', 'TTX', '20.0000', 1, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transfers`
--

CREATE TABLE `transfers` (
  `id` int(11) NOT NULL,
  `ref_no` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `from_store_id` int(11) NOT NULL,
  `to_store_id` int(11) NOT NULL,
  `note` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `total_item` decimal(25,4) DEFAULT NULL,
  `total_quantity` decimal(25,4) NOT NULL,
  `created_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(55) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `attachment` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transfer_items`
--

CREATE TABLE `transfer_items` (
  `id` int(11) NOT NULL,
  `store_id` int(11) DEFAULT NULL,
  `transfer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` decimal(25,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `units`
--

CREATE TABLE `units` (
  `unit_id` int(10) NOT NULL,
  `unit_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `code_name` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `unit_details` text CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `units`
--

INSERT INTO `units` (`unit_id`, `unit_name`, `code_name`, `unit_details`) VALUES
(1, 'Pieces', 'pieces', ''),
(2, 'Unidades', 'unidades', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unit_to_store`
--

CREATE TABLE `unit_to_store` (
  `unit2s_id` int(10) UNSIGNED NOT NULL,
  `uunit_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `store_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `unit_to_store`
--

INSERT INTO `unit_to_store` (`unit2s_id`, `uunit_id`, `store_id`, `status`, `sort_order`) VALUES
(1, 1, 1, 1, 0),
(2, 2, 1, 1, 0),
(3, 2, 2, 1, 0),
(4, 2, 3, 1, 0),
(5, 2, 4, 1, 0),
(6, 2, 5, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `group_id` int(11) NOT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `mobile` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `dob` date DEFAULT NULL,
  `sex` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'M',
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `raw_password` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pass_reset_code` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reset_code_time` datetime DEFAULT NULL,
  `login_try` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `last_login` datetime DEFAULT NULL,
  `ip` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `preference` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_image` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `group_id`, `username`, `email`, `mobile`, `dob`, `sex`, `password`, `raw_password`, `pass_reset_code`, `reset_code_time`, `login_try`, `last_login`, `ip`, `address`, `preference`, `user_image`, `created_at`, `updated_at`) VALUES
(1, 1, 'Fredy Alegre', 'mitienda@gmail.com', '931240141', '1993-11-27', 'M', '6c5dc4ff5d0be4ac99337cea5576e7ff', 'tusolutionweb', '', NULL, 0, '2021-04-15 22:13:43', '::1', NULL, 'a:4:{s:10:\"base_color\";s:5:\"black\";s:14:\"pos_side_panel\";s:5:\"right\";s:11:\"pos_pattern\";s:13:\"brickwall.jpg\";s:8:\"language\";s:2:\"es\";}', '/idea.png', '2019-12-14 22:27:47', '2019-07-03 22:29:20'),
(2, 2, 'Cashier', 'cashier@gmail.com', '01521504597', '1990-01-01', 'M', '6c5dc4ff5d0be4ac99337cea5576e7ff', 'tusolutionweb', '', NULL, 0, '2019-12-16 00:33:07', '201.240.205.187', NULL, 'a:4:{s:10:\"base_color\";s:5:\"green\";s:14:\"pos_side_panel\";s:5:\"right\";s:11:\"pos_pattern\";s:31:\"abstract-attractive-backdro.jpg\";s:8:\"language\";s:2:\"es\";}', '', '2019-12-14 22:27:47', '2019-07-02 16:28:15'),
(3, 3, 'Salesman', 'salesman@gmail.com', '0113743700', '1990-01-01', 'M', '6c5dc4ff5d0be4ac99337cea5576e7ff', 'tusolutionweb', '', NULL, 0, '2019-12-16 00:32:01', '201.240.205.187', NULL, 'a:4:{s:10:\"base_color\";s:5:\"black\";s:14:\"pos_side_panel\";s:4:\"left\";s:11:\"pos_pattern\";s:13:\"brickwall.jpg\";s:8:\"language\";s:2:\"es\";}', NULL, '2019-12-14 22:27:47', '2019-07-02 21:35:07'),
(4, 2, 'YOHN ALEGRE', 'yohn@gmail.com', '956828019', '0000-00-00', 'M', '3f266c577a6dc2770393aa6c5c47c832', 'yohn123', NULL, NULL, 0, NULL, '', NULL, NULL, '', '2021-01-20 20:58:24', NULL),
(5, 2, 'JOSE TORRES', 'JOSE@GMAIL.COM', '935256458', '0000-00-00', 'M', '3a72497a566e3d8ec2750cd3424d30a6', 'JOSE123', NULL, NULL, 0, NULL, '', NULL, NULL, '', '2021-01-24 23:00:37', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_group`
--

CREATE TABLE `user_group` (
  `group_id` int(11) NOT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `sort_order` int(10) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `permission` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `user_group`
--

INSERT INTO `user_group` (`group_id`, `name`, `slug`, `sort_order`, `status`, `permission`) VALUES
(1, 'Admin', 'admin', 1, 1, 'a:1:{s:6:\"access\";a:122:{s:16:\"read_sell_report\";s:4:\"true\";s:20:\"read_overview_report\";s:4:\"true\";s:22:\"read_collection_report\";s:4:\"true\";s:27:\"read_full_collection_report\";s:4:\"true\";s:35:\"read_customer_due_collection_report\";s:4:\"true\";s:29:\"read_supplier_due_paid_report\";s:4:\"true\";s:14:\"read_analytics\";s:4:\"true\";s:24:\"read_sell_payment_report\";s:4:\"true\";s:20:\"read_sell_tax_report\";s:4:\"true\";s:24:\"read_tax_overview_report\";s:4:\"true\";s:17:\"read_stock_report\";s:4:\"true\";s:21:\"send_report_via_email\";s:4:\"true\";s:8:\"withdraw\";s:4:\"true\";s:7:\"deposit\";s:4:\"true\";s:8:\"transfer\";s:4:\"true\";s:17:\"read_bank_account\";s:4:\"true\";s:23:\"read_bank_account_sheet\";s:4:\"true\";s:18:\"read_bank_transfer\";s:4:\"true\";s:22:\"read_bank_transactions\";s:4:\"true\";s:19:\"create_bank_account\";s:4:\"true\";s:19:\"update_bank_account\";s:4:\"true\";s:19:\"delete_bank_account\";s:4:\"true\";s:12:\"read_expense\";s:4:\"true\";s:14:\"create_expense\";s:4:\"true\";s:14:\"update_expense\";s:4:\"true\";s:14:\"delete_expense\";s:4:\"true\";s:21:\"read_sell_transaction\";s:4:\"true\";s:23:\"create_purchase_invoice\";s:4:\"true\";s:18:\"read_purchase_list\";s:4:\"true\";s:28:\"update_purchase_invoice_info\";s:4:\"true\";s:23:\"delete_purchase_invoice\";s:4:\"true\";s:16:\"purchase_payment\";s:4:\"true\";s:13:\"read_transfer\";s:4:\"true\";s:12:\"add_transfer\";s:4:\"true\";s:15:\"update_transfer\";s:4:\"true\";s:13:\"read_giftcard\";s:4:\"true\";s:12:\"add_giftcard\";s:4:\"true\";s:15:\"update_giftcard\";s:4:\"true\";s:15:\"delete_giftcard\";s:4:\"true\";s:14:\"giftcard_topup\";s:4:\"true\";s:19:\"read_giftcard_topup\";s:4:\"true\";s:21:\"delete_giftcard_topup\";s:4:\"true\";s:12:\"read_product\";s:4:\"true\";s:14:\"create_product\";s:4:\"true\";s:14:\"update_product\";s:4:\"true\";s:14:\"delete_product\";s:4:\"true\";s:14:\"import_product\";s:4:\"true\";s:19:\"product_bulk_action\";s:4:\"true\";s:18:\"delete_all_product\";s:4:\"true\";s:13:\"read_category\";s:4:\"true\";s:15:\"create_category\";s:4:\"true\";s:15:\"update_category\";s:4:\"true\";s:15:\"delete_category\";s:4:\"true\";s:16:\"read_stock_alert\";s:4:\"true\";s:20:\"read_expired_product\";s:4:\"true\";s:19:\"restore_all_product\";s:4:\"true\";s:13:\"read_supplier\";s:4:\"true\";s:15:\"create_supplier\";s:4:\"true\";s:15:\"update_supplier\";s:4:\"true\";s:15:\"delete_supplier\";s:4:\"true\";s:21:\"read_supplier_profile\";s:4:\"true\";s:8:\"read_box\";s:4:\"true\";s:10:\"create_box\";s:4:\"true\";s:10:\"update_box\";s:4:\"true\";s:10:\"delete_box\";s:4:\"true\";s:9:\"read_unit\";s:4:\"true\";s:11:\"create_unit\";s:4:\"true\";s:11:\"update_unit\";s:4:\"true\";s:11:\"delete_unit\";s:4:\"true\";s:12:\"read_taxrate\";s:4:\"true\";s:14:\"create_taxrate\";s:4:\"true\";s:14:\"update_taxrate\";s:4:\"true\";s:14:\"delete_taxrate\";s:4:\"true\";s:9:\"read_loan\";s:4:\"true\";s:17:\"read_loan_summary\";s:4:\"true\";s:9:\"take_loan\";s:4:\"true\";s:11:\"update_loan\";s:4:\"true\";s:11:\"delete_loan\";s:4:\"true\";s:8:\"loan_pay\";s:4:\"true\";s:13:\"read_customer\";s:4:\"true\";s:21:\"read_customer_profile\";s:4:\"true\";s:15:\"create_customer\";s:4:\"true\";s:15:\"update_customer\";s:4:\"true\";s:15:\"delete_customer\";s:4:\"true\";s:9:\"read_user\";s:4:\"true\";s:11:\"create_user\";s:4:\"true\";s:11:\"update_user\";s:4:\"true\";s:11:\"delete_user\";s:4:\"true\";s:15:\"change_password\";s:4:\"true\";s:14:\"read_usergroup\";s:4:\"true\";s:16:\"create_usergroup\";s:4:\"true\";s:16:\"update_usergroup\";s:4:\"true\";s:16:\"delete_usergroup\";s:4:\"true\";s:13:\"read_currency\";s:4:\"true\";s:15:\"create_currency\";s:4:\"true\";s:15:\"update_currency\";s:4:\"true\";s:15:\"change_currency\";s:4:\"true\";s:15:\"delete_currency\";s:4:\"true\";s:16:\"read_filemanager\";s:4:\"true\";s:12:\"read_pmethod\";s:4:\"true\";s:14:\"create_pmethod\";s:4:\"true\";s:14:\"update_pmethod\";s:4:\"true\";s:14:\"delete_pmethod\";s:4:\"true\";s:10:\"read_store\";s:4:\"true\";s:12:\"create_store\";s:4:\"true\";s:12:\"update_store\";s:4:\"true\";s:12:\"delete_store\";s:4:\"true\";s:14:\"activate_store\";s:4:\"true\";s:14:\"upload_favicon\";s:4:\"true\";s:11:\"upload_logo\";s:4:\"true\";s:12:\"read_printer\";s:4:\"true\";s:14:\"create_printer\";s:4:\"true\";s:14:\"update_printer\";s:4:\"true\";s:14:\"delete_printer\";s:4:\"true\";s:20:\"read_user_preference\";s:4:\"true\";s:22:\"update_user_preference\";s:4:\"true\";s:9:\"filtering\";s:4:\"true\";s:13:\"language_sync\";s:4:\"true\";s:6:\"backup\";s:4:\"true\";s:7:\"restore\";s:4:\"true\";s:11:\"show_profit\";s:4:\"true\";s:10:\"show_graph\";s:4:\"true\";}}'),
(2, 'Cashier', 'cashier', 2, 1, 'a:1:{s:6:\"access\";a:2:{s:9:\"read_user\";s:4:\"true\";s:11:\"update_user\";s:4:\"true\";}}'),
(3, 'Salesman', 'salesman', 3, 1, 'a:1:{s:6:\"access\";a:191:{s:22:\"read_recent_activities\";s:4:\"true\";s:32:\"read_dashboard_accounting_report\";s:4:\"true\";s:16:\"read_sell_report\";s:4:\"true\";s:20:\"read_overview_report\";s:4:\"true\";s:22:\"read_collection_report\";s:4:\"true\";s:27:\"read_full_collection_report\";s:4:\"true\";s:35:\"read_customer_due_collection_report\";s:4:\"true\";s:29:\"read_supplier_due_paid_report\";s:4:\"true\";s:14:\"read_analytics\";s:4:\"true\";s:20:\"read_purchase_report\";s:4:\"true\";s:28:\"read_purchase_payment_report\";s:4:\"true\";s:24:\"read_purchase_tax_report\";s:4:\"true\";s:24:\"read_sell_payment_report\";s:4:\"true\";s:20:\"read_sell_tax_report\";s:4:\"true\";s:24:\"read_tax_overview_report\";s:4:\"true\";s:17:\"read_stock_report\";s:4:\"true\";s:21:\"send_report_via_email\";s:4:\"true\";s:8:\"withdraw\";s:4:\"true\";s:7:\"deposit\";s:4:\"true\";s:8:\"transfer\";s:4:\"true\";s:17:\"read_bank_account\";s:4:\"true\";s:23:\"read_bank_account_sheet\";s:4:\"true\";s:18:\"read_bank_transfer\";s:4:\"true\";s:22:\"read_bank_transactions\";s:4:\"true\";s:18:\"read_income_source\";s:4:\"true\";s:19:\"create_bank_account\";s:4:\"true\";s:19:\"update_bank_account\";s:4:\"true\";s:19:\"delete_bank_account\";s:4:\"true\";s:20:\"create_income_source\";s:4:\"true\";s:20:\"update_income_source\";s:4:\"true\";s:20:\"delete_income_source\";s:4:\"true\";s:21:\"read_income_monthwise\";s:4:\"true\";s:30:\"read_income_and_expense_report\";s:4:\"true\";s:27:\"read_profit_and_loss_report\";s:4:\"true\";s:20:\"read_cashbook_report\";s:4:\"true\";s:14:\"read_quotation\";s:4:\"true\";s:16:\"create_quotation\";s:4:\"true\";s:16:\"update_quotation\";s:4:\"true\";s:16:\"delete_quotation\";s:4:\"true\";s:16:\"read_installment\";s:4:\"true\";s:18:\"create_installment\";s:4:\"true\";s:18:\"update_installment\";s:4:\"true\";s:18:\"delete_installment\";s:4:\"true\";s:19:\"installment_payment\";s:4:\"true\";s:20:\"installment_overview\";s:4:\"true\";s:12:\"read_expense\";s:4:\"true\";s:14:\"create_expense\";s:4:\"true\";s:14:\"update_expense\";s:4:\"true\";s:14:\"delete_expense\";s:4:\"true\";s:21:\"read_expense_category\";s:4:\"true\";s:23:\"create_expense_category\";s:4:\"true\";s:23:\"update_expense_category\";s:4:\"true\";s:23:\"delete_expense_category\";s:4:\"true\";s:22:\"read_expense_monthwise\";s:4:\"true\";s:20:\"read_expense_summary\";s:4:\"true\";s:17:\"read_sell_invoice\";s:4:\"true\";s:14:\"read_sell_list\";s:4:\"true\";s:19:\"create_sell_invoice\";s:4:\"true\";s:24:\"update_sell_invoice_info\";s:4:\"true\";s:19:\"delete_sell_invoice\";s:4:\"true\";s:12:\"sell_payment\";s:4:\"true\";s:15:\"create_sell_due\";s:4:\"true\";s:18:\"create_sell_return\";s:4:\"true\";s:16:\"read_sell_return\";s:4:\"true\";s:18:\"update_sell_return\";s:4:\"true\";s:18:\"delete_sell_return\";s:4:\"true\";s:16:\"sms_sell_invoice\";s:4:\"true\";s:18:\"email_sell_invoice\";s:4:\"true\";s:13:\"read_sell_log\";s:4:\"true\";s:23:\"create_purchase_invoice\";s:4:\"true\";s:18:\"read_purchase_list\";s:4:\"true\";s:28:\"update_purchase_invoice_info\";s:4:\"true\";s:23:\"delete_purchase_invoice\";s:4:\"true\";s:16:\"purchase_payment\";s:4:\"true\";s:19:\"create_purchase_due\";s:4:\"true\";s:22:\"create_purchase_return\";s:4:\"true\";s:20:\"read_purchase_return\";s:4:\"true\";s:22:\"update_purchase_return\";s:4:\"true\";s:22:\"delete_purchase_return\";s:4:\"true\";s:17:\"read_purchase_log\";s:4:\"true\";s:13:\"read_transfer\";s:4:\"true\";s:12:\"add_transfer\";s:4:\"true\";s:15:\"update_transfer\";s:4:\"true\";s:15:\"delete_transfer\";s:4:\"true\";s:13:\"read_giftcard\";s:4:\"true\";s:12:\"add_giftcard\";s:4:\"true\";s:15:\"update_giftcard\";s:4:\"true\";s:15:\"delete_giftcard\";s:4:\"true\";s:14:\"giftcard_topup\";s:4:\"true\";s:19:\"read_giftcard_topup\";s:4:\"true\";s:21:\"delete_giftcard_topup\";s:4:\"true\";s:12:\"read_product\";s:4:\"true\";s:14:\"create_product\";s:4:\"true\";s:14:\"update_product\";s:4:\"true\";s:14:\"delete_product\";s:4:\"true\";s:14:\"import_product\";s:4:\"true\";s:19:\"product_bulk_action\";s:4:\"true\";s:18:\"delete_all_product\";s:4:\"true\";s:13:\"read_category\";s:4:\"true\";s:15:\"create_category\";s:4:\"true\";s:15:\"update_category\";s:4:\"true\";s:15:\"delete_category\";s:4:\"true\";s:16:\"read_stock_alert\";s:4:\"true\";s:20:\"read_expired_product\";s:4:\"true\";s:13:\"barcode_print\";s:4:\"true\";s:19:\"restore_all_product\";s:4:\"true\";s:13:\"read_supplier\";s:4:\"true\";s:15:\"create_supplier\";s:4:\"true\";s:15:\"update_supplier\";s:4:\"true\";s:15:\"delete_supplier\";s:4:\"true\";s:21:\"read_supplier_profile\";s:4:\"true\";s:10:\"read_brand\";s:4:\"true\";s:12:\"create_brand\";s:4:\"true\";s:12:\"update_brand\";s:4:\"true\";s:12:\"delete_brand\";s:4:\"true\";s:18:\"read_brand_profile\";s:4:\"true\";s:8:\"read_box\";s:4:\"true\";s:10:\"create_box\";s:4:\"true\";s:10:\"update_box\";s:4:\"true\";s:10:\"delete_box\";s:4:\"true\";s:9:\"read_unit\";s:4:\"true\";s:11:\"create_unit\";s:4:\"true\";s:11:\"update_unit\";s:4:\"true\";s:11:\"delete_unit\";s:4:\"true\";s:12:\"read_taxrate\";s:4:\"true\";s:14:\"create_taxrate\";s:4:\"true\";s:14:\"update_taxrate\";s:4:\"true\";s:14:\"delete_taxrate\";s:4:\"true\";s:9:\"read_loan\";s:4:\"true\";s:17:\"read_loan_summary\";s:4:\"true\";s:9:\"take_loan\";s:4:\"true\";s:11:\"update_loan\";s:4:\"true\";s:11:\"delete_loan\";s:4:\"true\";s:8:\"loan_pay\";s:4:\"true\";s:13:\"read_customer\";s:4:\"true\";s:21:\"read_customer_profile\";s:4:\"true\";s:15:\"create_customer\";s:4:\"true\";s:15:\"update_customer\";s:4:\"true\";s:15:\"delete_customer\";s:4:\"true\";s:20:\"add_customer_balance\";s:4:\"true\";s:26:\"substract_customer_balance\";s:4:\"true\";s:25:\"read_customer_transaction\";s:4:\"true\";s:9:\"read_user\";s:4:\"true\";s:11:\"create_user\";s:4:\"true\";s:11:\"update_user\";s:4:\"true\";s:11:\"delete_user\";s:4:\"true\";s:15:\"change_password\";s:4:\"true\";s:14:\"read_usergroup\";s:4:\"true\";s:16:\"create_usergroup\";s:4:\"true\";s:16:\"update_usergroup\";s:4:\"true\";s:16:\"delete_usergroup\";s:4:\"true\";s:13:\"read_currency\";s:4:\"true\";s:15:\"create_currency\";s:4:\"true\";s:15:\"update_currency\";s:4:\"true\";s:15:\"change_currency\";s:4:\"true\";s:15:\"delete_currency\";s:4:\"true\";s:16:\"read_filemanager\";s:4:\"true\";s:12:\"read_pmethod\";s:4:\"true\";s:14:\"create_pmethod\";s:4:\"true\";s:14:\"update_pmethod\";s:4:\"true\";s:14:\"delete_pmethod\";s:4:\"true\";s:22:\"updadte_pmethod_status\";s:4:\"true\";s:10:\"read_store\";s:4:\"true\";s:12:\"create_store\";s:4:\"true\";s:12:\"update_store\";s:4:\"true\";s:12:\"delete_store\";s:4:\"true\";s:14:\"activate_store\";s:4:\"true\";s:14:\"upload_favicon\";s:4:\"true\";s:11:\"upload_logo\";s:4:\"true\";s:12:\"read_printer\";s:4:\"true\";s:14:\"create_printer\";s:4:\"true\";s:14:\"update_printer\";s:4:\"true\";s:14:\"delete_printer\";s:4:\"true\";s:10:\"send_email\";s:4:\"true\";s:13:\"read_language\";s:4:\"true\";s:15:\"create_language\";s:4:\"true\";s:15:\"update_language\";s:4:\"true\";s:15:\"delete_language\";s:4:\"true\";s:20:\"language_translation\";s:4:\"true\";s:19:\"delete_language_key\";s:4:\"true\";s:16:\"receipt_template\";s:4:\"true\";s:20:\"read_user_preference\";s:4:\"true\";s:22:\"update_user_preference\";s:4:\"true\";s:9:\"filtering\";s:4:\"true\";s:13:\"language_sync\";s:4:\"true\";s:6:\"backup\";s:4:\"true\";s:7:\"restore\";s:4:\"true\";s:5:\"reset\";s:4:\"true\";s:19:\"show_purchase_price\";s:4:\"true\";s:11:\"show_profit\";s:4:\"true\";s:10:\"show_graph\";s:4:\"true\";}}');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_to_store`
--

CREATE TABLE `user_to_store` (
  `u2s_id` int(10) NOT NULL,
  `user_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL DEFAULT 1,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `user_to_store`
--

INSERT INTO `user_to_store` (`u2s_id`, `user_id`, `store_id`, `status`, `sort_order`) VALUES
(2, 2, 1, 1, 2),
(3, 3, 1, 1, 3),
(4, 2, 2, 1, 0),
(7, 4, 1, 1, 0),
(8, 4, 2, 1, 0),
(13, 2, 3, 1, 0),
(16, 2, 4, 1, 0),
(23, 2, 5, 1, 0),
(26, 5, 1, 1, 0),
(27, 5, 2, 1, 0),
(28, 5, 3, 1, 0),
(29, 5, 4, 1, 0),
(30, 5, 5, 1, 0),
(31, 1, 1, 1, 1),
(32, 1, 2, 1, 0),
(33, 1, 3, 1, 0),
(34, 1, 4, 1, 0),
(35, 1, 5, 1, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `bank_account_to_store`
--
ALTER TABLE `bank_account_to_store`
  ADD PRIMARY KEY (`ba2s`),
  ADD KEY `account_id` (`account_id`);

--
-- Indices de la tabla `bank_transaction_info`
--
ALTER TABLE `bank_transaction_info`
  ADD PRIMARY KEY (`info_id`),
  ADD KEY `ref_no` (`ref_no`),
  ADD KEY `created_at` (`created_at`);

--
-- Indices de la tabla `bank_transaction_price`
--
ALTER TABLE `bank_transaction_price`
  ADD PRIMARY KEY (`price_id`),
  ADD KEY `ref_no` (`ref_no`);

--
-- Indices de la tabla `boxes`
--
ALTER TABLE `boxes`
  ADD PRIMARY KEY (`box_id`);

--
-- Indices de la tabla `box_to_store`
--
ALTER TABLE `box_to_store`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indices de la tabla `brand_to_store`
--
ALTER TABLE `brand_to_store`
  ADD PRIMARY KEY (`b2s_id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Indices de la tabla `categorys`
--
ALTER TABLE `categorys`
  ADD PRIMARY KEY (`category_id`);

--
-- Indices de la tabla `category_to_store`
--
ALTER TABLE `category_to_store`
  ADD PRIMARY KEY (`c2s_id`),
  ADD KEY `ccategory_id` (`ccategory_id`);

--
-- Indices de la tabla `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`currency_id`);

--
-- Indices de la tabla `currency_to_store`
--
ALTER TABLE `currency_to_store`
  ADD PRIMARY KEY (`ca2s_id`),
  ADD KEY `currency_id` (`currency_id`);

--
-- Indices de la tabla `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indices de la tabla `customer_to_store`
--
ALTER TABLE `customer_to_store`
  ADD PRIMARY KEY (`c2s_id`);

--
-- Indices de la tabla `customer_transactions`
--
ALTER TABLE `customer_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indices de la tabla `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `expense_categorys`
--
ALTER TABLE `expense_categorys`
  ADD PRIMARY KEY (`category_id`);

--
-- Indices de la tabla `gift_cards`
--
ALTER TABLE `gift_cards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `card_no` (`card_no`);

--
-- Indices de la tabla `gift_card_topups`
--
ALTER TABLE `gift_card_topups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `card_id` (`card_id`);

--
-- Indices de la tabla `holding_info`
--
ALTER TABLE `holding_info`
  ADD PRIMARY KEY (`info_id`);

--
-- Indices de la tabla `holding_item`
--
ALTER TABLE `holding_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ref_no` (`ref_no`),
  ADD KEY `item_id` (`item_id`);

--
-- Indices de la tabla `holding_price`
--
ALTER TABLE `holding_price`
  ADD PRIMARY KEY (`price_id`),
  ADD KEY `ref_no` (`ref_no`);

--
-- Indices de la tabla `income_sources`
--
ALTER TABLE `income_sources`
  ADD PRIMARY KEY (`source_id`);

--
-- Indices de la tabla `installment_orders`
--
ALTER TABLE `installment_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `store_id` (`store_id`);

--
-- Indices de la tabla `installment_payments`
--
ALTER TABLE `installment_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indices de la tabla `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `language_translations`
--
ALTER TABLE `language_translations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`loan_id`);

--
-- Indices de la tabla `loan_payments`
--
ALTER TABLE `loan_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lloan_id` (`lloan_id`);

--
-- Indices de la tabla `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `mail_sms_tag`
--
ALTER TABLE `mail_sms_tag`
  ADD PRIMARY KEY (`tag_id`);

--
-- Indices de la tabla `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indices de la tabla `pmethods`
--
ALTER TABLE `pmethods`
  ADD PRIMARY KEY (`pmethod_id`);

--
-- Indices de la tabla `pmethod_to_store`
--
ALTER TABLE `pmethod_to_store`
  ADD PRIMARY KEY (`p2s_id`),
  ADD KEY `ppmethod_id` (`ppmethod_id`);

--
-- Indices de la tabla `pos_register`
--
ALTER TABLE `pos_register`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pos_templates`
--
ALTER TABLE `pos_templates`
  ADD PRIMARY KEY (`template_id`);

--
-- Indices de la tabla `pos_template_to_store`
--
ALTER TABLE `pos_template_to_store`
  ADD PRIMARY KEY (`pt2s`),
  ADD KEY `ttemplate_id` (`ttemplate_id`);

--
-- Indices de la tabla `printers`
--
ALTER TABLE `printers`
  ADD PRIMARY KEY (`printer_id`);

--
-- Indices de la tabla `printer_to_store`
--
ALTER TABLE `printer_to_store`
  ADD PRIMARY KEY (`p2s_id`),
  ADD KEY `pprinter_id` (`pprinter_id`);

--
-- Indices de la tabla `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`p_id`),
  ADD UNIQUE KEY `p_code` (`p_code`) USING BTREE;

--
-- Indices de la tabla `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indices de la tabla `product_to_store`
--
ALTER TABLE `product_to_store`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `p_date` (`p_date`);

--
-- Indices de la tabla `purchase_info`
--
ALTER TABLE `purchase_info`
  ADD PRIMARY KEY (`info_id`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indices de la tabla `purchase_item`
--
ALTER TABLE `purchase_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indices de la tabla `purchase_logs`
--
ALTER TABLE `purchase_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sup_id` (`sup_id`),
  ADD KEY `reference_no` (`reference_no`),
  ADD KEY `ref_invoice_id` (`ref_invoice_id`);

--
-- Indices de la tabla `purchase_payments`
--
ALTER TABLE `purchase_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indices de la tabla `purchase_price`
--
ALTER TABLE `purchase_price`
  ADD PRIMARY KEY (`price_id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indices de la tabla `purchase_returns`
--
ALTER TABLE `purchase_returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indices de la tabla `purchase_return_items`
--
ALTER TABLE `purchase_return_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indices de la tabla `quotation_info`
--
ALTER TABLE `quotation_info`
  ADD PRIMARY KEY (`info_id`);

--
-- Indices de la tabla `quotation_item`
--
ALTER TABLE `quotation_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reference_no` (`reference_no`);

--
-- Indices de la tabla `quotation_price`
--
ALTER TABLE `quotation_price`
  ADD PRIMARY KEY (`price_id`),
  ADD KEY `reference_no` (`reference_no`);

--
-- Indices de la tabla `returns`
--
ALTER TABLE `returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indices de la tabla `return_items`
--
ALTER TABLE `return_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transfer_id` (`invoice_id`),
  ADD KEY `product_id` (`item_id`);

--
-- Indices de la tabla `selling_info`
--
ALTER TABLE `selling_info`
  ADD PRIMARY KEY (`info_id`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indices de la tabla `selling_item`
--
ALTER TABLE `selling_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indices de la tabla `selling_price`
--
ALTER TABLE `selling_price`
  ADD PRIMARY KEY (`price_id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indices de la tabla `sell_logs`
--
ALTER TABLE `sell_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `shortcut_links`
--
ALTER TABLE `shortcut_links`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sms_schedule`
--
ALTER TABLE `sms_schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `people_type` (`people_type`);

--
-- Indices de la tabla `sms_setting`
--
ALTER TABLE `sms_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`store_id`);

--
-- Indices de la tabla `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`sup_id`);

--
-- Indices de la tabla `supplier_to_store`
--
ALTER TABLE `supplier_to_store`
  ADD PRIMARY KEY (`s2s_id`),
  ADD KEY `sup_id` (`sup_id`);

--
-- Indices de la tabla `taxrates`
--
ALTER TABLE `taxrates`
  ADD PRIMARY KEY (`taxrate_id`);

--
-- Indices de la tabla `transfers`
--
ALTER TABLE `transfers`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `transfer_items`
--
ALTER TABLE `transfer_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transfer_id` (`transfer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indices de la tabla `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`unit_id`);

--
-- Indices de la tabla `unit_to_store`
--
ALTER TABLE `unit_to_store`
  ADD PRIMARY KEY (`unit2s_id`),
  ADD KEY `uunit_id` (`uunit_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`);

--
-- Indices de la tabla `user_group`
--
ALTER TABLE `user_group`
  ADD PRIMARY KEY (`group_id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indices de la tabla `user_to_store`
--
ALTER TABLE `user_to_store`
  ADD PRIMARY KEY (`u2s_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bank_accounts`
--
ALTER TABLE `bank_accounts`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `bank_account_to_store`
--
ALTER TABLE `bank_account_to_store`
  MODIFY `ba2s` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `bank_transaction_info`
--
ALTER TABLE `bank_transaction_info`
  MODIFY `info_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `bank_transaction_price`
--
ALTER TABLE `bank_transaction_price`
  MODIFY `price_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `boxes`
--
ALTER TABLE `boxes`
  MODIFY `box_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `box_to_store`
--
ALTER TABLE `box_to_store`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `brand_to_store`
--
ALTER TABLE `brand_to_store`
  MODIFY `b2s_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `categorys`
--
ALTER TABLE `categorys`
  MODIFY `category_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `category_to_store`
--
ALTER TABLE `category_to_store`
  MODIFY `c2s_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `currency`
--
ALTER TABLE `currency`
  MODIFY `currency_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `currency_to_store`
--
ALTER TABLE `currency_to_store`
  MODIFY `ca2s_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `customer_to_store`
--
ALTER TABLE `customer_to_store`
  MODIFY `c2s_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `customer_transactions`
--
ALTER TABLE `customer_transactions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `expense_categorys`
--
ALTER TABLE `expense_categorys`
  MODIFY `category_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `gift_cards`
--
ALTER TABLE `gift_cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `gift_card_topups`
--
ALTER TABLE `gift_card_topups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `holding_info`
--
ALTER TABLE `holding_info`
  MODIFY `info_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `holding_item`
--
ALTER TABLE `holding_item`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `holding_price`
--
ALTER TABLE `holding_price`
  MODIFY `price_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `income_sources`
--
ALTER TABLE `income_sources`
  MODIFY `source_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `installment_orders`
--
ALTER TABLE `installment_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `installment_payments`
--
ALTER TABLE `installment_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `language_translations`
--
ALTER TABLE `language_translations`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11170;

--
-- AUTO_INCREMENT de la tabla `loans`
--
ALTER TABLE `loans`
  MODIFY `loan_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `loan_payments`
--
ALTER TABLE `loan_payments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `mail_sms_tag`
--
ALTER TABLE `mail_sms_tag`
  MODIFY `tag_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `pmethods`
--
ALTER TABLE `pmethods`
  MODIFY `pmethod_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `pmethod_to_store`
--
ALTER TABLE `pmethod_to_store`
  MODIFY `p2s_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `pos_register`
--
ALTER TABLE `pos_register`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `pos_templates`
--
ALTER TABLE `pos_templates`
  MODIFY `template_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pos_template_to_store`
--
ALTER TABLE `pos_template_to_store`
  MODIFY `pt2s` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `printers`
--
ALTER TABLE `printers`
  MODIFY `printer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `printer_to_store`
--
ALTER TABLE `printer_to_store`
  MODIFY `p2s_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
  MODIFY `p_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `product_images`
--
ALTER TABLE `product_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `product_to_store`
--
ALTER TABLE `product_to_store`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `purchase_info`
--
ALTER TABLE `purchase_info`
  MODIFY `info_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `purchase_item`
--
ALTER TABLE `purchase_item`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `purchase_logs`
--
ALTER TABLE `purchase_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `purchase_payments`
--
ALTER TABLE `purchase_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `purchase_price`
--
ALTER TABLE `purchase_price`
  MODIFY `price_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `purchase_returns`
--
ALTER TABLE `purchase_returns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `purchase_return_items`
--
ALTER TABLE `purchase_return_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `quotation_info`
--
ALTER TABLE `quotation_info`
  MODIFY `info_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `quotation_item`
--
ALTER TABLE `quotation_item`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `quotation_price`
--
ALTER TABLE `quotation_price`
  MODIFY `price_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `returns`
--
ALTER TABLE `returns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `return_items`
--
ALTER TABLE `return_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `selling_info`
--
ALTER TABLE `selling_info`
  MODIFY `info_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `selling_item`
--
ALTER TABLE `selling_item`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `selling_price`
--
ALTER TABLE `selling_price`
  MODIFY `price_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `sell_logs`
--
ALTER TABLE `sell_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `shortcut_links`
--
ALTER TABLE `shortcut_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `sms_schedule`
--
ALTER TABLE `sms_schedule`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sms_setting`
--
ALTER TABLE `sms_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `stores`
--
ALTER TABLE `stores`
  MODIFY `store_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `sup_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `supplier_to_store`
--
ALTER TABLE `supplier_to_store`
  MODIFY `s2s_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `taxrates`
--
ALTER TABLE `taxrates`
  MODIFY `taxrate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `transfers`
--
ALTER TABLE `transfers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `transfer_items`
--
ALTER TABLE `transfer_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `units`
--
ALTER TABLE `units`
  MODIFY `unit_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `unit_to_store`
--
ALTER TABLE `unit_to_store`
  MODIFY `unit2s_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `user_group`
--
ALTER TABLE `user_group`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `user_to_store`
--
ALTER TABLE `user_to_store`
  MODIFY `u2s_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
