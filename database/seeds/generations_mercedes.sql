-- C-Class

INSERT INTO generations
(model_id, code, name, production_from, production_to)

SELECT
id,
'W202',
'C-Class W202',
1993,
2000

FROM models
WHERE slug='c-class';

INSERT INTO generations
(model_id, code, name, production_from, production_to)

SELECT
id,
'W203',
'C-Class W203',
2000,
2007

FROM models
WHERE slug='c-class';

INSERT INTO generations
(model_id, code, name, production_from, production_to)

SELECT
id,
'W204',
'C-Class W204',
2007,
2014

FROM models
WHERE slug='c-class';

INSERT INTO generations
(model_id, code, name, production_from, production_to)

SELECT
id,
'W205',
'C-Class W205',
2014,
2021

FROM models
WHERE slug='c-class';

INSERT INTO generations
(model_id, code, name, production_from, production_to)

SELECT
id,
'W206',
'C-Class W206',
2021,
NULL

FROM models
WHERE slug='c-class';