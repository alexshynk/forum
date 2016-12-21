set names utf8;

drop procedure if exists pr_add_forum_theme;

drop view if exists v_forum_posts;
drop view if exists v_forum_themes;
drop view if exists v_forum_sections;

drop table if exists forum_data;
drop table if exists forum_users;

CREATE TABLE forum_users(
	id int NOT NULL AUTO_INCREMENT PRIMARY KEY comment 'user id',
	username varchar(30) NOT NULL UNIQUE comment 'user''s login',
	password varchar(120) NOT NULL comment 'user''s password',
	authKey varchar(120) NOT NULL comment 'auth key column - required for cookie based login, should be unique for every user',
	accessToken varchar(120) NOT NULL comment 'acces token column - required for REST autenticacion',
	created timestamp NOT NULL comment 'date of registration',
	email varchar(120) comment 'email of user',
	role int NOT NULL default 0 comment '1 - admin, 0 - ordinary user',
	state int NOT NULL default 1 comment 'user''s state: 1 - active, 0 - not active',
	firstName varchar(50) comment 'first name of user',
	surName varchar(50) comment 'sur name of user'
);

create table forum_data(
	id int not null auto_increment primary key comment 'id of section, theme, post',
	parent_id int,
	caption varchar(120),
	date_ timestamp not null, 
	text text,
	user_id int not null
);
alter table forum_data add constraint foreign key(user_id) references forum_users(id);
alter table forum_data add constraint foreign key(parent_id) references forum_data(id);

insert into forum_users(username, password, authKey, accessToken, role, firstName, surName)
values('admin', 'admin', 'authKey1', 'accessToken1', 1, null, null);
set @user_id = last_insert_id();

insert into forum_users(username, password, authKey, accessToken, role, firstName, surName)
values('user2', 'user2', 'authKey2', 'accessToken2', 1, 'Пользователь', 'Тестовый');
set @user_id2 = last_insert_id();

insert into forum_data(parent_id, caption, text, user_id) values (null, 'Общие вопросы по PHP', null, @user_id);
insert into forum_data(parent_id, caption, text, user_id) values (null, 'Общие вопросы по MySQL', null, @user_id);
insert into forum_data(parent_id, caption, text, user_id) values (null, 'Общие вопросы по javascript', null, @user_id);
insert into forum_data(parent_id, caption, text, user_id) values (null, 'Общие вопросы по HTML, CSS', null, @user_id);
insert into forum_data(parent_id, caption, text, user_id) values (null, 'Общие вопросы по Yii2', null, @user_id);
set @section_id = last_insert_id();


insert into forum_data(parent_id, caption, text, user_id)
values (@section_id, 'Как сделать авторизацию и регистрацию пользователей из БД?', 'Как сделать авторизацию и регистрацию пользователей из БД?', @user_id2);
set @theme_id = last_insert_id();

insert into forum_data(parent_id, caption, text, user_id)
values (@theme_id, null, 'Подробно рассмотрено \r\n https://www.youtube.com/watch?v=pKq_iiAL_dA', @user_id);


create view v_forum_sections
as
select
	d.id as section_id,
	d.caption as section,
	(select count(1) from forum_data d1 where d1.parent_id = d.id) as theme_count,
	(select count(1) from forum_data d1, forum_data d2  where d1.parent_id = d.id and d2.parent_id = d1.id) as post_count,
	(select concat(d1.caption,'<br>',d1.date_,'<br>от <b>',u.username,'</b>') 
	 from forum_data d1 
		inner join forum_users u on u.id = d1.user_id
	 where d1.parent_id = d.id order by d1.id desc limit 1
	) as last_theme
	
from forum_data d
where d.parent_id is null
group by d.id, d.caption
order by d.id;


create view v_forum_themes
as
select
	d1.id as theme_id,
	d1.caption as theme,
	d.id as section_id,
	d.caption as section,	
	d1.text as theme_text,
	d1.date_ as theme_date,
	u1.username as theme_creator,
	(select count(1) from forum_data d2 where d2.parent_id = d1.id) as post_count,
	(select concat(d2.date_,'<br>от <b>',u.username,'</b>') 
	 from forum_data d2 
		inner join forum_users u on u.id = d2.user_id
	 where d2.parent_id = d1.id order by d2.id desc limit 1
	) as last_post	
from forum_data d1
	inner join forum_users u1 on u1.id=d1.user_id
	inner join forum_data d on d.id = d1.parent_id and d.parent_id is null
order by d1.id;


create view v_forum_posts
as
select
	d2.id as post_id,
	d1.id as theme_id,
	d2.caption as post,
	d2.text as post_text,
	d2.date_ as post_date,
	u2.username as post_creator
from forum_data d2
	inner join forum_data d1 on d2.parent_id = d1.id
	inner join forum_data d on d1.parent_id = d.id and d.parent_id is null 
	inner join forum_users u2 on u2.id = d2.user_id
order by d1.id, d2.id;

delimiter ;;

create procedure pr_add_forum_theme(
	out err_code integer,
    out err_msg varchar(250),
	out new_theme_id integer,
	in psection_id integer,
	in puser_id integer, 
	in pcaption varchar(120),
	in ptext text)
begin
	declare exit handler for sqlexception
	begin
		set err_code = sqlcode;
		set err_msg = 'Ошибка при добавлении новой темы';
	
		rollback;
	end;
	
	insert into forum_data(parent_id, caption, text, user_id)
	values(psection_id, pcaption, ptext, puser_id);
	
	set new_theme_id = last_insert_id();
	set err_code=0;
	set err_msg='';
end;;

delimiter ;