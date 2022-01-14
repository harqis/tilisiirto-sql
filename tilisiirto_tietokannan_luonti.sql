-- Tilisiirto, tietokannan luonti
-- Tommi Kivinen
-- tommi.kivinen@tuni.fi

CREATE TABLE tilit(
tilinumero INT,
omistaja VARCHAR(50) NOT NULL,
saldo INT,
PRIMARY KEY (tilinumero));

INSERT INTO tilit VALUES (1234508,'Frank Lampard', 50000);
INSERT INTO tilit VALUES (1234526,'John Terry', 25000);
INSERT INTO tilit VALUES (1234511,'Didier Drogba', 28000);
INSERT INTO tilit VALUES (1234501,'Petr Cech', 10000);
INSERT INTO tilit VALUES (1234503,'Ashley Cole', 30000);
