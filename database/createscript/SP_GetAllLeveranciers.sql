DROP PROCEDURE IF EXISTS SP_GetAllLeveranciers;
DELIMITER ??

CREATE PROCEDURE SP_GetAllLeveranciers()
BEGIN
    SELECT 
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
        ON lvrn.contactpersoon_id = cprs.id;
END ??

DELIMITER ;
