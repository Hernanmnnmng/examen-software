-- Stored procedure om een leverancier soft delete te doen (is_actief op 0 zetten)
DROP PROCEDURE IF EXISTS SP_SoftDeleteLeverancierById;

DELIMITER //

CREATE PROCEDURE SP_SoftDeleteLeverancierById(
    IN p_leverancier_id INT
)
BEGIN
    UPDATE leveranciers
    SET is_actief = 0
    WHERE id = p_leverancier_id;

    -- Return aantal aangepaste rijen
    SELECT ROW_COUNT() AS affected_rows;
END //

DELIMITER ;
