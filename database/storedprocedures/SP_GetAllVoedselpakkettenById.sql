USE Voedselbank;

-- Verwijder bestaande procedure
DROP PROCEDURE IF EXISTS SP_GetAllVoedselpakkettenById;

DELIMITER $$

CREATE PROCEDURE SP_GetAllVoedselpakkettenById(
    IN klantid INT
)
BEGIN
    SELECT
        vpkt.id
        ,vpkt.pakketnummer
        ,klnt.naam
        ,gzn.gezins_naam
        ,COUNT(vdpr.id) AS producten_totaal
    FROM
        voedselpakketten vpkt
    JOIN
        klanten klnt ON vpkt.klant_id = klnt.id
    JOIN
        gezinnen gzn ON klnt.gezin_id = gzn.id
    JOIN
        voedselpakket_producten vdpr ON vdpr.voedselpakket_id = vpkt.id
    WHERE
        klnt.id = klantid
    GROUP BY
        vpkt.id
        ,vpkt.pakketnummer
        ,klnt.naam
        ,gzn.gezins_naam
    ORDER BY vpkt.id DESC;
END$$

DELIMITER ;

CALL SP_GetAllVoedselpakkettenById(1);
