DELIMITER $$

CREATE PROCEDURE SP_GetMaandoverzichtProductcategorie(
    IN p_maand INT,
    IN p_jaar INT
)
BEGIN
    -- Maandoverzicht per productcategorie
    -- Geeft aantal producten per leverancier voor een specifieke maand/jaar

    SELECT
        pc.Naam AS Productcategorie,
        p.Naam AS Product,
        l.Bedrijfsnaam AS Leverancier,
        COUNT(vp.Id) AS Aantal,
        SUM(vp.AantalProducten) AS TotaalProducten
    FROM voedselpakket_producten vp
    INNER JOIN product p ON vp.ProductId = p.Id
    INNER JOIN productcategorie pc ON p.ProductCategorieId = pc.Id
    INNER JOIN voedselpakket v ON vp.VoedselpakketId = v.Id
    INNER JOIN levering lev ON p.Id = lev.ProductId
    INNER JOIN leveranciers l ON lev.LeverancierId = l.Id
    WHERE MONTH(v.SamenstelDatum) = p_maand
      AND YEAR(v.SamenstelDatum) = p_jaar
      AND v.IsDeleted = 0
      AND p.IsDeleted = 0
      AND l.IsDeleted = 0
    GROUP BY pc.Id, pc.Naam, p.Id, p.Naam, l.Id, l.Bedrijfsnaam
    ORDER BY pc.Naam, p.Naam, l.Bedrijfsnaam;
END$$

DELIMITER ;
