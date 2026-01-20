-- Voorraadbeheer - Create category
-- Enforces unique naam.
-- Usage: CALL sp_category_create('Nieuwe categorie');

DROP PROCEDURE IF EXISTS sp_category_create;
DELIMITER $$
CREATE PROCEDURE sp_category_create(IN p_naam VARCHAR(100))
BEGIN
    DECLARE v_cnt INT DEFAULT 0;

    IF p_naam IS NULL OR TRIM(p_naam) = '' THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Categorienaam is verplicht';
    END IF;

    SELECT COUNT(*) INTO v_cnt
    FROM product_categorieen
    WHERE naam = p_naam;

    IF v_cnt > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Categorie bestaat al';
    END IF;

    INSERT INTO product_categorieen (naam, is_actief, datum_aangemaakt, datum_gewijzigd)
    VALUES (p_naam, 1, CURRENT_TIMESTAMP(6), CURRENT_TIMESTAMP(6));

    SELECT LAST_INSERT_ID() AS id;
END$$
DELIMITER ;

