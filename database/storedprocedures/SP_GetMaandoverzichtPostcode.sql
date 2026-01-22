USE Voedselbank;

DROP PROCEDURE IF EXISTS SP_GetMaandoverzichtPostcode;

DELIMITER $$

CREATE PROCEDURE SP_GetMaandoverzichtPostcode(
    IN p_maand INT,
    IN p_jaar INT
)
BEGIN
    -- Maandoverzicht per postcode
    -- Geeft aantallen per productcategorie per postcode voor een specifieke maand/jaar

    SELECT
        a.postcode AS Postcode,
        a.plaats AS Stad,
        pc.naam AS Productcategorie,
        COUNT(DISTINCT v.id) AS AantalVoedselpakketten,
        SUM(vp.aantal) AS TotaalProducten
    FROM voedselpakketten v
    INNER JOIN klanten k ON v.klant_id = k.id
    INNER JOIN adressen a ON k.adres_id = a.id
    INNER JOIN voedselpakket_producten vp ON v.id = vp.voedselpakket_id
    INNER JOIN producten p ON vp.product_id = p.id
    INNER JOIN product_categorieen pc ON p.categorie_id = pc.id
    WHERE MONTH(v.datum_samenstelling) = p_maand
      AND YEAR(v.datum_samenstelling) = p_jaar
    GROUP BY a.postcode, a.plaats, pc.id, pc.naam
    ORDER BY a.postcode, pc.naam;
END$$

DELIMITER ;
