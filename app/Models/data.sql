create table productos(
    pr_id int auto_increment,
    pr_name varchar(50),
    pr_img varchar(300),
    primary key(pr_id)
);

create table recetas(
    re_id int auto_increment,
    re_product int,
    re_supply int,
    re_quantity int,
    primary key(re_id),
    foreign key(re_product) references productos(pr_id) on delete set null,
    foreign key(re_supply) references insumos(in_id) on delete set null
);