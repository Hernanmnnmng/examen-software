-- Voorraadbeheer - Category list
-- Usage: CALL sp_category_list();

DROP PROCEDURE IF EXISTS sp_category_list;
DELIMITER $$
CREATE PROCEDURE sp_category_list()
BEGIN
    SELECT id, naam, is_actief, opmerking
    FROM product_categorieen
    WHERE is_actief = 1
    ORDER BY naam ASC;
END$$
DELIMITER ;

