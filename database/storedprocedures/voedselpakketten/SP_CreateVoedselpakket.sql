USE Voedselbank;

-- Verwijder bestaande procedure
DROP PROCEDURE IF EXISTS SP_CreateVoedselpakket;

DELIMITER $$

CREATE PROCEDURE SP_CreateVoedselpakket(
    IN klantid INT,
    IN pakketnmr char(12)
)
BEGIN
    INSERT INTO voedselpakketten(pakketnummer, klant_id)
    VALUES(pakketnmr, klantid);

    SELECT ROW_COUNT() AS Affected;
END$$

DELIMITER ;

CALL SP_CreateVoedselpakket(
1,'vp0000000005'
);
