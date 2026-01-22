DROP PROCEDURE IF EXISTS SP_GetAllWensen;
DELIMITER $$

CREATE PROCEDURE SP_GetAllWensen()
BEGIN
    SELECT 
        id,
        omschrijving,
        is_actief,
        opmerking
    FROM wensen
    WHERE is_actief = 1
    ORDER BY omschrijving ASC;
END$$

DELIMITER ;
