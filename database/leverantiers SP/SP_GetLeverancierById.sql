DROP PROCEDURE IF EXISTS SP_GetLeverancierById;
DELIMITER $$

CREATE PROCEDURE SP_GetLeverancierById(
    IN l_id INT
)
BEGIN
    SELECT 
        id,
        bedrijfsnaam
    FROM leveranciers
    WHERE id = l_id;
END $$

DELIMITER ;
