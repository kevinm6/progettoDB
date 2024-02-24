---
File          : documentazione_tecnica.md
Author        : Kevin Manca
Matricola     : 978578
output        :
   pdf_document: default
---

# Documentazione Tecnica

---

## Progetto Basi di Dati

**PGEU**: **P**iattaforma per la **G**estione degli **E**sami **U**niversitari

---

Gli schemi **logico** ed **E-R** si trovano nella cartella `/docs` con il loro relativo nome  

[Schema logico](./schema_logico.pdf) &rarr; `./schema_logico.pdf`  

[Schema E-R](./ER_schema.pdf) &rarr; `./ER_schema.pdf`  

---

### Entità

| Entità    | Identificatore    | Attributi    | Descrizione    |
| :----------------: | :---------------: | :---------------: | --------------- |
| Utente    |  **profilo_utente**, **nome_utente**, **password**   | nome\_utente, profilo\_utente, password    | utente generico della web-app  |
| Segreteria   | **id**  | id, nome, cognome   | personale della segreteria   |
| Docente   |  **id** | **id**, **nome**, **cognome**, **email**   | personale docente  |
| Studente   | **matricola**   | matricola, nome, cognome, email   | studenti  |
| Corso di Laurea   | **nome**, **tipologia**   | nome, tipologia  | un corso di laurea   |
| Insegnamento   | **codice_univoco**, **nome(cdl)**, **tipologia(cdl)**   |  codice univoco, nome, descrizione, anno  | insegnamento specifico per un corso di laurea |
| Esame   | **data** | data, insegnamento.codice\_univoco, cdl\.id   |  esame di uno specifico insegnamento  |
| Studente\_storico   | **matricola**  | **nome**, **cognome**, **email**   | informazioni di uno studente rimosso   |  

### Relazioni

| Relazione    | Entità coinvolte  | Attributi    | Descrizione |
| :----------------: | :---------------: | :---------------: | --- |
| login_segreteria    |  Utente(1, 1), Segreteria(1, 1)   | nome_utente, profilo_utente, password    | utente segreteria con relativi privilegi |  
| login_docente   |  Utente(1, 1), Docente(1, 1) | id, nome, cognome   | utente docente con relative funzioni specifiche |  
| login_studente   |  Utente(1, 1), Studente(1, 1) | **id**, **nome**, **cognome**, **email**   |  utente studente con le funzioni relative |  
| calendario   |  Insegnamento(0, N), Esame(1, 1)  | -  | calendario degli esami |  
| iscrizione   | Corso di laurea(0, N), Studente(1, 1)   | anno  | associa uno studente al corso di laurea a cui è iscritto |  
| iscrizione_esame   | Studente(0, N), Esame(0, N)   | nome, tipologia  | associa gli studenti agli esami a cui sono iscritti |  
| iscrizione_passata   | Corso di laurea(0, N), Storico_studente(1, 1)   | nome, tipologia  | studente rimosso associato corso_di_laurea a cui era iscritto |  
| carriera   | Studente(0, N), Esame(0, N)   |  voto, esito  | associa studenti ed esami, con informazioni sul voto ottenuto per gli studenti che hanno sostenuto un dato esame |  
| carriera_storico   | Studente_storico(0, N), Esame(0, N) |  voto, esito  | come l'altra carriera ma per studenti rimossi |  
| compone   | **data**, **Insegnamento.codice_univoco**, **corso_di_laurea.id**   |  - | associa un insegnamento ad un corso di laurea di cui fa parte |  
| responsabile_cdl   | Docente(0, 1), Corso_di_Laurea(1, 1)   | -  | associa un docente ad un corso di laurea di cui è responsabile |  
| responsabile_insegnamento   | Docente(0, 3), Insegnamento(1, 1)   |  -  | associa un docente ad un insegnamento del quale è responsabile |  
| propedeuticità   | Insegnamento(0, N), Insegnamento(0, N) | -  | associa un insegnamento agli insegnamenti di cui è propedeutico |  

---

#### Vincoli dei dati

Dalla specifica richiesta sono stati considerati i seguenti vincoli:

1. L'attributo tipologia può assumere i valori "triennale" o "magistrale"  
2. Ogni docente è responsabile di al massimo 3 insegnamenti e massimo 1 corso di laurea  
3. Uno studente può iscriversi ad un esame relativo ad un insegnamento solo se ha superato tutti gli
esami propedeutici ad esso  
4. uno studente può sostentere un esame relativo ad un insegnamento solo se è previsto dal suo CdL  
5. esami relativi ad insegnamenti dello stesso CdL non possono avere la medesima data  
6. L'attributo profilo\_utente può assumere solo uno dei valori tra "studente", "docente", "segreteria"  
7. L'attributo esito deve avere un valore positivo se >= 18 o negativo se < 18 e comunque compreso
tra 0 e 30  
8. Un utente può partecipare all'associazione login solo relativa al suo profilo\_utente
corrispondente

---

##### Scelte implementative

La maggior parte delle scelte implementative sono state dettate dalla specifica.  
L'entità **Utente** rappresenta il generico utente della web-app, identificato univocamente attraverso  
l'attributo **nome_utente**; è costituito dagli altri attributi che ne facilitano il
funzionamento dell'app stessa come **password** e il **profilo_utente**.  
Per la **segreteria**, l'attributo **id** è quello che permette di identificare il personale univocamente.  
Per l'entità **studente** l'attributo **matricola** è sicuramente quello che lo identifica meglio e in  
modo univoco, poi gli altri attributi aggiungono semplicemente delle informazioni.  
Per i **docenti**, un **codice** **identificativo** è la scelta per identificarli in modo univoco, dovuto  
principalmente alla soluzione personalmente più opportuna rispetto ad altri attributi.  
L'entità **Esame** è identificato univocamente attraverso l'attributo **data**, in quanto per i vincoli,  
non possono esserci due appelli dello stesso insegnamento nella stessa data.  

**Relazioni e cardinalità**

- **login_studente**
   - Utente (1,1): ad un utente è associato uno studente
   - Studente (1,1): ad uno studente è associato un Utente

- **login_docente**
   - Utente (1,1): ad un utente è associato un docente
   - Docente (1,1): ad un docente è associato un utente

- **login_segreteria**
   - Utente (1,1): ad un Utente e' associato una sola Segreteria
   - Segreteria (1,1): una Segreteria e' associata ad un solo Utente.

- **iscrizione**
   - Studente (1, 1): in quanto uno studente è obbligatoriamente iscritto ad uno e uno solo corso di laurea
   - Corso di laurea (0, N)    
      - min 0: corso di laurea con nessun studente iscritto  
      - max N: nessun limite imposto per il numero di studenti a un cdl (no numero chiuso)  

- **calendario**
   - Insegnamento (0,N): nessuna data di esame o N
   - Esame (1,1): è riferito ad un solo insegnamento

- carriera:
   - Studente (0,N): uno studente con nessun esame dato o N
   - Esame (0,N): un esame sostenuto da nessuno studente o N

- **compone**
   - Corso di laurea (1,N): corso di laurea ha almeno un insegnamento o N
   - Insegnamento (1,1): un insegnamento fa riferimento ad un unico cdl

- **iscrizione_esami**
   - Studente (0,N): studente iscritto a nessun esame o N
   - Esame (0,N): esame con nessun iscritto o N

- **storico_carriera**
   - Studente_storico (0,N): uno studente con nessun esame sostenuto o N
   - Esame (0,N): un esame sostenuto da nessuno studente o N

- **propedeuticità**
   - insegnamento (0,N)
   - min 0: un insegnamento può non essere propedeutico ad altri insegnamenti e può non avere propedeuticità
   - max N: un insegnamento può essere propedeutico a più insegnamenti e avere più propedeuticità

- **responsabilita' insegnamento**
   - Docente (0,3): un docente può non essere responsabile di insegnamenti o averne al massimo 3  
   - Insegnamento (1,1): un insegnamento deve avere al massimo un responsabile

- **responsabilità corso**
   - Corso di laurea (1,1): un corso di laurea ha obbligatoriamente e uno e un solo responsabile
   - Docente (0,1): un docente può non essere responsabile di un cdl, ma al massimo lo è di uno

- **carriera**
   - Studente (0,N): uno studente può non aver sostenuto esami o averne sostenuti N
   - Esame (0,N): un esame può non essere stato sostenuto da nessuno o da N studenti

- **iscrizione_passata**
   - Storico_studente (1,1): uno studente deve essere iscritto ad uno e un solo cdl
   - Corso di laurea (0,N):
      - min 0: cdl senza studenti iscritti
      - max N: nessun limite di studenti iscritti ad un cdl (no numero chiuso)
