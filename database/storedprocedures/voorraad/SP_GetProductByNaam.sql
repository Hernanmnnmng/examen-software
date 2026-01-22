DROP PROCEDURE IF EXISTS SP_GetProductByNaam;
DELIMITER $$

CREATE PROCEDURE SP_GetProductByNaam(
    IN p_product_naam VARCHAR(255)
)
BEGIN
    SELECT COUNT(*) AS totaal
    FROM producten
    WHERE product_naam COLLATE utf8mb4_unicode_ci = p_product_naam COLLATE utf8mb4_unicode_ci
    AND is_actief = 1;
END$$

DELIMITER ;
