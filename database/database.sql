CREATE TABLE Film (
	ID SERIAL NOT NULL,
	Titolo VARCHAR(255) NOT NULL,
	Genere VARCHAR(20) NOT NULL,
	DataUscita DATE NULL,
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
	FOREIGN KEY (NumeroSala) REFERENCES Sala(Numero)
);

CREATE TABLE Proiezione (
	ID SERIAL NOT NULL,
	Data DATE NOT NULL,
	Orario TIME NOT NULL,
	IDFilm SERIAL NOT NULL,
	NumeroSala SMALLINT NOT NULL,
	PRIMARY KEY (ID),
	UNIQUE(Data, Orario, FilmID, NumeroSala),
	FOREIGN KEY (IDFilm) REFERENCES Film(ID),
	FOREIGN KEY (NumeroSala) REFERENCES Sala(Numero)
	
);

CREATE TABLE Utente (
	ID SERIAL NOT NULL,
	DataNascita DATE NOT NULL,
	Nome VARCHAR(50) NOT NULL,
	Cognome VARCHAR(50) NOT NULL,
	Username VARCHAR(50) NOT NULL,
	Password VARCHAR(50) NOT NULL,
	Email VARCHAR(100),
	PRIMARY KEY(ID)
);

CREATE TABLE Prenotazione (
	ID SERIAL NOT NULL,
	NumeroPersone SMALLINT NOT NULL,
	IDUtente SERIAL NOT NULL,
	IDProiezione SERIAL NOT NULL,
	PRIMARY KEY (ID),
	FOREIGN KEY(IDUtente) REFERENCES Utente(ID),
	FOREIGN KEY(IDProiezione) REFERENCES Proiezione(ID),
	FOREIGN KEY(NumeroPosto, FilaPosto, NumeroSala) REFERENCES Posto(Numero, Fila, NumeroSala)
);

CREATE TABLE Partecipazione (
	NumeroPosto SMALLINT NOT NULL,
	FilaPosto CHAR(1) NOT NULL,
	NumeroSala SMALLINT NOT NULL,
	IDPrenotazione SERIAL NOT NULL,
	PRIMARY KEY (NumeroPosto, FilaPosto, NumeroSala, IDPrenotazione),
	FOREIGN KEY(IDUtente) REFERENCES Utente(ID),
	FOREIGN KEY(IDProiezione) REFERENCES Proiezione(ID),
	FOREIGN KEY(NumeroPosto, FilaPosto, NumeroSala) REFERENCES Posto(Numero, Fila, NumeroSala)
);

CREATE TABLE Cast (
	Nome VARCHAR(50) NOT NULL,
	Cognome VARCHAR(50) NOT NULL,
	Ruolo CHAR(1) NOT NULL,
	IDFilm SERIAL NOT NULL,
	CHECK (UPPER(Ruolo)='A' OR UPPER(Ruolo)='R'),
	PRIMARY KEY (Nome, Cognome, IDFilm),
	FOREIGN KEY (IDFilm) REFERENCES Film(ID)
);
