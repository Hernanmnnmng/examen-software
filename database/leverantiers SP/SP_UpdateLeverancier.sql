DROP PROCEDURE IF EXISTS SP_UpdateLeverancier;
DELIMITER $$

CREATE PROCEDURE SP_UpdateLeverancier(
    IN p_id INT,
    IN p_bedrijfsnaam VARCHAR(255)
)
BEGIN
    UPDATE leveranciers
    SET 
        bedrijfsnaam = p_bedrijfsnaam
    WHERE id = p_id;
END $$

DELIMITER ;
