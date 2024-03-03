-- Database: `minifacebook`


-- --------------------------------------------------------



Create database miniFacebook;

use miniFacebook;


-- Table structure for table `Città`



CREATE TABLE Città(
    nome varchar(20) NOT NULL,
    nazione varchar(20) NOT NULL,
    provincia varchar(20) NOT NULL,
    PRIMARY KEY (nome,nazione,provincia)
);


-- Table structure for table `Utenti`



CREATE TABLE Utenti(
    email varchar(50) PRIMARY KEY,
    nome varchar(20),
    cognome varchar(20),
    sesso enum('M','F'),
    valutazioneMedia float NOT NULL DEFAULT 5 CHECK (valutazioneMedia BETWEEN 1 AND 10),
    amministratore enum('amministratore'),
    cittàNascita varchar(20),
    nazioneNascita varchar(20),
    provinciaNascita varchar(20),
    dataNascita date,
    amministratoreBlocca varchar(50) CHECK ((amministratore = 'amministratore' AND amministratoreBlocca IS NULL) OR amministratore IS NULL),
    cittàResidenza varchar(20),
    nazioneResidenza varchar(20),
    provinciaResidenza varchar(20),
    FOREIGN KEY (amministratoreBlocca) REFERENCES Utenti(email)    
     ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (cittàNascita,nazioneNascita,provinciaNascita) REFERENCES Città(nome,nazione,provincia)  
     ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (cittàResidenza,nazioneResidenza,provinciaResidenza) REFERENCES Città(nome,nazione,provincia)   
     ON DELETE CASCADE ON UPDATE CASCADE
);


-- Table structure for table `Hobby`



CREATE TABLE Hobby(
    tipo varchar(20) PRIMARY KEY
);



-- Table structure for table `Messaggi`



CREATE TABLE Messaggi(
    email varchar(50),
    dataPubblicazione timestamp DEFAULT CURRENT_TIMESTAMP,
    tipo enum('testo','foto') NOT NULL DEFAULT 'testo',
    testo varchar(100),
    nomeFile varchar(50),
    posizioneFileSystem varchar(100),
    descrizione varchar(50),
    nomeCittà varchar(20),
    nazione varchar(20),
    provincia varchar(20),
    PRIMARY KEY (email,dataPubblicazione),
    FOREIGN KEY (email) REFERENCES Utenti(email)    
     ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (nomeCittà,nazione,provincia) REFERENCES Città(nome,nazione,provincia)
     ON DELETE CASCADE ON UPDATE CASCADE
);


-- Table structure for table `Commenti`



CREATE TABLE Commenti(
    emailComment varchar(50),
    emailPost varchar(50),
    dataPubblicazione timestamp DEFAULT CURRENT_TIMESTAMP,
    dataCommento timestamp DEFAULT CURRENT_TIMESTAMP,
    testo varchar(100),
    indiceGradimento int CHECK (indiceGradimento BETWEEN -3 AND 3),
    progressivo int NOT NULL,
    PRIMARY KEY (emailComment,emailPost,dataPubblicazione,dataCommento),
    KEY (progressivo),
    FOREIGN KEY (emailComment) REFERENCES Utenti(email)    
     ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (emailPost,dataPubblicazione) REFERENCES Messaggi(email,dataPubblicazione)    
     ON DELETE CASCADE ON UPDATE CASCADE
);


-- Table structure for table `AmicoDi`



CREATE TABLE AmicoDi(
    Richiedente varchar(50),
    Ricevente varchar(50),
    dataRichiesta timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dataAccettazione timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (Richiedente) REFERENCES Utenti(email),
    FOREIGN KEY (Ricevente) REFERENCES Utenti(email),
    PRIMARY KEY (Richiedente,Ricevente)
);



-- Table structure for table `Praticano`



CREATE TABLE Praticano(
    email varchar(50),
    tipo varchar(20),
    PRIMARY KEY (email,tipo),
    FOREIGN KEY (email) REFERENCES Utenti(email)    
     ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (tipo) REFERENCES Hobby(tipo)    
     ON DELETE CASCADE ON UPDATE CASCADE
);


-- Table structure for table `RiferitiA`



CREATE TABLE RiferitiA(
    emailComment varchar(50),
    emailPost1 varchar(50),
    dataPubblicazionePost1 timestamp DEFAULT CURRENT_TIMESTAMP,
    dataCommento timestamp DEFAULT CURRENT_TIMESTAMP,
    emailPost2 varchar(50),
    dataPubblicazionePost2 timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (emailComment,emailPost1,dataPubblicazionePost1,dataCommento,emailPost2,dataPubblicazionePost2),
    FOREIGN KEY (emailComment,emailPost1,dataPubblicazionePost1,dataCommento) REFERENCES Commenti(emailComment,emailPost,dataPubblicazione,dataCommento)   
     ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (emailPost2,dataPubblicazionePost2) REFERENCES Messaggi(email,dataPubblicazione)
    
);

-- Popolamento delle tabelle con due inserimenti per tabella

INSERT INTO Città (nome,nazione,provincia) VALUES ('Roma','Italia','Roma');
INSERT INTO Città (nome,nazione,provincia) VALUES ('Milano','Italia','Milano');

INSERT INTO Utenti (email,nome,cognome,sesso) VALUES ('riccardo.cataldi@studenti.unimi.it','Riccardo','Cataldi','M');
INSERT INTO Utenti (email,nome,cognome,sesso) VALUES ('luca.giussani2@studenti.unimi.it','Luca','Giussani','M');
INSERT INTO Utenti (email,nome,cognome,sesso) VALUES ('giovanni.bonadeo@studenti.unimi.it','Giovanni','Bonadeo','M');
INSERT INTO Utenti (email,nome,cognome,sesso) VALUES ('laura.capella@studenti.unimi.it','Laura','Capella','F');

INSERT INTO Hobby (tipo) VALUES ('Scacchi');
INSERT INTO Hobby (tipo) VALUES ('Nuoto');

INSERT INTO AmicoDi (Richiedente,Ricevente,dataRichiesta,dataAccettazione) VALUES ('riccardo.cataldi@studenti.unimi.it','luca.giussani2@studenti.unimi.it','2018-01-01 00:10:05','2018-01-01 00:11:05');
INSERT INTO AmicoDi (Richiedente,Ricevente,dataRichiesta,dataAccettazione) VALUES ('giovanni.bonadeo@studenti.unimi.it','laura.capella@studenti.unimi.it','2010-10-01 10:00:10','2010-10-01 10:11:10');
INSERT INTO AmicoDi (Richiedente,Ricevente,dataRichiesta,dataAccettazione) VALUES ('giovanni.bonadeo@studenti.unimi.it','luca.giussani2@studenti.unimi.it','2011-01-01 00:10:05','2011-01-01 00:30:05');
INSERT INTO AmicoDi (Richiedente,Ricevente,dataRichiesta) VALUES ('riccardo.cataldi@studenti.unimi.it','laura.capella@studenti.unimi.it','2017-03-01 11:11:05');


-- Testo
INSERT INTO Messaggi (email,dataPubblicazione,testo) VALUES ('giovanni.bonadeo@studenti.unimi.it','2018-01-01 00:10:05','Messaggio di Giovanni');
INSERT INTO Messaggi (email,dataPubblicazione,testo) VALUES ('luca.giussani2@studenti.unimi.it','2018-01-01 00:11:30','Messaggio di Luca');
INSERT INTO Messaggi (email,dataPubblicazione,testo) VALUES ('laura.capella@studenti.unimi.it','2010-10-01 10:00:10','Messaggio di Laura');
INSERT INTO Messaggi (email,dataPubblicazione,testo) VALUES ('riccardo.cataldi@studenti.unimi.it','2020-01-01 03:50:00','Messaggio di Riccardo');

-- Foto
INSERT INTO Messaggi (email,dataPubblicazione,tipo,nomeFile,posizioneFileSystem,descrizione,nomeCittà,nazione,provincia) VALUES ('riccardo.cataldi@studenti.unimi.it','2017-03-01 11:11:05','foto','foto1','/home/riccardo/foto1','Foto di Riccardo','Roma','Italia','Roma');
INSERT INTO Messaggi (email,dataPubblicazione,tipo,nomeFile,posizioneFileSystem,descrizione,nomeCittà,nazione,provincia) VALUES ('luca.giussani2@studenti.unimi.it','2020-01-01 12:50:05','foto','foto2','/home/luca/foto2','Foto di Luca','Milano','Italia','Milano');

INSERT INTO Commenti (emailComment,emailPost,dataPubblicazione,dataCommento,testo) VALUES ('luca.giussani2@studenti.unimi.it','giovanni.bonadeo@studenti.unimi.it','2018-01-01 00:10:05','2019-01-01 00:10:05','Luca commenta il post di Giovanni');
INSERT INTO Commenti (emailComment,emailPost,dataPubblicazione,dataCommento,testo) VALUES ('laura.capella@studenti.unimi.it','giovanni.bonadeo@studenti.unimi.it','2018-01-01 00:10:05','2019-01-01 00:11:05','Laura commenta il post di Giovanni');



INSERT INTO Praticano (email,tipo) VALUES ('luca.giussani2@studenti.unimi.it','Scacchi');
INSERT INTO Praticano (email,tipo) VALUES ('giovanni.bonadeo@studenti.unimi.it','Nuoto');

INSERT INTO RiferitiA (emailComment,emailPost1,dataPubblicazionePost1,dataCommento,emailPost2,dataPubblicazionePost2) VALUES ('luca.giussani2@studenti.unimi.it','giovanni.bonadeo@studenti.unimi.it','2018-01-01 00:10:05','2019-01-01 00:10:05','laura.capella@studenti.unimi.it','2010-10-01 10:00:10');
INSERT INTO RiferitiA (emailComment,emailPost1,dataPubblicazionePost1,dataCommento,emailPost2,dataPubblicazionePost2) VALUES ('laura.capella@studenti.unimi.it','giovanni.bonadeo@studenti.unimi.it','2018-01-01 00:10:05','2019-01-01 00:11:05','riccardo.cataldi@studenti.unimi.it','2017-03-01 11:11:05');


