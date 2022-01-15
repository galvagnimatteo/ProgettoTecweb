CREATE TABLE Film (
	ID INT NOT NULL AUTO_INCREMENT,
	Titolo VARCHAR(255) NOT NULL,
	Genere VARCHAR(20) NOT NULL,
	DataUscita DATE NULL,
	Descrizione VARCHAR(3000) NOT NULL,
	SrcImg VARCHAR(100) NOT NULL,
	AltImg VARCHAR(150) NOT NULL,
	Durata INT NOT NULL,
	PRIMARY KEY (ID)
);

CREATE TABLE Sala (
	Numero SMALLINT NOT NULL,
	PRIMARY KEY (Numero)
);

CREATE TABLE Posto (
	Fila CHAR(1) NOT NULL,
	Numero SMALLINT NOT NULL,
	NumeroSala SMALLINT NOT NULL,
	PRIMARY KEY (Numero, Fila, NumeroSala),
	FOREIGN KEY (NumeroSala) REFERENCES Sala(Numero) ON DELETE CASCADE
);

CREATE TABLE Proiezione (
	ID INT NOT NULL AUTO_INCREMENT,
	Data DATE NOT NULL,
	Orario TIME NOT NULL,
	IDFilm INT NOT NULL,
	NumeroSala SMALLINT NOT NULL,
	PRIMARY KEY (ID),
	UNIQUE(Data, Orario, IDFilm, NumeroSala),
	FOREIGN KEY (IDFilm) REFERENCES Film(ID) ON DELETE CASCADE,
	FOREIGN KEY (NumeroSala) REFERENCES Sala(Numero) ON DELETE CASCADE

);

CREATE TABLE Utente (
	ID INT NOT NULL AUTO_INCREMENT,
	DataNascita DATE NOT NULL,
	Nome VARCHAR(50) NOT NULL,
	Cognome VARCHAR(50) NOT NULL,
	Username VARCHAR(50) NOT NULL,
	Password VARCHAR(50) NOT NULL,
	Email VARCHAR(100),
	PRIMARY KEY(ID)
);

CREATE TABLE Prenotazione (
	ID INT NOT NULL AUTO_INCREMENT,
	NumeroPersone SMALLINT NOT NULL,
	IDUtente INT NOT NULL,
	IDProiezione INT NOT NULL,
	PRIMARY KEY (ID),
	FOREIGN KEY(IDUtente) REFERENCES Utente(ID) ON DELETE CASCADE,
	FOREIGN KEY(IDProiezione) REFERENCES Proiezione(ID) ON DELETE CASCADE
);

CREATE TABLE Partecipa (
	NumeroPosto SMALLINT NOT NULL,
	FilaPosto CHAR(1) NOT NULL,
	NumeroSala SMALLINT NOT NULL,
	IDPrenotazione INT NOT NULL,
	PRIMARY KEY (NumeroPosto, FilaPosto, NumeroSala, IDPrenotazione),
	FOREIGN KEY(NumeroPosto, FilaPosto, NumeroSala)
	REFERENCES Posto(Numero, Fila, NumeroSala) ON DELETE CASCADE
);

CREATE TABLE CastFilm (
	Nome VARCHAR(50) NOT NULL,
	Cognome VARCHAR(50) NOT NULL,
	ID INT NOT NULL AUTO_INCREMENT,
	Lingua CHAR(2) NULL,
	Ruolo CHAR(1) NOT NULL,
	CHECK (UPPER(Ruolo)='A' OR UPPER(Ruolo)='R'),
	PRIMARY KEY (ID),
	UNIQUE(Nome, Cognome, Ruolo)
);

CREATE TABLE Afferisce (
	IDFilm INT NOT NULL,
	IDCast INT NOT NULL,
	PRIMARY KEY (IDFilm, IDCast),
	FOREIGN KEY (IDFilm) REFERENCES Film(ID) ON DELETE CASCADE,
	FOREIGN KEY (IDCast) REFERENCES CastFilm(ID) ON DELETE CASCADE
);
