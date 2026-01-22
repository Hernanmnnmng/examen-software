DROP PROCEDURE IF EXISTS SP_CheckIfProductIsUsedInVoedselpakket;
DELIMITER $$

CREATE PROCEDURE SP_CheckIfProductIsUsedInVoedselpakket(
    IN p_product_id INT UNSIGNED
)
BEGIN
    SELECT COUNT(*) AS totaal
    FROM voedselpakket_producten
    WHERE product_id = p_product_id;
END$$

DELIMITER ;
