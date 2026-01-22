DROP PROCEDURE IF EXISTS SP_GetProductById;
DELIMITER $$

CREATE PROCEDURE SP_GetProductById(
    IN p_id INT UNSIGNED
)
BEGIN
    SELECT 
        p.id,
        p.product_naam,
        p.ean,
        p.categorie_id,
        p.aantal_voorraad,
        c.naam AS categorie_naam
    FROM producten p
    INNER JOIN product_categorieen c 
        ON p.categorie_id = c.id
    WHERE p.id = p_id AND p.is_actief = 1
    LIMIT 1;
END$$

DELIMITER ;
