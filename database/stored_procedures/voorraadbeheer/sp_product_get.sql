-- Voorraadbeheer - Get one product
-- Usage: CALL sp_product_get(1);

DROP PROCEDURE IF EXISTS sp_product_get;
DELIMITER $$
CREATE PROCEDURE sp_product_get(IN p_id INT UNSIGNED)
BEGIN
    SELECT
        p.id,
        p.product_naam,
        p.ean,
        p.aantal_voorraad,
        p.categorie_id,
        c.naam AS categorie_naam
    FROM producten p
    JOIN product_categorieen c ON c.id = p.categorie_id
    WHERE p.id = p_id
    LIMIT 1;
END$$
DELIMITER ;

