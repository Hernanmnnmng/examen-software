USE Voedselbank;

-- Verwijder bestaande procedure
DROP PROCEDURE IF EXISTS SP_GetAllVoedselpakketen;

DELIMITER $$

CREATE PROCEDURE SP_GetAllVoedselpakketen(

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
    GROUP BY
        vpkt.id
        ,vpkt.pakketnummer
        ,klnt.naam
        ,gzn.gezins_naam
    ORDER BY vpkt.id DESC;
END$$

DELIMITER ;

CALL SP_GetAllVoedselpakketen();
