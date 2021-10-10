<?php

if(!isset($page)){
    $page=1;
}
if(!isset($num_pages)){
    $num_pages=1;
}

?>

<tr>
    <td colspan="6">
        <form id="form-filter" method="post">
            <input type="hidden" name="page" value="<?php echo $page;?>" id="page">
            <nav aria-label="Page navigation" class="mt-8">
                <ul class="pagination">
                    <li class="page-item"><a class="page-link  <?php if($num_pages>1 && $page>1){echo "prev-page"; }?>" href="#">Previous</a></li>
                    <?php

                    $limitleft=0;
                    $limitright1=$num_pages-3;
                    $center=0;

                    for($i=$page;$i<=$num_pages;$i++){

                        if($i==$page && $page>=($num_pages-3) && $num_pages>=5){?>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">... </a></li>
                        <?php }
                        if($i==$page){?>

                            <li class="page-item active"><a class="page-link" href="#"><?php echo $page?></a></li>

                        <?php }

                        if($limitleft<3 && $i>$page){
                            $limitleft++;?>
                            <li class="page-item"><a class="page-link" href="#"><?php echo $i;?></a></li>
                        <?php }



                        if($i==$num_pages && $page<($num_pages-3)){?>
                            <li class="page-item"><a class="page-link" href="#">... </a></li>
                            <li class="page-item"><a class="page-link" href="#"><?php echo $num_pages;?></a></li>

                        <?php }




                    }?>




                    <li class="page-item"><a class="page-link  <?php if($num_pages>1  && $page>=1 &&  $page<=$num_pages){echo "next-page"; }?>" href="#">Next</a></li>

                </ul>
            </nav>
        </form>
        <!--paginacion-->

    </td>

</tr>