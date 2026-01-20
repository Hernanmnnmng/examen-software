DROP PROCEDURE IF EXISTS SP_GetAllLeveranciers
DELIMITER ??

CREATE PROCEDURE SP_GetAllLeveranciers()
BEGIN
    SELECT * FROM leveranciers;
END ??