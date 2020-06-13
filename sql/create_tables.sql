create table if not exists user (
    id bigint AUTO_INCREMENT  primary key,
    username VARCHAR(30) NOT NULL,
    passhash VARCHAR(30) NOT NULL,
    full_name VARCHAR(50) NOT NULL,
    ip varchar(45) NOT NULL DEFAULT '127.0.0.1',
    email varchar(100) NOT NULL,
    lastseen datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    access_level enum('god', 'admin', 'user') NOT NULL DEFAULT 'user',
    verified enum('0', '1') NOT NULL DEFAULT '0'
);

insert into chat (sender, receiver, message_string) 
values 
(1, 16, "meethe"), 
(1, 17, "i am fine, ty"), 
(15, 1, "hehe");

alter table chat add constraint foreign key (sender) references user(id) , add constraint foreign key (receiver) references user(id);

create table if not exists chat (
    id bigint primary key AUTO_INCREMENT,
    sender bigint,
    receiver bigint,
    message_timestamp datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    message_string blob
);

create table if not exists valid_queue (
    id bigint AUTO_INCREMENT primary key,
    username varchar(30) NOT NULL,
    link varchar(100) NOT NULL,
    sent_date datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
);

create table if not exists online_status (
    id bigint AUTO_INCREMENT primary key,
    username varchar(30) NOT NULL
);