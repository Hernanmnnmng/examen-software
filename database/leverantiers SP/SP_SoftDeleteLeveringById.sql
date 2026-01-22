-- Stored procedure om een levering soft delete te doen (is_actief op 0 zetten)
DROP PROCEDURE IF EXISTS SP_SoftDeleteLeveringById;

DELIMITER //

CREATE PROCEDURE SP_SoftDeleteLeveringById(
    IN p_levering_id INT
)
BEGIN
    UPDATE leveringen
    SET is_actief = 0
    WHERE id = p_levering_id;

    -- Return aantal aangepaste rijen
    SELECT ROW_COUNT() AS affected_rows;
END //

DELIMITER ;
