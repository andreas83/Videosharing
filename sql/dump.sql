SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `videodb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `videodb` ;

-- -----------------------------------------------------
-- Table `videodb`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `videodb`.`user` ;

CREATE  TABLE IF NOT EXISTS `videodb`.`user` (
  `id` INT UNSIGNED NOT NULL ,
  `group_id` INT UNSIGNED NOT NULL ,
  `username` VARCHAR(60) NULL ,
  `password` VARCHAR(45) NULL ,
  `email` VARCHAR(80) NULL ,
  `firstname` VARCHAR(45) NULL ,
  `lastname` VARCHAR(45) NULL ,
  `info` LONGTEXT NULL ,
  PRIMARY KEY (`id`, `group_id`) ,
  UNIQUE INDEX `username_UNIQUE` (`username` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `videodb`.`video`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `videodb`.`video` ;

CREATE  TABLE IF NOT EXISTS `videodb`.`video` (
  `id` INT UNSIGNED NOT NULL ,
  `user_id` INT UNSIGNED NOT NULL ,
  `title` VARCHAR(255) NULL ,
  `descripton` MEDIUMTEXT NULL ,
  `visibility_setting` TINYINT NOT NULL DEFAULT 0 ,
  `enabled` TINYINT NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`id`, `user_id`) ,
  INDEX () ,
  CONSTRAINT ``
    FOREIGN KEY ()
    REFERENCES `videodb`.`user` ()
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `videodb`.`group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `videodb`.`group` ;

CREATE  TABLE IF NOT EXISTS `videodb`.`group` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(80) NULL ,
  `permission` LONGTEXT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

