DROP PROCEDURE IF EXISTS SP_CreateCategorie;
DELIMITER $$

CREATE PROCEDURE SP_CreateCategorie(
    IN p_naam VARCHAR(100)
)
BEGIN
    INSERT INTO product_categorieen (
        naam,
        is_actief
    )
    VALUES (
        p_naam,
        1
    );
END$$

DELIMITER ;
