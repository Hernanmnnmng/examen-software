-- Stored procedure om alle leveringen van een leverancier soft delete te doen
DROP PROCEDURE IF EXISTS SP_SoftDeleteLeveringenByLeverancierId;

DELIMITER //

CREATE PROCEDURE SP_SoftDeleteLeveringenByLeverancierId(
    IN p_leverancier_id INT
)
BEGIN
    UPDATE leveringen
    SET is_actief = 0
    WHERE leverancier_id = p_leverancier_id;

    -- Return aantal aangepaste rijen
    SELECT ROW_COUNT() AS affected_rows;
END //

DELIMITER ;
