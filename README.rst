4money
======

4money, let you create your own quotation for your customers.

Requirement 
------------
# php 5.3
# mysql 5
# apache2 or nginx with url rewrite

Install
---------
# create sql using 4money.sql::

    # mysqladmin create 4money
    # mysql 4money < sql/4money.sql

# let logs can be written by www user::

    # chown -R www:www logs tpl_cache

# enable apache rewrite

Thanks for these libraries
--------------------------
# Slim php framework (http://www.slimframework.com/)
# idiorm (http://j4mie.github.com/idiormandparis/)
# Free HTML5 Admin Template (http://medialoot.com/item/html5-admin-template/)
