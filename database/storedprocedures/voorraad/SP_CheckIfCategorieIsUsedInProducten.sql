DROP PROCEDURE IF EXISTS SP_CheckIfCategorieIsUsedInProducten;
DELIMITER $$

CREATE PROCEDURE SP_CheckIfCategorieIsUsedInProducten(
    IN p_categorie_id INT UNSIGNED
)
BEGIN
    SELECT COUNT(*) AS totaal
    FROM producten
    WHERE categorie_id = p_categorie_id;
END$$

DELIMITER ;
