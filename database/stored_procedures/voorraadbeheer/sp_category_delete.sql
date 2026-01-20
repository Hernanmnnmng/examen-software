-- Voorraadbeheer - Delete category (only if no products are linked)
-- Usage: CALL sp_category_delete(1);

DROP PROCEDURE IF EXISTS sp_category_delete;
DELIMITER $$
CREATE PROCEDURE sp_category_delete(IN p_id INT UNSIGNED)
BEGIN
    DECLARE v_cnt INT DEFAULT 0;

    SELECT COUNT(*) INTO v_cnt
    FROM producten
    WHERE categorie_id = p_id;

    IF v_cnt > 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Categorie kan niet worden verwijderd, er zijn producten aan gekoppeld';
    END IF;

    DELETE FROM product_categorieen WHERE id = p_id;
    SELECT ROW_COUNT() AS affected;
END$$
DELIMITER ;

