USE Voedselbank;

-- Verwijder bestaande procedure
DROP PROCEDURE IF EXISTS SP_Updatevoedselpakketproduct;

DELIMITER $$

CREATE PROCEDURE SP_Updatevoedselpakketproduct(
    IN voedselpakketid INT UNSIGNED
    ,IN productid INT UNSIGNED
    ,IN prdaantal INT UNSIGNED
    ,IN verschil INT
)
main_block: BEGIN
    DECLARE current_aantal INT UNSIGNED DEFAULT 0;
    DECLARE error_message VARCHAR(500);
    DECLARE error_code INT;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        GET DIAGNOSTICS CONDITION 1
            error_code = MYSQL_ERRNO,
            error_message = MESSAGE_TEXT;
        ROLLBACK;
        SELECT -1 AS affected, error_code AS code, error_message AS message;
    END;

    START TRANSACTION;

    -- Haal huidige aantal op
    SELECT aantal INTO current_aantal
    FROM voedselpakket_producten
    WHERE voedselpakket_id = voedselpakketid AND product_id = productid
    FOR UPDATE;

    -- Geen verandering nodig
    IF prdaantal = current_aantal THEN
        COMMIT;
        SELECT 0 AS affected, 0 AS code, 'Geen wijziging' AS message;
        LEAVE main_block;
    END IF;

    -- Als alles weg moet: verwijder koppeling en geef voorraad terug
    IF prdaantal = 0 THEN
        DELETE FROM voedselpakket_producten
        WHERE voedselpakket_id = voedselpakketid AND product_id = productid;

        UPDATE producten
           SET aantal_voorraad = aantal_voorraad + current_aantal
        WHERE id = productid;

        COMMIT;
        SELECT 1 AS affected, 0 AS code, 'Product verwijderd uit pakket' AS message;
        LEAVE main_block;
    END IF;

    -- Check of er genoeg voorraad is (alleen als verschil positief is)
    IF verschil > 0 AND (SELECT aantal_voorraad FROM producten WHERE id = productid) < verschil THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Onvoldoende voorraad';
    END IF;

    UPDATE voedselpakket_producten
        SET aantal = prdaantal
    WHERE
        voedselpakket_id = voedselpakketid AND product_id = productid;

    UPDATE producten
       SET aantal_voorraad = aantal_voorraad - verschil
    WHERE id = productid;

    -- 3. Als we hier zijn zonder fouten: Commit (Sla definitief op)
    COMMIT;

    -- Geef een succesbericht terug
    SELECT 1 AS affected, 0 AS code, 'Success' AS message;
END main_block$$

DELIMITER ;

-- Voeg testdata in
-- INSERT INTO voedselpakket_producten (voedselpakket_id, product_id, aantal)
-- VALUES (1, 7, 15);

CALL SP_Updatevoedselpakketproduct
(
1, 2, 0, 15
);
