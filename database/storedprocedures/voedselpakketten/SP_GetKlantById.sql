USE Voedselbank;

-- Verwijder bestaande procedure
DROP PROCEDURE IF EXISTS SP_GetKlantById;

DELIMITER $$

CREATE PROCEDURE SP_GetKlantById(
    IN klantid INT
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
        gezinnen gzn ON klnt.gezin_id = gzn.id
    WHERE
        klnt.id = klantid;
END$$

DELIMITER ;

CALL SP_GetKlantById(2);
