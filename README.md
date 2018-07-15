#Тестовый проект Список Контактов

Основан на Yii 2 Advanced Project Template.
Все действия над пользователями (регистрация, логие и т.д.) - чистый APT skeleton.
Для запуска необходим [Docker](https://www.docker.com).

```
> cd ./docker
> bash start_docker.sh

выбрать вход под deploer

> composer install
> ./yii migrate
```

## Список файлов

- ./public
  - config/
    - bootstrap.php
    - main.php
    - params.php
  - models
    - Contact.php
    - Image.php
  - uploads
    - thumbnails
  - console
    - m180710_140424_create_contacts_table.php
    - m180710_200533_create_image_table.php
  - frontend
    - controllers
      - ImageController.php
      - SiteController.php
    - models
      - Contact.php
      - UploadForm.php
    - services
      - ContactService.php
    - views
      - site
        - __item-contact.php
        - contactForm.php
        - index.php   