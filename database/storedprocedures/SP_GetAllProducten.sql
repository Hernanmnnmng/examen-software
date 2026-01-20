DELIMITER $$

DROP PROCEDURE IF EXISTS SP_GetAllProducten$$

CREATE PROCEDURE SP_GetAllProducten(
    IN in_klant_id INT
)
BEGIN
    -- 1. Cache Wensen (Dingen die de klant wil VERMIJDEN)
    CREATE TEMPORARY TABLE cache_klant_filters (
        filter_id INT,
        PRIMARY KEY (filter_id)
    );
    -- We gooien wensen en allergenen op één grote hoop: "Dingen die we niet willen"
    INSERT INTO cache_klant_filters (filter_id)
    SELECT wens_id FROM klant_wensen WHERE klant_id = in_klant_id;

    -- 2. Cache Allergenen (Ook dingen die de klant wil vermijden)
    CREATE TEMPORARY TABLE cache_klant_allergenen (
        allergeen_id INT,
        PRIMARY KEY (allergeen_id)
    );
    INSERT INTO cache_klant_allergenen (allergeen_id)
    SELECT allergie_id FROM klant_allergenen WHERE klant_id = in_klant_id;

    -- 3. De Producten ophalen
    SELECT
        ctgr.naam as categorie
        ,prd.id
        ,prd.product_naam
        ,prd.ean
        ,prd.aantal_voorraad
    FROM
        producten prd
    JOIN
        product_categorieen ctgr ON ctgr.id = prd.categorie_id
    WHERE
        -- CHECK A: ALLERGENEN (Standaard filter)
        NOT EXISTS (
            SELECT 1
            FROM product_allergenen pa
            WHERE pa.product_id = prd.id
            AND pa.allergie_id IN (SELECT allergeen_id FROM cache_klant_allergenen)
        )
    AND
        -- CHECK B: WENSEN (NU OOK ALS FILTER!)
        -- Logica: Verberg het product als het een kenmerk heeft dat de klant heeft aangevinkt.
        NOT EXISTS (
            SELECT 1
            FROM product_kenmerken pk
            WHERE pk.product_id = prd.id
            AND pk.wens_id IN (SELECT filter_id FROM cache_klant_filters)
        )

    ORDER BY
        ctgr.naam, prd.product_naam;

    DROP TEMPORARY TABLE IF EXISTS cache_klant_filters, cache_klant_allergenen;
END$$

DELIMITER ;

CALL SP_GetAllProducten(1);
