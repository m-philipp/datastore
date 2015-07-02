<div class="col-sm-12">
    <div class="well bs-component">
        <h2>Kontaktieren Sie uns!</h2>
        <br/>

        <?php if ($fail) { ?>
            Das hat leider nicht geklappt. Bitte füllen Sie alle Felder aus.<br/><br/>
        <?php } ?>

        <form method="post" action="<?php echo $bp; ?>contact/contactReceive" class="form-horizontal" role="form">
            <div class="form-group">
                <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                <div class="col-sm-10">
                    <input name="mail" type="email" class="form-control" id="inputEmail" placeholder="Email">
                </div>
            </div>

            <div class="form-group">
                <label for="inputContent" class="col-sm-2 control-label">Text</label>

                <div class="col-sm-10 col-sm-offset-2">
                    <textarea name="content" id="inputContent" class="form-control" cols="40" rows="10"
                              placeholder="Wir freuen uns auf Ihr Feedback!"></textarea>
                </div>
            </div>

            <input type="hidden" name="form" value="<?php echo $fail ? "fail" : "contact" ?>">

            <div class="">
                <button class="btn btn-primary pull-right" type="submit">
                    Go!
                </button>
            </div>
            <div class="clearfix"></div>
        </form>
    </div>
</div>
