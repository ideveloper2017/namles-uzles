<?php


include(PATH . '/components/polls/class_polls.php');
Registry::set("Polls", new Polls());

if (isset($_REQUEST['opt'])) {
    $opt = $_REQUEST['opt'];
} else {
    $opt = 'view';
}


$db = Registry::get("DataBase");
$polls = Registry::get("Polls");
$pager = Registry::get("Paginator");
$content = Registry::get("Content");

if ($opt=='view'){
    $pollrow = Registry::get("Polls")->getPolls();
    ?>

    <div class="page-header">

        <div class="visible-xs header-element-toggle">
            <a class="btn btn-primary btn-icon" data-toggle="collapse" data-target="#header-buttons"><i
                    class="icon-insert-template"></i></a>
        </div>

        <div class="header-buttons">
            <div class="collapse" id="header-buttons">
                <div class="well">
                    <div class="btn-group">
                        <a href="index.php?do=components&amp;action=config&amp;id=<?php echo Core::$id?>&amp;opt=add" class="btn btn-info"><i class="icon-plus"></i>
                            Добавить Опрос</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="panel panel-default">
<!--        <div class="wojo message">--><?php //echo Core::langIcon();?><!----><?php //echo Lang::$word->_PLG_PL_INFO3;?><!--</div>-->
        <div class="panel-heading"><h6 class="panel-title">Просмотр всех опросах</h6></div>
        <div class="panel-body">


            <div class="wojo fitted divider"></div>
            <table class="table table-bordered ta">
                <thead>
                <tr>
                    <th data-sort="int">#</th>
                    <th data-sort="string">Вопрос</th>
                    <th data-sort="int">Создано/th>
                    <th data-sort="int">Конечная дата</th>
                    <th data-sort="int">Языки</th>
                    <th data-sort="int">Опубликованный</th>
                    <th class="disabled">Действия</th>
                </tr>
                </thead>
                <tbody>
                <?php if(!$pollrow):?>
                    <tr>
<!--                        <td colspan="4">--><?php //echo Filter::msgSingleAlert(Lang::$word->_PLG_PL_NOPOLL);?><!--</td>-->
                    </tr>
                <?php else:?>
                    <?php foreach ($pollrow as $row):?>
                        <tr>
                            <td><?php echo $row->id;?>.</td>
                            <td><?php echo $row->{'question'.Lang::$lang};?></td>
                            <td data-sort-value="<?php echo strtotime($row->created);?>"><?php echo Registry::get("Core")->cmsRusDate(Registry::get("Core")->dodate($config->short_date, $row->startdate));?></td>
                            <td data-sort-value="<?php echo strtotime($row->created);?>"><?php echo Registry::get("Core")->cmsRusDate(Registry::get("Core")->dodate($config->short_date, $row->enddate));?></td>
                            <td data-sort-value="<?php echo strtotime($row->created);?>"><?php echo $row->lang;?></td>
                            <td data-sort-value="<?php echo strtotime($row->created);?>"><?php echo $row->status;?></td>
                            <td>
                                <div class="table-controls">
                                <a class="btn btn-default btn-icon btn-xs tip view-poll" data-id="<?php echo $row->id;?>" data-content="View" data-name="<?php echo $row->question;?>"><i class="icon-laptop"></i></a>
                                <a class="btn btn-default btn-icon btn-xs tip" href="index.php?do=components&amp;action=config&amp;id=<?php echo Core::$id?>&amp;opt=edit&amp;itemid=<?php echo $row->id;?>"><i class="icon-link"></i></a>
                                <a class="btn btn-default btn-icon btn-xs tip" href="index.php?do=components&amp;action=config&amp;id=<?php echo Core::$id?>&amp;opt=delete&amp;itemid=<?php echo $row->id;?>"  data-option="deletePoll" data-id="<?php echo $row->id;?>" data-name="<?php echo $row->question;?>">
                                <i class="icon-remove"></i></a>

                                    </div>
                            </td>
                        </tr>
                    <?php endforeach;?>
                    <?php unset($row);?>
                <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
    <script type="text/javascript">
        // <![CDATA[
        $(document).ready(function () {
            $('body').on('click', 'a.view-poll', function () {
                var id = $(this).data('id')
                var title = $(this).data('name');
                Messi.load('plugins/poll/controller.php', {
                    viewPoll: 1,
                    id: id
                }, {
                    title: title
                });
            });
        });
        // ]]>
    </script>
    <?php

}

if ($opt=='add'){

    ?>
    <!--    <div class="wojo icon heading message orange"> <a class="helper wojo top right info corner label" data-help="poll"><i class="icon help"></i></a> <i class="umbrella icon"></i>-->
    <!--        <div class="content">-->
    <!--            <div class="header"> Manage CMS Poll Plugin </div>-->
    <!--            <div class="wojo breadcrumb"><i class="icon home"></i> <a href="index.php" class="section">Dashboard</a>-->
    <!--                <div class="divider"> / </div>-->
    <!--                <a href="index.php?do=plugins" class="section">Plugins</a>-->
    <!--                <div class="divider"> / </div>-->
    <!--<!--                <a href="index.php?do=plugins&amp;action=config&amp;plugname=poll" class="section">--><?php ////echo $content->getPluginName(Filter::$plugname);?><!--<!--</a>-->
    <!--                <div class="divider"> / </div>-->
    <!--                <div class="active section">Add New Poll</div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <div class="panel panel-default">
        <!--        <div class="wojo message">--><?php //echo Core::langIcon();?><!----><?php //echo Lang::$word->_PLG_PL_INFO2. Lang::$word->_REQ1 . '<i class="icon asterisk"></i>' . Lang::$word->_REQ2;?><!--</div>-->
        <div class="panel-heading"><h6 class="panel-title">Добавление Опрос</h6></div>

        <div class="panel-body">

            <form id="wojo_form" name="wojo_form" class="form-horizontal" method="post">
                <div class="form-group">

                    <label class="col-sm-2 control-label">Вопрос</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" placeholder="Вопрос" name="question"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Сделать опрос активны</label>
                    <div  class="block-inner col-sm-2">
                        <label class="radio-inline">
                            <input name="status" class="styled" type="radio" value="1" checked="checked"/>
                            Да</label>
                        <label class="radio-inline">
                            <input name="status" type="radio" class="styled" value="0" >
                            Нет</label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Опции опроса</label>
                    <div class="col-sm-4">
                        <div class="row">


                                <div id="input_1" class="col-sm-10 newQuestion">
                                    <input placeholder="Опции опроса" class="form-control" 1 name="value[1]" type="text"  id="value1">
                                </div>



                            <div class="inline-block">
                                <div class="col-sm-10">

                                    <button type="button" id="btnAdd" class="btn btn-success"><i class="icon-plus"></i></button>
                                    <button type="button" id="btnDel" class="btn btn-danger"><i class="icon-minus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="field">&nbsp;</div>
                </div>

                <div class="form-actions">
                    <button type="submit"  class="btn btn-info">Добавить Опрос</button>
                    <button type="button" class="btn btn-danger">Отмена</button>
                     <input name="opt" type="hidden" value="addPoll">
                </div>
            </form>
        </div>
        <div id="msgholder"></div>
    </div>
    <script type="text/javascript">
        // <![CDATA[
        $(document).ready(function () {
            $('#btnDel').hide();
            $('#btnAdd').click(function () {
                var num = $('.newQuestion').length;
                var newNum = new Number(num + 1);
                var newElem = $('#input_' + num).clone().attr('id', 'input_' + newNum);

                newElem.children(':first').attr('id', 'value' + newNum).attr('name', 'value[' + newNum + ']');
                $('#input_' + num).after(newElem);
                (num) ? $('#btnDel').show() : $('#btnDel').hide();
            });

            $('#btnDel').click(function () {
                var num = $('.newQuestion').length;
                $('#input_' + num).remove();
                (num - 1 == 1) ? $('#btnDel').hide() : $('#btnDel').show();
            });
        });
        // ]]>
    </script>


    <?php
}


if ($opt=='edit'){
    $itemid=$_REQUEST['itemid'];
    $row = Core::getRowById("poll_questions", $itemid);
    $pollopt = Registry::get("Polls")->getPollOptions($itemid);
    ?>
<!--    <div class="wojo icon heading message orange"> <a class="helper wojo top right info corner label" data-help="poll"><i class="icon help"></i></a> <i class="umbrella icon"></i>-->
<!--        <div class="content">-->
<!--            <div class="header"> Manage CMS Poll Plugin </div>-->
<!--            <div class="wojo breadcrumb"><i class="icon home"></i> <a href="index.php" class="section">Dashboard</a>-->
<!--                <div class="divider"> / </div>-->
<!--                <a href="index.php?do=plugins" class="section">Plugins</a>-->
<!--                <div class="divider"> / </div>-->
<!--<!--                <a href="index.php?do=plugins&amp;action=config&amp;plugname=poll" class="section">--><?php ////echo $content->getPluginName(Filter::$plugname);?><!--<!--</a>-->
<!--                <div class="divider"> / </div>-->
<!--                <div class="active section">Add New Poll</div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
    <div class="panel panel-default">
<!--        <div class="wojo message">--><?php //echo Core::langIcon();?><!----><?php //echo Lang::$word->_PLG_PL_INFO2. Lang::$word->_REQ1 . '<i class="icon asterisk"></i>' . Lang::$word->_REQ2;?><!--</div>-->
        <div class="panel-heading"><h6 class="panel-title">Добавление Опрос</h6></div>

        <div class="panel-body">

            <form id="wojo_form" name="wojo_form" class="form-horizontal" method="post">
                <div class="form-group">

                        <label class="col-sm-2 control-label">Вопрос</label>
                    <div class="col-sm-4">
                             <input type="text" class="form-control" placeholder="Вопрос" name="question" value="<?php echo $row->question;?>"/>
                    </div>
                </div>
                <div class="form-group">
                         <label class="col-sm-2 control-label">Сделать опрос активны</label>
                        <div  class="block-inner col-sm-2">
                            <label class="radio-inline">
                                <input name="status" class="styled" type="radio" value="1" <?php getChecked($row->status, 1);?>/>
                                Да</label>
                            <label class="radio-inline">
                                <input name="status" type="radio" class="styled" value="0" <?php getChecked($row->status, 0);?>>
                                Нет</label>
                        </div>
                    </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Опции опроса</label>
                    <div class="col-sm-4">
                        <div class="row">

                            <?php foreach($pollopt as $k=>$v): ?>
                                <?php $k++;?>
                                <div id="input_<?php echo $k;?>" class="col-sm-10 newQuestion">
                                    <input placeholder="Опции опроса" class="form-control" value="<?php echo $v->value;?>" name="value[<?php echo $v->id?>]" type="text"  id="value<?php echo $k; ?>">
                                </div>
                            <?php endforeach;?>


                        <div class="inline-block">
                        <div class="col-sm-10">

                        <button type="button" id="btnAdd" class="btn btn-success"><i class="icon-plus"></i></button>
                        <button type="button" id="btnDel" class="btn btn-danger"><i class="icon-minus"></i></button>
                        </div>
                        </div>
                            </div>
                        </div>

                    <div class="field">&nbsp;</div>
                </div>

                <div class="form-actions">
                <button type="submit"  class="btn btn-info">Добавить Опрос</button>
                <button type="button" class="btn btn-danger">Отмена</button>
                <?php if ($opt=='edit'){?>
                    <input type="hidden" value="<?php echo $itemid;?>" name="itemid"/>
                    <?php }?>
                <input name="opt" type="hidden" value="<?php if ($opt=='add') {?>addPoll <?php } else {?>updatePoll<?php }?>">
                </div>
            </form>
        </div>
        <div id="msgholder"></div>
    </div>
    <script type="text/javascript">
        // <![CDATA[
        $(document).ready(function () {
            $('#btnDel').hide();
            $('#btnAdd').click(function () {
                var num = $('.newQuestion').length;
                var newNum = new Number(num + 1);
                var newElem = $('#input_' + num).clone().attr('id', 'input_' + newNum);

                newElem.children(':first').attr('id', 'value' + newNum).attr('name', 'value[' + newNum + ']');
                $('#input_' + num).after(newElem);
                (num) ? $('#btnDel').show() : $('#btnDel').hide();
            });

            $('#btnDel').click(function () {
                var num = $('.newQuestion').length;
                $('#input_' + num).remove();
                (num - 1 == 1) ? $('#btnDel').hide() : $('#btnDel').show();
            });
        });
        // ]]>
    </script>


<?php
}

if ($opt=='addPoll'){
    Registry::get("Polls")->addPoll();
    header("Location:index.php?do=components&action=config&id=".Core::$id);
}

if ($opt=='updatePoll'){
    Registry::get("Polls")->updatePoll();
    header("Location:index.php?do=components&action=config&id=".Core::$id);
}
if ($opt=='delete'){
    $itemid=$_REQUEST['itemid'];
    $title = sanitize($_POST['title']);
    $result = $db->delete("poll_questions", "id=" . $itemid);
    $db->delete("poll_votes", "option_id IN(SELECT id FROM poll_options WHERE question_id='" . $itemid. "')");
    $db->delete("poll_options", "question_id=" . $itemid);
    header("Location:index.php?do=components&action=config&id=".Core::$id);
}
?>