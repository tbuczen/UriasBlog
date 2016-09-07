SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema urias-blog
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `urias-blog` DEFAULT CHARACTER SET utf8 ;
USE `urias-blog` ;

-- -----------------------------------------------------
-- Table `urias-blog`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `urias-blog`.`user` (
  `id` INT NOT NULL,
  `username` VARCHAR(45) NULL,
  `password` VARCHAR(255) NULL,
  `pep` VARCHAR(255) NULL,
  `nickname` VARCHAR(45) NULL,
  `email` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `urias-blog`.`media`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `urias-blog`.`media` (
  `id` INT NOT NULL,
  `filename` VARCHAR(45) NULL,
  `extension` VARCHAR(45) NULL,
  `post_id` INT NOT NULL,
  `type` ENUM('image', 'video') NULL,
  PRIMARY KEY (`id`, `post_id`),
  INDEX `fk_media_post1_idx` (`post_id` ASC),
  CONSTRAINT `fk_media_post1`
    FOREIGN KEY (`post_id`)
    REFERENCES `urias-blog`.`post` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `urias-blog`.`post`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `urias-blog`.`post` (
  `id` INT NOT NULL,
  `date` DATETIME NULL,
  `tags` TEXT NULL,
  `description` TEXT NULL,
  `main_img_id` INT NOT NULL,
  `author_id` INT NOT NULL,
  `location` VARCHAR(255) NULL,
  PRIMARY KEY (`id`, `main_img_id`, `author_id`),
  INDEX `fk_post_media_idx` (`main_img_id` ASC),
  INDEX `fk_post_user1_idx` (`author_id` ASC),
  CONSTRAINT `fk_post_media`
    FOREIGN KEY (`main_img_id`)
    REFERENCES `urias-blog`.`media` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_post_user1`
    FOREIGN KEY (`author_id`)
    REFERENCES `urias-blog`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;