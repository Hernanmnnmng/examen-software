USE Voedselbank;

-- Verwijder bestaande procedure
DROP PROCEDURE IF EXISTS SP_Deletevoedselpakket;

DELIMITER $$

CREATE PROCEDURE SP_Deletevoedselpakket(
    IN voedselpakketid INT UNSIGNED
)
main_block: BEGIN
    DECLARE error_message VARCHAR(500);
    DECLARE error_code INT;
    DECLARE is_afgegeven BIT DEFAULT 0;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        GET DIAGNOSTICS CONDITION 1
            error_code = MYSQL_ERRNO,
            error_message = MESSAGE_TEXT;
        ROLLBACK;
        SELECT -1 AS affected, error_code AS code, error_message AS message;
    END;

    START TRANSACTION;

    -- Check of pakket al is afgegeven
    SELECT (datum_uitgifte IS NOT NULL) INTO is_afgegeven
    FROM voedselpakketten
    WHERE id = voedselpakketid;

    IF is_afgegeven = 1 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Dit pakket is al afgegeven en kan niet meer worden verwijderd';
    END IF;

    -- Voor elke product in het pakket: voorraad terugboeken
    UPDATE producten
    SET aantal_voorraad = aantal_voorraad + (
        SELECT COALESCE(SUM(aantal), 0)
        FROM voedselpakket_producten
        WHERE voedselpakket_id = voedselpakketid AND product_id = producten.id
    )
    WHERE id IN (
        SELECT product_id FROM voedselpakket_producten
        WHERE voedselpakket_id = voedselpakketid
    );

    -- Verwijder de tussentabel entries
    DELETE FROM voedselpakket_producten
    WHERE voedselpakket_id = voedselpakketid;

    -- Verwijder het pakket zelf
    DELETE FROM voedselpakketten
    WHERE id = voedselpakketid;

    COMMIT;

    SELECT 1 AS affected, 0 AS code, 'Pakket succesvol verwijderd' AS message;
END main_block$$
DELIMITER ;

CALL SP_Deletevoedselpakket(1);
