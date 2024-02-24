---
File          : manuale_utente.md
Author        : Kevin Manca
output        :
   pdf_document: default
---

# Manuale Utente di PGEU

<!--toc:start-->
- [Manuale Utente di PGEU](#manuale-utente-di-pgeu)
  - [Requisiti minimi](#requisiti-minimi)
<!--toc:end-->

---

> Il seguente manuale utente è relativo al progetto del corso di Basi di Dati

---

## Requisiti minimi

- **php 8+**
- **postgres 15.x**

> Nota  
> PHP integra già un web server nelle versioni più recenti.  
> Tuttavia nel caso in cui non si possieda una versione abbastanza aggiornata di PHP  
> è necessario installare anche un Web Server (come [Apache](https://httpd.apache.org)) e  
> configurarlo in modo da potersi interfacciare con PHP come indicato nella documentazione del  
> software stesso.
> Il progetto non è stato testato in versioni precedenti dei software indicati,  
> quindi il relativo funzionamento pogtrebbe essere compromesso.

---

### Setup

Se i requisiti indicati sono soddisfatti, è sufficiente:

- estrarre il contenuto dell'archivio  
- posizionarsi nella cartella di root `cd BDLAB_978578_MancaKevin`
- modificare i parametri del file `.env` contenente la configurazione del Database (se necessario)  
- eseguire il restore del db tramite `pg_restore -U Kevin -d pgeu_db db_dumps/db.dump`
- avviare il server PHP (dalla directory root, con il file **index.php**)
   - eseguendo il commando `php -S localhost:9000` o specificando una porta diversa dalla *9000*
