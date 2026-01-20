USE Voedselbank;

DROP PROCEDURE IF EXISTS SP_DeliverVoedselpakket;

DELIMITER $$

CREATE PROCEDURE SP_DeliverVoedselpakket(
    IN p_voedselpakket_id INT
)
BEGIN
    UPDATE voedselpakketten
    SET datum_uitgifte = NOW()
    WHERE id = p_voedselpakket_id;

    SELECT ROW_COUNT() AS Affected;
END$$

DELIMITER ;
