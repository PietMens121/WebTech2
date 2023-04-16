# WebTech2
Dit is het project van Erwin Veenhoven en Piet mens voor periode 3, Webtechnologie II. Hierond staat een uitleg hoe je de website kan starten.

<br>**Database aanmaken:**</br>

1. Onderaan deze readme staat een SQL dump voor een test database.
2. Maak een .env in de projectfolder aan met de database gegevens. Er staat ook een voorbeeld in het project genaamd: _.env.example_

<br>**Webserver starten:**</br>
1. Run "composer install" voor de PSR interfaces.
2. Run php serve in public folder om de server te starten met: `php -S localhost:8000 -t public` 

<br>**Navigatie website:**</br>

Je kan inloggen met de volgende accounts:

| Rol      | Gebruikersnaam | Wachtwoord |
|----------|----------------|------------|
| Leerling | Piet           | Mens       |
| Leraar   | Ralf           | Broek      |
| Admin    | Erwin          | Veenhoven  |

Je kan nieuwe accounts aanmaken als je admin bent.

<br>**Database dump:**</br>
```SQL
DROP TABLE IF EXISTS `exam_user`;
CREATE TABLE `exam_user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `exam_id` int DEFAULT NULL,
  `grade` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `Exams`;
CREATE TABLE `Exams` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `weight` int DEFAULT NULL,
  `abbreviation` varchar(255) DEFAULT NULL,
  `Assignee_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `Roles`;
CREATE TABLE `Roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `Users`;
CREATE TABLE `Users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `exam_user` (`id`, `user_id`, `exam_id`, `grade`) VALUES
(26, 1, 2, NULL),
(27, 20, 2, NULL),
(28, 20, 5, 5);

INSERT INTO `Exams` (`id`, `name`, `weight`, `abbreviation`, `Assignee_id`) VALUES
(1, 'Algoritmiek I', 1, 'ALG', NULL),
(2, 'Webtechnologie II', 5, 'WEB', 18),
(3, 'Object georienteerd programmeren', 5, 'OOP', NULL),
(4, 'Webtechnologie III', 5, 'WEB II', NULL),
(5, 'Algoritmiek II', 5, 'ALG II', 18),
(6, 'Project', 5, 'PRO', NULL),
(7, 'Project II', 5, 'PRO II', NULL);

INSERT INTO `Roles` (`id`, `name`) VALUES
(1, 'student'),
(2, 'lecturer'),
(3, 'admin');

INSERT INTO `Users` (`id`, `username`, `password`, `role_id`) VALUES
(18, 'Ralf', 'bcc6bf5cba08758d27efb5df5d2f2332816295074e16c81d14b1322cb2845f67', 2),
(19, 'Erwin', 'e700ba12d3816e55af9fc5e1e94e6a8786976cd8ece72cd0d48b9841dc9d7023', 3),
(20, 'Piet', '408ff7e062e2902c88aae884f71ad23c6d83d6d1cb3c12a68f7216e339a6e869', 1);
```