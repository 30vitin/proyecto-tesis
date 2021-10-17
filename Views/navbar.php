<?php

$navbar = (isset($navbar)) ? $navbar : '';
?>

<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
    <div class="container-fluid">
        <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:;"><?php echo $navbar; ?></a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
        </button>
        <div class="collapse navbar-collapse ">

            <ul class="navbar-nav">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <?php
                        $breadcrumb = isset($breadcrumb) ? $breadcrumb : [];
                        foreach ($breadcrumb as $link) {

                            if($link->current){?>
                                <li class="breadcrumb-item active" aria-current="page"><?php echo $link->name;?></li>

                            <?php }else{?>

                                <li class="breadcrumb-item"><a href="<?php echo $link->link;?>"><?php echo $link->name;?></a></li>

                            <?php }?>


                        <?php } ?>
                    </ol>
                </nav>




            </ul>
        </div>
    </div>
</nav>
