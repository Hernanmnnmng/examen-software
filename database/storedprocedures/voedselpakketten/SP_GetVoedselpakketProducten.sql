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
        ,prdt.product_naam
        ,vdpr.aantal
        ,prdt.aantal_voorraad
    FROM
        voedselpakket_producten vdpr
    JOIN
        producten prdt ON vdpr.product_id = prdt.id
    WHERE
        vdpr.voedselpakket_id = p_voedselpakket_id
    ORDER BY vdpr.id;
END$$

DELIMITER ;
