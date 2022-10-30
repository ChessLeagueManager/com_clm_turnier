-- DEFAULT bei 'typ_calculation' entfernt.
-- Kompatibilitätsprobleme mit MySQL Datenbank
ALTER TABLE `#__clm_turniere_grand_prix`
	ALTER `typ_calculation` DROP DEFAULT;
