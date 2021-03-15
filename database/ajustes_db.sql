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

INSERT INTO `u424725676_dbpys`.`idiomas` (`idiomaNombre`) VALUES ('Español');
INSERT INTO `u424725676_dbpys`.`idiomas` (`idiomaNombre`) VALUES ('Ingles');

INSERT INTO `u424725676_dbpys`.`formatos` (`formatoNombre`) VALUES ('MP3');
INSERT INTO `u424725676_dbpys`.`formatos` (`formatoNombre`) VALUES ('MP4');
INSERT INTO `u424725676_dbpys`.`formatos` (`formatoNombre`) VALUES ('JPG');
INSERT INTO `u424725676_dbpys`.`formatos` (`formatoNombre`) VALUES ('PDF');

INSERT INTO `u424725676_dbpys`.`tiposcontenido` (`tipoContenidoNombre`) VALUES ('No Aplica');
