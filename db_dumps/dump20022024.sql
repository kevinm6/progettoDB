--
-- PostgreSQL database dump
--

-- Dumped from database version 14.11 (Homebrew)
-- Dumped by pg_dump version 14.11 (Homebrew)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: project; Type: SCHEMA; Schema: -; Owner: Kevin
--

CREATE SCHEMA project;


ALTER SCHEMA project OWNER TO "Kevin";

--
-- Name: tipologia_cdl; Type: DOMAIN; Schema: public; Owner: Kevin
--

CREATE DOMAIN public.tipologia_cdl AS character varying(10)
	CONSTRAINT tipologia_cdl_check CHECK (((VALUE)::text = ANY ((ARRAY['triennale'::character varying, 'magistrale'::character varying])::text[])));


ALTER DOMAIN public.tipologia_cdl OWNER TO "Kevin";

--
-- Name: tipologia_corso; Type: DOMAIN; Schema: public; Owner: Kevin
--

CREATE DOMAIN public.tipologia_corso AS character varying(10)
	CONSTRAINT tipologia_corso_check CHECK (((VALUE)::text = ANY ((ARRAY['triennale'::character varying, 'magistrale'::character varying])::text[])));


ALTER DOMAIN public.tipologia_corso OWNER TO "Kevin";

--
-- Name: userprofile; Type: DOMAIN; Schema: public; Owner: Kevin
--

CREATE DOMAIN public.userprofile AS character varying(10)
	CONSTRAINT userprofile_check CHECK (((VALUE)::text = ANY ((ARRAY['segreteria'::character varying, 'docente'::character varying, 'studente'::character varying])::text[])));


ALTER DOMAIN public.userprofile OWNER TO "Kevin";

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: carriera; Type: TABLE; Schema: public; Owner: Kevin
--

CREATE TABLE public.carriera (
    corso_di_laurea integer NOT NULL,
    insegnamento integer NOT NULL,
    data date NOT NULL,
    studente integer NOT NULL,
    voto smallint NOT NULL,
    esito character varying(8) GENERATED ALWAYS AS (
CASE
    WHEN (voto >= 18) THEN 'superato'::text
    ELSE 'respinto'::text
END) STORED,
    CONSTRAINT carriera_voto_check CHECK (((voto >= 0) AND (voto <= 30)))
);


ALTER TABLE public.carriera OWNER TO "Kevin";

--
-- Name: carriera_storico; Type: TABLE; Schema: public; Owner: Kevin
--

CREATE TABLE public.carriera_storico (
    corso_di_laurea integer NOT NULL,
    insegnamento integer NOT NULL,
    data date NOT NULL,
    studente integer NOT NULL,
    voto smallint NOT NULL,
    esito character varying(8),
    CONSTRAINT carriera_storico_voto_check CHECK (((voto >= 0) AND (voto <= 30)))
);


ALTER TABLE public.carriera_storico OWNER TO "Kevin";

--
-- Name: corso_di_laurea; Type: TABLE; Schema: public; Owner: Kevin
--

CREATE TABLE public.corso_di_laurea (
    id integer NOT NULL,
    responsabile integer NOT NULL,
    tipologia public.tipologia_cdl NOT NULL,
    nome character varying(32) NOT NULL
);


ALTER TABLE public.corso_di_laurea OWNER TO "Kevin";

--
-- Name: corso_di_laurea_id_seq; Type: SEQUENCE; Schema: public; Owner: Kevin
--

ALTER TABLE public.corso_di_laurea ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.corso_di_laurea_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: docente; Type: TABLE; Schema: public; Owner: Kevin
--

CREATE TABLE public.docente (
    id integer NOT NULL,
    utente character varying(20) NOT NULL,
    nome character varying(20),
    cognome character varying(20),
    email character varying(30) NOT NULL
);


ALTER TABLE public.docente OWNER TO "Kevin";

--
-- Name: docente_id_seq; Type: SEQUENCE; Schema: public; Owner: Kevin
--

ALTER TABLE public.docente ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.docente_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: esame; Type: TABLE; Schema: public; Owner: Kevin
--

CREATE TABLE public.esame (
    corso_di_laurea integer NOT NULL,
    insegnamento integer NOT NULL,
    data date NOT NULL
);


ALTER TABLE public.esame OWNER TO "Kevin";

--
-- Name: insegnamento; Type: TABLE; Schema: public; Owner: Kevin
--

CREATE TABLE public.insegnamento (
    codice_univoco integer NOT NULL,
    corso_di_laurea integer NOT NULL,
    responsabile integer NOT NULL,
    nome character varying(40) NOT NULL,
    descrizione text,
    anno smallint NOT NULL,
    CONSTRAINT insegnamento_anno_check CHECK (((anno > 0) AND (anno < 4)))
);


ALTER TABLE public.insegnamento OWNER TO "Kevin";

--
-- Name: insegnamento_codice_univoco_seq; Type: SEQUENCE; Schema: public; Owner: Kevin
--

CREATE SEQUENCE public.insegnamento_codice_univoco_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.insegnamento_codice_univoco_seq OWNER TO "Kevin";

--
-- Name: insegnamento_codice_univoco_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: Kevin
--

ALTER SEQUENCE public.insegnamento_codice_univoco_seq OWNED BY public.insegnamento.codice_univoco;


--
-- Name: iscrizione_esame; Type: TABLE; Schema: public; Owner: Kevin
--

CREATE TABLE public.iscrizione_esame (
    corso_di_laurea integer NOT NULL,
    insegnamento integer NOT NULL,
    data date NOT NULL,
    studente integer NOT NULL
);


ALTER TABLE public.iscrizione_esame OWNER TO "Kevin";

--
-- Name: propedeuticita; Type: TABLE; Schema: public; Owner: Kevin
--

CREATE TABLE public.propedeuticita (
    corso_di_laurea_main integer NOT NULL,
    insegnamento integer NOT NULL,
    corso_di_laurea_dep integer NOT NULL,
    propedeutico_a integer NOT NULL,
    CONSTRAINT propedeuticita_check CHECK ((corso_di_laurea_main = corso_di_laurea_dep)),
    CONSTRAINT propedeuticita_check1 CHECK ((insegnamento <> propedeutico_a))
);


ALTER TABLE public.propedeuticita OWNER TO "Kevin";

--
-- Name: segreteria; Type: TABLE; Schema: public; Owner: Kevin
--

CREATE TABLE public.segreteria (
    id integer NOT NULL,
    utente character varying(20) NOT NULL,
    nome character varying(20),
    cognome character varying(20)
);


ALTER TABLE public.segreteria OWNER TO "Kevin";

--
-- Name: segreteria_id_seq; Type: SEQUENCE; Schema: public; Owner: Kevin
--

ALTER TABLE public.segreteria ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.segreteria_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: studente; Type: TABLE; Schema: public; Owner: Kevin
--

CREATE TABLE public.studente (
    matricola integer NOT NULL,
    cdl integer NOT NULL,
    utente character varying(20) NOT NULL,
    nome character varying(20),
    cognome character varying(20),
    email character varying(30)
);


ALTER TABLE public.studente OWNER TO "Kevin";

--
-- Name: studente_matricola_seq; Type: SEQUENCE; Schema: public; Owner: Kevin
--

ALTER TABLE public.studente ALTER COLUMN matricola ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.studente_matricola_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: studente_storico; Type: TABLE; Schema: public; Owner: Kevin
--

CREATE TABLE public.studente_storico (
    matricola integer NOT NULL,
    corso_di_laurea integer NOT NULL,
    nome character varying(20),
    cognome character varying(20),
    email character varying(30)
);


ALTER TABLE public.studente_storico OWNER TO "Kevin";

--
-- Name: utente; Type: TABLE; Schema: public; Owner: Kevin
--

CREATE TABLE public.utente (
    nome_utente character varying(20) NOT NULL,
    password text NOT NULL,
    profilo_utente public.userprofile NOT NULL
);


ALTER TABLE public.utente OWNER TO "Kevin";

--
-- Name: insegnamento codice_univoco; Type: DEFAULT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.insegnamento ALTER COLUMN codice_univoco SET DEFAULT nextval('public.insegnamento_codice_univoco_seq'::regclass);


--
-- Data for Name: carriera; Type: TABLE DATA; Schema: public; Owner: Kevin
--

COPY public.carriera (corso_di_laurea, insegnamento, data, studente, voto) FROM stdin;
\.


--
-- Data for Name: carriera_storico; Type: TABLE DATA; Schema: public; Owner: Kevin
--

COPY public.carriera_storico (corso_di_laurea, insegnamento, data, studente, voto, esito) FROM stdin;
\.


--
-- Data for Name: corso_di_laurea; Type: TABLE DATA; Schema: public; Owner: Kevin
--

COPY public.corso_di_laurea (id, responsabile, tipologia, nome) FROM stdin;
\.


--
-- Data for Name: docente; Type: TABLE DATA; Schema: public; Owner: Kevin
--

COPY public.docente (id, utente, nome, cognome, email) FROM stdin;
\.


--
-- Data for Name: esame; Type: TABLE DATA; Schema: public; Owner: Kevin
--

COPY public.esame (corso_di_laurea, insegnamento, data) FROM stdin;
\.


--
-- Data for Name: insegnamento; Type: TABLE DATA; Schema: public; Owner: Kevin
--

COPY public.insegnamento (codice_univoco, corso_di_laurea, responsabile, nome, descrizione, anno) FROM stdin;
\.


--
-- Data for Name: iscrizione_esame; Type: TABLE DATA; Schema: public; Owner: Kevin
--

COPY public.iscrizione_esame (corso_di_laurea, insegnamento, data, studente) FROM stdin;
\.


--
-- Data for Name: propedeuticita; Type: TABLE DATA; Schema: public; Owner: Kevin
--

COPY public.propedeuticita (corso_di_laurea_main, insegnamento, corso_di_laurea_dep, propedeutico_a) FROM stdin;
\.


--
-- Data for Name: segreteria; Type: TABLE DATA; Schema: public; Owner: Kevin
--

COPY public.segreteria (id, utente, nome, cognome) FROM stdin;
13	admin	admin	admin
\.


--
-- Data for Name: studente; Type: TABLE DATA; Schema: public; Owner: Kevin
--

COPY public.studente (matricola, cdl, utente, nome, cognome, email) FROM stdin;
\.


--
-- Data for Name: studente_storico; Type: TABLE DATA; Schema: public; Owner: Kevin
--

COPY public.studente_storico (matricola, corso_di_laurea, nome, cognome, email) FROM stdin;
\.


--
-- Data for Name: utente; Type: TABLE DATA; Schema: public; Owner: Kevin
--

COPY public.utente (nome_utente, password, profilo_utente) FROM stdin;
docente01	docente01	docente
docente02	docente02	docente
docente03	docente03	docente
docente04	docente04	docente
docente05	docente05	docente
docente06	docente06	docente
docente07	docente07	docente
docente08	docente08	docente
docente09	docente09	docente
docente10	docente10	docente
docente11	docente11	docente
docente12	docente12	docente
docente13	docente13	docente
docente14	docente14	docente
docente15	docente15	docente
docente16	docente16	docente
docente17	docente17	docente
docente18	docente18	docente
docente19	docente19	docente
docente20	docente20	docente
docente21	docente21	docente
docente22	docente22	docente
docente23	docente23	docente
docente24	docente24	docente
docente25	docente25	docente
studente01	studente01	studente
studente02	studente02	studente
studente03	studente03	studente
studente04	studente04	studente
studente05	studente05	studente
studente06	studente06	studente
studente07	studente07	studente
studente08	studente08	studente
studente09	studente09	studente
studente10	studente10	studente
studente11	studente11	studente
studente12	studente12	studente
studente13	studente13	studente
studente14	studente14	studente
studente15	studente15	studente
studente16	studente16	studente
studente17	studente17	studente
studente18	studente18	studente
studente19	studente19	studente
studente20	studente20	studente
studente21	studente21	studente
studente22	studente22	studente
studente23	studente23	studente
studente24	studente24	studente
studente25	studente25	studente
studente26	studente26	studente
studente27	studente27	studente
studente28	studente28	studente
studente29	studente29	studente
studente30	studente30	studente
studente31	studente31	studente
studente32	studente32	studente
studente33	studente33	studente
studente34	studente34	studente
studente35	studente35	studente
studente36	studente36	studente
studente37	studente37	studente
studente38	studente38	studente
studente39	studente39	studente
studente40	studente40	studente
studente41	studente41	studente
studente42	studente42	studente
studente43	studente43	studente
studente44	studente44	studente
studente45	studente45	studente
studente46	studente46	studente
studente47	studente47	studente
studente48	studente48	studente
studente49	studente49	studente
studente50	studente50	studente
studente51	studente51	studente
studente52	studente52	studente
studente53	studente53	studente
studente54	studente54	studente
studente55	studente55	studente
studente56	studente56	studente
studente57	studente57	studente
studente58	studente58	studente
studente59	studente59	studente
studente60	studente60	studente
studente61	studente61	studente
studente62	studente62	studente
segreteria01	segreteria01	segreteria
segreteria02	segreteria02	segreteria
segreteria03	segreteria03	segreteria
segreteria04	segreteria04	segreteria
segreteria05	segreteria05	segreteria
segreteria06	segreteria06	segreteria
segreteria07	segreteria07	segreteria
segreteria08	segreteria08	segreteria
segreteria09	segreteria09	segreteria
segreteria10	segreteria10	segreteria
segreteria11	segreteria11	segreteria
segreteria12	segreteria12	segreteria
admin	21232f297a57a5a743894a0e4a801fc3	segreteria
\.


--
-- Name: corso_di_laurea_id_seq; Type: SEQUENCE SET; Schema: public; Owner: Kevin
--

SELECT pg_catalog.setval('public.corso_di_laurea_id_seq', 11, true);


--
-- Name: docente_id_seq; Type: SEQUENCE SET; Schema: public; Owner: Kevin
--

SELECT pg_catalog.setval('public.docente_id_seq', 25, true);


--
-- Name: insegnamento_codice_univoco_seq; Type: SEQUENCE SET; Schema: public; Owner: Kevin
--

SELECT pg_catalog.setval('public.insegnamento_codice_univoco_seq', 1, false);


--
-- Name: segreteria_id_seq; Type: SEQUENCE SET; Schema: public; Owner: Kevin
--

SELECT pg_catalog.setval('public.segreteria_id_seq', 13, true);


--
-- Name: studente_matricola_seq; Type: SEQUENCE SET; Schema: public; Owner: Kevin
--

SELECT pg_catalog.setval('public.studente_matricola_seq', 70, true);


--
-- Name: carriera carriera_pkey; Type: CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.carriera
    ADD CONSTRAINT carriera_pkey PRIMARY KEY (corso_di_laurea, insegnamento, data, studente);


--
-- Name: carriera_storico carriera_storico_pkey; Type: CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.carriera_storico
    ADD CONSTRAINT carriera_storico_pkey PRIMARY KEY (corso_di_laurea, insegnamento, data, studente);


--
-- Name: corso_di_laurea corso_di_laurea_nome_tipologia_key; Type: CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.corso_di_laurea
    ADD CONSTRAINT corso_di_laurea_nome_tipologia_key UNIQUE (nome, tipologia);


--
-- Name: corso_di_laurea corso_di_laurea_pkey; Type: CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.corso_di_laurea
    ADD CONSTRAINT corso_di_laurea_pkey PRIMARY KEY (id);


--
-- Name: docente docente_email_key; Type: CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.docente
    ADD CONSTRAINT docente_email_key UNIQUE (email);


--
-- Name: docente docente_pkey; Type: CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.docente
    ADD CONSTRAINT docente_pkey PRIMARY KEY (id);


--
-- Name: docente docente_utente_key; Type: CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.docente
    ADD CONSTRAINT docente_utente_key UNIQUE (utente);


--
-- Name: esame esame_corso_di_laurea_data_key; Type: CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.esame
    ADD CONSTRAINT esame_corso_di_laurea_data_key UNIQUE (corso_di_laurea, data);


--
-- Name: esame esame_pkey; Type: CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.esame
    ADD CONSTRAINT esame_pkey PRIMARY KEY (corso_di_laurea, insegnamento, data);


--
-- Name: insegnamento insegnamento_corso_di_laurea_nome_key; Type: CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.insegnamento
    ADD CONSTRAINT insegnamento_corso_di_laurea_nome_key UNIQUE (corso_di_laurea, nome);


--
-- Name: insegnamento insegnamento_pkey; Type: CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.insegnamento
    ADD CONSTRAINT insegnamento_pkey PRIMARY KEY (codice_univoco, corso_di_laurea);


--
-- Name: iscrizione_esame iscrizione_esame_pkey; Type: CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.iscrizione_esame
    ADD CONSTRAINT iscrizione_esame_pkey PRIMARY KEY (corso_di_laurea, insegnamento, data, studente);


--
-- Name: propedeuticita propedeuticita_pkey; Type: CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.propedeuticita
    ADD CONSTRAINT propedeuticita_pkey PRIMARY KEY (corso_di_laurea_main, insegnamento, propedeutico_a);


--
-- Name: segreteria segreteria_pkey; Type: CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.segreteria
    ADD CONSTRAINT segreteria_pkey PRIMARY KEY (id);


--
-- Name: segreteria segreteria_utente_key; Type: CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.segreteria
    ADD CONSTRAINT segreteria_utente_key UNIQUE (utente);


--
-- Name: studente studente_email_key; Type: CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.studente
    ADD CONSTRAINT studente_email_key UNIQUE (email);


--
-- Name: studente studente_pkey; Type: CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.studente
    ADD CONSTRAINT studente_pkey PRIMARY KEY (matricola);


--
-- Name: studente_storico studente_storico_email_key; Type: CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.studente_storico
    ADD CONSTRAINT studente_storico_email_key UNIQUE (email);


--
-- Name: studente_storico studente_storico_pkey; Type: CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.studente_storico
    ADD CONSTRAINT studente_storico_pkey PRIMARY KEY (matricola);


--
-- Name: studente studente_utente_key; Type: CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.studente
    ADD CONSTRAINT studente_utente_key UNIQUE (utente);


--
-- Name: utente utente_pkey; Type: CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.utente
    ADD CONSTRAINT utente_pkey PRIMARY KEY (nome_utente);


--
-- Name: carriera carriera_corso_di_laurea_insegnamento_data_fkey; Type: FK CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.carriera
    ADD CONSTRAINT carriera_corso_di_laurea_insegnamento_data_fkey FOREIGN KEY (corso_di_laurea, insegnamento, data) REFERENCES public.esame(corso_di_laurea, insegnamento, data);


--
-- Name: carriera_storico carriera_storico_corso_di_laurea_insegnamento_data_fkey; Type: FK CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.carriera_storico
    ADD CONSTRAINT carriera_storico_corso_di_laurea_insegnamento_data_fkey FOREIGN KEY (corso_di_laurea, insegnamento, data) REFERENCES public.esame(corso_di_laurea, insegnamento, data);


--
-- Name: corso_di_laurea corso_di_laurea_responsabile_fkey; Type: FK CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.corso_di_laurea
    ADD CONSTRAINT corso_di_laurea_responsabile_fkey FOREIGN KEY (responsabile) REFERENCES public.docente(id);


--
-- Name: esame esame_corso_di_laurea_insegnamento_fkey; Type: FK CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.esame
    ADD CONSTRAINT esame_corso_di_laurea_insegnamento_fkey FOREIGN KEY (corso_di_laurea, insegnamento) REFERENCES public.insegnamento(corso_di_laurea, codice_univoco);


--
-- Name: insegnamento insegnamento_responsabile_fkey; Type: FK CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.insegnamento
    ADD CONSTRAINT insegnamento_responsabile_fkey FOREIGN KEY (responsabile) REFERENCES public.docente(id);


--
-- Name: iscrizione_esame iscrizione_esame_corso_di_laurea_insegnamento_data_fkey; Type: FK CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.iscrizione_esame
    ADD CONSTRAINT iscrizione_esame_corso_di_laurea_insegnamento_data_fkey FOREIGN KEY (corso_di_laurea, insegnamento, data) REFERENCES public.esame(corso_di_laurea, insegnamento, data) ON DELETE CASCADE;


--
-- Name: propedeuticita propedeuticita_corso_di_laurea_dep_propedeutico_a_fkey; Type: FK CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.propedeuticita
    ADD CONSTRAINT propedeuticita_corso_di_laurea_dep_propedeutico_a_fkey FOREIGN KEY (corso_di_laurea_dep, propedeutico_a) REFERENCES public.insegnamento(corso_di_laurea, codice_univoco);


--
-- Name: propedeuticita propedeuticita_corso_di_laurea_main_insegnamento_fkey; Type: FK CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.propedeuticita
    ADD CONSTRAINT propedeuticita_corso_di_laurea_main_insegnamento_fkey FOREIGN KEY (corso_di_laurea_main, insegnamento) REFERENCES public.insegnamento(corso_di_laurea, codice_univoco);


--
-- Name: segreteria segreteria_utente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.segreteria
    ADD CONSTRAINT segreteria_utente_fkey FOREIGN KEY (utente) REFERENCES public.utente(nome_utente) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: studente studente_corso_di_laurea_fkey; Type: FK CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.studente
    ADD CONSTRAINT studente_corso_di_laurea_fkey FOREIGN KEY (cdl) REFERENCES public.corso_di_laurea(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: studente studente_utente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: Kevin
--

ALTER TABLE ONLY public.studente
    ADD CONSTRAINT studente_utente_fkey FOREIGN KEY (utente) REFERENCES public.utente(nome_utente) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

