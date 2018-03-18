<div class="widget">
<!--    <div class="widget_title"><h3>Search widget</h3></div>-->
    <h2>Быстрая регистрация с почтой и паролем</h2>
    <div class="tb_widget_search" style=" ">
        <?php  Core::msgError(Core::$msgs);?>
        <form name="form"  method="post" style="width:500px;">
            <input type="hidden" name="action" value="signin"/>
            <p>
           <label>
               Email
               <input type="text" name="email" placeholder="email">
           </label>
            </p>
                <button class="btn btn_turquoise btn_expand" type="submit" > Зарегистрироваться</button>

        </form>
    </div>
</div>