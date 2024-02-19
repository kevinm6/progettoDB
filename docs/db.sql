-- Creazione database
--RAISERROR(N'Test', 16, 1);
RETURN;
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
  primary key(codice_univoco, corso_di_laurea),
  unique (corso_di_laurea, nome),
  foreign key (corso_di_laurea) references corso_di_laurea(id)
);

create table esame(
  corso_di_laurea integer not null,
  insegnamento integer not null,
  data date not null,
  primary key (corso_di_laurea, insegnamento, data),
  unique(corso_di_laurea, data),
  foreign key (corso_di_laurea, insegnamento) references insegnamento(corso_di_laurea, codice_univoco)
);

create table iscrizione_esame(
  cdl integer not null,
  insegnamento integer not null,
  data date not null,
  studente integer not null,
  primary key (corso_di_laurea, insegnamento, data, studente),
  foreign key (corso_di_laurea, insegnamento, data) references esame(corso_di_laurea, insegnamento, data) on delete cascade,
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
  primary key (corso_di_laurea, insegnamento, data, studente),
  foreign key (corso_di_laurea, insegnamento, data) references esame(corso_di_laurea, insegnamento, data),
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
  primary key (corso_di_laurea, insegnamento, data, studente),
  foreign key (corso_di_laurea, insegnamento, data) references esame(corso_di_laurea, insegnamento, data),
  foreign key (studente) references studente(matricola)
);


create table propedeuticita(
  corso_di_laurea_main integer not null,
  corso_di_laurea_dep integer check ( corso_di_laurea_main = corso_di_laurea_dep ) not null,
  insegnamento integer not null,
  propedeutico_a integer check ( insegnamento != propedeutico_a ),
  primary key (corso_di_laurea_main, insegnamento, propedeutico_a),
  foreign key (corso_di_laurea_main, insegnamento) references insegnamento(corso_di_laurea, codice_univoco),
  foreign key (corso_di_laurea_dep, propedeutico_a) references insegnamento(corso_di_laurea, codice_univoco)
);


-- view per le carriere degli studenti
create view carriere as
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

-----------------------------Functions -----------------------------


-----------------------------Triggers -----------------------------

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

-- Trigger per controllare che un docente puo` avere al massimo 3 insegnamenti di cui è responsabile
create or replace function limite_docente_responsabile_corsi()
returns trigger as $$
begin
  if exists (
    select 1 from corso_di_laurea
    where responsabile = new.responsabile
    and id != new.id
  ) then raise exception 'Un docente può avere al massimo 3 insegnamenti di cui è responsabile'
  end if;
  return new;
end;
$$ language plpgsql;

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
      and studente = new.studente
  else
    raise exception "Nessuna iscrizione trovata per l'esame."
  end if;
  return new;
end;
$$ language plpgsql;

create trigger trig_check_iscrizione_esame
before insert on carriera
for each row execute check_iscrizione_esame();
