-- Stored procedure om alle actieve leveranciers op te halen
DROP PROCEDURE IF EXISTS SP_GetActiveLeveranciers;

DELIMITER //

CREATE PROCEDURE SP_GetActiveLeveranciers()
BEGIN
    SELECT
        lvrn.id,
        lvrn.bedrijfsnaam,
        adrs.straat,
        adrs.huisnummer,
        adrs.postcode,
        adrs.plaats,
        cprs.contact_naam,
        cprs.email,
        cprs.telefoon
    FROM leveranciers lvrn
    INNER JOIN adressen adrs
        ON lvrn.adres_id = adrs.id
    INNER JOIN contactpersonen cprs
        ON lvrn.contactpersoon_id = cprs.id
    WHERE lvrn.is_actief = 1;
END //

DELIMITER ;
