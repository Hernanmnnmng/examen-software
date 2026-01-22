DROP PROCEDURE IF EXISTS SP_CheckIfBedrijfIsActiefById;
DELIMITER ??

CREATE PROCEDURE SP_CheckIfBedrijfIsActiefById(
    IN l_id INT
)
BEGIN
    SELECT
        is_actief
    FROM leveranciers
    WHERE id = l_id;
END ??

DELIMITER ;
