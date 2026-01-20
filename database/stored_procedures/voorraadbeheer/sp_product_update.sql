-- Voorraadbeheer - Update product
-- Enforces unique product_naam and ean (excluding current record).
-- Usage: CALL sp_product_update(1, 'Melk', '1234567890123', 3, 25);

DROP PROCEDURE IF EXISTS sp_product_update;
DELIMITER $$
CREATE PROCEDURE sp_product_update(
    IN p_id INT UNSIGNED,
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
    WHERE id <> p_id AND (product_naam = p_product_naam OR ean = p_ean);

    IF v_cnt > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Productnaam of EAN code al bestaat';
    END IF;

    UPDATE producten
    SET
        product_naam = p_product_naam,
        ean = p_ean,
        categorie_id = p_categorie_id,
        aantal_voorraad = p_aantal_voorraad,
        datum_gewijzigd = CURRENT_TIMESTAMP(6)
    WHERE id = p_id;

    SELECT ROW_COUNT() AS affected;
END$$
DELIMITER ;

