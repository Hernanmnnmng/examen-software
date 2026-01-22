DROP PROCEDURE IF EXISTS SP_UpdateProduct;
DELIMITER $$

CREATE PROCEDURE SP_UpdateProduct(
    IN p_id INT UNSIGNED,
    IN p_product_naam VARCHAR(255),
    IN p_ean CHAR(13),
    IN p_categorie_id INT UNSIGNED,
    IN p_aantal_voorraad INT UNSIGNED
)
BEGIN
    UPDATE producten
    SET 
        product_naam = p_product_naam,
        ean = p_ean,
        categorie_id = p_categorie_id,
        aantal_voorraad = p_aantal_voorraad
    WHERE id = p_id;
END$$

DELIMITER ;
