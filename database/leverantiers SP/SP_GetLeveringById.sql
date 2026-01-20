DROP PROCEDURE IF EXISTS SP_GetLeveringById;
DELIMITER $$

CREATE PROCEDURE SP_GetLeveringById(
    IN l_id INT
)
BEGIN
    SELECT 
        id,
        leverancier_id,
        leverdatum_tijd,
        eerstvolgende_levering
    FROM leveringen
    WHERE id = l_id
    LIMIT 1;
END $$

DELIMITER ;
