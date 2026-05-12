Залить все файлы в /otch/project/.
Открывать: http://u82410.kubsu-dev.ru/otch/project/

Фронт сделан не картинкой, а обычной HTML-версткой по прошлому React/Vite сайту.
Меню, бургер, модальное окно, тарифы, FAQ и формы кликабельные.

API:
POST /otch/project/api/profile
PUT  /otch/project/api/profile/{id}

Данные сохраняются в data/users.json.
Если сохранение не работает, дать папке data права 755 или 777 на учебном сервере.
