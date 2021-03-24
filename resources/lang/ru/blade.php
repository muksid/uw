<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */
/* These credentials do not match our records */
/* Too many login attempts. Please try again in :seconds seconds. */
    'failed'    => 'Логин или пароль не совпадают!',
    'throttle'  => 'Слишком много попыток входа в систему. Пожалуйста, попробуйте снова через несколько секунд.',

    /* General buttons*/
    'cancel'            => 'Отменить',
    'update'            => 'Изменить',
    'send'              => 'Отправить',
    'save'              => 'Сохранить',
    'refresh'           => 'Обновить',
    'delete'            => 'Удалить',
    'reply'             => 'Ответить',
    'forward'           => 'Переслать',
    'print'             => 'Распечатать',
    'download_all'      => 'Скачать все',
    'file'              => 'Файл',
    'download'          => 'Скачать',
    'approve'           => 'Одобрить',
    'approved'          => 'Одобрено',
    'select'            => 'Выбрать',
    'receive'           => 'Получить',
    'reject'            => 'Отклонять',
    'close'             => 'Закрыть',
    'approve'           => 'Подтвердить',

    'new'               => 'новый',
    'search_users'      => 'Поиск пользователя',
    'write_new_message' => 'Написать новое письмо',
    'text'              => 'Краткое содержание',
    'subject'           => 'Тема',
    'type_of_message'   => 'Выберите тип письма',
    'upload_file'       => 'Загрузить файл',
    'uploaded_files'    => 'Загруженные файлы',
    'choose_group'      => 'Выберите группу',
    'others'            => 'Другие',
    'deadline'          => 'Cрок исполнения',
    'deadline_date'     => 'Дата',
    'error'             => 'Ошибка!',
    'error_check'       => 'Проверьте данные еще раз',
    'exist'             => 'есть',
    'to_send_choose'    => 'Чтобы отправить письмо, пожалуйста, выберите пользователей.',
    'select_users'      => 'Выберите сотрудников',
    'messages'          => 'Письма',
    'unread_messages'   => 'Непрочитанные письма',
    
    'branch'            => 'Филиал',
    'from_whom'         => 'От кого',
    'text_message'      => 'Тема письма',
    'reset'             => 'Сброс',
    'search'            => 'Поиск',
    'position'          => 'Должность',
    'type_message'      => 'Тип письма',
    'sent_date'         => 'Дата отправки',
    'received_date'     => 'Дата получения',
    'delete_selected'   => 'Удалить выбранные',
    'delete_file'       => 'Удалить файл',
    'overall'           => 'Итого',
    'found'             => 'Найдено',
    'to_whom'           => 'Кому',
    'choose_period'     => 'Выберите период',
    'from'              => 'От',
    'till'              => 'до',
    'deleted_messages'  => 'Удаленные письма',
    'for_last_3_months' => 'за последние 3 месяца',
    'deleted_date'      => 'Дата удаления',
    'no_deadline'       => 'Нет срока',
    'selet_at_least_one'=> 'Чтобы удалить, выберите хотя бы одно!',
    'additional'        => 'дополнительный',

    'read_received'     => 'Входящее письма',
    'read_sent'         => 'Отправленные письма',
    'read_control'      => 'Контрольные письма',
    'sent_to'           => 'Приемники',
    'senders'           => 'Отправители',
    'receivers'         => 'Получатели',
    'from_spesific_user'=> 'отправитель',



    /* dashboard*/
    'title'     => 'ТуронБанк',
    'webEdo'    => 'Веб Почта',

    /* login */
    'login_seans'   => 'Чтобы зайти в Web EDO необходимо ввести личный логин и пароль',
    'username'      => 'Логин',
    'password'      => 'Пароль',
    'login'         => 'Вход',
    'remember'      => 'Запомнить',

    /* home blade */
    'lang_uz'   => 'УЗ',
    'lang_ru'   => 'РУ',
    'lang_uzl'  => 'UZ',
    'tb_wide'   => 'Акционерный коммерческий банк "Туронбанк"',
    'tb'        => 'Туронбанк АКБ',
    'profile'   => 'Профиль',
    'logout'    => 'Выход',

    /* sidebar */
    'online'            => 'Онлайн',
    'my_group'          => 'Создать группы',
    'write_message'     => 'Написать письмо',
    'write_group'       => 'Написать (по группам)',
    'unread_message'    => 'Входящие письма',
    'sent_message'      => 'Исходящие письма',
    'term_message'      => 'Письма со сроком',
    'archive_inbox'     => 'Архив вход-х писем',
    'chat'              => 'Чат',
    'trash_message'     => 'Корзина',
    'iabs'              => 'ИАБС',  // added to sidebar 2020-05-21 14:19:34
    'sidebar_dep'       => 'Департаменты',
    'sidebar_users'     => 'Пользователей',
    'sidebar_control'   => 'Контрольные',
    'sidebar_edo'       => 'ЭДО',
    'main_nav'          => 'ОСНОВНАЯ НАВИГАЦИЯ',
    'mailbox'           => 'ПОЧТОВЫЙ ЯЩИК',

    /* welcome blade modal*/
    'id_number'     => 'ID номер',
    'tin_number'    => 'ИНН (Предприятия)',
    'org_not_found' => 'Предприятия не найдена!',
    'org_found'     => 'Наименование предприятия:',

    /* account index*/
    'home'              => 'Главная страница',
    'client_inf'        => 'Информация о клиенте',
    'fr_filial_code'    => 'Выберите филиал',
    'fr_acc_name'       => 'Наименование предприятия',
    'fr_acc_type'       => 'Укажите вид предприятия',
    'fr_acc_inn'        => 'ИНН',
    'fr_sch_type'       => 'Выберите вид счета',
    'fr_curr_type'      => 'Выберите валюту счета',
    'fr_type_activity'  => 'Вид деятельности',
    'fr_acc_address'    => 'Адрес',
    'fr_owner_fname'    => 'Имя',
    'fr_owner_lname'    => 'Фамилия',
    'fr_owner_sname'    => 'Отчество',
    'fr_org_owner'      => '(руководителя)',
    'fr_owner_phone'    => 'Номер телефона',
    'fr_offer'          => 'Договор оферты',
    'fr_offer_modal'    => 'Я прочитал договор оферты',
    'fr_send'           => 'Отправить',
    'fr_file'           => '(Копия паспорта руководителя)',
    'fr_file1'          => '(Документы предприятия: Устав,свидельства и др.)',

    /* show client*/
    'sh_acc_inf'    => 'Данные о предприятии',
    'sh_acc_filial' => 'Филиал:',
    'sh_acc_type'   => 'Вид предприятия:',
    'sh_acc_sch'    => 'Вид счета:',
    'sh_acc_curr'   => 'Валюта счета:',
    'sh_owner_inf'  => 'Информация об руководителя предприятия',
    'sh_owner_fio'  => 'ФИО руководителя предприятия:',
    'sh_acc_created'=> 'Дата подачи заявления:',
    'sh_apply_st'   => 'Статус заявки',
    'sh_apply_proc' => 'В процессе..',
    'sh_attach'     => 'Документы предприятия',
    'sh_attach_down'=> 'Скачать',
    'sh_bank_user'  => 'Сотрудник банка',
    'sh_attach_file'=> 'Приложения',
    'sh_iam'        => 'Я',
    'sh_text'       => 'Введите текст',

    /* showB client*/

    /*  Home blade  */
    'home_page'             => 'Главная страница',
    'home_control_panel'    => 'панель управления',
    'home_new_messages'     => 'Входящие письма',
    'home_chat'             => 'Чат',
    'home_registered_users' => 'Зарегистрированные пользователи',
    'home_control_messages' => ' Письма со сроком',
    'home_more'             => 'Подробно',
    'home_search'           => 'Поиск сотрудников',
    'home_write_message'    => 'Написать сообщение',
    'home_send'             => 'Отправить',
    'messages_chat'         => 'Сообщения',
    'not_found_chat'        => 'Переписка не найдена. Выберите из персонала слева, чтобы использовать чат',
    'unread_chat_messages'  => 'Входящие сообщения',


    /*  Edit User   */
    'edit_user'             => 'Редактировать пользователя', 
    'name'                  => 'Имя',
    'surname'               => 'Фамилия',
    'fathers_name'          => 'Отчество',
    'tabel_num'             => 'Номер табела',
    'confirm_pass'          => 'Подтвердите Пароль',
    'enter_pass_at_least_6' => 'Введите пароль (не менее 6 букв)',
    'repeat_pass'           => 'Повторите пароль',

    /*   Group index   */ 
    'groups_my_groups'      => 'Мои группы',
    'groups_table'          => 'Таблица',
    'groups_nav_groups'     => 'Группы',
    'groups_group_table'    => 'Таблица групп',
    'groups_create_group'   => 'Создать группу',
    'group_table_title'     => 'Наименование',
    'group_table_title_ru'  => 'Наименование (ru)', 
    'group_table_date'      => 'Число',
    'group_table_user_count'=> 'Количество пользователей',
    'group_table_status'    => 'Статус',

    /* Group edit */
    'group_edit_groupname'  => 'Название группы',
    'group_edit_num_user'   => 'Количество пользователей в группе',
    'group_edit_count'      => 'ед',
    'group_edit_add_users'  => 'Добавить пользователей',
    'group_edit_status'     => 'Выберите статус',

    /* Group create */
    'group_create_add_users' => 'Добавитьe пользователей в группу.',
    'to_create_group_choose' => 'Для создания группы необходимо выбрать сотрудников.',



    /***        EDO      ***/
    'edo'               => 'ЭДО',
    'user'              => 'Пользователь',
    'panel'             => 'панель',
    'user_panel'        => 'Панель пользователя',
    'task'              => 'Задача',
    'tasks'             => 'Задания',
    'overdue'           => 'Просроченные',
    'term'              => 'Со сроком',
    'add_task'          => 'Добавить задачу',
    'request_to_admin'  => 'Запрос к администратору',
    'create_doc'        => 'Создать документ',
    'edit_doc'          => 'Изменить документ',
    'inbox_doc'         => 'Входящие документы',
    'journals'          => 'Журналы',
    'journal'           => 'Журнал',
    'to_resolution'     => 'Резолюция', 
    'on_process'        => 'В процессе',
    'sent'              => 'Отправлено',
    'dep_staff'         => 'Сотрудники отдела',
    'exit'              => 'Выход',

    'doc_view'          => 'Просмотр документа',
    'incoming'          => 'Входящий',
    'outgoing'          => 'Исходящий',
    'doc'               => 'Документ',
    'request'           => 'Обращение',
    'type_of_doc'       => 'Тип документа',
    'select_doc'        => 'Выберите документ',
    'add'               => 'Дабавить',
    'select_journal'    => 'Выберите журнал',
    'incoming_num'      => 'Входящий номер',
    'incoming_date'     => 'Вх. Дата',
    'outgoing_num'      => 'Исходящий номер',
    'outgoing_date'     => 'Ис. Дата',
    'enter_num'         => 'Введите номер',
    'select_receiver'   => 'Выберите получателя',
    'select_employee'   => 'Выберите сотрудника',
    'sender_name'       => 'Имя отправителя',
    'enter_name'        => 'Введите название ...',
    'doc_name'          => 'Наименование документа',
    'enter_title'       => 'Введите название',
    'summary'           => 'Краткое содержание',

    // Guide
    'reg_journal'           => 'Журнал регистрации', 
    'doc_form'              => 'Форма документа',
    'incoming_letter'       => 'Входящие письмо',
    'doc_app'               => 'Приложения',
    'sender_organization'   => 'Отправитель (Организация)',

    'purpose'               => 'Цель',
    'sender'                => 'Отправитель',
    'date'                  => 'Дата',
    'status'                => 'Статус',
    'direct_task'           => 'Направь задачу',   
    'add_mini_task'         => 'Добавить фишку',
    'execution_time'        => 'Время исполнения',
    'at_least_3_letters'    => 'Введите не менее 3 букв', 
    'reply_letters'         => 'Ответные письма',
    'received'              => 'получено ...',
    'sent_to_approve'       => 'отправлено на утверждение ...',


    /****** ***/
    'protocol_management'   => 'Протокол правления',
    'management_guide'      => 'Бошқарув Раиси',
    'management_members'    => 'Бошқарув аъзолари',
    'committe_members'      => 'Қўмита аъзолари',
    'select_members'        => 'Прикрепить участники',


    //
    'not_found_reply_letters'   => 'Не найдено ответных писем',

    //      NEED TO TRANSLATE TO RUSSIAN BELOW
    'enter_letter'              => 'Введите письмо',
    'approve_and_close_task'    => 'Подтвердить и закрыть задачу',
    'task_closed'               => 'Задание закрыто',
    'execution_steps'           => 'Шаги исполнения',
    'reg'                       => 'Регистрация',
    'execution_control'         => 'Контроль исполнения',
    'execution'                 => 'Исполнение',
    // Edo journal
    'create_journal'            => 'Создать журнал',
    'error_journal_create'      => 'При создании журнала произошла ошибка',
    'title_uz'                  => 'Заглавие',
    'title_ru'                  => 'Заглавие (RU)',
    'error_journal_add'         => 'Вы должны назначить сотрудников для создания журналов',
    'edo_journal'               => 'ЭДО Журнал',
    'reg_date_num_kanc'         => 'Рег № и дата канцеларии',
    // Edo message
    'urgent'                    => 'Срочно',
    'limit_512'                 => 'ограничение по тексту 512 символов',
    'limit_100'                 => 'Ограничение 100 символов',
    // editGiedeTask
    'forward_task'              => 'Переслать задачу',
    'search_executors'          => 'Поиск исполнителей',
    // viewDirectorResolution
    'leave_comment'             => 'Оставить комментарий',
    // viewGuideTask
    'cancel_task'               => 'Отменить задание',
    'canceled'                  => 'Отменём',
    'comment'                   => 'Комментарий',
    'forward_steps'             => 'Этапи пересылки',
    // viewTaskProcess
    'me'                        => 'Я',
    'enter_message'             => 'Введите сообщение',
    //      edo-message-journals
    // control blade
    'executors'                 => 'Исполнители',
    'reg_date'                  => 'Рег №. Дата',
    'send_date'                 => 'Дата отправки',
    'in_execution'              => 'В исполнении',
    'closed'                    => 'Закрыто',
    // departmentTasks
    'resolution_received_docs'  => 'Письма в резолюцию',
    'doc_num'                   => 'Номер документа',
    'hour'                      => 'час',
    'minute'                    => 'минут',
    'docs'                      => 'Документы',
    // guideTaskRedirect
    'forwarded_docs'            => 'Переадресованные',
    'forwarded_date'            => 'Дата направления',
    'not_detected'              => 'Не обнаружен',
    'rejected_task'             => 'Отклонено задание',
    'reject_task'               => 'Отклонить задание',
    // viewDirctDepartTaskProcs    
    'reply_doc'                 => 'Ответное письмо',
    'send_message'              => 'Отправить сообщение',
    'approve_reply'             => 'Подтвердить ответное письмо',
    'selected_files'            => 'Выбранные файлы',
    'max_limit_err_12'          => 'Вы установили файл сверх лимита',
    'execution_in_process'      => 'идет исполнение',
    //  Edo users
    'users'                     => 'Пользователей',
    'user_create'               => 'Добавить пользователя',
    // 'err_user_create'           => 'User yaratishda xatolik mavjud',  
    'full_name'                 => 'Полное имя',
    'dep'                       => 'Отдел',
    'sort'                      => 'Сортировать',
    // helper task index
    'task_create'               => 'Создать задачу',
    'to_create_task_err'        => 'Вам необходимо назначить персонал для создания задачи',
    //  edo sidebar
    'on_process_docs'           => 'Письма в процессе',
    'addition'                  => 'Добавить',
    'position_date'              => 'Дата назначения',
    'create_user'               => 'Создать пользователя',
    'select_section'            => 'Выберите раздел',
    'role'                      => 'Роль',
    'ph_name'                   => 'Введите имя ...',
    'ph_surname'                => 'Введите фамилию ...',
    'ph_fathers_name'           => 'Введите отчество ...',
    'ph_login'                  => 'Введите логин ...',
    'ph_tabel_num'              => 'Введите номер табеля ...',
    'ph_enter_position'         => 'Выберите профессию ...',
    'select_branch'             => 'Выберите филиал',
    'reg_num'                   => 'Рег №',
    'close_task'                => 'Закройте задачу',
    'office'                    => 'Канцелярия',
    'not_found'                 => 'Записей не найдено',
    'confirm'                   => 'Подтвердить',

    // qr code messages
    'qr_documents'             => 'QR-документы',
    'qr_sender'                => 'Отправитель:',
    'qr_signed_by'             => 'Кем подписано:',
    'qr_sign'                  => 'Подписать',
    'qr_performer'             => 'Исполнитель документа:',
    'qr_signed_date'           => 'Дата подписания:',
    'qr_number_d'              => 'Номер документа:',
    'qr_files'                 => 'Прикрепленные файлы:',
    'sb_registration'          => 'Регистрация',
    'qr_download_pdf'          => 'Скачать pdf',
    'qr_number_d1'             => 'Хужжат коди:',
    'qr_executor'              => 'Ижрочи:',
    'qr_tel'                   => 'Тел:',
    'qr_mobile'                => 'Сот:',
    'qr_conf_sign'             => 'Подтверждение подписи',
    'qr_you_sign'              => 'Вы хотите подписать письмо?',
    'qr_cancel'                => 'Вы действительно хотите отменить?',
    'qr_i_sign'                => 'Да, я согласен',

    // office department orders
    'of_orders'                => 'УПР приказы',

    // hr department orders
    'hr_orders'                => 'ОК приказы',
    'my_orders'                => 'Мои приказы',
    'strategy_orders'          => 'Стратег протокол',
    'kazna_protocols'          => 'Казна протокол',
    

    // 2020-09-15 10:43:20 Addition
    'incoming_num_doc'  => 'Входящий номер документа',
    'incoming_date_doc' => 'Входящая дата документа',
    'outgoing_num_doc'  => 'Исходящий номер документа',
    'outgoing_date_doc' => 'Исходящая дата документа',
    'sent_date_time'    => 'Дата и время отправки',
    'in_num'            => 'Конц.№',
    'out_num'           => 'Ис.№',
    'reg_date_only'     => 'Дата реистрации',
    'superviser'        => 'Руководитель',    

    // 2020-09-29 10:54:18 compose

    'with_respect'      => 'С уважением',
    
    // 2020-10-8

    'rejected'          => 'Отклонено',
    'create_task'       => 'Создать задачу',

    'all_dep_name'      => 'Ко Всем Отделам',
    'all_filial_name'   => 'Ко Всем Филиалам',
    'change_sent'       => 'Изменить фишку',

    'archive'  => 'Архив',
    'archived' => 'Заархивированы',
    'archive_info' => 'В целях архивации данных были заархивированы письма с 01.01.2020 по 31.07.2020. 
    Вы можете просмотреть заархивированные сообщения по следующей ссылке:',
    'view_archived' => 'Просмотр заархивированных сообщений',
    'settings_archived' => 'Для просмотра заархивированных сообщений вам необходимо настроить следующие параметры:',

    // UW lang
    'previous'  => 'предыдущий',
    'next'  => 'следующий',
    'clear'  => 'следующий',

];
