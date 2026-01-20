DROP PROCEDURE IF EXISTS SP_UpdateLevering;
DELIMITER $$

CREATE PROCEDURE SP_UpdateLevering(
    IN l_id INT,
    IN l_leverdatum_tijd DATETIME,
    IN l_eerstvolgende_levering DATETIME,
    IN l_leverancier_id INT
)
BEGIN
    UPDATE leveringen
    SET 
        leverdatum_tijd = l_leverdatum_tijd,
        eerstvolgende_levering = l_eerstvolgende_levering,
        leverancier_id = l_leverancier_id
    WHERE id = l_id;
END $$

DELIMITER ;
