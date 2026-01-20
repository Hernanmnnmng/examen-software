USE Voedselbank;

-- Verwijder bestaande procedure
DROP PROCEDURE IF EXISTS SP_GetVoedselpakketById;

DELIMITER $$

CREATE PROCEDURE SP_GetVoedselpakketById(
    IN id INT
)
BEGIN
    SELECT
        vpkt.id
        ,vpkt.klant_id
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
    LEFT JOIN
        voedselpakket_producten vdpr ON vdpr.voedselpakket_id = vpkt.id
    WHERE
        vpkt.id = id
    GROUP BY
        vpkt.id
        ,vpkt.klant_id
        ,vpkt.pakketnummer
        ,klnt.naam
        ,gzn.gezins_naam;
END$$

DELIMITER ;

CALL SP_GetVoedselpakketById(1);
