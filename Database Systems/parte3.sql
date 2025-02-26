SET search_path to "dummysocialmarket";

/************** INFORMAZIONI**************/
-- Kb, numero di pagine e tuple delle tabelle
SELECT C.relname, C.relpages, pg_size_pretty(pg_relation_size(C.oid)) as kb, C.reltuples
FROM pg_namespace N JOIN pg_class C ON N.oid = C.relnamespace
WHERE  N.nspname = 'dummysocialmarket';


/********* TRE INTERROGAZIONI *********/
--seleziono i clienti che hanno ente Renault il reddito minore di 2500 o maggiore di 6000 e con data inizio maggiore del 18 luglio 2023
SELECT Cod_Fisc
FROM clienti
WHERE ente='Renault' AND (reddito < 2500 or reddito> 6000) AND data_inizio>='07/18/2023';

--indice su ente
CREATE INDEX ind_ente
ON clienti(ente);
CLUSTER clienti USING ind_ente;
--DROP INDEX ind_ente;

--tempo senza indice: 70 ms
-- tempo con: 49 ms


-- Il prodotto con quantità in uscita = 3
SELECT Cod_Prod
FROM ProdottiUscita
WHERE quantità= 3;
--indice su quantità di prodottiuscita
CREATE INDEX ind_quant
ON ProdottiUscita(quantità);
--DROP INDEX ind_quant;

--tempo senza indice: 65ms
-- tempo con: 46ms


-- I codici fiscali dei volontari che hanno appuntamento 
SELECT Volontari.id_Vol
FROM Volontari
JOIN Appuntamenti ON Appuntamenti.id_Vol=Volontari.id_Vol;

--creazione inidice sul volontario in appuntamenti
CREATE INDEX ind_a
ON Appuntamenti(id_Vol);
--DROP INDEX ind_quant;

--creazione indice sul volontario in volontari
CREATE INDEX ind_b
ON Volontari(id_Vol);
--DROP INDEX ind_quant;

--tempo esecuzuone hash: 86 ms
--tempo esecuzuone merge: 50 ms


/**************  TRANSAZIONI   ***************/
-- Selezionare i crediti di tutte le famiglie
-- Aggiungo 10 al credito della famiglia f0008
-- Seleziono le informazioni della famiglia f0008 per verificarne l’adeguatezza del vincolo check (non può essere maggiore di 60)

SET search_path to "socialmarket";
BEGIN;
SET CONSTRAINTS ALL IMMEDIATE;
SET TRANSACTION ISOLATION LEVEL READ COMMITTED ;
-- guardo i crediti mensili delle famiglie
SELECT Cod_Fam, punti_mensili
FROM Nuclei_Familiari
GROUP BY Cod_Fam;

-- Operazione di scrittura
-- vado ad aggiornare i punti con il valore nella famiglia con codice f0008 
UPDATE Nuclei_Familiari
SET Punti_mensili = Punti_mensili+10
WHERE Cod_Fam = 'f0008';

-- Leggo dati aggiornati 
SELECT *
FROM Nuclei_Familiari
WHERE Cod_Fam = 'f0008';

COMMIT;


/**************  PRIVILEGI   ***************/
--alice (corrispondente a un gestore del market)
-- roberto (corrispondente a un volontario)

CREATE USER Alice PASSWORD 'gestore';
CREATE USER Roberto PASSWORD 'volontario';
GRANT USAGE ON SCHEMA "socialmarket" TO Alice;
GRANT USAGE ON SCHEMA "socialmarket" TO Roberto;

--permessi di Alice
-- Essendo gestore avrà abbastanza permessi
-- Le revoco la delete sulle persone, clienti, nuclei, volontari, inventario e enti
GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA "socialmarket" TO Alice;
REVOKE DELETE ON Persone, Nuclei_Familiari, Clienti, Volontari, Inventario, Enti FROM Alice;

--permessi di Roberto
-- do i diritti necessari affinche' possa svolgere l'attività di volontario al meglio
-- può vedere tutto ma ha delle limitazioni essendo un volontario sull' update delete e insert
GRANT SELECT, UPDATE ON ALL TABLES IN SCHEMA "socialmarket" TO Roberto;
REVOKE UPDATE ON Persone, Volontari, Enti, Donazioni, ScaricoProdotti FROM Roberto;
GRANT DELETE ON Appuntamenti, Trasporti, ProdottiUscita, ProdottiEntrata TO Roberto;
GRANT INSERT ON Inventario, ProdottiUscita, ProdottiEntrata TO Roberto;
