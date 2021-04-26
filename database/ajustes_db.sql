CREATE TABLE `u424725676_dbpys`.`idiomas` (
  `idIdiomas` INT NOT NULL AUTO_INCREMENT,
  `idiomaNombre` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`idIdiomas`));

CREATE TABLE `u424725676_dbpys`.`formatos` (
  `idFormatos` INT NOT NULL AUTO_INCREMENT,
  `formatoNombre` VARCHAR(500) NOT NULL,
  `formatoDescripcion` VARCHAR(500) NULL,
  PRIMARY KEY (`idFormatos`));

CREATE TABLE `u424725676_dbpys`.`tiposcontenido` (
  `idtiposContenido` INT NOT NULL AUTO_INCREMENT,
  `tipoContenidoNombre` VARCHAR(500) NOT NULL,
  `tipoContenidoDescripcion` VARCHAR(500) NULL,
  PRIMARY KEY (`idtiposContenido`));

ALTER TABLE `u424725676_dbpys`.`pys_actproductos` 
CHANGE COLUMN `etiquetaProd` `palabrasClave` VARCHAR(2000) NULL ;

ALTER TABLE `u424725676_dbpys`.`pys_actproductos` 
ADD COLUMN `idioma` INT NULL AFTER `est`,
ADD COLUMN `formato` INT NULL AFTER `idioma`,
ADD COLUMN `tipoContenido` INT NULL AFTER `formato`;

INSERT INTO `u424725676_dbpys`.`idiomas` (`idiomaNombre`) VALUES ('No aplica');
INSERT INTO `u424725676_dbpys`.`idiomas` (`idiomaNombre`) VALUES ('Español');
INSERT INTO `u424725676_dbpys`.`idiomas` (`idiomaNombre`) VALUES ('Ingles');

INSERT INTO `u424725676_dbpys`.`formatos` (`formatoNombre`) VALUES ('No aplica');
INSERT INTO `u424725676_dbpys`.`formatos` (`formatoNombre`) VALUES ('MP4');
INSERT INTO `u424725676_dbpys`.`formatos` (`formatoNombre`) VALUES ('JPG');
INSERT INTO `u424725676_dbpys`.`formatos` (`formatoNombre`) VALUES ('PDF');
INSERT INTO `u424725676_dbpys`.`formatos` (`formatoNombre`) VALUES ('MP3');

INSERT INTO `u424725676_dbpys`.`tiposcontenido` (`tipoContenidoNombre`) VALUES ('No Aplica');



-- MySQL Workbench Synchronization
-- Generated: 2021-04-14 09:13
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: Jova1091

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

CREATE TABLE IF NOT EXISTS `u424725676_dbpys`.`pys_areaconocimiento` (
  `idAreaConocimiento` INT(11) NOT NULL AUTO_INCREMENT,
  `areaNombre` VARCHAR(500) NOT NULL,
  `areaDescripcion` VARCHAR(500) NULL DEFAULT NULL,
  PRIMARY KEY (`idAreaConocimiento`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `u424725676_dbpys`.`areaconocimientohasproyectos` (
  `pys_areaconocimiento_idAreaConocimiento` INT(11) NOT NULL,
  `pys_proyectos_idProy` VARCHAR(6) NOT NULL,
  `areaEstado` TINYINT(4) NOT NULL,
  PRIMARY KEY (`pys_areaconocimiento_idAreaConocimiento`, `pys_proyectos_idProy`),)
ENGINE = InnoDB;
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

ALTER TABLE areaconocimientohasproyectos ADD FOREIGN KEY ( pys_areaconocimiento_idAreaConocimiento) REFERENCES `u424725676_dbpys`.`pys_areaconocimiento` (`idAreaConocimiento`);

ALTER TABLE `pys_actproductos` ADD `idAreaConocimiento` INT(11) NOT NULL AFTER `tipoContenido`;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;