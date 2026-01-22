DROP PROCEDURE IF EXISTS SP_GetAllProductenVoorraad;
DELIMITER $$

CREATE PROCEDURE SP_GetAllProductenVoorraad()
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
    WHERE p.is_actief = 1 AND c.is_actief = 1
    ORDER BY p.product_naam ASC;
END$$

DELIMITER ;
