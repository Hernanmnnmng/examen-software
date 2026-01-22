DELIMITER $$

CREATE PROCEDURE SP_GetMaandoverzichtPostcode(
    IN p_maand INT,
    IN p_jaar INT
)
BEGIN
    -- Maandoverzicht per postcode
    -- Geeft aantallen per productcategorie per postcode voor een specifieke maand/jaar

    SELECT
        k.Postcode,
        k.Stad,
        pc.Naam AS Productcategorie,
        COUNT(DISTINCT v.Id) AS AantalVoedselpakketten,
        SUM(vp.AantalProducten) AS TotaalProducten
    FROM voedselpakket v
    INNER JOIN klant k ON v.KlantId = k.Id
    INNER JOIN voedselpakket_producten vp ON v.Id = vp.VoedselpakketId
    INNER JOIN product p ON vp.ProductId = p.Id
    INNER JOIN productcategorie pc ON p.ProductCategorieId = pc.Id
    WHERE MONTH(v.SamenstelDatum) = p_maand
      AND YEAR(v.SamenstelDatum) = p_jaar
      AND v.IsDeleted = 0
      AND k.IsDeleted = 0
      AND p.IsDeleted = 0
    GROUP BY k.Postcode, k.Stad, pc.Id, pc.Naam
    ORDER BY k.Postcode, pc.Naam;
END$$

DELIMITER ;
