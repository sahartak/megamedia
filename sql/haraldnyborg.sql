-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Ноя 17 2015 г., 11:26
-- Версия сервера: 5.6.24
-- Версия PHP: 5.5.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `haraldnyborg`
--

-- --------------------------------------------------------

--
-- Структура таблицы `additional_orders_items`
--

CREATE TABLE IF NOT EXISTS `additional_orders_items` (
  `id` int(11) NOT NULL,
  `order_id` int(10) unsigned NOT NULL,
  `store_id` int(10) unsigned NOT NULL,
  `width` int(10) unsigned NOT NULL,
  `height` int(10) unsigned NOT NULL,
  `amount` smallint(5) unsigned NOT NULL,
  `material_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ophaeng_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(3) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `campaign_orders`
--

CREATE TABLE IF NOT EXISTS `campaign_orders` (
  `id` int(11) NOT NULL,
  `order_id` int(10) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `campaign_orders_items`
--

CREATE TABLE IF NOT EXISTS `campaign_orders_items` (
  `id` int(11) NOT NULL,
  `parent_id` int(10) unsigned NOT NULL,
  `store_id` int(10) unsigned NOT NULL,
  `type_1` smallint(5) unsigned NOT NULL DEFAULT '0',
  `type_2` smallint(5) unsigned NOT NULL DEFAULT '0',
  `type_3` smallint(5) unsigned NOT NULL DEFAULT '0',
  `type_4` smallint(6) NOT NULL DEFAULT '0',
  `material_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ophaeng_id` tinyint(3) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `hanging_methods`
--

CREATE TABLE IF NOT EXISTS `hanging_methods` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `hanging_methods`
--

INSERT INTO `hanging_methods` (`id`, `name`) VALUES
(1, 'D.S'),
(2, 'Sign P'),
(3, 'Spider');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `total_price` int(11) NOT NULL DEFAULT '0',
  `order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `orders_weeks`
--

CREATE TABLE IF NOT EXISTS `orders_weeks` (
  `id` int(11) NOT NULL,
  `order_id` int(10) unsigned NOT NULL,
  `week_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `stores`
--

CREATE TABLE IF NOT EXISTS `stores` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `street_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `postal_code` int(11) NOT NULL,
  `country` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `contact_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `phone_number` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email_address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `lat` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `long` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `stores`
--

INSERT INTO `stores` (`id`, `name`, `street_name`, `city`, `postal_code`, `country`, `contact_name`, `phone_number`, `email_address`, `lat`, `long`) VALUES
(1, 'StoreOne', 'Rådhuspladsen 1, 1550 København V, Denmark', 'Copenhagen', 345, 'Denmark', 'asdg', '234234', 'asdas@sad.sad', '55.67600000506989', '12.570869105310067'),
(2, 'StoreTwo', 'H. C. Andersens Blvd. 17, 1553 København V, Denmark', 'Copenhagen', 345345, 'Denmark', 'asdfdsg', '6565', 'sdf@asd.asd', '55.67808104328805', '12.56477512642823'),
(3, 'StoreTree', 'Lille Colbjørnsensgade 1A, 1703 København V, Denmark', 'Copenhagen', 234234, 'Denmark', 'SDFg', 'dfgh', 'asdf2@ASD.ZXC', '55.66842505914486', '12.561856883020027');

-- --------------------------------------------------------

--
-- Структура таблицы `stores_hanging_methods`
--

CREATE TABLE IF NOT EXISTS `stores_hanging_methods` (
  `id` int(11) NOT NULL,
  `store_id` int(10) unsigned NOT NULL,
  `hanging_method_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `stores_hanging_methods`
--

INSERT INTO `stores_hanging_methods` (`id`, `store_id`, `hanging_method_id`) VALUES
(1, 1, 1),
(2, 1, 3),
(3, 2, 1),
(4, 2, 2),
(5, 2, 3),
(6, 3, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `login` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `first_name`, `last_name`) VALUES
(1, 'login', '123', 'Some', 'Name');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `additional_orders_items`
--
ALTER TABLE `additional_orders_items`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `campaign_orders`
--
ALTER TABLE `campaign_orders`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `campaign_orders_items`
--
ALTER TABLE `campaign_orders_items`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `hanging_methods`
--
ALTER TABLE `hanging_methods`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders_weeks`
--
ALTER TABLE `orders_weeks`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `stores_hanging_methods`
--
ALTER TABLE `stores_hanging_methods`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `additional_orders_items`
--
ALTER TABLE `additional_orders_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `campaign_orders`
--
ALTER TABLE `campaign_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `campaign_orders_items`
--
ALTER TABLE `campaign_orders_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `hanging_methods`
--
ALTER TABLE `hanging_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `orders_weeks`
--
ALTER TABLE `orders_weeks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `stores_hanging_methods`
--
ALTER TABLE `stores_hanging_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
