Hevelop Custom Orders Grid Extension
=====================

Magento 2 module that adds a new menu to magento backend, and adds a feature to upload custom files


Facts
-----
- version: 0.0.1
- you can configure which files can be uploaded
- extension key: Hevelop_FileUploader
- [extension on GitHub](https://github.com/Hevelop/module-file-uploader)

Requirements (tested with)
------------
- PHP = 7.0.X

Compatibility (tested on)
-------------
- Magento = 2.1.11

Installation Instructions
-------------------------
1. Install the extension via composer or copy all the files into your document root /app/code/
2. Enable module
3. Login to backend, you now must see 2 new menus: _Hevelop -> Upload File_ and _Store->Configuration->Hevelop_
4. if you want to upload big files make sure your webserver/php has upload limits set correctly **php:** memory_limit  upload_max_filesize post_max_size **nginx:** client_max_body_size
5. uploaded files are saved into media/wysiwyg folder

Uninstallation
--------------
1. Remove all extension files from your Magento installation

Support
-------
If you have any issues with this extension, open an issue on [GitHub](https://github.com/Hevelop/module-file-uploader/issues).

Contribution
------------
Any contribution is highly appreciated. The best way to contribute code is to open a [pull request on GitHub](https://help.github.com/articles/using-pull-requests).

Developer
---------
Yuriy Boyko

[https://hevelop.com](https://hevelop.com)

yuriy@hevelop.com


Licence
-------
[GNU AGPL - GNU Affero General Public License - 3.0](https://www.gnu.org/licenses/agpl-3.0.en.html)

Copyright
---------
(c) 2018 Hevelop