Залить все файлы в /otch/project/ на учебном сервере.
Открывать: http://u82410.kubsu-dev.ru/otch/project/

API:
POST /otch/project/api/profile - создание заявки, JSON или XML.
PUT  /otch/project/api/profile/{id} - изменение заявки после Basic Auth.

Данные сохраняются в data/users.json. Если сервер ругается на запись, дать папке data права 755 или 777.
