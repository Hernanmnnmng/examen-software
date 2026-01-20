USE Voedselbank;

-- Verwijder bestaande procedure
DROP PROCEDURE IF EXISTS SP_GetAllKlanten;

DELIMITER $$

CREATE PROCEDURE SP_GetAllKlanten(
)
BEGIN
    SELECT
         klnt.id
        ,klnt.naam
        ,gzn.gezins_naam
        ,gzn.volwassenen
        ,gzn.kinderen
        ,gzn.babys
    FROM
        klanten klnt
    JOIN
        gezinnen gzn ON klnt.gezin_id = gzn.id;
END$$

DELIMITER ;

CALL SP_GetAllKlanten();
