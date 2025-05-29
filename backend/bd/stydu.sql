-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 28 2025 г., 00:29
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `stydu`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Classrooms`
--

CREATE TABLE `Classrooms` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `short_name` varchar(100) DEFAULT NULL,
  `responsible_user_id` int DEFAULT NULL,
  `temp_responsible_user_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Classrooms`
--

INSERT INTO `Classrooms` (`id`, `name`, `short_name`, `responsible_user_id`, `temp_responsible_user_id`) VALUES
(1, 'Компьютерный класс', 'CL', 2, NULL),
(2, 'Физическая лаборатория', 'PL', 3, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `Consumables`
--

CREATE TABLE `Consumables` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `date_received` date NOT NULL,
  `image` longblob,
  `quantity` int NOT NULL,
  `responsible_user_id` int DEFAULT NULL,
  `temp_responsible_user_id` int DEFAULT NULL,
  `consumable_type_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Consumables`
--

INSERT INTO `Consumables` (`id`, `name`, `description`, `date_received`, `image`, `quantity`, `responsible_user_id`, `temp_responsible_user_id`, `consumable_type_id`) VALUES
(1, 'Бумага А4', 'Бумага для принтера', '2025-05-01', NULL, 500, 1, NULL, 1),
(2, 'Чёрные чернила', 'Картридж с чернилами', '2025-05-02', NULL, 50, 1, NULL, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `consumable_characteristics`
--

CREATE TABLE `consumable_characteristics` (
  `id` int NOT NULL,
  `consumable_id` int NOT NULL,
  `characteristic_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `consumable_characteristics`
--

INSERT INTO `consumable_characteristics` (`id`, `consumable_id`, `characteristic_name`) VALUES
(1, 1, 'Размер'),
(2, 1, 'Цвет'),
(3, 2, 'Объём');

-- --------------------------------------------------------

--
-- Структура таблицы `consumable_types`
--

CREATE TABLE `consumable_types` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `consumable_types`
--

INSERT INTO `consumable_types` (`id`, `name`) VALUES
(1, 'Бумага'),
(2, 'Чернила');

-- --------------------------------------------------------

--
-- Структура таблицы `directions`
--

CREATE TABLE `directions` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `directions`
--

INSERT INTO `directions` (`id`, `name`) VALUES
(1, 'ИТ'),
(2, 'Наука'),
(4, 'Математика');

-- --------------------------------------------------------

--
-- Структура таблицы `equipment`
--

CREATE TABLE `equipment` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `photo` longblob,
  `inventory_number` varchar(50) NOT NULL,
  `classroom_id` int DEFAULT NULL,
  `responsible_user_id` int DEFAULT NULL,
  `temp_responsible_user_id` int DEFAULT NULL,
  `cost` decimal(12,2) DEFAULT NULL,
  `direction_id` int DEFAULT NULL,
  `status_id` int DEFAULT NULL,
  `model_id` int DEFAULT NULL,
  `comment` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `equipment`
--

INSERT INTO `equipment` (`id`, `name`, `photo`, `inventory_number`, `classroom_id`, `responsible_user_id`, `temp_responsible_user_id`, `cost`, `direction_id`, `status_id`, `model_id`, `comment`) VALUES
(1, 'Проектор A', NULL, '001', 2, 3, NULL, '1200.00', 2, 1, 2, 'Установлен в физической лаборатории'),
(2, 'Ноутбук A', NULL, '002', 1, 2, NULL, '1500.00', 1, 1, 1, 'Закреплён за преподавателем Петровым');

--
-- Триггеры `equipment`
--
DELIMITER $$
CREATE TRIGGER `trg_equipment_after_insert_movement` AFTER INSERT ON `equipment` FOR EACH ROW BEGIN
  INSERT INTO `EquipmentMovementHistory`
    (`equipment_id`, `classroom_id`, `responsible_user_id`,
     `temp_responsible_user_id`, `movement_date`, `comment`)
  VALUES
    (NEW.id, NEW.room_id, NEW.responsible_user_id,
     NEW.temp_responsible_user_id, CURDATE(), 'Initial placement');
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_equipment_after_update_movement` AFTER UPDATE ON `equipment` FOR EACH ROW BEGIN
  IF OLD.room_id              <> NEW.room_id
   OR OLD.responsible_user_id <> NEW.responsible_user_id
   OR OLD.temp_responsible_user_id <> NEW.temp_responsible_user_id
  THEN
    INSERT INTO `EquipmentMovementHistory`
      (`equipment_id`, `classroom_id`, `responsible_user_id`,
       `temp_responsible_user_id`, `movement_date`, `comment`)
    VALUES
      (NEW.id,
       NEW.room_id,
       NEW.responsible_user_id,
       NEW.temp_responsible_user_id,
       CURDATE(),
       CONCAT_WS('; ',
         IF(OLD.room_id <> NEW.room_id,
            CONCAT('Room ', OLD.room_id, '→', NEW.room_id), NULL),
         IF(OLD.responsible_user_id <> NEW.responsible_user_id,
            CONCAT('User ', OLD.responsible_user_id, '→', NEW.responsible_user_id), NULL),
         IF(OLD.temp_responsible_user_id <> NEW.temp_responsible_user_id,
            CONCAT('TempUser ', OLD.temp_responsible_user_id, '→', NEW.temp_responsible_user_id), NULL)
       )
      );
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_equipment_before_insert` BEFORE INSERT ON `equipment` FOR EACH ROW BEGIN
  IF NEW.cost < 0 THEN
    INSERT INTO `Errors` (`table_name`,`operation`,`error_type`,`error_desc`)
    VALUES (
      'equipment',
      'INSERT',
      'NEGATIVE_COST',
      CONCAT('Отрицательная стоимость: ', NEW.cost)
    );
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_equipment_before_update` BEFORE UPDATE ON `equipment` FOR EACH ROW BEGIN
  IF NEW.cost < 0 THEN
    INSERT INTO `Errors` (`table_name`,`operation`,`error_type`,`error_desc`)
    VALUES (
      'equipment',
      'UPDATE',
      'NEGATIVE_COST',
      CONCAT('Отрицательная стоимость при обновлении: ', NEW.cost)
    );
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `EquipmentMovementHistory`
--

CREATE TABLE `EquipmentMovementHistory` (
  `id` int NOT NULL,
  `equipment_id` int NOT NULL,
  `classroom_id` int NOT NULL,
  `responsible_user_id` int DEFAULT NULL,
  `temp_responsible_user_id` int DEFAULT NULL,
  `movement_date` date NOT NULL,
  `comment` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `EquipmentMovementHistory`
--

INSERT INTO `EquipmentMovementHistory` (`id`, `equipment_id`, `classroom_id`, `responsible_user_id`, `temp_responsible_user_id`, `movement_date`, `comment`) VALUES
(1, 1, 2, 3, NULL, '2025-05-23', 'Первоначальный импорт из таблицы оборудования. Оборудование: Проектор A, инвентарный номер: INV-001'),
(2, 2, 1, 2, NULL, '2025-05-23', 'Первоначальный импорт из таблицы оборудования. Оборудование: Ноутбук A, инвентарный номер: INV-002');

-- --------------------------------------------------------

--
-- Структура таблицы `equipment_consumables`
--

CREATE TABLE `equipment_consumables` (
  `equipment_id` int NOT NULL,
  `consumable_id` int NOT NULL,
  `quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `equipment_consumables`
--

INSERT INTO `equipment_consumables` (`equipment_id`, `consumable_id`, `quantity`) VALUES
(2, 1, 100);

-- --------------------------------------------------------

--
-- Структура таблицы `equipment_programs`
--

CREATE TABLE `equipment_programs` (
  `equipment_id` int NOT NULL,
  `program_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `equipment_programs`
--

INSERT INTO `equipment_programs` (`equipment_id`, `program_id`) VALUES
(2, 1),
(1, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `equipment_types`
--

CREATE TABLE `equipment_types` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `equipment_types`
--

INSERT INTO `equipment_types` (`id`, `name`) VALUES
(1, 'Ноутбук'),
(2, 'Проектор');

-- --------------------------------------------------------

--
-- Структура таблицы `Errors`
--

CREATE TABLE `Errors` (
  `error_id` int NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `operation` enum('INSERT','UPDATE','DELETE') NOT NULL,
  `error_type` varchar(64) NOT NULL,
  `error_desc` text,
  `error_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Errors`
--

INSERT INTO `Errors` (`error_id`, `table_name`, `operation`, `error_type`, `error_desc`, `error_timestamp`) VALUES
(1, 'equipment', 'INSERT', 'NEGATIVE_COST', 'Отрицательная стоимость: -100.00', '2025-05-22 21:11:15');

-- --------------------------------------------------------

--
-- Структура таблицы `Inventory`
--

CREATE TABLE `Inventory` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Inventory`
--

INSERT INTO `Inventory` (`id`, `name`, `start_date`, `end_date`, `created_by`) VALUES
(1, 'Первичная инвентаризация', '2025-05-01', '2025-05-02', 1);

--
-- Триггеры `Inventory`
--
DELIMITER $$
CREATE TRIGGER `trg_inventory_sessions_before_insert` BEFORE INSERT ON `Inventory` FOR EACH ROW BEGIN
  IF NEW.start_date > NEW.end_date THEN
    INSERT INTO `Errors` (`table_name`,`operation`,`error_type`,`error_desc`)
    VALUES (
      'inventory_sessions',
      'INSERT',
      'INVALID_DATE_RANGE',
      CONCAT('Дата начала ', NEW.start_date, ' позже даты окончания ', NEW.end_date)
    );
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_inventory_sessions_before_update` BEFORE UPDATE ON `Inventory` FOR EACH ROW BEGIN
  IF NEW.start_date > NEW.end_date THEN
    INSERT INTO `Errors` (`table_name`,`operation`,`error_type`,`error_desc`)
    VALUES (
      'inventory_sessions',
      'UPDATE',
      'INVALID_DATE_RANGE',
      CONCAT('Дата начала при обновлении ', NEW.start_date, ' позже даты окончания ', NEW.end_date)
    );
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `inventoryResults`
--

CREATE TABLE `inventoryResults` (
  `id` int NOT NULL,
  `session_id` int NOT NULL,
  `equipment_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `checked_by` int DEFAULT NULL,
  `check_date` datetime DEFAULT NULL,
  `status_id` int DEFAULT NULL,
  `comment` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `inventoryResults`
--

INSERT INTO `inventoryResults` (`id`, `session_id`, `equipment_id`, `user_id`, `checked_by`, `check_date`, `status_id`, `comment`) VALUES
(1, 1, 1, 2, 2, '2025-05-23 14:41:28', 1, 'Проверено, всё в порядке'),
(2, 1, 2, 3, 2, '2025-05-23 14:41:28', 1, 'Проверено, всё в порядке');

--
-- Триггеры `inventoryResults`
--
DELIMITER $$
CREATE TRIGGER `trg_inventoryresults_teacher_check` BEFORE INSERT ON `inventoryResults` FOR EACH ROW BEGIN
    IF NEW.checked_by IS NOT NULL AND 
       (SELECT `role` FROM `users` WHERE `id` = NEW.checked_by) != 'teacher' THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Only users with teacher role can be assigned as checked_by';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_inventoryresults_teacher_check_update` BEFORE UPDATE ON `inventoryResults` FOR EACH ROW BEGIN
    IF NEW.checked_by IS NOT NULL AND 
       (SELECT `role` FROM `users` WHERE `id` = NEW.checked_by) != 'teacher' THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Only users with teacher role can be assigned as checked_by';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `models`
--

CREATE TABLE `models` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `equipment_type_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `models`
--

INSERT INTO `models` (`id`, `name`, `equipment_type_id`) VALUES
(1, 'Dell XPS 13', 1),
(2, 'Epson X200', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `network_settings`
--

CREATE TABLE `network_settings` (
  `id` int NOT NULL,
  `equipment_id` int NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `subnet_mask` varchar(15) NOT NULL,
  `gateway` varchar(15) DEFAULT NULL,
  `dns_servers` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `network_settings`
--

INSERT INTO `network_settings` (`id`, `equipment_id`, `ip_address`, `subnet_mask`, `gateway`, `dns_servers`) VALUES
(1, 2, '192.168.1.10', '255.255.255.0', '192.168.1.1', '8.8.8.8'),
(2, 1, '192.168.1.11', '255', '192.168.1.1', '');

-- --------------------------------------------------------

--
-- Структура таблицы `programs`
--

CREATE TABLE `programs` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `version` varchar(100) DEFAULT NULL,
  `developer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `programs`
--

INSERT INTO `programs` (`id`, `name`, `version`, `developer`) VALUES
(1, 'Microsoft Word', '2019', 'Microsoft'),
(2, 'Adobe Acrobat', '2020', 'Adobe'),
(4, 'test2', 'tes', 'test2');

-- --------------------------------------------------------

--
-- Структура таблицы `statuses`
--

CREATE TABLE `statuses` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `statuses`
--

INSERT INTO `statuses` (`id`, `name`) VALUES
(1, 'В использовании'),
(2, 'На обслуживании'),
(3, 'Списано'),
(4, 'Починка'),
(5, 'Сломанно');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `login` varchar(100) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `role` enum('administrator','teacher','staff') NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `role`, `email`, `last_name`, `first_name`, `middle_name`, `phone`, `address`) VALUES
(1, 'admin', 'pbkdf2:sha256:150000$abc$def', 'administrator', 'admin@example.com', 'Иванов', 'Иван', 'Иванович', '+70000000000', '123 Main St'),
(2, 'teacher1', 'pbkdf2:sha256:150000$ghi$jkl', 'teacher', 'teacher1@example.com', 'Петров', 'Пётр', 'Петрович', '+70000000001', '124 Main St'),
(3, 'staff1', 'pbkdf2:sha256:150000$mno$pqr', 'staff', 'staff1@example.com', 'Сидоров', 'Сидор', 'Сидорович', '+70000000002', '125 Main St');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Classrooms`
--
ALTER TABLE `Classrooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `fk_rooms_responsible_user` (`responsible_user_id`),
  ADD KEY `fk_rooms_temp_responsible_user` (`temp_responsible_user_id`),
  ADD KEY `idx_rooms_short_name` (`short_name`);

--
-- Индексы таблицы `Consumables`
--
ALTER TABLE `Consumables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_consumables_responsible_user` (`responsible_user_id`),
  ADD KEY `fk_consumables_temp_responsible_user` (`temp_responsible_user_id`),
  ADD KEY `fk_consumables_type` (`consumable_type_id`),
  ADD KEY `idx_consumables_name` (`name`);

--
-- Индексы таблицы `consumable_characteristics`
--
ALTER TABLE `consumable_characteristics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_conschar_consumable` (`consumable_id`),
  ADD KEY `idx_conschar_name` (`characteristic_name`);

--
-- Индексы таблицы `consumable_types`
--
ALTER TABLE `consumable_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `directions`
--
ALTER TABLE `directions`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventory_number` (`inventory_number`),
  ADD KEY `fk_equipment_room` (`classroom_id`),
  ADD KEY `fk_equipment_responsible_user` (`responsible_user_id`),
  ADD KEY `fk_equipment_temp_responsible_user` (`temp_responsible_user_id`),
  ADD KEY `fk_equipment_direction` (`direction_id`),
  ADD KEY `fk_equipment_status` (`status_id`),
  ADD KEY `fk_equipment_model` (`model_id`),
  ADD KEY `idx_equipment_name` (`name`);

--
-- Индексы таблицы `EquipmentMovementHistory`
--
ALTER TABLE `EquipmentMovementHistory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_emhm_equipment` (`equipment_id`),
  ADD KEY `fk_emhm_room` (`classroom_id`),
  ADD KEY `fk_emhm_responsible_user` (`responsible_user_id`),
  ADD KEY `fk_emhm_temp_responsible_user` (`temp_responsible_user_id`);

--
-- Индексы таблицы `equipment_consumables`
--
ALTER TABLE `equipment_consumables`
  ADD PRIMARY KEY (`equipment_id`,`consumable_id`),
  ADD KEY `fk_equipcons_consumable` (`consumable_id`);

--
-- Индексы таблицы `equipment_programs`
--
ALTER TABLE `equipment_programs`
  ADD PRIMARY KEY (`equipment_id`,`program_id`),
  ADD KEY `fk_equipprog_program` (`program_id`);

--
-- Индексы таблицы `equipment_types`
--
ALTER TABLE `equipment_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `Errors`
--
ALTER TABLE `Errors`
  ADD PRIMARY KEY (`error_id`);

--
-- Индексы таблицы `Inventory`
--
ALTER TABLE `Inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_inventory_created_by` (`created_by`);

--
-- Индексы таблицы `inventoryResults`
--
ALTER TABLE `inventoryResults`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_invrec_equipment` (`equipment_id`),
  ADD KEY `fk_invrec_user` (`user_id`),
  ADD KEY `idx_invrec_session_equipment` (`session_id`,`equipment_id`),
  ADD KEY `fk_inventoryresults_checked_by` (`checked_by`),
  ADD KEY `fk_inventoryresults_status` (`status_id`);

--
-- Индексы таблицы `models`
--
ALTER TABLE `models`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_models_equipment_type` (`equipment_type_id`);

--
-- Индексы таблицы `network_settings`
--
ALTER TABLE `network_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_netsettings_equipment` (`equipment_id`),
  ADD KEY `idx_network_ip` (`ip_address`);

--
-- Индексы таблицы `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `statuses`
--
ALTER TABLE `statuses`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD KEY `idx_users_last_first` (`last_name`,`first_name`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `Classrooms`
--
ALTER TABLE `Classrooms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `Consumables`
--
ALTER TABLE `Consumables`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `consumable_characteristics`
--
ALTER TABLE `consumable_characteristics`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `consumable_types`
--
ALTER TABLE `consumable_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `directions`
--
ALTER TABLE `directions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `equipment`
--
ALTER TABLE `equipment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `EquipmentMovementHistory`
--
ALTER TABLE `EquipmentMovementHistory`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `equipment_types`
--
ALTER TABLE `equipment_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `Errors`
--
ALTER TABLE `Errors`
  MODIFY `error_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `Inventory`
--
ALTER TABLE `Inventory`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `inventoryResults`
--
ALTER TABLE `inventoryResults`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `models`
--
ALTER TABLE `models`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `network_settings`
--
ALTER TABLE `network_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `programs`
--
ALTER TABLE `programs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `statuses`
--
ALTER TABLE `statuses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `Classrooms`
--
ALTER TABLE `Classrooms`
  ADD CONSTRAINT `fk_rooms_responsible_user` FOREIGN KEY (`responsible_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_rooms_temp_responsible_user` FOREIGN KEY (`temp_responsible_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `Consumables`
--
ALTER TABLE `Consumables`
  ADD CONSTRAINT `fk_consumables_responsible_user` FOREIGN KEY (`responsible_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_consumables_temp_responsible_user` FOREIGN KEY (`temp_responsible_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_consumables_type` FOREIGN KEY (`consumable_type_id`) REFERENCES `consumable_types` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `consumable_characteristics`
--
ALTER TABLE `consumable_characteristics`
  ADD CONSTRAINT `fk_conschar_consumable` FOREIGN KEY (`consumable_id`) REFERENCES `Consumables` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `equipment`
--
ALTER TABLE `equipment`
  ADD CONSTRAINT `fk_equipment_direction` FOREIGN KEY (`direction_id`) REFERENCES `directions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_equipment_model` FOREIGN KEY (`model_id`) REFERENCES `models` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_equipment_responsible_user` FOREIGN KEY (`responsible_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_equipment_room` FOREIGN KEY (`classroom_id`) REFERENCES `Classrooms` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_equipment_status` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_equipment_temp_responsible_user` FOREIGN KEY (`temp_responsible_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `EquipmentMovementHistory`
--
ALTER TABLE `EquipmentMovementHistory`
  ADD CONSTRAINT `fk_emhm_equipment` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_emhm_responsible_user` FOREIGN KEY (`responsible_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_emhm_room` FOREIGN KEY (`classroom_id`) REFERENCES `Classrooms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_emhm_temp_responsible_user` FOREIGN KEY (`temp_responsible_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `equipment_consumables`
--
ALTER TABLE `equipment_consumables`
  ADD CONSTRAINT `fk_equipcons_consumable` FOREIGN KEY (`consumable_id`) REFERENCES `Consumables` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_equipcons_equipment` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `equipment_programs`
--
ALTER TABLE `equipment_programs`
  ADD CONSTRAINT `fk_equipprog_equipment` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_equipprog_program` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `Inventory`
--
ALTER TABLE `Inventory`
  ADD CONSTRAINT `fk_inventory_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `inventoryResults`
--
ALTER TABLE `inventoryResults`
  ADD CONSTRAINT `fk_inventoryresults_checked_by` FOREIGN KEY (`checked_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_inventoryresults_status` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_invrec_equipment` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_invrec_session` FOREIGN KEY (`session_id`) REFERENCES `Inventory` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_invrec_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `models`
--
ALTER TABLE `models`
  ADD CONSTRAINT `fk_models_equipment_type` FOREIGN KEY (`equipment_type_id`) REFERENCES `equipment_types` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `network_settings`
--
ALTER TABLE `network_settings`
  ADD CONSTRAINT `fk_netsettings_equipment` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
