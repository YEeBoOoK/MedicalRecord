-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Дек 19 2022 г., 07:36
-- Версия сервера: 10.6.11-MariaDB-1:10.6.11+maria~ubu2004
-- Версия PHP: 8.1.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `638-19_MedicalRecord`
--

-- --------------------------------------------------------

--
-- Структура таблицы `appointment`
--

CREATE TABLE `appointment` (
  `id_appointment` int(11) NOT NULL,
  `id_patient` int(11) DEFAULT NULL,
  `id_doctor` int(11) NOT NULL,
  `acceptance_date` date NOT NULL,
  `acceptance_time` time NOT NULL,
  `id_clinic` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Дамп данных таблицы `appointment`
--

INSERT INTO `appointment` (`id_appointment`, `id_patient`, `id_doctor`, `acceptance_date`, `acceptance_time`, `id_clinic`) VALUES
(2, 2, 2, '2022-12-31', '00:00:00', 4),
(3, 10, 3, '2022-12-31', '00:00:00', 4),
(4, 10, 3, '2022-12-15', '11:00:00', 4),
(5, 15, 2, '2022-12-15', '13:00:00', 7),
(6, 15, 3, '2022-12-17', '17:30:00', 4),
(9, NULL, 3, '2022-12-30', '09:00:00', 4),
(10, NULL, 2, '2022-12-28', '12:00:00', 4);

-- --------------------------------------------------------

--
-- Структура таблицы `clinic`
--

CREATE TABLE `clinic` (
  `id_clinic` int(11) NOT NULL,
  `clinic_city` varchar(40) NOT NULL,
  `clinic_region` varchar(40) NOT NULL,
  `clinic_name` varchar(50) NOT NULL,
  `clinic_address` varchar(50) NOT NULL,
  `clinic_phone` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Дамп данных таблицы `clinic`
--

INSERT INTO `clinic` (`id_clinic`, `clinic_city`, `clinic_region`, `clinic_name`, `clinic_address`, `clinic_phone`) VALUES
(4, 'Санкт-Петербург', 'Приморский', 'Медкомиссия №1', ' Богатырский просп., 35, корп. 1', '+7 (812) 458-53-28'),
(7, 'Санкт-Петербург', 'Адмиралтейский', 'Городская поликлиника №27', 'пр-кт. Вознесенский, д. 27, лит. А', '+7 (812) 246-39-90');

-- --------------------------------------------------------

--
-- Структура таблицы `doctor`
--

CREATE TABLE `doctor` (
  `id_doctor` int(11) NOT NULL,
  `doctor_photo` varchar(255) DEFAULT NULL,
  `doctor_surname` varchar(40) NOT NULL,
  `doctor_name` varchar(40) NOT NULL,
  `doctor_patronymic` varchar(40) NOT NULL,
  `id_doctor_clinic` int(11) NOT NULL,
  `doctor_specialty` varchar(40) NOT NULL,
  `doctor_office` int(20) NOT NULL,
  `start_date` date NOT NULL,
  `weekday` varchar(30) NOT NULL,
  `office_hours` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Дамп данных таблицы `doctor`
--

INSERT INTO `doctor` (`id_doctor`, `doctor_photo`, `doctor_surname`, `doctor_name`, `doctor_patronymic`, `id_doctor_clinic`, `doctor_specialty`, `doctor_office`, `start_date`, `weekday`, `office_hours`) VALUES
(2, '', 'Абоба', 'Абоб', 'Абобовна', 4, 'Нерволог', 11, '2022-12-12', 'пн-пт', '09:00-18:00'),
(3, '', 'Федоров', 'Абоб', 'Абобовн', 4, 'Акула', 12, '2022-12-12', 'пн-пт', '09:00-18:00');

-- --------------------------------------------------------

--
-- Структура таблицы `patient`
--

CREATE TABLE `patient` (
  `id_patient` int(11) NOT NULL,
  `patient_surname` varchar(40) NOT NULL,
  `patient_name` varchar(40) NOT NULL,
  `patient_patronymic` varchar(40) NOT NULL,
  `patient_birthday` date NOT NULL,
  `patient_phone` varchar(20) NOT NULL,
  `patient_address` varchar(100) NOT NULL,
  `id_patient_clinic` int(11) NOT NULL,
  `ID_card` varchar(30) NOT NULL,
  `insurance` varchar(30) NOT NULL,
  `login` varchar(30) NOT NULL,
  `password` varchar(200) NOT NULL,
  `is_admin` int(2) NOT NULL DEFAULT 0,
  `token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Дамп данных таблицы `patient`
--

INSERT INTO `patient` (`id_patient`, `patient_surname`, `patient_name`, `patient_patronymic`, `patient_birthday`, `patient_phone`, `patient_address`, `id_patient_clinic`, `ID_card`, `insurance`, `login`, `password`, `is_admin`, `token`) VALUES
(2, 'Абобов', 'Абоба', 'Абобович', '2000-12-12', '+7(999)999-99-99', 'г. Санкт-Петербург, пр. Абоб, дом 1', 4, '123456789101', '1234567891011121', 'AA', 'AA2000', 0, '123'),
(3, 'Виталов', 'Мукалий', 'Владимирович', '1999-02-08', '+7(981)199-43-28', 'Боровая ул., 18, Санкт-Петербург, 191357', 4, '11111111111', '123456789123456', 'MukalovV', 'MV1999', 0, ''),
(4, 'Абобо', 'Абоба', 'Абобовна', '2000-12-12', '+7(999)999-99-97', 'г. Санкт-Петербург, пр. Або, дом 1', 4, '123456789111', '1234567891011111', 'AAA', 'AA2000', 0, '133'),
(7, 'AAA', 'Владимир', 'Петрович', '1999-02-08', '+7 (999) 999-99-99', 'Санкт-Петербург, Богатырский проспект, д7к3', 4, '111111111111', '1111111111111111', '1111', '$2y$13$pZz6VUmZFTn2jz1gYNMh/e/l3BGvDfbfSdrnZxa6oR48Rffb5kGMy', 0, NULL),
(8, 'AAA', 'Владимир', 'Петрович', '1999-02-08', '+7 (989) 999-99-99', 'Санкт-Петербург, Богатырский проспект, д7к3', 4, '1111111111', '1111111111111', '111', '$2y$13$sOmkQOaQP4b5yY.tIA0vAOtU6TW3/nD21IFLppuj52Ulm7zLSMYg2', 0, 'f_kdsTjCMJbXSJhJgW4j-PHk-DUa6wcH'),
(9, 'Алексеева', 'Admin', 'Admin', '2003-07-31', '+7 (963) 242-94-20', 'Санкт-Петербург, Богатырский проспект, д7к3', 4, '999999999999', '2222222222222222', 'admin', '$2y$13$5KWkVAhrcWxekyVW4QRGUuqv/Lt4xOlrqSujBVjmtK0.Tv17rsXIe', 1, 'R5NAGwYbwzUtgw1O8yy4sOKf2BnhZDs0'),
(10, 'Антонов', 'Антон', 'Антонович', '1980-11-21', '+7 (970) 200-95-20', 'Санкт-Петербург, Богатырский проспект, д7к3', 4, '9999999999999', '222222222222222', 'antonio', '$2y$13$ZsfTDlB5nVgAfSWS4oebueTIsDk5YywoLLDqb4am2Kyvijvji/i7G', 0, 'mTF9Q3dU0BqVQI3xxq9dpvwjpYIQKKm5'),
(15, 'Немынова', 'Анастасия', 'Антонович', '1980-11-21', '+7 (971) 200-95-20', 'Санкт-Петербург, Богатырский проспект, д7к3', 4, '9999999999', '222222222222', 'anast', '$2y$13$vaq0K1nD840h5shys1dETOWK1pleKBkTQ9GBb2sl3Pic9NGnr0Gxu', 0, 'i6zdY8xi-BUsdtYJLskWh4u23_l0Y4KF'),
(16, 'Немынова', 'Анастасия', 'Антонович', '1980-11-21', '+7 (977) 200-95-20', 'Санкт-Петербург, Богатырский проспект, д7к3', 4, '999999999', '22222222222', 'anastas', '$2y$13$lpiUtlaaWSD2rkHjwEzS9OFNHgxzN2o.02e6yi2OGekpenstgjhaG', 0, NULL),
(17, 'a', 'a', 'a', '1970-11-21', '+7 (974) 200-95-20', 'Санкт-Петербург, Богатырский проспект, д7к3', 4, '999999499', '22222122222', 'aaaaa', '$2y$13$4nGECXn1/IVZZ/Q4D3CUOOGuoNcg.B3O/.92hZ6HclTt5k2pEUxEe', 0, 'uVIJhUX73aEYj01AiAu1SwjcPhVAHx14'),
(18, '1', '2', '3', '1970-11-21', '+7 (977) 277-95-20', 'Санкт-Петербург, Богатырский проспект, д7к3', 4, '99999999979', '2222322222222', 'a3nast', '$2y$13$L64zJNyVgBgIcmFQS1KMSe7CPatjNgR95u/04SVllDhwae7lFPtGG', 0, NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`id_appointment`),
  ADD KEY `appointment_ibfk_1` (`id_patient`),
  ADD KEY `appointment_ibfk_2` (`id_clinic`),
  ADD KEY `appointment_ibfk_3` (`id_doctor`);

--
-- Индексы таблицы `clinic`
--
ALTER TABLE `clinic`
  ADD PRIMARY KEY (`id_clinic`);

--
-- Индексы таблицы `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`id_doctor`),
  ADD KEY `doctor_ibfk_1` (`id_doctor_clinic`);

--
-- Индексы таблицы `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`id_patient`),
  ADD UNIQUE KEY `ID_card` (`ID_card`),
  ADD UNIQUE KEY `insurance` (`insurance`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `patient_phone` (`patient_phone`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `patient_ibfk_1` (`id_patient_clinic`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `appointment`
--
ALTER TABLE `appointment`
  MODIFY `id_appointment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `clinic`
--
ALTER TABLE `clinic`
  MODIFY `id_clinic` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `doctor`
--
ALTER TABLE `doctor`
  MODIFY `id_doctor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `patient`
--
ALTER TABLE `patient`
  MODIFY `id_patient` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`id_patient`) REFERENCES `patient` (`id_patient`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `appointment_ibfk_2` FOREIGN KEY (`id_clinic`) REFERENCES `clinic` (`id_clinic`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `appointment_ibfk_3` FOREIGN KEY (`id_doctor`) REFERENCES `doctor` (`id_doctor`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `doctor`
--
ALTER TABLE `doctor`
  ADD CONSTRAINT `doctor_ibfk_1` FOREIGN KEY (`id_doctor_clinic`) REFERENCES `clinic` (`id_clinic`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `patient`
--
ALTER TABLE `patient`
  ADD CONSTRAINT `patient_ibfk_1` FOREIGN KEY (`id_patient_clinic`) REFERENCES `clinic` (`id_clinic`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
