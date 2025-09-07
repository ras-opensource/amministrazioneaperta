-- Tabella per gli alloggi del modulo SICAR
CREATE TABLE IF NOT EXISTS 'aa_sicar_data' (
    'id' INT AUTO_INCREMENT PRIMARY KEY UNIQUE,
    'immobile' VARCHAR(64) NOT NULL,
    'descrizione' VARCHAR(255) NOT NULL,
    'tipologia_utilizzo' VARCHAR(64) NOT NULL,
    'stato_conservazione' VARCHAR(64) NOT NULL,
    'anno_ristrutturazione' INT DEFAULT NULL,
    'condominio_misto' BOOLEAN NOT NULL DEFAULT 0,
    'superficie_netta' DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    'superficie_utile_abitabile' DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    'piano' INT NOT NULL DEFAULT 0,
    'ascensore' BOOLEAN NOT NULL DEFAULT 0,
    'fruibile_dis' BOOLEAN NOT NULL DEFAULT 0,
    'gestione' JSON NOT NULL,
    'proprieta' JSON NOT NULL,
    'stato' JSON NOT NULL,
    'note' TEXT DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_immobile` (`immobile`),
    KEY `idx_tipologia_utilizzo` (`tipologia_utilizzo`),
    KEY `idx_stato_conservazione` (`stato_conservazione`),
    KEY `idx_condominio_misto` (`condominio_misto`),
    KEY `idx_piano` (`piano`),
    KEY `idx_ascensore` (`ascensore`),
    KEY `idx_fruibile_dis` (`fruibile_dis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Alloggi associati agli immobili';

-- Inserimento dati di esempio per le zone urbanistiche (esempio per Cagliari)
INSERT INTO `aa_sicar_zone_urbanistiche` (`codice`, `descrizione`, `comune`, `ordine`) VALUES
('A', 'Zona A - Centro storico', '092009', 1),
('B', 'Zona B - Residenziale', '092009', 2),
('C', 'Zona C - Commerciale', '092009', 3),
('D', 'Zona D - Industriale', '092009', 4),
('E', 'Zona E - Agricola', '092009', 5),
('F', 'Zona F - Verde pubblico', '092009', 6),
('G', 'Zona G - Servizi', '092009', 7),
('H', 'Zona H - Attrezzature collettive', '092009', 8);

