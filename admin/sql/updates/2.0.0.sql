--
-- Anpassungen für Joomla / CLM Berechtigungen
--

UPDATE `#__clm_turniere_grand_prix` SET `published` = '0' WHERE `published` IS NULL; 
ALTER TABLE `#__clm_turniere_grand_prix`
	MODIFY `published` tinyint(4) unsigned NOT NULL DEFAULT '0';
	
UPDATE `#__clm_turniere_grand_prix` SET `checked_out` = '0' WHERE `published` IS NULL; 
ALTER TABLE `#__clm_turniere_grand_prix`
	MODIFY `checked_out` int(10) unsigned NOT NULL DEFAULT '0';
