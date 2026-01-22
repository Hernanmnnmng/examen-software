DROP PROCEDURE IF EXISTS SP_GetCategorieByNaam;
DELIMITER $$

CREATE PROCEDURE SP_GetCategorieByNaam(
    IN p_naam VARCHAR(100)
)
BEGIN
    SELECT COUNT(*) AS totaal
    FROM product_categorieen
    WHERE naam COLLATE utf8mb4_unicode_ci = p_naam COLLATE utf8mb4_unicode_ci
    AND is_actief = 1;
END$$

DELIMITER ;
