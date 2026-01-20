DROP PROCEDURE IF EXISTS SP_GetLeverancierByBedrijfsnaam;
DELIMITER $$

CREATE PROCEDURE SP_GetLeverancierByBedrijfsnaam(
    IN l_bedrijfsnaam VARCHAR(255)
)
BEGIN
    SELECT COUNT(*) AS totaal
    FROM leveranciers
    WHERE bedrijfsnaam COLLATE utf8mb4_unicode_ci = l_bedrijfsnaam COLLATE utf8mb4_unicode_ci;
END $$

DELIMITER ;
