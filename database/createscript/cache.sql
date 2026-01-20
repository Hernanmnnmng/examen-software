CREATE TABLE `cache` (
  `key` VARCHAR(255) NOT NULL,
  `value` MEDIUMTEXT NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Directie: directie@voedselbank.nl
-- Magazijnmedewerker: magazijnmedewerker@voedselbank.nl
-- Vrijwilliger: vrijwilliger@voedselbank.nl (or piet@voedselbank.nl)