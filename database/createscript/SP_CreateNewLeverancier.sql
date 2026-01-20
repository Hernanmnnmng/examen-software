DROP PROCEDURE IF EXISTS SP_CreateNewLeverancier;
DELIMITER $$

CREATE PROCEDURE SP_CreateNewLeverancier(
    IN l_bedrijfsnaam       VARCHAR(255),
    IN l_straat             VARCHAR(255),
    IN l_huisnummer         VARCHAR(50),
    IN l_postcode           VARCHAR(20),
    IN l_plaats             VARCHAR(100),
    IN l_contact_naam       VARCHAR(255),
    IN l_email              VARCHAR(255),
    IN l_telefoon           VARCHAR(50)
)
BEGIN
    DECLARE v_adres_id INT;
    DECLARE v_contactpersoon_id INT;

    START TRANSACTION;
    
        INSERT INTO adressen (
             straat
            ,huisnummer
            ,postcode
            ,plaats
        )
        VALUES (
             l_straat
            ,l_huisnummer
            ,l_postcode
            ,l_plaats
        );
        SET v_adres_id = LAST_INSERT_ID();

        INSERT INTO contactpersonen (
             contact_naam
            ,email
            ,telefoon
        )
        VALUES (
             l_contact_naam
            ,l_email
            ,l_telefoon
        );
        SET v_contactpersoon_id = LAST_INSERT_ID();

        INSERT INTO leveranciers (
             bedrijfsnaam
            ,adres_id
            ,contactpersoon_id
        )
        VALUES (
             l_bedrijfsnaam
            ,v_adres_id
            ,v_contactpersoon_id
        );
    COMMIT;
END$$

DELIMITER ;

