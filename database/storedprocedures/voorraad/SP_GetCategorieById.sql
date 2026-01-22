DROP PROCEDURE IF EXISTS SP_GetCategorieById;
DELIMITER $$

CREATE PROCEDURE SP_GetCategorieById(
    IN p_id INT UNSIGNED
)
BEGIN
    SELECT 
        id,
        naam,
        is_actief,
        opmerking
    FROM product_categorieen
    WHERE id = p_id AND is_actief = 1
    LIMIT 1;
END$$

DELIMITER ;
