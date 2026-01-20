USE Voedselbank;

-- Verwijder bestaande procedure
DROP PROCEDURE IF EXISTS SP_Createvoedselpakketproduct;

DELIMITER $$

CREATE PROCEDURE SP_Createvoedselpakketproduct(
    IN voedselpakketid INT
    ,IN productid INT
    ,IN prdaantal INT
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK; -- Draai alles terug
        SELECT -1 AS affected;
    END;

    START TRANSACTION;

    INSERT INTO voedselpakket_producten(voedselpakket_id, product_id, aantal)
    VALUES(voedselpakketid, productid, prdaantal);

    -- Voor de UPDATE:
    IF (SELECT aantal_voorraad FROM producten WHERE id = productid) < prdaantal THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Onvoldoende voorraad';
    END IF;

    UPDATE producten
       SET aantal_voorraad = aantal_voorraad - prdaantal
    WHERE id = productid;

    -- 3. Als we hier zijn zonder fouten: Commit (Sla definitief op)
    COMMIT;

    -- Geef een succesbericht terug
    SELECT 1 AS affected;
END$$

DELIMITER ;

CALL SP_Createvoedselpakketproduct(
1,2, 20
);
