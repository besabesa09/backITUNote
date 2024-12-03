CREATE TABLE IF NOT EXISTS etudiant
(
    id serial primary key,
    etu character varying(50) NOT NULL,
    nom character varying(100) ,
    prom character varying(50) ,
    CONSTRAINT etudiant_etu_key UNIQUE (etu)
);

CREATE TABLE IF NOT EXISTS matiere
(
    id serial primary key,
    code_matiere character varying(20) ,
    nom character varying(100),
    credit integer,
    CONSTRAINT matiere_code_matiere_key UNIQUE (code_matiere)
);

CREATE TABLE IF NOT EXISTS examen
(
    id serial primary key,
    session date NOT NULL,
    code_matiere character varying(50) ,
    sem character varying(10)
);



CREATE TABLE IF NOT EXISTS note
(
    id serial primary key,
    etu character varying(50) ,
    value numeric(5,2) NOT NULL,
    id_exam integer NOT NULL,
    CONSTRAINT fk_etudiant FOREIGN KEY (etu)
    REFERENCES etudiant (etu) ,
    CONSTRAINT fk_examen FOREIGN KEY (id_exam)
    REFERENCES examen (id)
);
