delete from charges;
delete from alimentation;
delete from categorie_charge;
delete from categorie_alimentation;

alter table categorie_charge AUTO_INCREMENT 1;
alter table categorie_alimentation AUTO_INCREMENT 1;
alter table charges AUTO_INCREMENT 1;
alter table alimentation AUTO_INCREMENT 1;