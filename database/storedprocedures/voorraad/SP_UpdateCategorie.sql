DROP PROCEDURE IF EXISTS SP_UpdateCategorie;
DELIMITER $$

CREATE PROCEDURE SP_UpdateCategorie(
    IN p_id INT UNSIGNED,
    IN p_naam VARCHAR(100)
)
BEGIN
    UPDATE product_categorieen
    SET 
        naam = p_naam
    WHERE id = p_id;
END$$

DELIMITER ;
