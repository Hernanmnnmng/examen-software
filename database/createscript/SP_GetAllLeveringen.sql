DROP PROCEDURE IF EXISTS SP_GetAllLeveringen
DELIMITER ??

CREATE PROCEDURE SP_GetAllLeveringen()
BEGIN
    SELECT 
         lvrn.bedrijfsnaam
        ,cprs.contact_naam
        ,lvng.eerstvolgende_levering
    ;
END ??