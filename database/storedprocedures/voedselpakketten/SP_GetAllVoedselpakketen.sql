USE Voedselbank;

-- Verwijder bestaande procedure
DROP PROCEDURE IF EXISTS SP_GetAllVoedselpakketen;

DELIMITER $$

CREATE PROCEDURE SP_GetAllVoedselpakketen()
BEGIN
    SELECT
        vpkt.id
        ,vpkt.pakketnummer
        ,klnt.naam
        ,gzn.gezins_naam
        ,COALESCE(COUNT(vdpr.id), 0) AS producten_totaal
        ,vpkt.datum_uitgifte
    FROM
        voedselpakketten vpkt
    JOIN
        klanten klnt ON vpkt.klant_id = klnt.id
    JOIN
        gezinnen gzn ON klnt.gezin_id = gzn.id
    LEFT JOIN -- toont dan ook de voedselpakketen zonder producten
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
