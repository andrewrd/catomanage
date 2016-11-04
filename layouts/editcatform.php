<!--Display this selection first then display the form-->
<div class="container">

    <form action="editcat.php" method="POST" class="form-horizontal">
            <div class="form-group row">
                <!--Column left-->
                <div class="hidden-xs col-sm-3 col-md-3 col-lg-2">
                    <p class="labelText">Select the category you want to edit.</p>
                </div>
                <!--List the categories-->
                <div class="col-xs-12 col-sm-9 col-md-9  col-lg-10">
                     <?php get_all_categories_edit($dbo); ?>
                </div>
            </div>
    </form>
</div>

<!--Form Container Ends here-->
<script type="text/javascript" src="../js/validateForm.js"></script>
</div>
<!--Form Container ends here-->
