DROP PROCEDURE IF EXISTS SP_GetAllLeveringen;
DELIMITER ??

CREATE PROCEDURE SP_GetAllLeveringen()
BEGIN
    SELECT 
        lvng.id,
        lvrn.bedrijfsnaam,
        cprs.contact_naam,
        lvng.eerstvolgende_levering
    FROM leveringen lvng
    INNER JOIN leveranciers lvrn 
        ON lvng.leverancier_id = lvrn.id
    INNER JOIN contactpersonen cprs 
        ON lvrn.contactpersoon_id = cprs.id;
END ??

DELIMITER ;
