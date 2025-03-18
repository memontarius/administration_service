### Предварительные требования

* PHP ^8.2
* Make
* Composer
* Docker
* Node.js & NPM

### Запуск через докер

1. Установить зависимости
    ```sh
    make i
    ```

2. Подготовить конфигурационный файл
     ```sh
    make prepare-env
    ```

3. Настроить параметры в .env
    ```dotenv
    DB_USERNAME=your_user
    DB_PASSWORD=your_password
    ```

4. Запуск контейнеров
    ```sh
    make up
    ```
   
5. Миграция
    ```sh
    make mig
    ```
   
6. Заполнить таблицы
    ```sh
    make seed
    ```

Postman коллекция:
https://drive.google.com/file/d/1dZ0p6yceXtznE6ye1ZuSDE_gnb5-lUqX/view?usp=sharing
