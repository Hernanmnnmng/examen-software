DROP PROCEDURE IF EXISTS SP_GetAllAllergenen;
DELIMITER $$

CREATE PROCEDURE SP_GetAllAllergenen()
BEGIN
    SELECT 
        id,
        naam,
        is_actief,
        opmerking
    FROM allergenen
    WHERE is_actief = 1
    ORDER BY naam ASC;
END$$

DELIMITER ;
