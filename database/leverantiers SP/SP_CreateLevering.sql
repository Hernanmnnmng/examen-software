DROP PROCEDURE IF EXISTS SP_CreateLevering;
DELIMITER ??

CREATE PROCEDURE SP_CreateLevering(
     IN p_leverancier_id         INT
    ,IN p_leverdatum_tijd        DATETIME
    ,IN p_eerstvolgende_levering DATETIME
)
BEGIN
    INSERT INTO leveringen (
         leverancier_id
        ,leverdatum_tijd
        ,eerstvolgende_levering
    )
    VALUES (
         p_leverancier_id
        ,p_leverdatum_tijd
        ,p_eerstvolgende_levering
    );
END ??

DELIMITER ;
