INSERT INTO variants
(generation_id,name,slug,fuel_type,transmission,drivetrain,engine_code,displacement,power_hp,power_kw)

SELECT
id,
'C 180',
'c180',
'Petrol',
'9G-TRONIC',
'RWD',
'M254',
1.5,
170,
125

FROM generations
WHERE code='W206';

INSERT INTO variants
(generation_id,name,slug,fuel_type,transmission,drivetrain,engine_code,displacement,power_hp,power_kw)

SELECT
id,
'C 200',
'c200',
'Petrol',
'9G-TRONIC',
'RWD',
'M254',
1.5,
204,
150

FROM generations
WHERE code='W206';

INSERT INTO variants
(generation_id,name,slug,fuel_type,transmission,drivetrain,engine_code,displacement,power_hp,power_kw)

SELECT
id,
'C 220 d',
'c220d',
'Diesel',
'9G-TRONIC',
'RWD',
'OM654',
2.0,
200,
147

FROM generations
WHERE code='W206';

INSERT INTO variants
(generation_id,name,slug,fuel_type,transmission,drivetrain,engine_code,displacement,power_hp,power_kw)

SELECT
id,
'C 300',
'c300',
'Petrol',
'9G-TRONIC',
'RWD',
'M254',
2.0,
258,
190

FROM generations
WHERE code='W206';

INSERT INTO variants
(generation_id,name,slug,fuel_type,transmission,drivetrain,engine_code,displacement,power_hp,power_kw)

SELECT
id,
'AMG C 43',
'amg-c43',
'Petrol',
'9G-TRONIC',
'4MATIC',
'M139',
2.0,
408,
300

FROM generations
WHERE code='W206';