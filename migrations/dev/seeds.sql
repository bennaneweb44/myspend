##########################################################################

delete from charges;
delete from alimentation;
delete from categorie_charge;
delete from categorie_alimentation;

alter table categorie_charge AUTO_INCREMENT 1;
alter table categorie_alimentation AUTO_INCREMENT 1;
alter table charges AUTO_INCREMENT 1;
alter table alimentation AUTO_INCREMENT 1;

##########################################################################

INSERT INTO `categorie_alimentation` (`id`, `label`, `couleur`, `icone`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'E-leclerc', '#C5AEF0', 'fa fa-shopping-cart', '2021-01-14 04:16:33', '2021-01-14 04:16:33', NULL),
(2, 'Boucher', '#F7927C', 'fa fa-chain-broken', '2021-01-14 04:16:33', '2021-01-14 04:16:33', NULL),
(3, 'Poissonnier', '#AAFBFF', 'fa fa-scissors', '2021-01-14 04:16:33', '2021-01-14 04:16:33', NULL);

INSERT INTO `categorie_charge` (`id`, `label`, `couleur`, `icone`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Charges fixes', '#FAE288', 'fa fa-edit', '2021-01-14 04:16:33', '2021-01-14 04:16:33', NULL),
(2, 'Charges variables', '#98D4E0', 'fa fa-calculator', '2021-01-14 04:16:33', '2021-01-14 04:16:33', NULL);

/****************************************************************************************/

INSERT INTO `alimentation` (`id`, `categorie_id`, `libelle`, `montant`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Courses', 46.09, '2021-01-14 04:16:33', '2021-01-14 04:16:33', NULL),
(2, 1, 'Courses', 84.98, '2021-01-14 04:16:33', '2021-01-14 04:16:33', NULL),
(3, 2, 'World Market', 30.28, '2021-01-14 04:16:33', '2021-01-14 04:16:33', NULL),
(4, 3, 'Sainte-luce-sur-loire', 37.3, '2021-01-14 04:16:34', '2021-01-14 04:16:34', NULL);

INSERT INTO `charges` (`id`, `categorie_id`, `libelle`, `montant`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Abonnement SFR', 52.78, '2021-01-14 04:16:33', '2021-01-14 04:16:33', NULL),
(2, 1, 'Loyer', 682.5, '2021-01-14 04:16:33', '2021-01-14 04:16:33', NULL),
(3, 1, 'Assurance habitation', 12.24, '2021-01-14 04:16:33', '2021-01-14 04:16:33', NULL),
(4, 2, 'Vidange B.M.W', 98.71, '2021-01-14 04:16:33', '2021-01-14 04:16:33', NULL),
(5, 2, 'Cadeaux noêl', 161.68, '2021-01-14 04:16:33', '2021-01-14 04:16:33', NULL);