-- Voorraadbeheer - Product list with EAN filter + safe sorting
-- Usage: CALL sp_product_list(NULL, 'product_naam', 'asc');
--        CALL sp_product_list('1234567890123', 'ean', 'desc');

DROP PROCEDURE IF EXISTS sp_product_list;
DELIMITER $$
CREATE PROCEDURE sp_product_list(
    IN p_ean VARCHAR(13),
    IN p_sort VARCHAR(50),
    IN p_dir VARCHAR(4)
)
BEGIN
    DECLARE v_sort_col VARCHAR(64) DEFAULT 'p.product_naam';
    DECLARE v_dir VARCHAR(4) DEFAULT 'ASC';
    DECLARE v_sql TEXT;

    IF p_sort = 'product_naam' THEN
        SET v_sort_col = 'p.product_naam';
    ELSEIF p_sort = 'ean' THEN
        SET v_sort_col = 'p.ean';
    ELSEIF p_sort = 'categorie' THEN
        SET v_sort_col = 'c.naam';
    ELSEIF p_sort = 'aantal_voorraad' THEN
        SET v_sort_col = 'p.aantal_voorraad';
    END IF;

    IF UPPER(p_dir) = 'DESC' THEN
        SET v_dir = 'DESC';
    END IF;

    SET v_sql = CONCAT(
        'SELECT ',
            'p.id, p.product_naam, p.ean, p.aantal_voorraad, ',
            'p.categorie_id, c.naam AS categorie_naam ',
        'FROM producten p ',
        'JOIN product_categorieen c ON c.id = p.categorie_id ',
        'WHERE p.is_actief = 1 AND c.is_actief = 1'
    );

    IF p_ean IS NOT NULL AND p_ean <> '' THEN
        SET v_sql = CONCAT(v_sql, ' AND p.ean = ?');
    END IF;

    SET v_sql = CONCAT(v_sql, ' ORDER BY ', v_sort_col, ' ', v_dir);

    SET @sql = v_sql;
    PREPARE stmt FROM @sql;
    IF p_ean IS NOT NULL AND p_ean <> '' THEN
        SET @ean := p_ean;
        EXECUTE stmt USING @ean;
    ELSE
        EXECUTE stmt;
    END IF;
    DEALLOCATE PREPARE stmt;
END$$
DELIMITER ;

