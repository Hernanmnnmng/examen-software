USE Voedselbank;

DROP PROCEDURE IF EXISTS SP_GetMaandoverzichtProductcategorie;

DELIMITER $$

CREATE PROCEDURE SP_GetMaandoverzichtProductcategorie(
    IN p_maand INT,
    IN p_jaar INT
)
BEGIN
    -- Maandoverzicht per productcategorie
    -- Geeft aantal producten per leverancier voor een specifieke maand/jaar

    SELECT
        pc.naam AS Productcategorie,
        p.product_naam AS Product,
        l.bedrijfsnaam AS Leverancier,
        COUNT(vp.id) AS Aantal,
        SUM(vp.aantal) AS TotaalProducten
    FROM voedselpakket_producten vp
    INNER JOIN producten p ON vp.product_id = p.id
    INNER JOIN product_categorieen pc ON p.categorie_id = pc.id
    INNER JOIN voedselpakketten v ON vp.voedselpakket_id = v.id
    INNER JOIN levering_producten lp ON p.id = lp.product_id
    INNER JOIN leveringen lev ON lp.levering_id = lev.id
    INNER JOIN leveranciers l ON lev.leverancier_id = l.id
    WHERE MONTH(v.datum_samenstelling) = p_maand
      AND YEAR(v.datum_samenstelling) = p_jaar
    GROUP BY pc.id, pc.naam, p.id, p.product_naam, l.id, l.bedrijfsnaam
    ORDER BY pc.naam, p.product_naam, l.bedrijfsnaam;
END$$

DELIMITER ;
