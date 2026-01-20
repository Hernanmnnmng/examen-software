-- Voorraadbeheer - Delete product (only if never used in a voedselpakket)
-- Usage: CALL sp_product_delete(1);

DROP PROCEDURE IF EXISTS sp_product_delete;
DELIMITER $$
CREATE PROCEDURE sp_product_delete(IN p_id INT UNSIGNED)
BEGIN
    DECLARE v_used_cnt INT DEFAULT 0;

    -- Business rule: product cannot be deleted if used in any voedselpakket
    SELECT COUNT(*) INTO v_used_cnt
    FROM voedselpakket_producten
    WHERE product_id = p_id;

    IF v_used_cnt > 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Product kan niet worden verwijderd, het is al gebruikt in een voedselpakket';
    END IF;

    DELETE FROM producten WHERE id = p_id;
    SELECT ROW_COUNT() AS affected;
END$$
DELIMITER ;

