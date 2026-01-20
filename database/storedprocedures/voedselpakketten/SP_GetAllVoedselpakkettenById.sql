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
        ,vpkt.datum_uitgifte
        ,klnt.naam AS naam
        ,gzn.gezins_naam
        ,COUNT(vdpr.id) AS producten_totaal
        ,vpkt.datum_samenstelling
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
        ,vpkt.datum_uitgifte
        ,vpkt.datum_samenstelling
        ,klnt.naam
        ,gzn.gezins_naam;
END$$

DELIMITER ;
