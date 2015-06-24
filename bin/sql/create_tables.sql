CREATE TABLE IF NOT EXISTS lol.images (
  id int(3) NOT NULL AUTO_INCREMENT,
  name varchar(100) NOT NULL,
  filename varchar(50) NOT NULL,
  is_nsfw tinyint(1) NOT NULL DEFAULT 0,
  created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS lol.image_tags (
  id int(5) NOT NULL AUTO_INCREMENT,
  image_id int(3) NOT NULL,
  tag varchar(50) NOT NULL,
  PRIMARY KEY (id),
  INDEX image_idx (image_id),
  FOREIGN KEY (image_id) 
      REFERENCES images(id)
      ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS lol.users (
  id int(3) NOT NULL AUTO_INCREMENT,
  name varchar(100) NOT NULL,
  gid varchar(50) NOT NULL,
  token varchar(200) DEFAULT NULL,
  created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB;