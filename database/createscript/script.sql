SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

USE Voedselbank;

-- --------------------------------------------------------
-- 0. TABELLEN VERWIJDEREN
-- --------------------------------------------------------

DROP TABLE IF EXISTS
    VoedselpakketProducten,
    Voedselpakketten,
    LeveringProducten,
    Leveringen,
    ProductAllergenen,
    ProductKenmerken,
    Producten,
    KlantAllergenen,
    KlantWensen,
    Klanten,
    Gezinnen,
    Leveranciers,
    Contactpersonen,
    Allergenen,
    Wensen,
    ProductCategorieen,
    Adressen,
    Users,
    Roles;
    
DROP TABLE IF EXISTS
    voedselpakket_producten,
    voedselpakketten,
    levering_producten,
    leveringen,
    product_allergenen,
    product_kenmerken,
    producten,
    klant_allergenen,
    klant_wensen,
    klanten,
    gezinnen,
    leveranciers,
    contactpersonen,
    allergenen,
    wensen,
    product_categorieen,
    adressen,
    users,
    roles;


-- --------------------------------------------------------
-- 1. AUTHENTICATIE (Laravel Standaard)
-- --------------------------------------------------------
CREATE TABLE users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  role varchar(30) NOT NULL,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  email_verified_at TIMESTAMP NULL DEFAULT NULL,
  password VARCHAR(255) NOT NULL,
  remember_token VARCHAR(100) NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY users_email_unique (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 2. STAMGEGEVENS
-- --------------------------------------------------------

CREATE TABLE adressen (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  straat VARCHAR(255) NOT NULL,
  huisnummer VARCHAR(20) NOT NULL,
  postcode CHAR(6) NOT NULL,
  plaats VARCHAR(100) NOT NULL,
  is_actief BIT(1) NOT NULL DEFAULT 1,
  opmerking VARCHAR(255) NULL,
  datum_aangemaakt DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  datum_gewijzigd DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE product_categorieen (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  naam VARCHAR(100) NOT NULL,
  is_actief BIT(1) NOT NULL DEFAULT 1,
  opmerking VARCHAR(255) NULL,
  datum_aangemaakt DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  datum_gewijzigd DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  PRIMARY KEY (id),
  UNIQUE KEY categorieen_naam_unique (naam)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE wensen (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  omschrijving VARCHAR(100) NOT NULL,
  is_actief BIT(1) NOT NULL DEFAULT 1,
  opmerking VARCHAR(255) NULL,
  datum_aangemaakt DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  datum_gewijzigd DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  PRIMARY KEY (id),
  UNIQUE KEY wensen_omschrijving_unique (omschrijving)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE allergenen (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  naam VARCHAR(100) NOT NULL,
  is_actief BIT(1) NOT NULL DEFAULT 1,
  opmerking VARCHAR(255) NULL,
  datum_aangemaakt DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  datum_gewijzigd DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  PRIMARY KEY (id),
  UNIQUE KEY allergenen_naam_unique (naam)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 3. RELATIES & PARTIJEN
-- --------------------------------------------------------

CREATE TABLE contactpersonen (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  contact_naam VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  telefoon VARCHAR(20) NOT NULL,
  is_actief BIT(1) NOT NULL DEFAULT 1,
  opmerking VARCHAR(255) NULL,
  datum_aangemaakt DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  datum_gewijzigd DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE leveranciers (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  bedrijfsnaam VARCHAR(255) NOT NULL,
  adres_id INT UNSIGNED NOT NULL,
  contactpersoon_id INT UNSIGNED NOT NULL,
  is_actief BIT(1) NOT NULL DEFAULT 1,
  opmerking VARCHAR(255) NULL,
  datum_aangemaakt DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  datum_gewijzigd DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  PRIMARY KEY (id),
  CONSTRAINT fk_leverancier_adres FOREIGN KEY (adres_id) REFERENCES adressen (id),
  CONSTRAINT fk_leverancier_contact FOREIGN KEY (contactpersoon_id) REFERENCES contactpersonen (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE gezinnen (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  gezins_naam VARCHAR(255) NOT NULL,
  volwassenen TINYINT UNSIGNED NOT NULL DEFAULT 0,
  kinderen TINYINT UNSIGNED NOT NULL DEFAULT 0,
  babys TINYINT UNSIGNED NOT NULL DEFAULT 0,
  is_actief BIT(1) NOT NULL DEFAULT 1,
  opmerking VARCHAR(255) NULL,
  datum_aangemaakt DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  datum_gewijzigd DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE klanten (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  naam VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  telefoon VARCHAR(20) NOT NULL,
  adres_id INT UNSIGNED NOT NULL,
  gezin_id INT UNSIGNED NOT NULL,
  is_actief BIT(1) NOT NULL DEFAULT 1,
  opmerking VARCHAR(255) NULL,
  datum_aangemaakt DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  datum_gewijzigd DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  PRIMARY KEY (id),
  UNIQUE KEY klanten_email_unique (email),
  CONSTRAINT fk_klanten_adres FOREIGN KEY (adres_id) REFERENCES adressen (id),
  CONSTRAINT fk_klanten_gezin FOREIGN KEY (gezin_id) REFERENCES gezinnen (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 4. PRODUCTEN & VOORRAAD
-- --------------------------------------------------------

CREATE TABLE producten (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  product_naam VARCHAR(255) NOT NULL,
  ean CHAR(13) NOT NULL,
  categorie_id INT UNSIGNED NOT NULL,
  aantal_voorraad INT UNSIGNED NOT NULL DEFAULT 0,
  is_actief BIT(1) NOT NULL DEFAULT 1,
  opmerking VARCHAR(255) NULL,
  datum_aangemaakt DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  datum_gewijzigd DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  PRIMARY KEY (id),
  UNIQUE KEY product_naam_unique (product_naam),
  UNIQUE KEY product_ean_unique (ean),
  CONSTRAINT fk_producten_categorie FOREIGN KEY (categorie_id) REFERENCES product_categorieen (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE product_kenmerken (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  product_id INT UNSIGNED NOT NULL,
  wens_id INT UNSIGNED NOT NULL,
  is_actief BIT(1) NOT NULL DEFAULT 1,
  opmerking VARCHAR(255) NULL,
  datum_aangemaakt DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  datum_gewijzigd DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  PRIMARY KEY (id),
  CONSTRAINT fk_pk_product FOREIGN KEY (product_id) REFERENCES producten (id),
  CONSTRAINT fk_pk_wens FOREIGN KEY (wens_id) REFERENCES wensen (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE product_allergenen (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  product_id INT UNSIGNED NOT NULL,
  allergie_id INT UNSIGNED NOT NULL,
  is_actief BIT(1) NOT NULL DEFAULT 1,
  opmerking VARCHAR(255) NULL,
  datum_aangemaakt DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  datum_gewijzigd DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  PRIMARY KEY (id),
  CONSTRAINT fk_pa_product FOREIGN KEY (product_id) REFERENCES producten (id),
  CONSTRAINT fk_pa_allergie FOREIGN KEY (allergie_id) REFERENCES allergenen (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 5. PROCES: LEVERINGEN & PAKKETTEN
-- --------------------------------------------------------

CREATE TABLE leveringen (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  leverancier_id INT UNSIGNED NOT NULL,
  leverdatum_tijd DATETIME(6) NOT NULL,
  eerstvolgende_levering DATETIME(6) NOT NULL,
  is_actief BIT(1) NOT NULL DEFAULT 1,
  opmerking VARCHAR(255) NULL,
  datum_aangemaakt DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  datum_gewijzigd DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  PRIMARY KEY (id),
  CONSTRAINT fk_leveringen_lev FOREIGN KEY (leverancier_id) REFERENCES leveranciers (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE levering_producten (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  levering_id INT UNSIGNED NOT NULL,
  product_id INT UNSIGNED NOT NULL,
  aantal INT UNSIGNED NOT NULL,
  is_actief BIT(1) NOT NULL DEFAULT 1,
  opmerking VARCHAR(255) NULL,
  datum_aangemaakt DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  datum_gewijzigd DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  PRIMARY KEY (id),
  CONSTRAINT fk_lp_levering FOREIGN KEY (levering_id) REFERENCES leveringen (id),
  CONSTRAINT fk_lp_product FOREIGN KEY (product_id) REFERENCES producten (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE voedselpakketten (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  klant_id INT UNSIGNED NOT NULL,
  datum_samenstelling DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  datum_uitgifte DATETIME(6) NULL,
  is_actief BIT(1) NOT NULL DEFAULT 1,
  opmerking VARCHAR(255) NULL,
  datum_aangemaakt DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  datum_gewijzigd DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  PRIMARY KEY (id),
  CONSTRAINT fk_pakket_klant FOREIGN KEY (klant_id) REFERENCES klanten (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE voedselpakket_producten (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  voedselpakket_id INT UNSIGNED NOT NULL,
  product_id INT UNSIGNED NOT NULL,
  aantal TINYINT UNSIGNED NOT NULL DEFAULT 1,
  is_actief BIT(1) NOT NULL DEFAULT 1,
  opmerking VARCHAR(255) NULL,
  datum_aangemaakt DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  datum_gewijzigd DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  PRIMARY KEY (id),
  CONSTRAINT fk_vpp_pakket FOREIGN KEY (voedselpakket_id) REFERENCES voedselpakketten (id),
  CONSTRAINT fk_vpp_product FOREIGN KEY (product_id) REFERENCES producten (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 6. KLANT SPECIFIEK
-- --------------------------------------------------------

CREATE TABLE klant_wensen (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  klant_id INT UNSIGNED NOT NULL,
  wens_id INT UNSIGNED NOT NULL,
  is_actief BIT(1) NOT NULL DEFAULT 1,
  opmerking VARCHAR(255) NULL,
  datum_aangemaakt DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  datum_gewijzigd DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  PRIMARY KEY (id),
  CONSTRAINT fk_kw_klant FOREIGN KEY (klant_id) REFERENCES klanten (id),
  CONSTRAINT fk_kw_wens FOREIGN KEY (wens_id) REFERENCES wensen (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE klant_allergenen (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  klant_id INT UNSIGNED NOT NULL,
  allergie_id INT UNSIGNED NOT NULL,
  is_actief BIT(1) NOT NULL DEFAULT 1,
  opmerking VARCHAR(255) NULL,
  datum_aangemaakt DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  datum_gewijzigd DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  PRIMARY KEY (id),
  CONSTRAINT fk_ka_klant FOREIGN KEY (klant_id) REFERENCES klanten (id),
  CONSTRAINT fk_ka_allergie FOREIGN KEY (allergie_id) REFERENCES allergenen (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------
-- 7. TESTDATA TOEVOEGEN
-- --------------------------------------------------------

-- De data wordt nu direct ingevoerd met de rolnaam in plaats van een nummer
INSERT INTO users (id, role, name, email, password, created_at, updated_at) VALUES
(1, 'Directie', 'Directeur Jan', 'directie@voedselbank.nl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW()),
(2, 'Magazijnmedewerker', 'Kees Magazijn', 'magazijnmedewerker@voedselbank.nl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW()),
(3, 'Vrijwilliger', 'Marie Vrijwilliger', 'vrijwilliger@voedselbank.nl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW()),
(4, 'Vrijwilliger', 'Piet Vrijwilliger', 'piet@voedselbank.nl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW());

INSERT INTO adressen (id, straat, huisnummer, postcode, plaats, is_actief, opmerking) VALUES
(1, 'Industrieweg', '10', '5256PN', 'Maaskantje', 1, 'Hoofdvestiging leverancier'),
(2, 'Dorpsstraat', '5', '5256AA', 'Maaskantje', 1, 'Bakkerij locatie'),
(3, 'Kerkstraat', '12', '5261CR', 'Vught', 1, NULL),
(4, 'Stationstraat', '88-B', '5211AX', 'Den Bosch', 1, 'Moeilijk bereikbaar met bus'),
(5, 'De Laan', '101', '5256ZZ', 'Maaskantje', 1, NULL),
(6, 'Polderweg', '3', '5256CC', 'Maaskantje', 0, 'Oud adres');

INSERT INTO product_categorieen (id, naam, is_actief, opmerking) VALUES
(1, 'AGF (Aardappelen, Groente, Fruit)', 1, 'Versproducten'),
(2, 'Zuivel', 1, 'Gekoeld bewaren'),
(3, 'Brood & Banket', 1, 'Dagvers'),
(4, 'Houdbaar', 1, 'Blik, pot, droogwaren'),
(5, 'Vlees & Vis', 1, 'Diepvries');

INSERT INTO wensen (id, omschrijving, is_actief) VALUES
(1, 'Veganistisch', 1),
(2, 'Halal', 1),
(3, 'Geen varkensvlees', 1),
(4, 'Vegetarisch', 1);

INSERT INTO allergenen (id, naam, is_actief) VALUES
(1, 'Gluten', 1),
(2, 'Lactose', 1),
(3, 'Noten', 1),
(4, 'Schaaldieren', 1);

INSERT INTO contactpersonen (id, contact_naam, email, telefoon, is_actief) VALUES
(1, 'Bert de Bakker', 'bert@bakkerijbart.nl', '0612345678', 1),
(2, 'Sven de Boer', 'sven@jumbo.nl', '0687654321', 1),
(3, 'Boer Harms', 'info@boerharms.nl', '0735555555', 1),
(4, 'Oud Contact', 'weg@weg.nl', '00000000', 0);

INSERT INTO leveranciers (id, bedrijfsnaam, adres_id, contactpersoon_id, is_actief, opmerking) VALUES
(1, 'Bakkerij Bart', 2, 1, 1, 'Levert dagelijks brood'),
(2, 'Supermarkt Jumbo', 1, 2, 1, 'Grote leveringen op maandag'),
(3, 'Boerderij Harms', 1, 3, 1, 'Verse groenten'),
(4, 'Slagerij Henk', 2, 1, 0, 'Failliet gegaan');

INSERT INTO gezinnen (id, gezins_naam, volwassenen, kinderen, babys, is_actief) VALUES
(1, 'Fam. Jansen', 1, 0, 0, 1),
(2, 'Fam. de Vries', 2, 2, 0, 1),
(3, 'Fam. El Amrani', 2, 3, 1, 1),
(4, 'Fam. Pietersen', 1, 1, 0, 1);

INSERT INTO klanten (id, naam, email, telefoon, adres_id, gezin_id, is_actief) VALUES
(1, 'Jan Jansen', 'jan@hotmail.com', '0611111111', 3, 1, 1),
(2, 'Els de Vries', 'els@gmail.com', '0622222222', 4, 2, 1),
(3, 'Mo El Amrani', 'mo@live.nl', '0633333333', 5, 3, 1),
(4, 'Petra Pietersen', 'petra@yahoo.com', '0644444444', 3, 4, 1);

INSERT INTO producten (id, product_naam, ean, categorie_id, aantal_voorraad, is_actief) VALUES
(1, 'Volkoren Brood', '8710400000001', 3, 50, 1),
(2, 'Halfvolle Melk', '8710400000002', 2, 100, 1),
(3, 'Pindakaas', '8710400000003', 4, 200, 1),
(4, 'Aardbeienjam', '8710400000004', 4, 150, 1),
(5, 'Kipfilet', '8710400000005', 5, 20, 1),
(6, 'Appels (Zak)', '8710400000006', 1, 60, 1),
(7, 'Oude Kaas', '8710400000007', 2, 0, 1);

INSERT INTO product_kenmerken (product_id, wens_id) VALUES
(1, 1), (1, 2), (3, 1), (5, 2);

INSERT INTO product_allergenen (product_id, allergie_id) VALUES
(1, 1), (2, 2), (3, 3), (7, 2);

INSERT INTO leveringen (id, leverancier_id, leverdatum_tijd, eerstvolgende_levering, is_actief) VALUES
(1, 1, '2023-10-01 08:00:00', '2023-10-08 08:00:00', 1),
(2, 2, '2023-10-02 10:00:00', '2023-10-09 10:00:00', 1),
(3, 3, '2023-10-03 14:00:00', '2023-10-10 14:00:00', 1);

INSERT INTO levering_producten (levering_id, product_id, aantal) VALUES
(1, 1, 100), (2, 2, 200), (2, 3, 50), (3, 6, 100);

INSERT INTO voedselpakketten (id, klant_id, datum_samenstelling, datum_uitgifte) VALUES
(1, 1, '2023-10-05 09:00:00', '2023-10-05 16:00:00'),
(2, 2, '2023-10-05 09:15:00', NULL),
(3, 3, '2023-10-05 09:30:00', NULL);

INSERT INTO voedselpakket_producten (voedselpakket_id, product_id, aantal) VALUES
(1, 1, 1), (1, 2, 1), (1, 6, 1),
(2, 1, 2), (2, 2, 2), (2, 3, 1), (2, 6, 2),
(3, 1, 3), (3, 2, 3), (3, 4, 1), (3, 5, 2);

INSERT INTO klant_wensen (klant_id, wens_id) VALUES (3, 2);
INSERT INTO klant_allergenen (klant_id, allergie_id) VALUES (2, 3);

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
