-- Voorraadbeheer - Create product
-- Enforces unique product_naam and ean and validates EAN length/digits.
-- Usage: CALL sp_product_create('Melk', '1234567890123', 3, 10);

DROP PROCEDURE IF EXISTS sp_product_create;
DELIMITER $$
CREATE PROCEDURE sp_product_create(
    IN p_product_naam VARCHAR(255),
    IN p_ean CHAR(13),
    IN p_categorie_id INT UNSIGNED,
    IN p_aantal_voorraad INT UNSIGNED
)
BEGIN
    DECLARE v_cnt INT DEFAULT 0;

    IF p_product_naam IS NULL OR TRIM(p_product_naam) = '' THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Productnaam is verplicht';
    END IF;

    IF p_ean IS NULL OR p_ean NOT REGEXP '^[0-9]{13}$' THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'EAN moet 13 cijfers zijn';
    END IF;

    SELECT COUNT(*) INTO v_cnt
    FROM producten
    WHERE product_naam = p_product_naam OR ean = p_ean;

    IF v_cnt > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Productnaam of EAN-code al bestaat';
    END IF;

    INSERT INTO producten (product_naam, ean, categorie_id, aantal_voorraad, is_actief, datum_aangemaakt, datum_gewijzigd)
    VALUES (p_product_naam, p_ean, p_categorie_id, p_aantal_voorraad, 1, CURRENT_TIMESTAMP(6), CURRENT_TIMESTAMP(6));

    SELECT LAST_INSERT_ID() AS id;
END$$
DELIMITER ;

