DROP PROCEDURE IF EXISTS SP_GetProductByEan;
DELIMITER $$

CREATE PROCEDURE SP_GetProductByEan(
    IN p_ean CHAR(13)
)
BEGIN
    SELECT COUNT(*) AS totaal
    FROM producten
    WHERE ean = p_ean
    AND is_actief = 1;
END$$

DELIMITER ;
