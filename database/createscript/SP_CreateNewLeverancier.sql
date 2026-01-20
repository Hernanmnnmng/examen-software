DROP PROCEDURE IF EXISTS SP_CreateNewLeverancier;
DELIMITER ??

CREATE PROCEDURE SP_CreateNewLeverancier(
     IN l_bedrijfsnaam           VARCHAR(255)
    ,IN l_adres_id               INT
    ,IN l_contactpersoon_id      INT
    ,IN l_opmerking              VARCHAR(255)
)
BEGIN
    INSERT INTO leveranciers (
         bedrijfsnaam
        ,adres_id
        ,contactpersoon_id
        ,opmerking
    )
    VALUES (
         l_bedrijfsnaam
        ,l_adres_id
        ,l_contactpersoon_id
        ,l_opmerking
    );
END ??

DELIMITER ;
