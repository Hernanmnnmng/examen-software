DROP PROCEDURE IF EXISTS SP_GetAllCategorieen;
DELIMITER $$

CREATE PROCEDURE SP_GetAllCategorieen()
BEGIN
    SELECT 
        id,
        naam,
        is_actief,
        opmerking
    FROM product_categorieen
    WHERE is_actief = 1
    ORDER BY naam ASC;
END$$

DELIMITER ;
