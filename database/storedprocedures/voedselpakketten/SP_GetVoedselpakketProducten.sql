USE Voedselbank;

-- Verwijder bestaande procedure
DROP PROCEDURE IF EXISTS SP_GetVoedselpakketProducten;

DELIMITER $$

CREATE PROCEDURE SP_GetVoedselpakketProducten(
    IN p_voedselpakket_id BIGINT UNSIGNED
)
BEGIN
    SELECT
        vdpr.id
        ,vdpr.voedselpakket_id
        ,vdpr.product_id
        ,prdt.product_naam AS naam
        ,prdt.ean
        ,ctgr.naam AS categorie
        ,vdpr.aantal
        ,prdt.aantal_voorraad
    FROM
        voedselpakket_producten vdpr
    JOIN
        producten prdt ON vdpr.product_id = prdt.id
    LEFT JOIN
        product_categorieen ctgr ON prdt.categorie_id = ctgr.id
    WHERE
        vdpr.voedselpakket_id = p_voedselpakket_id
    ORDER BY vdpr.id;
END$$

DELIMITER ;
