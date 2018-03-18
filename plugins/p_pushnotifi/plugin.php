<?php
/**
 * Created by PhpStorm.
 * User: iDeveloper
 * Date: 10.03.2016
 * Time: 13:26
 */

class p_pushnotifi extends Plugins{

    public function __construct()
    {
        parent::__construct();

        $this->info['plugin']           = 'p_pushnotifi';
        $this->info['title']            = 'Отправка рассылки';
        $this->info['description']      = 'Добавляет в конец каждой статьи список похожих статей.';
        $this->info['author']           = 'Maximov & InstantCMS Team';
        $this->info['version']          = '1.0';

        $this->config['API_USER_ID']     = 'b377c4916ca8f97674daefdca17acd74';
        $this->config['API_SECRET']  = '49c0592c3f8e8737922da7f5f7ae3545';
        $this->config['TOKEN_STORAGE']  = '90d47e35f5b909c05400ea554cb2c56d';

        $this->events[] = 'ADD_POST_DONE';
        $this->events[] = 'ADD_ARTICLE_DONE';
        $this->events[] = 'ADD_BOARD_DONE';

    }

    public function execute($event, $item)
    {
        parent::execute();
        switch($event){
            case  'ADD_ARTICLE_DONE':
            $this->push_notifi();
                break;
        }
    }

    private function push_notifi(){
        require_once( 'api/sendpulseInterface.php' );
        require_once( 'api/sendpulse.php' );
        $SPApiProxy = new SendpulseApi( 'b377c4916ca8f97674daefdca17acd74', '49c0592c3f8e8737922da7f5f7ae3545', '90d47e35f5b909c05400ea554cb2c56d' );

        $task = array(
            'title'      => 'Hello!',
            'body'       => 'This is my first push message',
            'website_id' => 3924,
            'ttl'        => 20,
            'stretch_time' => 10
        );

        $additionalParams = array(
            'link' => 'http://livenews.uz',
            'filter_browsers' => 'Chrome,Safari',
            'filter_lang' => 'en',
            'filter' => '{"variable_name":"some","operator":"or","conditions":[{"condition":"likewith","value":"a"},{"condition":"notequal","value":"b"}]}'
        );
        $SPApiProxy->createPushTask($task, $additionalParams);
    }
}