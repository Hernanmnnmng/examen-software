DROP PROCEDURE IF EXISTS SP_CreateProduct;
DELIMITER $$

CREATE PROCEDURE SP_CreateProduct(
    IN p_product_naam VARCHAR(255),
    IN p_ean CHAR(13),
    IN p_categorie_id INT UNSIGNED,
    IN p_aantal_voorraad INT UNSIGNED
)
BEGIN
    INSERT INTO producten (
        product_naam,
        ean,
        categorie_id,
        aantal_voorraad,
        is_actief
    )
    VALUES (
        p_product_naam,
        p_ean,
        p_categorie_id,
        p_aantal_voorraad,
        1
    );
END$$

DELIMITER ;
