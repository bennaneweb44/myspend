delete from `charges`;
delete from `alimentation`;
delete from `categorie_charge`;
delete from `categorie_alimentation`;
delete from `user`;

alter table `user` AUTO_INCREMENT 1;
alter table `categorie_charge` AUTO_INCREMENT 1;
alter table `categorie_alimentation` AUTO_INCREMENT 1;
alter table `charges` AUTO_INCREMENT 1;
alter table `alimentation` AUTO_INCREMENT 1;

INSERT INTO `categorie_alimentation` (`id`, `label`, `couleur`, `icone`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'E-leclerc', '#C5AEF0', 'fa fa-shopping-cart', '2021-01-14 04:16:33', '2021-01-14 04:16:33', NULL),
(2, 'Boucher', '#F7927C', 'fa fa-chain-broken', '2021-01-14 04:16:33', '2021-01-14 04:16:33', NULL),
(3, 'Poissonnier', '#AAFBFF', 'fa fa-scissors', '2021-01-14 04:16:33', '2021-01-14 04:16:33', NULL),
(4,	'Essence', '#AAFBFF', 'fa fa-car',	'2021-01-14 04:16:33',	'2021-01-14 04:16:33',	NULL),
(5,	'Alcool', '#AAFBFF', 'fa fa-beer', '2021-01-14 04:16:33',	'2021-01-14 04:16:33',	NULL),
(6,	'Pharmacie', '#AAFBFF',	'fa fa-medkit',	'2021-01-14 04:16:33',	'2021-01-14 04:16:33',	NULL);

INSERT INTO `categorie_charge` (`id`, `label`, `couleur`, `icone`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Charges fixes', '#FAE288', 'fa fa-edit', '2021-01-14 04:16:33', '2021-01-14 04:16:33', NULL),
(2, 'Charges variables', '#98D4E0', 'fa fa-calculator', '2021-01-14 04:16:33', '2021-01-14 04:16:33', NULL);

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1,	'bennaneweb44@gmail.com',	'[\"ROLE_USER\"]',	'$argon2id$v=19$m=65536,t=4,p=1$8j5+Y3rBIqAg6VjmfNE3TA$u0vXdUe+oeVDWSxqwohp8QkOp7oRC5DTXox6uNgDZDc',	'Lam3allam',	'2021-01-14 04:40:55',	'2021-01-14 04:40:55',	NULL),
(2,	'demo@demo.fr',	'[\"ROLE_USER\"]',	'$argon2id$v=19$m=65536,t=4,p=1$ft2PeVJeT4Pc0qpVt4Mm2w$LBsqtAW1DsY/Wqnl+O/4yfb97zRz5goxbx+GOvQaLF8',	'Demo',	'2021-01-14 04:40:55',	'2021-01-14 04:40:55',	NULL);


INSERT INTO `alimentation` (`id`, `categorie_id`, `libelle`, `montant`, `user_id`, `created_at`, `updated_at`, `deleted_at`, `commentaires`) VALUES
(8,	1,	'Super u',	12, 1,	'2021-03-02 00:00:00',	'2021-03-02 00:00:00',	NULL,	NULL),
(9,	1,	'Leclerc ',	60, 1,	'2021-03-05 00:00:00',	'2021-03-02 00:00:00',	NULL,	NULL),
(11,	2,	'Boulangerie ',	34, 1,	'2021-03-06 00:00:00',	'2021-03-06 00:00:00',	NULL,	NULL),
(12,	1,	'Auchan ',	65, 1,	'2021-03-06 00:00:00',	'2021-03-06 00:00:00',	NULL,	NULL),
(13,	2,	'Lidl ', 18, 1, '2021-03-09 00:00:00',	'2021-03-09 00:00:00',	NULL,	NULL),
(14,	1,	'Lidl',	57, 1,	'2021-03-11 00:00:00',	'2021-03-11 00:00:00',	NULL,	NULL),
(15,	2,	'Leclerc ',	67, 1,	'2021-03-13 00:00:00',	'2021-03-13 00:00:00',	NULL,	NULL),
(23,	1,	'Leclerc',	37, 1,	'2021-03-16 00:00:00',	'2021-03-16 00:00:00',	NULL,	NULL),
(24,	1,	'Leclerc ',	39, 1,	'2021-03-18 00:00:00',	'2021-03-18 00:00:00',	NULL,	NULL),
(25,	1,	'Leclerc ',	54, 1,	'2021-03-20 00:00:00',	'2021-03-20 00:00:00',	NULL,	NULL),
(26,	2,	'World market ',	15, 1,	'2021-03-20 00:00:00',	'2021-03-20 00:00:00',	NULL,	NULL),
(27,	2,	'Boucher',	9, 1,	'2021-03-20 00:00:00',	'2021-03-20 00:00:00',	NULL,	NULL),
(28,	1,	'Leclerc ',	37, 1,	'2021-03-24 00:00:00',	'2021-03-24 00:00:00',	NULL,	NULL),
(29,	1,	'Leclerc ',	45, 1,	'2021-03-26 00:00:00',	'2021-03-26 00:00:00',	NULL,	NULL),
(30,	1,	'Leclerc',	90, 1,	'2021-04-01 00:00:00',	'2021-04-01 00:00:00',	NULL,	'Don\'t 15 € chocolat pâques\n10 € cire'),
(31,	1,	'Leclerc',	130, 1,	'2021-04-03 00:00:00',	'2021-04-03 00:00:00',	NULL,	NULL),
(32,	1,	'Super u',	19, 1,	'2021-04-08 00:00:00',	'2021-04-08 00:00:00',	NULL,	''),
(33,	1,	'Leclerc',	50, 1,	'2021-04-09 00:00:00',	'2021-04-09 00:00:00',	NULL,	''),
(34,	1,	'Carrefour ',	38, 1,	'2021-04-10 00:00:00',	'2021-04-10 00:00:00',	NULL,	''),
(35,	1,	'Super u',	45, 1,	'2021-04-14 00:00:00',	'2021-04-14 00:00:00',	NULL,	''),
(36,	1,	'Leclerc ',	30, 1,	'2021-04-16 00:00:00',	'2021-04-16 00:00:00',	NULL,	''),
(37,	1,	'Carrefour beuajoire',	50, 1,	'2021-04-17 00:00:00',	'2021-04-17 00:00:00',	NULL,	'8 euros de saumon\n10 euros Charcuterie '),
(38,	1,	'Carrefour beaujoire',	9.27, 1,	'2021-04-19 00:00:00',	'2021-04-19 00:00:00',	NULL,	''),
(39,	1,	'Super u',	30, 1,	'2021-04-20 00:00:00',	'2021-04-20 00:00:00',	NULL,	'6 euros Bières\n5 euros fraises'),
(40,	1,	'Carrefour ',	37, 1,	'2021-04-22 00:00:00',	'2021-04-22 00:00:00',	NULL,	'5 euros Grosses crevettes\n4 euros mousse chocolat '),
(41,	2,	'Boucher',	30, 1,	'2021-04-22 00:00:00',	'2021-04-22 00:00:00',	NULL,	'Bœuf 17€'),
(42,	1,	'Auchan',	16, 1,	'2021-04-23 00:00:00',	'2021-04-23 00:00:00',	NULL,	'Sushi'),
(43,	1,	'Auchan',	22, 1,	'2021-04-23 00:00:00',	'2021-04-23 00:00:00',	NULL,	'8 euros Bières\n8 lait'),
(44,	1,	'KFC',	34, 1,	'2021-04-24 00:00:00',	'2021-04-24 00:00:00',	NULL,	''),
(45,	1,	'Super U',	12, 1,	'2021-04-24 00:00:00',	'2021-04-24 00:00:00',	NULL,	'7 euros de bière '),
(46,	1,	'Carrefour ',	120, 1,	'2021-05-01 00:00:00',	'2021-05-01 00:00:00',	NULL,	'10 euros Rhum\n'),
(47,	3,	'Sainte-luce-sur-loire',	49, 1,	'2021-05-01 00:00:00',	'2021-05-01 00:00:00',	NULL,	'10 soles\n9 saumon\n6 huîtres \n'),
(48,	1,	'Leclerc',	21.13, 1,	'2021-05-03 00:00:00',	'2021-05-03 00:00:00',	NULL,	''),
(49,	2,	'La bottière',	17, 1,	'2021-05-03 00:00:00',	'2021-05-03 00:00:00',	NULL,	''),
(50,	1,	'Bières ',	7, 1,	'2021-05-01 00:00:00',	'2021-05-01 00:00:00',	NULL,	''),
(51,	1,	'Leclerc',	66.7, 1,	'2021-05-05 00:00:00',	'2021-05-05 00:00:00',	NULL,	'8 bières\n5 Kinder délice \n10 produits beauté, 4euros reversés sur carte leclerc \n'),
(52,	1,	'Leclerc ',	43.82, 1,	'2021-05-07 00:00:00',	'2021-05-07 00:00:00',	NULL,	'Sushi 19\nRoti 10\nPastis 10'),
(53,	1,	'Carrefour ',	14, 1,	'2021-05-09 00:00:00',	'2021-05-09 00:00:00',	NULL,	'Flash whiskey 5\nHareng 5'),
(54,	1,	'Auchan ',	31,	1,'2021-05-11 00:00:00',	'2021-05-11 00:00:00',	NULL,	'4 € bières\n10 € viande '),
(55,	1,	'Leclerc ',	28, 1,	'2021-05-12 00:00:00',	'2021-05-12 00:00:00',	NULL,	''),
(56,	1,	'Vidande bmw',	50, 1,	'2021-05-11 00:00:00',	'2021-05-11 00:00:00',	NULL,	'Mecanosk, main d\'ouevre seulement'),
(57,	1,	'Carrefour ',	36, 1,	'2021-05-14 00:00:00',	'2021-05-14 00:00:00',	NULL,	'9 € alcool\n6,40 €/Steack\n8,40€ café '),
(58,	1,	'Carrefour ',	54, 1,	'2021-05-15 00:00:00',	'2021-05-15 00:00:00',	NULL,	'6€ bieres\n7€ Kinder Bueno\n8€ poissons '),
(59,	1,	'Super u',	28, 1,	'2021-05-17 00:00:00',	'2021-05-17 00:00:00',	NULL,	'12,72 € bières\n11 € Charcuterie '),
(60,	1,	'Auchan ',	50, 1,	'2021-05-19 00:00:00',	'2021-05-19 00:00:00',	NULL,	'10 € Slips Dounia\n7 € poulet\n6 € alcool'),
(61,	1,	'Auchan sushis',	19, 1,	'2021-05-19 00:00:00',	'2021-05-19 00:00:00',	NULL,	''),
(62,	1,	'Bière v and b',	8, 1,	'2021-05-20 00:00:00',	'2021-05-20 00:00:00',	NULL,	'');


INSERT INTO `charges` (`id`, `categorie_id`, `libelle`, `montant`, `user_id`, `created_at`, `updated_at`, `deleted_at`, `commentaires`) VALUES
(13,	1,	'Loyer',	680.25, 1,	'2021-03-13 00:00:00',	'2021-03-15 00:00:00',	NULL,	NULL),
(14,	1,	'Edf',	93.93, 1,	'2021-03-28 00:00:00',	'2021-03-18 00:00:00',	NULL,	'Ça coute cher ce bordel'),
(15,	1,	'Assurance habitation ',	12.67, 1,	'2021-03-28 00:00:00',	'2021-03-15 00:00:00',	NULL,	NULL),
(16,	1,	'Virement PEL',	50, 1,	'2021-03-16 00:00:00',	'2021-03-16 00:00:00',	NULL,	NULL),
(17,	1,	'Impôts',	13, 1,	'2021-03-16 00:00:00',	'2021-03-16 00:00:00',	NULL,	NULL),
(18,	1,	'Virement Dounia ',	100, 1,	'2021-03-15 00:00:00',	'2021-03-15 00:00:00',	NULL,	NULL),
(20,	1,	'Virement Boursorama ',	10, 1,	'2021-03-10 00:00:00',	'2021-03-10 00:00:00',	NULL,	NULL),
(21,	1,	'Eurocompte frais bancaires ',	8.24, 1,	'2021-03-08 00:00:00',	'2021-03-08 00:00:00',	NULL,	NULL),
(23,	1,	'Macif assurance voiture x 2',	113.39, 1,	'2021-04-04 00:00:00',	'2021-04-04 00:00:00',	NULL,	'Saxo à vendre'),
(24,	1,	'Sfr internet + 2 mobiles',	62, 1,	'2021-04-11 00:00:00',	'2021-04-11 00:00:00',	NULL,	'- Dont 8euros de Netflix'),
(25,	2,	'Carter Cash',	29, 1,	'2021-04-19 00:00:00',	'2021-04-19 00:00:00',	NULL,	'Pour Saxo :\n- Multimètre électronique (10€)\n- 4 bougies d\'allumage  (15€)\n- clé à bougies (4€)'),
(26,	2,	'FILTRE BMW',	33, 1,	'2021-04-21 00:00:00',	'2021-04-21 00:00:00',	NULL,	''),
(27,	2,	'Pièce SAXO',	57, 1,	'2021-04-21 00:00:00',	'2021-04-21 00:00:00',	NULL,	''),
(28,	2,	'Bureau Dounia',	160, 1,	'2021-03-30 00:00:00',	'2021-03-30 00:00:00',	NULL,	''),
(29,	2,	'Difotec',	62.9, 1,	'2021-05-03 00:00:00',	'2021-05-03 00:00:00',	NULL,	'- Saxo : capteur de pression (37.9e)\n- BMW : essui-glaces (25e)');