-- Creazione database
--RAISERROR(N'Test', 16, 1);
return;
create database pgeu_db;
-- create schema project;

set search_path to public;

create domain tipologia_cdl as varchar(10)
check (value in ('triennale', 'magistrale'));

create domain userprofile as varchar(10)
check (value in ('segreteria', 'docente', 'studente'));

create table utente(
  nome_utente varchar(20) primary key,
  password text not null,
  profilo_utente userprofile not null
);

create table segreteria(
  id integer primary key generated always as identity,
  utente varchar(20) references utente(nome_utente) on update cascade on delete cascade not null unique,
  nome varchar(20),
  cognome varchar(20)
);

create table docente(
  id integer primary key generated always as identity,
  utente varchar(20) references utente(nome_utente) on update cascade on delete cascade not null unique,
  nome varchar(20),
  cognome varchar(20),
  email varchar(30) not null unique
);

create table corso_di_laurea(
  id integer primary key generated always as identity,
  responsabile integer references docente(id) not null,
  tipologia tipologia_cdl not null,
  nome varchar(32) not null,
  unique(nome, tipologia)
);

create table studente(
  matricola integer primary key generated always as identity,
  cdl integer references corso_di_laurea(id) on update cascade on delete cascade not null,
  utente varchar(20) references utente(nome_utente) on update cascade on delete cascade not null unique,
  nome varchar(20),
  cognome varchar(20),
  email varchar(30) unique
);

create table insegnamento(
  codice_univoco serial not null,
  cdl integer not null,
  responsabile integer references docente(id) not null,
  nome varchar(40) not null,
  descrizione text,
  anno smallint check (anno > 0 and anno < 4) not null,
  primary key(codice_univoco, cdl),
  unique (cdl, nome),
  foreign key (cdl) references corso_di_laurea(id)
);

create table esame(
  cdl integer not null,
  insegnamento integer not null,
  data date not null,
  primary key (cdl, insegnamento, data),
  unique(cdl, data),
  foreign key (cdl, insegnamento) references insegnamento(codice_univoco, cdl)
);

create table iscrizione_esame(
  cdl integer not null,
  insegnamento integer not null,
  data date not null,
  studente integer not null,
  primary key (cdl, insegnamento, data, studente),
  foreign key (cdl, insegnamento, data) references esame(cdl, insegnamento, data) on delete cascade,
  foreign key (studente) references studente(matricola) on delete cascade
);

create table carriera(
  cdl integer not null,
  insegnamento integer not null,
  data date not null,
  studente integer not null,
  voto smallint check ( voto >= 0 and voto <= 30 ) not null,
  esito varchar(8) generated always as (
    case
      when voto >= 18 then 'superato'
      else 'respinto'
    end
  ) stored,
  primary key (cdl, insegnamento, data, studente),
  foreign key (cdl, insegnamento, data) references esame(cdl, insegnamento, data),
  foreign key (studente) references studente(matricola) on delete cascade
);

create table studente_storico(
  matricola integer primary key,
  cdl integer references corso_di_laurea(id) on update cascade not null,
  nome varchar(20),
  cognome varchar(20),
  email varchar(30) unique
);


create table carriera_storico(
  cdl integer not null,
  insegnamento integer not null,
  data date not null,
  studente integer not null,
  voto smallint check ( voto >= 0 and voto <= 30 ) not null,
  esito varchar(8),
  primary key (cdl, insegnamento, data, studente),
  foreign key (cdl, insegnamento, data) references esame(cdl, insegnamento, data),
  foreign key (studente) references studente_storico(matricola)
);


create table propedeuticita(
  cdl_main integer not null,
  cdl_dep integer check ( cdl_main = cdl_dep ) not null,
  insegnamento integer not null
  propedeutico_a integer check ( insegnamento != propedeutico_a ),
  primary key (cdl_main, insegnamento, propedeutico_a),
  foreign key (cdl_main, insegnamento) references insegnamento(cdl, codice_univoco),
  foreign key (cdl_dep, propedeutico_a) references insegnamento(cdl, codice_univoco)
);


-- view per le carriere degli studenti
create view carriera_ok as
  select c.studente, c.insegnamento, c.cdl, c.data, c.voto, c.esito
  from carriera c
  where c.esito = 'superato' and c.data = (
    select max(c_alt.data)
    from carriera c_alt
    where c_alt.insegnamento = c.insegnamento
      and c_alt.cdl = c.cdl
      and c_alt.studente = c.studente
  );
)

-- view per le carriere degli studenti rimossi
create view carriera_valida_studenti_rimossi as 
    select c.studente, c.insegnamento, c.cdl, c.data, c.voto, c.esito
    from carriera_storico c 
    where c.esito = 'superato' and c.data = (
        select max(c1.data)
        from carriera_storico c1
        where c1.insegnamento = c.insegnamento
          and c1.cdl = c.cdl
          and c1.studente = c.studente
    );


-----------------------------triggers -----------------------------

create or replace function check_valid_date()
returns trigger as $$
begin
    if new.data < current_date then
        raise exception 'impossibile inserire un esame con data precedente alla data odierna';
    end if;
    return new;
end;
$$ language plpgsql;


create trigger block_date_enter
before insert or update on esame
for each row
execute function check_valid_date();


create or replace function verifica_data_esami_univoca()
returns trigger as $$
begin
    if exists (
        select 1
        from esame
        where cdl = new.cdl
          and data = new.data
    ) then
        raise notice 'non è possibile inserire un record con la stessa data e corso di laurea.';
    end if;
    return new;
end;
$$ language plpgsql;

create trigger trigger_verifica_data_esami_univoca
before insert or update on esame
for each row
execute function verifica_data_esami_univoca();

create or replace function check_valid_subscription()
returns trigger as $$
declare
studente_cdl integer;
n_propedeuticita integer;
n_esami_promossi integer;
begin
    -- ottieni il corso di laurea dello studente
    
    select corso_di_laurea into studente_cdl
    from studente
    where matricola = new.studente;
    
    -- controllo che lo studente sia iscritto al corso di laurea dell'esame a cui si vuole iscrivere
    if studente_cdl != new.cdl then
        raise exception 'lo studente non appartiene al CdL dell esame selezionato.';
    end if;

    -- conto il numero di esami propedeutici all'esame che ci si vuole iscrivere
    select count(*) into n_propedeuticita
    from propedeuticita
    where propedeutico_a = new.insegnamento;

    -- conto il numero di esami promossi per lo studente che si vuole iscrivere tra quelli propedeutici all'esame a cui si vuole iscrivere
    -- controllo questo tramite un join tra carriera_valida (per non avere voti duplicati) e propedeuticita'
    select count(*) into n_esami_promossi
    from propedeuticita p join carriera_valida c on p.cdl_main = c.corso_di_laurea and p.insegnamento = c.insegnamento
    where c.studente = new.studente and p.cdl_main = new.cdl and p.propedeutico_a = new.insegnamento and c.esito = 'superato';
    
    -- se i 2 numeri non coincidono sollevo un eccezione
    if n_propedeuticita != n_esami_promossi then
        raise exception 'studente non idoneo a iscriversi, non ha superato tutti gli esami propedeutici.';
    end if;

    return new;
end;
$$ language plpgsql;

create trigger trigger_check_valid_subscription
before insert on iscrizione_esami
for each row
execute function check_valid_subscription();


-- trigger che inserisce nello storico carriera
create or replace function inserimento_storico_carriera()
returns trigger as $$
begin
    insert into carriera_storico (data, insegnamento, cdl, studente, voto, esito)
    values (old.data, old.insegnamento, old.cdl, old.studente, old.voto, old.esito);
    return old;
end;
$$ language plpgsql;

create trigger inserimento_storico_carriera_trigger
after delete on carriera
for each row
execute function inserimento_storico_carriera();


create or replace function inserimento_storico_studente()
returns trigger as $$
begin
    insert into studente_storico (matricola, cdl, nome, cognome, email)
    values (old.matricola, old.cdl, old.nome, old.cognome, old.email);
    return old;
end;
$$ language plpgsql;

create trigger trigger_inserimento_storico_studente
before delete on studente
for each row
execute function inserimento_storico_studente();



create or replace function hash_password()
returns trigger as $$
begin
  new.passwd := md5(new.passwd);
  return new;
end;
$$ language plpgsql;

create trigger hash_password_trigger
before insert or update on utente
for each row execute function hash_password();

-- trigger per controllare che un docente puo` avere al massimo 3 insegnamenti di cui è responsabile
create or replace function limite_docente_responsabile_corsi()
returns trigger as $$
begin
  if exists (
    select 1 from corso_di_laurea
    where responsabile = new.responsabile
    and id != new.id
  ) then raise exception 'un docente può avere al massimo 3 insegnamenti di cui è responsabile';
  end if;
  return new;
end;
$$ language plpgsql;

create or replace function controllo_numero_responsabile_insegnamenti()
returns trigger as $$
declare
    total_insegnamenti integer;
begin
    select count(*) into total_insegnamenti
    from insegnamento
    where responsabile = new.responsabile;

    if total_insegnamenti >= 3  and new.responsabile != old.responsabile then
        raise exception 'un docente non puo essere responsabile di più di 3 insegnamenti.';
    end if;
    return new;
end;
$$ language plpgsql;

create trigger trigger_controllo_numero_responsabile_insegnamenti
before insert or update on insegnamento
for each row
execute function controllo_numero_responsabile_insegnamenti();


-- trigger inserimento studente_storico
create or replace function entry_studente_storico()
returns trigger as $$
begin
  insert into studente_storico (matricola, cdl, nome, cognome, email)
  values (old.matricola, old.cdl, old.nome, old.cognome, old.email);
  return old;
end;
$$ language plpgsql;

create trigger trig_entry_studente_storico
before delete on studente
for each row execute function entry_studente_storico();


create or replace function check_students_dups()
returns trigger as $$
begin
    if exists (
        select 1 from studente_storico where matricola = new.matricola
    ) then
        raise exception 'impossibile inserire: la matricola è presente in studente_storico.';
    end if;
    return new;
end;
$$ language plpgsql;

create trigger avoid_students_dups
before insert or update on studente
for each row
execute function check_students_dups();

create or replace function check_valid_year()
returns trigger as $$
declare
    tipologia_corso tipologia_corso;
begin
    select tipologia into tipologia_corso
    from corso_di_laurea
    where id = new.cdl;
    
    if tipologia_corso = 'triennale' and (new.anno < 1 or new.anno > 3) then
        raise exception 'attributo anno deve essere compreso tra 1 e 3 per i corsi triennali';
    end if;
    
    if tipologia_corso = 'magistrale' and (new.anno < 1 or new.anno > 2) then
        raise exception 'attributo anno deve essere compreso tra 1 e 2 per i corsi magistrali';
    end if;
    
    return new;
end;
$$ language plpgsql;

create trigger trigger_controlla_validita_anno
before insert or update on insegnamento
for each row
execute function check_valid_year();


-- check_iscrizione_esame e relativo trigger
create or replace function check_iscrizione_esame()
returns trigger as $$
begin
  if exists (
    select 1 from iscrizione_esame
    where data = new.data
      and insegnamento = new.insegnamento
      and cdl = new.cdl
      and studente = new.studente
  ) then
    delete from iscrizione_esame
    where data = new.data
      and insegnamento = new.insegnamento
      and cdl = new.cdl
      and studente = new.studente;
  else
    raise exception 'nessuna iscrizione trovata per esame.';
  end if;
  return new;
end;
$$ language plpgsql;

create trigger trig_check_iscrizione_esame
before insert on carriera
for each row execute check_iscrizione_esame();


create or replace function check_valid_requirements()
returns trigger as $$
begin
    if (select anno from insegnamento where codice_univoco = new.insegnamento) >= (select anno from insegnamento where codice_univoco = new.propedeutico_a) then
        raise exception 'insegnamento propedeutico non puo essere di un anno successivo';
    end if;
    return new;
end;
$$ language plpgsql;

create trigger trigger_check_valid_requirements
before insert or update on propedeuticita
for each row
execute function check_valid_requirements();
