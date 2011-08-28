4money
======

4money, let you create your own quotation for your customers.

Screenshot
----------

Home

.. image:: https://github.com/pct/4money/raw/master/images/4money_screenshot.png

Customer List

.. image:: https://github.com/pct/4money/raw/master/images/4money_screenshot5.png

Quotation List

.. image:: https://github.com/pct/4money/raw/master/images/4money_screenshot4.png

How to Print

.. image:: https://github.com/pct/4money/raw/master/images/4money_screenshot3.png

Quotation

.. image:: https://github.com/pct/4money/raw/master/images/4money_screenshot2.png

Requirement 
------------
#. php 5.3
#. mysql 5
#. apache2 or nginx with url rewrite
#. wkhtmltopdf http://code.google.com/p/wkhtmltopdf/ if you want to download quotation pdf files without using system print.

Install
---------
#. Create sql using 4money.sql::

    # mysqladmin create 4money
    # mysql 4money < sql/4money.sql

#. Let logs can be written by www user::

    # chown -R www:www logs tpl_cache pdf

#. Enable apache rewrite.

#. Change your company logo::

    Please replace 4money/logo.png with your company logo.

Thanks for these libraries
--------------------------
#. Slim php framework (http://www.slimframework.com/)
#. idiorm (http://j4mie.github.com/idiormandparis/)
#. Free HTML5 Admin Template (http://medialoot.com/item/html5-admin-template/)

FAQ
----
1. Q: Is 4money secure enough?
   
   A: It is NOT safe when you have bad guys at your place. It has been quick developed(in less than a week), but it's enough for simple use at private network.

2. Q: Where can I print my quotation?
   
   A: Please access **view quotation** from quotation list, you could also use system print hot key.

3. Q: How to use nginx without apache .htaccess?
   
   A: Use this in your nginx.conf::

    if (!-f $request_filename){
        set $rule_0 1$rule_0;
    }

    if ($rule_0 = "1"){
        rewrite ^/(.*)$ /bootstrap.php last;
    }

4. Q: I installed wkhtmltopdf, and changed $WKHTMLTOPDF_BIN_PATH in config.php, but I could not see the download PDF link?

   A: check if your `which wkhtmltopdf` path is in php.ini open_basedir, or the better way, change the bootstrap.php PDF_ENABLE to always TRUE::

    define('PDF_ENABLE', true);

License
-------
http://www.opensource.org/licenses/bsd-license.php

The BSD 2-Clause License ("Simplified BSD License" or "FreeBSD License")::

    Copyright (c) 2011, 4point-inc.com
    All rights reserved.

    Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

     * Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
     * Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.

    THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

License in zh-TW / 授權條款 (中文)
-----------------------------------
請以英文 The BSD 2-Clause License http://www.opensource.org/licenses/bsd-license.php 為主

條款翻譯文字取/改自 http://zh.wikipedia.org/wiki/BSD_licenses::

    Copyright (c) 2011 著作權由 4point-inc.com 所有。著作權人保留一切權利。
    
    這份授權條款，在使用者符合以下二條件的情形下，授予使用者使用及再散播本
    套裝軟體原始碼及二進位可執行形式的權利，無論此包裝是否經改作皆然：
    
    * 對於本軟體原始程式碼的再散播，必須保留上述的版權宣告、此二條件表列，以
      及下述的免責聲明。
    * 對於本套件二進位可執行形式的再散播，必須連帶以檔案以及／或者其他附
      於散播包裝中的媒介方式，重制上述之版權宣告、此二條件表列，以及下述
      的免責聲明。
    
    免責聲明：本軟體是由著作權人及本軟體之貢獻者以現狀（"as is"）提供，
    本套裝軟體包裝不負任何明示或默示之擔保責任，包括但不限於就適售性以及
    特定目的的適用性為默示性擔保。著作權人及本軟體之貢獻者，無論任何條件、
    無論成因或任何責任主義、無論此責任為因合約關係、無過失責任主義或因非
    違約之侵權（包括過失或其他原因等）而起，對於任何因使用本套裝軟體裝所
    產生的任何直接性、間接性、偶發性、特殊性、懲罰性或任何結果的損害（
    包括但不限於替代商品或勞務之購用、使用損失、資料損失、利益損失、業務
    中斷等等），不負任何責任，即在該種使用已獲事前告知可能會造成此類損害
    的情形下亦然。

Todos
-----

None. Please tell me your ideas, thanks!

Changelog
----------
v0.1.5::

    NEW: edit quotation
    NEW: use css3 button to replace link

v0.1.4::

    FIX: parseFloat with toFixed(2) 

v0.1.3::

    NEW: use parseFloat and you could use in item_price and item_quantity
    FIX: full_doc_root, then you could install 4money in subdir of your webroot.

v0.1.2::

    NEW: add PDF download

v0.1.1::

    FIX: create quotation if no quotation in it

v0.1::

    Project Init
