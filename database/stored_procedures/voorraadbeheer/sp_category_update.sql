-- Voorraadbeheer - Update category
-- Enforces unique naam (excluding current record).
-- Usage: CALL sp_category_update(1, 'Nieuwe naam');

DROP PROCEDURE IF EXISTS sp_category_update;
DELIMITER $$
CREATE PROCEDURE sp_category_update(
    IN p_id INT UNSIGNED,
    IN p_naam VARCHAR(100)
)
BEGIN
    DECLARE v_cnt INT DEFAULT 0;

    IF p_naam IS NULL OR TRIM(p_naam) = '' THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Categorienaam is verplicht';
    END IF;

    SELECT COUNT(*) INTO v_cnt
    FROM product_categorieen
    WHERE id <> p_id AND naam = p_naam;

    IF v_cnt > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Categorie bestaat al';
    END IF;

    UPDATE product_categorieen
    SET
        naam = p_naam,
        datum_gewijzigd = CURRENT_TIMESTAMP(6)
    WHERE id = p_id;

    SELECT ROW_COUNT() AS affected;
END$$
DELIMITER ;

