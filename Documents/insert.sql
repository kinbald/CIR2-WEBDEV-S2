------------------------------------------------------------
--        Script Postgre 
------------------------------------------------------------



------------------------------------------------------------
-- Table: enfant
------------------------------------------------------------
CREATE TABLE public.enfant(
	id_enfant             SERIAL  NOT NULL ,
	nom_enfant            VARCHAR (25) NOT NULL ,
	prenom_enfant         VARCHAR (25) NOT NULL ,
	date_naissance_enfant DATE   ,
	CONSTRAINT prk_constraint_enfant PRIMARY KEY (id_enfant)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: ecole
------------------------------------------------------------
CREATE TABLE public.ecole(
	id_ecole  SERIAL  NOT NULL ,
	nom_ecole VARCHAR (50)  ,
	CONSTRAINT prk_constraint_ecole PRIMARY KEY (id_ecole)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: section
------------------------------------------------------------
CREATE TABLE public.section(
	id_section  SERIAL  NOT NULL ,
	nom_section VARCHAR (25) NOT NULL UNIQUE,
	CONSTRAINT prk_constraint_section PRIMARY KEY (id_section)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: classes
------------------------------------------------------------
CREATE TABLE public.classes(
	id_classes  SERIAL  NOT NULL ,
	nom_classes VARCHAR (25)  ,
	annee       INT   ,
	enseignant  VARCHAR (40)  ,
	id_ecole    INT   ,
	CONSTRAINT prk_constraint_classes PRIMARY KEY (id_classes)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: admin
------------------------------------------------------------
CREATE TABLE public.admin(
	id_admin     SERIAL  NOT NULL ,
	type_droit   INT   ,
	mot_de_passe VARCHAR (256)  ,
	adresse_mail VARCHAR (50)  ,
	CONSTRAINT prk_constraint_admin PRIMARY KEY (id_admin)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: responsable_legal
------------------------------------------------------------
CREATE TABLE public.responsable_legal(
	id_responsable_legal SERIAL  NOT NULL ,
	nom_RL               VARCHAR (25)  ,
	prenom_RL            VARCHAR (25)  ,
	adresse_mail_RL      VARCHAR (50)  ,
	ville                VARCHAR (25)  ,
	code_postal          INT   ,
	complement_d_adresse VARCHAR (50)  ,
	mot_de_passe_RL      VARCHAR (256)  ,
	CONSTRAINT prk_constraint_responsable_legal PRIMARY KEY (id_responsable_legal)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: activite
------------------------------------------------------------
CREATE TABLE public.activite(
	id_activite SERIAL  NOT NULL ,
	intitule    VARCHAR (25)  ,
	CONSTRAINT prk_constraint_activite PRIMARY KEY (id_activite)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: CRENEAU
------------------------------------------------------------
CREATE TABLE public.CRENEAU(
	date_journee DATE NOT NULL  ,
	id_enfant    INT  NOT NULL ,
	id_activite  INT   ,
	CONSTRAINT prk_constraint_CRENEAU PRIMARY KEY (id_enfant,date_journee)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: token_responsable_legal
------------------------------------------------------------
CREATE TABLE public.token_responsable_legal(
	verifier_rl          VARCHAR (384) NOT NULL ,
	selector_rl          VARCHAR (384)  ,
	date_expiration_rl   DATE   ,
	id_responsable_legal INT   ,
	CONSTRAINT prk_constraint_token_responsable_legal PRIMARY KEY (verifier_rl)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: token_admin
------------------------------------------------------------
CREATE TABLE public.token_admin(
	verifier_admin        VARCHAR (384) NOT NULL ,
	selector_admin        VARCHAR (384)  ,
	date_expiration_admin DATE   ,
	id_admin              INT   ,
	CONSTRAINT prk_constraint_token_admin PRIMARY KEY (verifier_admin)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: est_de_niveau
------------------------------------------------------------
CREATE TABLE public.est_de_niveau(
	id_classes INT  NOT NULL ,
	id_section INT  NOT NULL ,
	CONSTRAINT prk_constraint_est_de_niveau PRIMARY KEY (id_classes,id_section)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: est_dans_classes
------------------------------------------------------------
CREATE TABLE public.est_dans_classes(
	type_inscription INT   ,
	id_enfant        INT  NOT NULL ,
	id_classes       INT  NOT NULL ,
	CONSTRAINT prk_constraint_est_dans_classes PRIMARY KEY (id_enfant,id_classes)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: supervise
------------------------------------------------------------
CREATE TABLE public.supervise(
	id_admin INT  NOT NULL ,
	id_ecole INT  NOT NULL ,
	CONSTRAINT prk_constraint_supervise PRIMARY KEY (id_admin,id_ecole)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: est_responsable_de
------------------------------------------------------------
CREATE TABLE public.est_responsable_de(
	type_RL              VARCHAR (25)  ,
	id_enfant            INT  NOT NULL ,
	id_responsable_legal INT  NOT NULL ,
	CONSTRAINT prk_constraint_est_responsable_de PRIMARY KEY (id_enfant,id_responsable_legal)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: a_droit
------------------------------------------------------------
CREATE TABLE public.a_droit(
	id_section  INT  NOT NULL ,
	id_activite INT  NOT NULL ,
	CONSTRAINT prk_constraint_a_droit PRIMARY KEY (id_section,id_activite)
)WITHOUT OIDS;



ALTER TABLE public.classes ADD CONSTRAINT FK_classes_id_ecole FOREIGN KEY (id_ecole) REFERENCES public.ecole(id_ecole);
ALTER TABLE public.CRENEAU ADD CONSTRAINT FK_CRENEAU_id_enfant FOREIGN KEY (id_enfant) REFERENCES public.enfant(id_enfant);
ALTER TABLE public.CRENEAU ADD CONSTRAINT FK_CRENEAU_id_activite FOREIGN KEY (id_activite) REFERENCES public.activite(id_activite);
ALTER TABLE public.token_responsable_legal ADD CONSTRAINT FK_token_responsable_legal_id_responsable_legal FOREIGN KEY (id_responsable_legal) REFERENCES public.responsable_legal(id_responsable_legal);
ALTER TABLE public.token_admin ADD CONSTRAINT FK_token_admin_id_admin FOREIGN KEY (id_admin) REFERENCES public.admin(id_admin);
ALTER TABLE public.est_de_niveau ADD CONSTRAINT FK_est_de_niveau_id_classes FOREIGN KEY (id_classes) REFERENCES public.classes(id_classes);
ALTER TABLE public.est_de_niveau ADD CONSTRAINT FK_est_de_niveau_id_section FOREIGN KEY (id_section) REFERENCES public.section(id_section);
ALTER TABLE public.est_dans_classes ADD CONSTRAINT FK_est_dans_classes_id_enfant FOREIGN KEY (id_enfant) REFERENCES public.enfant(id_enfant);
ALTER TABLE public.est_dans_classes ADD CONSTRAINT FK_est_dans_classes_id_classes FOREIGN KEY (id_classes) REFERENCES public.classes(id_classes);
ALTER TABLE public.supervise ADD CONSTRAINT FK_supervise_id_admin FOREIGN KEY (id_admin) REFERENCES public.admin(id_admin);
ALTER TABLE public.supervise ADD CONSTRAINT FK_supervise_id_ecole FOREIGN KEY (id_ecole) REFERENCES public.ecole(id_ecole);
ALTER TABLE public.est_responsable_de ADD CONSTRAINT FK_est_responsable_de_id_enfant FOREIGN KEY (id_enfant) REFERENCES public.enfant(id_enfant);
ALTER TABLE public.est_responsable_de ADD CONSTRAINT FK_est_responsable_de_id_responsable_legal FOREIGN KEY (id_responsable_legal) REFERENCES public.responsable_legal(id_responsable_legal);
ALTER TABLE public.a_droit ADD CONSTRAINT FK_a_droit_id_section FOREIGN KEY (id_section) REFERENCES public.section(id_section);
ALTER TABLE public.a_droit ADD CONSTRAINT FK_a_droit_id_activite FOREIGN KEY (id_activite) REFERENCES public.activite(id_activite);

CREATE VIEW ActiviteEnfant AS SELECT * FROM activite INNER JOIN a_droit USING(id_activite) INNER JOIN est_de_niveau USING (id_section) INNER JOIN  est_dans_classes USING(id_classes) INNER JOIN classes USING(id_classes);