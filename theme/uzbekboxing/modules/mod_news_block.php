<div class="panel-content">
    <?php

    $count = 0;

    foreach ($articles as $content) {
        $count++;
        if ($count == 1) {
            ?>
            <div class="article-big-block">
                <div class="article-photo">
				<span class="image-hover">
				<span class="drop-icons">
														<span class="icon-block"><a href="/images/content/<?php echo $content['image'] ?>" title="Show Image"
                                                                                    class="icon-loupe legatus-tooltip">
                                                                &nbsp;</a></span>
														<span class="icon-block"><a href="/content/<?php echo $content['href']; ?>"
                                                                                    title="Read Article"
                                                                                    class="icon-link legatus-tooltip">
                                                                &nbsp;</a></span>
													</span>
													<img src="/images/content/<?php echo $content['image'] ?>" class="setborder" alt=""/>
												</span>
                </div>

                <div class="article-header">
                    <h4><a href="/content/<?php echo $content['href']; ?>"><?php echo $content['title'] ?></a></h4>
                </div>

                <div class="article-content">
                    <?php echo $content['description'];?>
                               </div>

                <div class="article-links">
                    <a href="/content/<?php echo $content['href']; ?>#comments" class="article-icon-link">
                        <span class="meta-date"></span><?php echo $content['date'];?></a>
                    <a href="#" class="article-icon-link">
                        <span class="view-meta"></span><?php echo $content['hits'];?></a>
                    <a href="/content/<?php echo $content['href']; ?>" class="article-icon-link"><span
                            class="icon-text">&#59212;</span>Батафсил</a>
                </div>
                <!-- END .article-big-block -->
            </div>
            <?php
            ?>


        <?php
        }else {
            ?>


            <div class="article-small-block">

                <div class="article-photo">
										<span class="image-hover">
											<span class="drop-icons">
												<span class="icon-block"><a href="/content/<?php echo $content['href']; ?>" title="Read Article" class="icon-link legatus-tooltip">&nbsp;</a></span>
											</span>
											<img src="/images/content/small/<?php echo $content['image'] ?>" class="setborder" alt="" />
										</span>
                </div>

                <div class="article-content">
                    <h3><a href="/content/<?php echo $content['href']; ?>"><?php echo $content['title'] ?></a></h3>
                    </div>
                <div class="article-links">
                    <a href="/content/<?php echo $content['href']; ?>#comments" class="article-icon-link">
                        <span class="meta-date"></span><?php echo $content['date'];?>
                        <span class="view-meta"></span><?php echo $content['hits'];?></a>
                    <a href="/content/<?php echo $content['href']; ?>" class="article-icon-link"><span class="icon-text">&#59212;</span>Батафсил</a>
                </div>
            </div>

            <?php
        }
            ?>

    <?php } ?>

</div>