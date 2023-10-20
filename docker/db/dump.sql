CREATE TABLE `language` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(32) NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `translation` (
   `id` int NOT NULL AUTO_INCREMENT,
   `language_id` int NOT NULL,
   `phrase` varchar(255) NOT NULL,
   `translation` varchar(255) NOT NULL,
   PRIMARY KEY (`id`),
   KEY `IDX_LANGUAGE_ID` (`language_id`),
   CONSTRAINT `FK_CONFERENCE_ID` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`)
);

INSERT INTO `language` (`id`, `name`) VALUES
(1, 'French'),
(2, 'German'),
(3, 'Spanish');

INSERT INTO `translation` (`id`, `language_id`, `phrase`, `translation`) VALUES
(1, 1, 'hello', 'bonjour'),
(2, 2, 'hello', 'hallo'),
(3, 3, 'hello', 'hola');